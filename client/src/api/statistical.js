import apiClient from "@/plugins/axios";

const statisticalApi = {
    summary(params = {}) {
        return apiClient.get('/statistical/summary', { params });
    },
    getRevenueByPeriod(params = {}) {
        return apiClient.get('/statistical/revenue', { params });
    },
    getProfitByPeriod(params = {}) {
        return apiClient.get('/statistical/profit', { params });
    },
    getBestSellingProducts(params = {}) {
        return apiClient.get('/statistical/product/best-selling', { params });
    },
    getCustomerStatistics(params = {}) {
        return apiClient.get('/statistical/customer', { params });
    },
    getOrderStatistics(params = {}) {
        return apiClient.get('/statistical/order', { params });
    },
    getProductExpiredStatistics(params = {}) {
        return apiClient.get('/statistical/product/expired', { params });
    }
}

export default statisticalApi;
