import apiClient from "@/plugins/axios"

const inventoryApi = {
    get(params = {}) {
        return apiClient.get('/inventories', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/inventories/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/inventories', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/inventories/${id}`)
    },
    create(data) {
        return apiClient.post('/inventories', data)
    },
    update(id, data) {
        return apiClient.put(`/inventories/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/inventories/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/inventories/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/inventories/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/inventories/handle-form-actions', data)
    }
}

export default inventoryApi 