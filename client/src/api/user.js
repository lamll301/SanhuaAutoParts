import apiClient from "@/plugins/axios"

const userApi = {
    get(params = {}) {
        return apiClient.get('/users', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/users/trashed', { params })
    },
    getOne(id) {
        return apiClient.get(`/users/${id}`)
    },
    create(data) {
        return apiClient.post('/users', data)
    },
    update(id, data) {
        return apiClient.put(`/users/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/users/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/users/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/users/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/users/handle-form-actions', data)
    },
    updateProfile(data) {
        return apiClient.post('/users/profile', data)
    },
    resetPassword(oldPassword, newPassword) {
        return apiClient.patch('/users/password/reset', { oldPassword, newPassword })
    },
}

export default userApi
