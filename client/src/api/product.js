import apiClient from "@/plugins/axios"

const productApi = {
    get(params = {}) {
        return apiClient.get('/products', { params })
    },
    getAll(params = {}) {
        return apiClient.get('/products', { params: { ...params, all: true } })
    },
    getTrashed(params = {}) {
        return apiClient.get('/products/trashed', { params })
    },
    getOne(id) {
        return apiClient.get(`/products/${id}`)
    },
    getBySlug(slug) {
        return apiClient.get(`/products/slug/${slug}`)
    },
    getByCategory(slug = '', params = {}) {
        return apiClient.get(`/products/category/${slug}`, { params })
    },
    create(data) {
        return apiClient.post('/products', data)
    },
    updateWithImages(id, data) {
        return apiClient.post(`/products/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/products/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/products/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/products/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/products/handle-form-actions', data)
    }
}

export default productApi
