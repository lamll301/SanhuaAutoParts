import apiClient from "@/plugins/axios"

const disposalApi = {
    getDisposals(params = {}) {
        return apiClient.get('/disposals', { params })
    },
    getDisposalsTrashed(params = {}) {
        return apiClient.get('/disposals/trashed', { params })
    },
    getAllDisposals(params = {}) {
        return apiClient.get('/disposals', { params: { ...params, all: true } })
    },
    getDisposal(id) {
        return apiClient.get(`/disposals/${id}`)
    },
    create(data) {
        return apiClient.post('/disposals', data)
    },
    update(id, data) {
        return apiClient.put(`/disposals/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/disposals/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/disposals/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/disposals/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/disposals/handle-form-actions', data)
    }
}

export default disposalApi 