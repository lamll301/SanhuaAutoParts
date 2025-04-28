import { swalFire } from '@/utils/swal.js';
import router from '@/router';

export const handleApiCall = async (apiCall) => {
    try {
        const { data } = await apiCall();
        return data;
    } catch (error) {
        if (!error.response) {
            swalFire("Lỗi kết nối!", "Không thể kết nối đến máy chủ", "error");
            throw error;
        }
        
        switch (error.response.status) {
            case 401:
                swalFire("Lỗi xác thực!", "Vui lòng đăng nhập lại", "error");
                break;
            case 403:
                swalFire("Không có quyền truy cập!", "Bạn không có quyền thực hiện hành động này", "error");
                break;
            case 404:
                router.replace({ name: "NotFound" });
                break;
            case 422:
                swalFire("Lỗi dữ liệu!", error.response.data?.message || "Dữ liệu không hợp lệ", "error");
                break;
            case 500:
            case 502:
            case 503:
                swalFire("Lỗi máy chủ!", error.response.data?.message, "error");
                break;
            default:
                swalFire("Lỗi!", error.response.data?.message || "Đã có lỗi xảy ra", "error");
        }
        
        return Promise.reject(error);
    }
};