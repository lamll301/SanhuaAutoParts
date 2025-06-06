import axios from 'axios'
import axiosRetry from 'axios-retry';
import router from '@/router'
import { swal } from './sweetalert'
import { useAuthStore } from '@/stores/auth'
import { authApi } from '@/api';
import { useCartStore } from '@/stores/cart';

const apiClient = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL + '/api',
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
})

axiosRetry(apiClient, {
    retries: 3,
    retryDelay: (retryCount) => {
        return axiosRetry.exponentialDelay(retryCount);
    },
    retryCondition: (error) => {
        return (
            error.code === 'ECONNABORTED' || 
            !error.response || 
            (error.response && error.response.status >= 500) ||
            !window.navigator.onLine
        );
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
            config.headers['Content-Type'] = 'multipart/form-data';
        }
        const token = localStorage.getItem('token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
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
        const cartStore = useCartStore();
        if (response) {
            if (response.status === 401 && !originalRequest._retry) {
                const errorCode = response.data?.code
                if (errorCode === 1001) {
                    authStore.removeToken();
                    authStore.removeUser();
                    cartStore.setCart([]);
                    router.push('/')
                    return Promise.reject(response.data)
                }
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
                    const res = await authApi.refresh();
                    const { token } = res.data;
                    authStore.setToken(token);
                    originalRequest.headers.Authorization = `Bearer ${token}`;
                    processQueue(null, token);
                    return apiClient(originalRequest);
                } catch (err) {
                    processQueue(err);
                    authStore.removeToken();
                    authStore.removeUser();
                    cartStore.setCart([]);
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
                    router.replace({ name: "AccessDenied" })
                    break
                case 404:
                    console.error(response.data)
                    router.replace({ name: "NotFound" })
                    break
                case 422: {
                    console.error(response.data)
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
                    break
                default:
                    console.error(response.data)
                    swal.fire('Lỗi không xác định', 'Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau.', 'error')
            }
        } else {
            console.error(error)
        }
        return Promise.reject(error)
    }
)

export default apiClient