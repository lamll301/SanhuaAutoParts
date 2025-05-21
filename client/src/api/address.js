
import apiClient from "@/plugins/axios"

const addressApi = {
    getProvinces() {
        return apiClient.get('/addresses/provinces')
    },
    getProvinceWithDistricts(id) {
        return apiClient.get(`/addresses/provinces/${id}/districts`)
    },
    getDistrictWithWards(id) {
        return apiClient.get(`/addresses/districts/${id}/wards`)
    }
}

export default addressApi;
