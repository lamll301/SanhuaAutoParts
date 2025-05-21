import apiClient from "@/plugins/axios"

const unitApi = {
    getUnits(params = {}) {
        return apiClient.get('/units', { params })
    },
    getUnitsTrashed(params = {}) {
        return apiClient.get('/units/trashed', { params })
    },
    getAllUnits(params = {}) {
        return apiClient.get('/units', { params: { ...params, all: true } })
    },
    getUnit(id) {
        return apiClient.get(`/units/${id}`)
    },
    create(data) {
        return apiClient.post('/units', data)
    },
    update(id, data) {
        return apiClient.put(`/units/${id}`, data)
    },
    delete(id) {
        return apiClient.delete(`/units/${id}`)
    },
    restore(id) {
        return apiClient.patch(`/units/${id}/restore`)
    },
    forceDelete(id) {
        return apiClient.delete(`/units/${id}/force-delete`)
    },
    handleFormActions(data) {
        return apiClient.post('/units/handle-form-actions', data)
    }
}

export default unitApi 