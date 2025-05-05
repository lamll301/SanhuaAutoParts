import apiClient from "@/plugins/axios"

const crudEndpoints = [
    'permissions', 'roles', 'users', 'units',
    'suppliers', 'vouchers', 'promotions', 'articles', 'categories', 'products',
    'locations', 'inventories', 'imports', 'exports', 'disposals', 'checks', 'cancels',
    'orders', 'carts', 'reviews'
]

const createCrudService = (endpoint) => {
    const baseUrl = `${process.env.VUE_APP_API_BASE_URL}/api/${endpoint}`

    const coreMethods = {
        get: (params = {}) => apiClient.get(baseUrl, { params }),
        getTrashed: (params = {}) => apiClient.get(`${baseUrl}/trashed`, { params }),
        getAll: (params = {}) => apiClient.get(baseUrl, { params: { ...params, all: true } }),
        getOne: (id) => apiClient.get(`${baseUrl}/${id}`),
        create: (data) => apiClient.post(baseUrl, data),
        update: (id, data) => apiClient.put(`${baseUrl}/${id}`, data),
        updateWithImages: (id, data) => apiClient.post(`${baseUrl}/${id}`, data),
        delete: (id) => apiClient.delete(`${baseUrl}/${id}`),
        restore: (id) => apiClient.patch(`${baseUrl}/${id}/restore`),
        forceDelete: (id) => apiClient.delete(`${baseUrl}/${id}/force-delete`),
        handleFormActions: (data) => apiClient.post(`${baseUrl}/handle-form-actions`, data),
    }

    const endpointSpecificMethods = {
        products: {
            getByCategorySlug: (slug = '', params = {}) => apiClient.get(`${baseUrl}/category/${slug}`, { params }),
            getBySlug: (slug) => apiClient.get(`${baseUrl}/by-slug/${slug}`)
        },
        articles: {
            getPublished: (params = {}) => apiClient.get(`${baseUrl}/published`, { params }),
            getBySlug: (slug) => apiClient.get(`${baseUrl}/by-slug/${slug}`)
        },
        categories: {
            getBySlug: (slug) => apiClient.get(`${baseUrl}/by-slug/${slug}`)
        },
        reviews: {
            getByProductSlug: (slug, params = {}) => apiClient.get(`${baseUrl}/product/${slug}`, { params })
        },
        carts: {
            getCart: () => apiClient.get(`${baseUrl}`),
            addToCart: (productId, quantity = 1) => apiClient.post(`${baseUrl}/${productId}`, { quantity }),
            updateCart: (productId, quantity) => apiClient.patch(`${baseUrl}/${productId}`, { quantity }),
            removeFromCart: (productId) => apiClient.delete(`${baseUrl}/${productId}`),
            clearCart: () => apiClient.delete(`${baseUrl}/clear`),
        },
        users: {
            
        }
    }

    return {
        ...coreMethods,
        ...(endpointSpecificMethods[endpoint] || {})
    }
}

const crudServices = Object.fromEntries(
    crudEndpoints.map(name => [name, createCrudService(name)])
)

const provincesService = () => {
    const baseUrl = 'https://provinces.open-api.vn/api'

    return {
        getProvinces: (params = {}) => apiClient.get(`${baseUrl}/p`, { params }),
        getProvinceWithDistricts: (id) => apiClient.get(`${baseUrl}/p/${id}`, { params: { depth: 2 } }),
        getDistricts: (params = {}) => apiClient.get(`${baseUrl}/d`, { params }),
        getDistrictWithWards: (id) => apiClient.get(`${baseUrl}/d/${id}`, { params: { depth: 2 } }),
        getWards: (params = {}) => apiClient.get(`${baseUrl}/w`, { params }),
    }
}

export default {
    ...crudServices,
    provinces: provincesService(),
    createService: (endpoint) => createCrudService(`/${endpoint}`),
}