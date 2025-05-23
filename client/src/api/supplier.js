import apiClient from "@/plugins/axios"

const supplierApi = {
    get(params = {}) {
        return apiClient.get('/suppliers', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/suppliers/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/suppliers', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/suppliers/${id}`)
    },
    create(data) {
        return apiClient.post('/suppliers', data)
    },
    update(id, data) {
        return apiClient.put(`/suppliers/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/suppliers/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/suppliers/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/suppliers/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/suppliers/handle-form-actions', data)
    }
}

export default supplierApi 