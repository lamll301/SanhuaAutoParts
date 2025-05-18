import apiClient from "@/plugins/axios"

const endpoints = [
    'permissions', 'roles', 'users', 'units', 'suppliers', 'vouchers', 'promotions', 'articles', 'categories', 'products',
    'locations', 'inventories', 'imports', 'exports', 'disposals', 'checks', 'cancels',
    'orders', 'carts', 'reviews', 'auth', 'payments'
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
            createMomoPayment: (data) => apiClient.post(`${baseUrl}/momo`, data),
            createVNPayPayment: (data) => apiClient.post(`${baseUrl}/vnpay`, data),
            createZaloPayPayment: (data) => apiClient.post(`${baseUrl}/zalopay`, data),
            createCODPayment: (id) => apiClient.post(`${baseUrl}/cod`, { id }),
        },
        vouchers: {
            applyCoupon: (couponCode) => apiClient.get(`${baseUrl}/apply-coupon`, { params: { couponCode } }),
        },
        orders: {
            updateStatus: (id, status) => apiClient.patch(`${baseUrl}/${id}/status`, { status }),
            view: (id) => apiClient.get(`${baseUrl}/${id}/view`),
            viewList: (params = {}) => apiClient.get(`${baseUrl}/view`, { params }),
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

const provincesService = () => {
    const baseUrl = 'https://provinces.open-api.vn/api'

    return {
        getProvinces: (id = null) => apiClient.get(`${baseUrl}/p${id ? `/${id}` : ''}`),
        getProvinceWithDistricts: (id) => apiClient.get(`${baseUrl}/p/${id}`, { params: { depth: 2 } }),
        getDistricts: (id = null) => apiClient.get(`${baseUrl}/d${id ? `/${id}` : ''}`),
        getDistrictWithWards: (id) => apiClient.get(`${baseUrl}/d/${id}`, { params: { depth: 2 } }),
        getWards: (id = null) => apiClient.get(`${baseUrl}/w${id ? `/${id}` : ''}`),
    }
}

const vietQRService = () => {
    const baseUrl = 'https://api.vietqr.io/v2'

    return {
        generateQR: (orderId, amount) => apiClient.post(`${baseUrl}/generate`, {
            accountNo: process.env.VUE_APP_BANK_ACCOUNT_NO,
            accountName: process.env.VUE_APP_BANK_ACCOUNT_NAME,
            acqId: process.env.VUE_APP_BANK_CODE,
            addInfo: `Thanh toan don hang Ma ${orderId}`,
            amount: amount.toString(),
            template: 'qr_only',
        }, {
            headers: {
                'x-client-id': process.env.VUE_APP_VIETQR_CLIENT_ID,
                'x-api-key': process.env.VUE_APP_VIETQR_API_KEY,
                'Content-Type': 'application/json',
            }, 
        }),
    }
}

export default {
    ...apiServices,
    provinces: provincesService(),
    vietQR: vietQRService(),
}