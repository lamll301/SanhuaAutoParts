
import apiClient from "@/plugins/axios"

const paymentApi = {
    createMomoPayment(id) {
        return apiClient.post('/payments/momo', { id })
    },
    createVNPayPayment(id) {
        return apiClient.post('/payments/vnpay', { id })
    },
    createZaloPayPayment(id) {
        return apiClient.post('/payments/zalopay', { id })
    },
    createCODPayment(id) {
        return apiClient.post('/payments/cod', { id })
    },
    createQRCodePayment(id) {
        return apiClient.post('/payments/vietqr', { id })
    },
}

export default paymentApi;
