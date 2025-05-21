import apiClient from "@/plugins/axios"

const cartApi = {
    getCart() {
        return apiClient.get('/carts')
    },
    add(productId, quantity = 1) {
        return apiClient.post(`/carts/${productId}`, { quantity })
    },
    update(productId, quantity) {
        return apiClient.patch(`/carts/${productId}`, { quantity })
    },
    remove(productId) {
        return apiClient.delete(`/carts/${productId}`)
    },
    clear() {
        return apiClient.delete('/carts/clear')
    },
}

export default cartApi;
