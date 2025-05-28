import apiClient from "@/plugins/axios"

const articleApi = {
    get(params = {}) {
        return apiClient.get('/articles', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/articles/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/articles', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/articles/${id}`)
    },
    getPublished(params = {}) {
        return apiClient.get('/articles/published', { params })
    },
    getByCategory(categorySlug) {
        return apiClient.get(`/articles/category/${categorySlug}`)
    },
    getBySlug(slug) {
        return apiClient.get(`/articles/slug/${slug}`)
    },
    create(data) {
        return apiClient.post('/articles', data)
    },
    updateWithImages(id, data) {
        return apiClient.post(`/articles/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/articles/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/articles/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/articles/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/articles/handle-form-actions', data)
    },
    approve(id) {
        return apiClient.patch(`/articles/${id}/approve`)
    },
    home() {
        return apiClient.get('/articles/home')
    }
}

export default articleApi;
