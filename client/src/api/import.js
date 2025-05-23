import apiClient from "@/plugins/axios"

const importApi = {
    get(params = {}) {
        return apiClient.get('/imports', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/imports/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/imports', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/imports/${id}`)
    },
    create(data) {
        return apiClient.post('/imports', data)
    },
    update(id, data) {
        return apiClient.put(`/imports/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/imports/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/imports/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/imports/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/imports/handle-form-actions', data)
    }
}

export default importApi 