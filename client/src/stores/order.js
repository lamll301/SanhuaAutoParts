import { defineStore } from 'pinia'

export const useOrderStore = defineStore('order', {
    state: () => ({
        buyNow: {},
        reorder: [],
    }),
    actions: {
        setBuyNow(product) {
            this.buyNow = product
            this.reorder = [];
        },
        setReorder(details) {
            this.reorder = details;
            this.buyNow = {};
        }
    }
})
