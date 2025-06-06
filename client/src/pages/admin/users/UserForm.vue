<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý người dùng</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4>Form xem người dùng</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Mã người dùng</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="user.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên tài khoản</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" v-model="user.username">
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Họ và tên</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" v-model="user.name">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày sinh</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="user.date_of_birth">
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Số điện thoại</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" v-model="user.phone">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Email</h3>
                                <div class="valid-elm input-group">
                                    <input type="email" class="fs-16 form-control" v-model="user.email">
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tỉnh / Thành phố</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="selectedCity">
                                        <option disabled selected value="">Chọn thành phố</option>
                                        <option v-for="city in cities" :key="city.code" :value="city.code">
                                            {{ city.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Quận / Huyện</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="selectedDistrict">
                                        <option disabled selected value="">Chọn quận huyện</option>
                                        <option v-for="district in districts" :key="district.code" :value="district.code">
                                            {{ district.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phường / Xã</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="selectedWard">
                                        <option disabled selected value="">Chọn phường xã</option>
                                        <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                            {{ ward.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Địa chỉ cụ thể</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" v-model="user.address"></textarea>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Vai trò</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="user.role_id">
                                    <option disabled value="">Chọn vai trò</option>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="user.status">
                                    <option disabled selected value="">Chọn trạng thái</option>
                                    <option v-for="([key, status]) in Object.entries(getAllStatusOptions('user'))" :key="key" :value="key">
                                        {{ status }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getAllStatusOptions } from '@/utils/statusMap';
import { userApi, roleApi, addressApi } from '@/api';

export default {
    data() {
        return {
            cities: [], districts: [], wards: [],
            user: {}, 
            roles: [],
        }
    },
    computed: {
        selectedCity: {
            get() {
                return this.user.city_id;
            },
            set(value) {
                this.user.city_id = value;
            }
        },
        selectedDistrict: {
            get() {
                return this.user.district_id;
            },
            set(value) {
                this.user.district_id = value;
            }
        },
        selectedWard: {
            get() {
                return this.user.ward_id;
            },
            set(value) {
                this.user.ward_id = value;
            }
        },
    },
    watch: {
        selectedCity() {
            this.districts = [];
            this.wards = [];
            this.fetchDistricts();
        },
        selectedDistrict() {
            this.wards = [];
            this.fetchWards();
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        getAllStatusOptions,
        async fetchData() {
            try {
                const req = [
                    userApi.getOne(this.$route.params.id),
                    roleApi.getAll(),
                    addressApi.getProvinces(),
                ];
                const res = await this.$swal.withLoading(Promise.all(req));
                this.user = res[0].data;
                this.roles = res[1].data.data;
                this.cities = res[2].data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchDistricts() {
            if (!this.selectedCity) return;
            try {
                const res = await addressApi.getProvinceWithDistricts(this.selectedCity);
                this.districts = res.data.districts;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchWards() {
            if (!this.selectedDistrict) return;
            try {
                const res = await addressApi.getDistrictWithWards(this.selectedDistrict);
                this.wards = res.data.wards;
            } catch (error) {
                console.error(error);
            }
        },
    }
}
</script>