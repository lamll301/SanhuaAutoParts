import { swalFire } from '@/utils/swal.js';
import router from '@/router';

export const handleApiCall = async (apiCall) => {
    try {
        const { data } = await apiCall();
        return data;
    } catch (error) {
        console.error("API Error:", error);
        if (error.response?.status === 404) {
            router.replace({ name: "NotFound" });
        } else {
            const errorMessage = error.response?.data?.message || "Đã có lỗi xảy ra";
            swalFire("Lỗi!", errorMessage, "error");
        }
        return null;
    }
};