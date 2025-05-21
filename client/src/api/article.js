import apiClient from "@/plugins/axios"

const articleApi = {
    getArticles() {
        return apiClient.get('/articles')
    },
    getArticlesTrashed() {
        return apiClient.get('/articles/trashed')
    },
    getArticle(id) {
        return apiClient.get(`/articles/${id}`)
    },
    getPublished(params = {}) {
        return apiClient.get('/articles/published', { params })
    },
    getByCategory(categorySlug) {
        return apiClient.get(`/articles/by-category/${categorySlug}`)
    },
    getBySlug(slug) {
        return apiClient.get(`/articles/by-slug/${slug}`)
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
    }
}

export default articleApi;
