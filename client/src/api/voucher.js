
import apiClient from "@/plugins/axios"

const voucherApi = {
    get(params = {}) {
        return apiClient.get('/vouchers', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/vouchers/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/vouchers', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/vouchers/${id}`)
    },
    create(data) {
        return apiClient.post('/vouchers', data)
    },
    update(id, data) {
        return apiClient.put(`/vouchers/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/vouchers/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/vouchers/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/vouchers/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/vouchers/handle-form-actions', data)
    },
    checkCoupon(couponCode) {
        return apiClient.get(`/vouchers/check/${couponCode}`)
    },
}

export default voucherApi;
