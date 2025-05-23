import apiClient from "@/plugins/axios"

const exportApi = {
    get(params = {}) {
        return apiClient.get('/exports', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/exports/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/exports', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/exports/${id}`)
    },
    create(data) {
        return apiClient.post('/exports', data)
    },
    update(id, data) {
        return apiClient.put(`/exports/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/exports/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/exports/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/exports/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/exports/handle-form-actions', data)
    }
}

export default exportApi 