import { defineStore } from 'pinia';

export const useHomeStore = defineStore('home', {
    state: () => ({
        sliders: [],
        companyArticles: [],
        saleArticles: [],
        bestSellersProducts: [],
        newestProducts: [],
        onSaleProducts: [],
        highClassProducts: [],
    }),
    actions: {
        setSliders(sliders) {
            this.sliders = sliders;
        },
        setCompanyArticles(companyArticles) {
            this.companyArticles = companyArticles;
        },
        setSaleArticles(saleArticles) {
            this.saleArticles = saleArticles;
        },
        setBestSellersProducts(bestSellersProducts) {
            this.bestSellersProducts = bestSellersProducts;
        },
        setNewestProducts(newestProducts) {
            this.newestProducts = newestProducts;
        },
        setOnSaleProducts(onSaleProducts) {
            this.onSaleProducts = onSaleProducts;
        },
        setHighClassProducts(highClassProducts) {
            this.highClassProducts = highClassProducts;
        },
    },
});
