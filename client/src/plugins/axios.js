import axios from 'axios'
import router from '@/router'
import { swal } from './sweetalert'
import { useAuthStore } from '@/stores/auth'
import apiService from '@/utils/apiService'

const apiClient = axios.create({
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

let isRefreshing = false;
let failedQueue = [];

const processQueue = (error, token = null) => {
    failedQueue.forEach(prom => {
        if (error) {
            prom.reject(error);
        } else {
            prom.resolve(token);
        }
    });
    failedQueue = [];
};

apiClient.interceptors.request.use(
    config => {
        if (config.data instanceof FormData) {
            // delete config.headers['Content-Type'];
            config.headers['Content-Type'] = 'multipart/form-data';
        }
        const isInternalApi = config.url.includes(process.env.VUE_APP_API_BASE_URL)
        const token = localStorage.getItem('token')
        if (token && isInternalApi) {
            config.headers.Authorization = `Bearer ${token}`
        } else if (token && !isInternalApi) {
            delete config.headers.Authorization
        }
        return config
    },
    error => Promise.reject(error)
)

apiClient.interceptors.response.use(
    response => response,
    async error => {
        const { response } = error
        const originalRequest = error.config;
        const authStore = useAuthStore();
        if (response) {
            if (response.status === 401 && !originalRequest._retry) {
                if (isRefreshing) {
                    return new Promise((resolve, reject) => {
                        failedQueue.push({ resolve, reject });
                    }).then(token => {
                        originalRequest.headers.Authorization = `Bearer ${token}`;
                        return apiClient(originalRequest);
                    }).catch(err => {
                        return Promise.reject(err);
                    });
                }
                originalRequest._retry = true;
                isRefreshing = true;
                try {
                    const res = await apiService.auth.refresh();
                    const { access_token } = res.data;
                    authStore.setToken(access_token);
                    originalRequest.headers.Authorization = `Bearer ${access_token}`;
                    processQueue(null, access_token);
                    return apiClient(originalRequest);
                } catch (err) {
                    processQueue(err);
                    authStore.removeToken();
                    authStore.removeUser();
                    router.push('/')
                    return Promise.reject(err)
                } finally {
                    isRefreshing = false;
                }
            }
            switch (response.status) {
                case 400:
                    console.error(response.data)
                    break
                case 403:
                    console.error(response.data)
                    router.replace({ name: "NotFound" })
                    break
                case 404:
                    console.error(response.data)
                    router.replace({ name: "NotFound" })
                    break
                case 422: {
                    console.error(response.data)
                    const errors = response.data.errors || {}
                    const firstError = Object.values(errors)[0]?.[0] || 'Vui lòng kiểm tra lại dữ liệu nhập vào.'
                    swal.fire('Dữ liệu không hợp lệ', firstError, 'error')
                    return Promise.reject(response.data)
                }
                case 429:
                    console.error(response.data)
                    swal.fire('Quá nhiều yêu cầu', 'Bạn đang gửi yêu cầu quá nhanh. Vui lòng thử lại sau ít phút.', 'warning')
                    break
                case 500:
                case 501:
                case 502:
                case 503:
                    console.error(response.data)
                    // swal.fire('Lỗi máy chủ', 'Hệ thống đang gặp sự cố. Vui lòng thử lại sau hoặc liên hệ quản trị viên.', 'error', {
                    //     footer: `Mã lỗi: ${response.status}`
                    // })
                    break
                default:
                    console.error(response.data)
                    swal.fire('Lỗi không xác định', 'Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau.', 'error')
            }
        } else {
            if (error.code === 'ECONNABORTED') {
                swal.fire('Hết thời gian chờ', 'Yêu cầu quá thời gian chờ phản hồi. Vui lòng kiểm tra kết nối và thử lại.', 'error')
            } else if (!window.navigator.onLine) {
                swal.fire('Mất kết nối mạng', 'Vui lòng kiểm tra kết nối internet của bạn và thử lại.', 'warning')
            } else {
                swal.fire('Lỗi kết nối', 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.', 'error')
            }
            console.error(error)
        }
        return Promise.reject(error)
    }
)

export default apiClient