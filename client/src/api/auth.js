import apiClient from "@/plugins/axios"

const authApi = {
    login(username, password) {
        return apiClient.post('/auth/login', { username, password })
    },
    register(username, password) {
        return apiClient.post('/auth/register', { username, password })
    },
    logout() {
        return apiClient.post('/auth/logout')
    },
    me() {
        return apiClient.get('/auth/me')
    },
    refresh() {
        return apiClient.post('/auth/refresh')
    },
}

export default authApi;
