import apiClient from "@/plugins/axios"

const authApi = {
    login(username, password) {
        return apiClient.post('/auth/login', { username, password })
    },
    register(email, username, password) {
        return apiClient.post('/auth/register', { email, username, password })
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
    redirectToProvider(provider) {
        return apiClient.get(`/auth/${provider}/redirect`)
    },
    handleProviderCallback(provider) {
        return apiClient.get(`/auth/${provider}/callback`)
    }
}

export default authApi;
