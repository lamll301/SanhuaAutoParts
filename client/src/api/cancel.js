import apiClient from "@/plugins/axios"

const cancelApi = {
    getCancels(params = {}) {
        return apiClient.get('/cancels', { params })
    },
    getCancelsTrashed(params = {}) {
        return apiClient.get('/cancels/trashed', { params })
    },
    getAllCancels(params = {}) {
        return apiClient.get('/cancels', { params: { ...params, all: true } })
    },
    getCancel(id) {
        return apiClient.get(`/cancels/${id}`)
    },
    create(data) {
        return apiClient.post('/cancels', data)
    },
    update(id, data) {
        return apiClient.put(`/cancels/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/cancels/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/cancels/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/cancels/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/cancels/handle-form-actions', data)
    }
}

export default cancelApi 