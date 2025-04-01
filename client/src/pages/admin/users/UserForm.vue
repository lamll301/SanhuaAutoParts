<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý người dùng</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa người dùng</h4>
                        <h4 v-else>Form thêm người dùng</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã người dùng</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="user.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên tài khoản</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên tài khoản" v-model="user.username"
                                v-bind:class="{'is-invalid': errors.username}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.username">{{ errors.username }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Mật khẩu</h3>
                            <div class="valid-elm input-group">
                                <input type="password" class="fs-16 form-control" v-model="user.password"
                                :placeholder="$route.params.id ? 'Nhập mật khẩu mới nếu muốn đổi' : 'Nhập mật khẩu'"
                                v-bind:class="{'is-invalid': errors.password}" @blur="validate()">
                                <div class=" invalid-feedback" v-if="errors.password">{{ errors.password }}</div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Họ và tên</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" placeholder="Nhập họ và tên" v-model="user.name">
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
                                    <input type="text" class="fs-16 form-control" placeholder="Nhập số điện thoại" v-model="user.phone"
                                    v-bind:class="{'is-invalid': errors.phone}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.phone">{{ errors.phone }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Email</h3>
                                <div class="valid-elm input-group">
                                    <input type="email" class="fs-16 form-control" placeholder="Nhập email người dùng" v-model="user.email"
                                    v-bind:class="{'is-invalid': errors.email}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tỉnh / Thành phố</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="user.city_id" @change="onCityChange">
                                        <option disabled value="null">Chọn thành phố</option>
                                        <option v-for="city in cities" :key="city.code" :value="city.code">
                                            {{ city.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Quận / Huyện</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="user.district_id" @change="onDistrictChange">
                                        <option disabled value="null">Chọn quận huyện</option>
                                        <option v-for="district in districts" :key="district.code" :value="district.code">
                                            {{ district.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phường / Xã</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="user.ward_id">
                                        <option disabled value="null">Chọn phường xã</option>
                                        <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                            {{ ward.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20 admin-content__textarea-5r">
                            <h3 class="admin-content__form-text">Địa chỉ cụ thể</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" placeholder="Nhập địa chỉ cụ thể" rows="5" v-model="user.address"></textarea>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Vai trò</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="user.role_id">
                                    <option disabled value="null">Chọn vai trò</option>
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
                                    <option disabled value="">Chọn trạng thái</option>
                                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                                        {{ status.label }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button type="submit" class="fs-16 btn btn-primary">Xác nhận</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';
import { statusService } from '@/utils/statusService';
import { isValidEmail, isValidPhone, isValidPassword } from '@/utils/helpers';

export default {
    data() {
        return {
            errors: {
                username: '', password: '', email: '', phone: '',
            },
            cities: [], districts: [], wards: [],
            user: {
                city_id: null, district_id: null, ward_id: null, role_id: null, status: '',
            }, 
            roles: [],
            statusOptions: statusService.getOptions('user'),
        }
    },
    async created() {
        await Promise.all([
            this.fetchCities(),
            this.fetchRoles()
        ]);
        if (this.$route.params.id) {
            await this.fetchData();
        }
    },
    methods: {
        validate() {
            let isValid = true;
            this.errors = {
                username: '', password: '', email: '', phone: '',
            }
            if (!this.user.username) {
                this.errors.username = 'Tên tài khoản không được để trống.';
                isValid = false;
            }
            if (!this.$route.params.id) {
                if (!this.user.password) {
                    this.errors.password = 'Mật khẩu không được để trống.';
                    isValid = false;
                }
            }
            if (this.user.password && !isValidPassword(this.user.password)) {
                this.errors.password = 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ và số.';
                isValid = false;
            }
            if (this.user.email && !isValidEmail(this.user.email)) {
                this.errors.email = 'Email không hợp lệ.';
                isValid = false;
            }
            if (this.user.phone && !isValidPhone(this.user.phone)) {
                this.errors.phone = 'Số điện thoại không hợp lệ.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            const res = await handleApiCall(() => this.$request.get(apiService.users.view(this.$route.params.id)));
            this.user = res;
            if (this.user.city_id) {
                await this.fetchDistrictsById();
                if (this.user.district_id) {
                    await this.fetchWardsById();
                }
            }
        },
        async fetchRoles() {
            const res = await handleApiCall(() => this.$request.get(apiService.roles.get({}, false, true)));
            this.roles = res.data;
        },
        async fetchCities() {
            const res = await handleApiCall(() => this.$request.get(apiService.getAllCities()));
            this.cities = res;
        },
        async fetchDistrictsById() {
            const res = await handleApiCall(() => this.$request.get(apiService.getDistrictsById(this.user.city_id)));
            this.districts = res.districts;
        },
        async fetchWardsById() {
            const res = await handleApiCall(() => this.$request.get(apiService.getWardsById(this.user.district_id)));
            this.wards = res.wards;
        },
        async save() {
            if (!this.validate()) return;
            const payload = Object.fromEntries(
                Object.entries(this.user).filter(([, value]) => 
                    value !== null && value !== undefined && value !== ''
                )
            );
            if (this.user.id) {
                await handleApiCall(() => this.$request.put(apiService.users.update(this.user.id), payload));
                await swalFire("Cập nhật thành công!", "Thông tin về người dùng đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.users.create(), payload));
                await swalFire("Thêm thành công!", "Người dùng mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.users' });
        },
        onCityChange() {
            this.user.district_id = null;
            this.districts = [];
            this.user.ward_id = null;
            this.wards = [];
            this.fetchDistrictsById();
        },
        onDistrictChange() {
            this.user.ward_id = null;
            this.wards = [];
            this.fetchWardsById();
        },
    }
}
</script>