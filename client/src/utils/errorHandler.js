import { swalFire } from '@/utils/swal.js';
import router from '@/router';

export const handleApiCall = async (apiCall) => {
    try {
        const { data } = await apiCall();
        return data;
    } catch (error) {
        console.error(error);

        if (error.response && error.response.status === 404) {
            router.replace({
                path: router.currentRoute.value.fullPath,
                name: 'NotFound',
            });
            throw new Error('"Not Found"');
        }

        const errorMessage = error.response?.data?.message || 'Đã có lỗi xảy ra';
        swalFire("Lỗi!", errorMessage, "error");
        
        throw error;
    }
};