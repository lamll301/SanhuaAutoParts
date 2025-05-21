import apiClient from "@/plugins/axios"

const userApi = {
    getUsers(params = {}) {
        return apiClient.get('/users', { params })
    },
    getUsersTrashed(params = {}) {
        return apiClient.get('/users/trashed', { params })
    },
    getUser(id) {
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
        return apiClient.post('/users/update-profile', data)
    },
    updatePassword(oldPassword, newPassword) {
        return apiClient.post('/users/update-password', { oldPassword, newPassword })
    }
}

export default userApi
