import apiClient from "@/plugins/axios"

const checkApi = {
    get(params = {}) {
        return apiClient.get('/checks', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/checks/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/checks', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/checks/${id}`)
    },
    create(data) {
        return apiClient.post('/checks', data)
    },
    update(id, data) {
        return apiClient.put(`/checks/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/checks/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/checks/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/checks/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/checks/handle-form-actions', data)
    }
}

export default checkApi 