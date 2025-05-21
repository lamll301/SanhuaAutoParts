import apiClient from "@/plugins/axios"

const supplierApi = {
    getSuppliers(params = {}) {
        return apiClient.get('/suppliers', { params })
    },
    getSuppliersTrashed(params = {}) {
        return apiClient.get('/suppliers/trashed', { params })
    },
    getAllSuppliers(params = {}) {
        return apiClient.get('/suppliers', { params: { ...params, all: true } })
    },
    getSupplier(id) {
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