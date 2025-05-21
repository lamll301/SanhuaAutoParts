import apiClient from "@/plugins/axios"

const permissionApi = {
    getPermissions(params = {}) {
        return apiClient.get('/permissions', { params })
    },
    getPermissionsTrashed(params = {}) {
        return apiClient.get('/permissions/trashed', { params })
    },
    getAllPermissions(params = {}) {
        return apiClient.get('/permissions', { params: { ...params, all: true } })
    },
    getPermission(id) {
        return apiClient.get(`/permissions/${id}`)
    },
    create(data) {
        return apiClient.post('/permissions', data)
    },
    update(id, data) {
        return apiClient.put(`/permissions/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/permissions/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/permissions/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/permissions/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/permissions/handle-form-actions', data)
    }
}

export default permissionApi 