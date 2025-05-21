import apiClient from "@/plugins/axios"

const endpoints = [
    'permissions', 'roles', 'users', 'units', 'suppliers', 'vouchers', 'promotions', 'articles', 'categories', 'products',
    'locations', 'inventories', 'imports', 'exports', 'disposals', 'checks', 'cancels',
    'orders', 'carts', 'reviews', 'auth', 'payments', 'addresses'
]

const createApiService = (endpoint) => {
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

    const extraMethods = {
        auth: {
            login: (data) => apiClient.post(`${baseUrl}/login`, data),
            register: (data) => apiClient.post(`${baseUrl}/register`, data),
            logout: () => apiClient.post(`${baseUrl}/logout`),
            me: () => apiClient.get(`${baseUrl}/me`),
            refresh: () => apiClient.post(`${baseUrl}/refresh`),
        },
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
            updateProfile: (data) => apiClient.post(`${baseUrl}/update-profile`, data),
            updatePassword: (data) => apiClient.put(`${baseUrl}/update-password`, data),
        },
        payments: {
            createMomoPayment: (id) => apiClient.post(`${baseUrl}/momo`, { id }),
            createVNPayPayment: (id) => apiClient.post(`${baseUrl}/vnpay`, { id }),
            createZaloPayPayment: (id) => apiClient.post(`${baseUrl}/zalopay`, { id }),
            createCODPayment: (id) => apiClient.post(`${baseUrl}/cod`, { id }),
            createQRCodePayment: (id) => apiClient.post(`${baseUrl}/vietqr`, { id }),
        },
        vouchers: {
            applyCoupon: (couponCode) => apiClient.get(`${baseUrl}/apply-coupon`, { params: { couponCode } }),
        },
        orders: {
            updateStatus: (id, status) => apiClient.patch(`${baseUrl}/${id}/status`, { status }),
            view: (id) => apiClient.get(`${baseUrl}/${id}/view`),
            viewList: (params = {}) => apiClient.get(`${baseUrl}/view`, { params }),
            cancel: (id, cancelReason) => apiClient.post(`${baseUrl}/${id}/cancel`, { cancelReason }),
        },
        addresses: {
            getProvinces: () => apiClient.get(`${baseUrl}/provinces`),
            getProvinceWithDistricts: (id) => apiClient.get(`${baseUrl}/provinces/${id}/districts`),
            getDistrictWithWards: (id) => apiClient.get(`${baseUrl}/districts/${id}/wards`),
        },
    }

    return {
        ...coreMethods,
        ...(extraMethods[endpoint] || {})
    }
}

const apiServices = Object.fromEntries(
    endpoints.map(name => [name, createApiService(name)])
)

export default {
    ...apiServices,
}