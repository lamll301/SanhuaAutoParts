
import apiClient from "@/plugins/axios"

const orderApi = {
    getOrders(params = {}) {
        return apiClient.get('/orders', { params })
    },
    getTrashedOrders(params = {}) {
        return apiClient.get('/orders/trashed', { params })
    },
    getOrder(id) {
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
    changeOrderStatus(id, status, paymentInfo = null) {
        return apiClient.patch(`/orders/${id}/status`, { status, paymentInfo })
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
}

export default orderApi;
