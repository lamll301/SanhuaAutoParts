import apiClient from "@/plugins/axios"

const disposalApi = {
    get(params = {}) {
        return apiClient.get('/disposals', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/disposals/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/disposals', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/disposals/${id}`)
    },
    create(data) {
        return apiClient.post('/disposals', data)
    },
    update(id, data) {
        return apiClient.put(`/disposals/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/disposals/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/disposals/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/disposals/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/disposals/handle-form-actions', data)
    }
}

export default disposalApi 