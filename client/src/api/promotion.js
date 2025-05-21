import apiClient from "@/plugins/axios"

const promotionApi = {
    getPromotions(params = {}) {
        return apiClient.get('/promotions', { params })
    },
    getPromotionsTrashed(params = {}) {
        return apiClient.get('/promotions/trashed', { params })
    },
    getAllPromotions(params = {}) {
        return apiClient.get('/promotions', { params: { ...params, all: true } })
    },
    getPromotion(id) {
        return apiClient.get(`/promotions/${id}`)
    },
    create(data) {
        return apiClient.post('/promotions', data)
    },
    update(id, data) {
        return apiClient.put(`/promotions/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/promotions/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/promotions/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/promotions/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/promotions/handle-form-actions', data)
    }
}

export default promotionApi 