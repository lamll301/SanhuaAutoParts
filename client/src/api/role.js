import apiClient from "@/plugins/axios"

const roleApi = {
    get(params = {}) {
        return apiClient.get('/roles', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/roles/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/roles', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/roles/${id}`)
    },
    create(data) {
        return apiClient.post('/roles', data)
    },
    update(id, data) {
        return apiClient.put(`/roles/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/roles/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/roles/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/roles/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/roles/handle-form-actions', data)
    }
}

export default roleApi 