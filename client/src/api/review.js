

import apiClient from "@/plugins/axios"

const reviewApi = {
    getByProductSlug(slug) {
        return apiClient.get(`/reviews/product/${slug}`)
    },
    create(data) {
        return apiClient.post('/reviews', data)
    },
}

export default reviewApi;
