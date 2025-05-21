import apiClient from "@/plugins/axios"

const inventoryApi = {
    getInventories(params = {}) {
        return apiClient.get('/inventories', { params })
    },
    getInventoriesTrashed(params = {}) {
        return apiClient.get('/inventories/trashed', { params })
    },
    getAllInventories(params = {}) {
        return apiClient.get('/inventories', { params: { ...params, all: true } })
    },
    getInventory(id) {
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