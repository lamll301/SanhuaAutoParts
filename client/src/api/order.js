
import apiClient from "@/plugins/axios"

const orderApi = {
    get(params = {}) {
        return apiClient.get('/orders', { params })
    },
    getTrashed(params = {}) {
        return apiClient.get('/orders/trashed', { params })
    },
    getOne(id) {
        return apiClient.get(`/orders/${id}`)
    },
    create(data) {
        return apiClient.post('/orders', data)
    },
    // updateOrder(id, data) {
    //     return apiClient.put(`/orders/${id}`, data)
    // },
    delete(id) {
        return apiClient.delete(`/orders/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/orders/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/orders/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/orders/handle-form-actions', data)
    },
    changeOrderStatus(id, status, payment_info = null) {
        return apiClient.patch(`/orders/${id}/status`, { status, payment_info })
    },
    getOrdersByUser(params = {}) {
        return apiClient.get('/orders/my-orders', { params })
    },
    getOrderByUser(id) {
        return apiClient.get(`/orders/my-orders/${id}`)
    },
    cancelOrderByUser(id, cancelReason) {
        return apiClient.patch(`/orders/my-orders/${id}/cancel`, { cancelReason })
    },
    approve(id, data) {
        return apiClient.patch(`/orders/${id}/approve`, {
            orderDetailInventory: data
        })
    },
}

export default orderApi;
