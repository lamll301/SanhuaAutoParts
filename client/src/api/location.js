import apiClient from "@/plugins/axios"

const locationApi = {
    get(params = {}) {
        return apiClient.get('/locations', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/locations/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/locations', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/locations/${id}`)
    },
    create(data) {
        return apiClient.post('/locations', data)
    },
    update(id, data) {
        return apiClient.put(`/locations/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/locations/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/locations/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/locations/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/locations/handle-form-actions', data)
    }
}

export default locationApi 