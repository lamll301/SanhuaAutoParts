import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
    state: () => ({
        details: [],
    }),
    getters: {
        total: (state) => {
            return state.details.reduce((sum, item) => {
                if (item.product.quantity > 0) {
                    return sum + item.product.price * item.quantity
                }
                return sum
            }, 0)
        }
    },
    actions: {
        setCart(details) {
            this.details = details
        },
        addCartItem(item) {
            const exist = this.details.find(detail => detail.product_id === item.product_id)
            if (exist) {
                const index = this.details.indexOf(exist);
                this.details[index] = item;
            } else {
                this.details.push(item)
            }
        },
        removeCartItem(id) {
            this.details = this.details.filter(item => item.id !== id)
        },
        updateCartItemQuantity(id, quantity) {
            const item = this.details.find(item => item.id === id)
            if (item) {
                item.quantity = quantity
                item.subtotal = item.product.price * quantity
            }
        },
    },
    persist: true
})
