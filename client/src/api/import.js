import apiClient from "@/plugins/axios"

const importApi = {
    getImports(params = {}) {
        return apiClient.get('/imports', { params })
    },
    getImportsTrashed(params = {}) {
        return apiClient.get('/imports/trashed', { params })
    },
    getAllImports(params = {}) {
        return apiClient.get('/imports', { params: { ...params, all: true } })
    },
    getImport(id) {
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