import axios from 'axios'
import router from '@/router'
import { swal } from './sweetalert'

const apiClient = axios.create({
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

apiClient.interceptors.request.use(
    config => {
        if (config.data instanceof FormData) {
            // delete config.headers['Content-Type'];
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
    error => {
        const { response } = error
        
        if (response) {
            switch (response.status) {
                case 400:
                    console.error('Lỗi dữ liệu gửi lên:', response.data)
                    swal.fire('Yêu cầu không hợp lệ', 'Dữ liệu gửi lên có vấn đề. Vui lòng kiểm tra lại.', 'error')
                    break
                case 401:
                    console.error('Lỗi xác thực:', response.data)
                    localStorage.removeItem('token')
                    swal.fire('Phiên đăng nhập hết hạn', 'Vui lòng đăng nhập lại để tiếp tục.', 'warning').then(() => {
                        router.push('/')
                    })
                    break
                case 403:
                    console.error('Không có quyền truy cập:', response.data)
                    swal.fire('Từ chối truy cập', 'Bạn không có quyền thực hiện thao tác này.', 'error')
                    // router.push('/forbidden')
                    break
                case 404:
                    console.error('Không tìm thấy tài nguyên:', response.data)
                    swal.fire('Không tìm thấy', 'Tài nguyên bạn yêu cầu không tồn tại hoặc đã bị xóa.', 'error').then(() => {
                        router.replace({ name: "NotFound" })
                    })
                    break
                case 422: {
                    console.error('Lỗi validation:', response.data)
                    const errors = response.data.errors || {}
                    const firstError = Object.values(errors)[0]?.[0] || 'Vui lòng kiểm tra lại dữ liệu nhập vào.'
                    swal.fire('Dữ liệu không hợp lệ', firstError, 'error')
                    return Promise.reject(response.data)
                }
                case 429:
                    console.error('Quá nhiều request:', response.data)
                    swal.fire('Quá nhiều yêu cầu', 'Bạn đang gửi yêu cầu quá nhanh. Vui lòng thử lại sau ít phút.', 'warning')
                    break
                case 500:
                case 501:
                case 502:
                case 503:
                    console.error('Lỗi server:', response.data)
                    swal.fire('Lỗi máy chủ', 'Hệ thống đang gặp sự cố. Vui lòng thử lại sau hoặc liên hệ quản trị viên.', 'error', {
                        footer: `Mã lỗi: ${response.status}`
                    })
                    break
                default:
                    console.error('Lỗi không xác định:', response.data)
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
            console.error('Network error:', error)
        }

        return Promise.reject(error)
    }
)

export default apiClient