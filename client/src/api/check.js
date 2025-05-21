import apiClient from "@/plugins/axios"

const checkApi = {
    getChecks(params = {}) {
        return apiClient.get('/checks', { params })
    },
    getChecksTrashed(params = {}) {
        return apiClient.get('/checks/trashed', { params })
    },
    getAllChecks(params = {}) {
        return apiClient.get('/checks', { params: { ...params, all: true } })
    },
    getCheck(id) {
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