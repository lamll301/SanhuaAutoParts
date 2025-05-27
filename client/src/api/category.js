import apiClient from "@/plugins/axios"

const categoryApi = {
    get(params = {}) {
        return apiClient.get('/categories', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/categories/trashed', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/categories', { params: { ...params, all: true } })
    },
    getOne(id) {
        return apiClient.get(`/categories/${id}`)
    },
    getBySlug(slug) {
        return apiClient.get(`/categories/slug/${slug}`)
    },
    create(data) {
        return apiClient.post('/categories', data)
    },
    updateWithImages(id, data) {
        return apiClient.post(`/categories/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/categories/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/categories/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/categories/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/categories/handle-form-actions', data)
    },
    getByType(type) {
        return apiClient.get(`/categories/type/${type}`)
    },
    getCategoryProduct() {
        return apiClient.get('/categories/product')
    }
}

export default categoryApi 