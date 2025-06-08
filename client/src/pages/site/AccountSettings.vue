<template>
    <div class="grid">
        <div class="settings">
            <h1 class="settings-heading">
                Cài đặt tài khoản
            </h1>
            <div class="settings-tab-container">
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'profile' }"
                    @click="activeTab = 'profile'"
                >
                    Hồ sơ của tôi
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'password' }"
                    @click="activeTab = 'password'"
                >
                    Đổi mật khẩu
                </div>
            </div>
            <div class="settings-tab-content settings-profile"
                v-if="activeTab === 'profile'"
            >
                <div class="settings-my-profile">
                    <div class="admin-content__form-divided">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Họ và tên
                            </p>
                            <input type="text" class="order-form-input" v-model="user.name"
                            v-bind:class="{'is-invalid': errors.name}" @blur="validateProfile()">
                            <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Ngày sinh
                            </p>
                            <input type="date" class="order-form-input" v-model="user.date_of_birth"
                            v-bind:class="{'is-invalid': errors.date_of_birth}" @blur="validateProfile()">
                            <div class="invalid-feedback" v-if="errors.date_of_birth">{{ errors.date_of_birth }}</div>
                        </div>
                    </div>
                    <div class="admin-content__form-divided">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Số điện thoại
                            </p>
                            <input type="text" class="order-form-input" v-model="user.phone"
                            v-bind:class="{'is-invalid': errors.phone}" @blur="validateProfile()">
                            <div class="invalid-feedback" v-if="errors.phone">{{ errors.phone }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Email
                            </p>
                            <input type="text" class="order-form-input" v-model="user.email"
                            v-bind:class="{'is-invalid': errors.email}" @blur="validateProfile()">
                            <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
                        </div>
                    </div>
                    <div class="admin-content__form-divided-3">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Tỉnh / Thành phố
                            </p>
                            <select v-model="selectedCity" @change="fetchDistricts" class="order-form-select order-form-input"
                            v-bind:class="{'is-invalid': errors.city}" @blur="validateProfile()"
                            >
                                <option v-for="city in cities" :key="city.code" :value="city.code">
                                    {{ city.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.city">{{ errors.city }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Quận / Huyện
                            </p>
                            <select v-model="selectedDistrict" @change="fetchWards" class="order-form-select order-form-input"
                            v-bind:class="{'is-invalid': errors.district}" @blur="validateProfile()"
                            >
                                <option v-for="district in districts" :key="district.code" :value="district.code">
                                    {{ district.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.district">{{ errors.district }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Phường / Xã
                            </p>
                            <select v-model="selectedWard" class="order-form-select order-form-input"
                            v-bind:class="{'is-invalid': errors.ward}" @blur="validateProfile()"
                            >
                                <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                    {{ ward.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.ward">{{ errors.ward }}</div>
                        </div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Địa chỉ cụ thể
                        </p>
                        <textarea class="order-form-area" rows="3" v-model="user.address"
                        v-bind:class="{'is-invalid': errors.address}" @blur="validateProfile()"></textarea>
                        <div class="invalid-feedback" v-if="errors.address">{{ errors.address }}</div>
                    </div>
                    <div class="settings-my-profile-btn">
                        <button class="settings-btn" @click="save">
                            Lưu
                        </button>
                    </div>
                </div>
                <div class="settings-avatar">
                    <img :src="avatarPreview || getImageUrl(user.avatar?.path)" alt="" class="settings-avatar-img">
                    <input type="file" ref="fileInput" accept="image/*" style="display: none" @change="handleAvatarChange">
                    <button class="settings-avatar-btn" @click="$refs.fileInput.click()">
                        Chọn ảnh
                    </button>
                </div>
            </div>
            <div class="settings-tab-content settings-profile"
                v-if="activeTab === 'password'"
            >
                <div class="settings-my-profile">
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Mật khẩu hiện tại
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <input type="password" class="order-form-input" v-model="oldPassword"
                        v-bind:class="{'is-invalid': errors.old_password}" @blur="validate()">
                        <div class="invalid-feedback" v-if="errors.old_password">{{ errors.old_password }}</div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Mật khẩu mới
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <input type="password" class="order-form-input" v-model="newPassword"
                        v-bind:class="{'is-invalid': errors.new_password}" @blur="validate()">
                        <div class="invalid-feedback" v-if="errors.new_password">{{ errors.new_password }}</div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Nhập lại mật khẩu mới
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <input type="password" class="order-form-input" v-model="confirmPassword"
                        v-bind:class="{'is-invalid': errors.confirm_password}" @blur="validate()">
                        <div class="invalid-feedback" v-if="errors.confirm_password">{{ errors.confirm_password }}</div>
                    </div>
                    <div class="settings-my-profile-btn">
                        <button class="settings-btn" @click="savePassword">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth';
import { getImageUrl } from '@/utils/helpers';
import { userApi, addressApi } from '@/api';

export default {
    setup() {
        const authStore = useAuthStore();
        return {
            authStore
        }
    },
    data() {
        return {
            activeTab: 'profile',
            cities: [],
            districts: [],
            wards: [],
            oldPassword: '',
            newPassword: '',
            confirmPassword: '',
            errors: {
                old_password: '',
                new_password: '',
                confirm_password: '',
            },
            selectedAvatar: null,
            avatarPreview: null,
        }
    },
    computed: {
        user() {
            return this.authStore.user;
        },
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
        }
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
    mounted() {
        this.fetchCities();
        if (this.selectedCity) {
            this.fetchDistricts();
        }
        if (this.selectedDistrict) {
            this.fetchWards();
        }
    },
    methods: {
        getImageUrl,
        async fetchCities() {
            try {
                const response = await addressApi.getProvinces()
                this.cities = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchDistricts() {
            if (!this.selectedCity) return;
            try {
                const response = await addressApi.getProvinceWithDistricts(this.selectedCity);
                this.districts = response.data.districts;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchWards() {
            if (!this.selectedDistrict) return;
            try {
                const response = await addressApi.getDistrictWithWards(this.selectedDistrict);
                this.wards = response.data.wards;
            } catch (error) {
                console.error(error);
            }
        },
        handleAvatarChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedAvatar = file;
                if (this.avatarPreview) {
                    URL.revokeObjectURL(this.avatarPreview);
                }
                this.avatarPreview = URL.createObjectURL(file);
                event.target.value = '';
            }
        },
        collectData() {
            const formData = new FormData();
            Object.entries(this.user).forEach(([key, value]) => {
                if (value !== null && value !== undefined) {
                    formData.append(key, value);
                }
            });
            if (this.selectedAvatar) {
                formData.append('avatar', this.selectedAvatar);
            }
            formData.append('_method', 'PUT');
            return formData;
        },
        async save() {
            if (!this.validateProfile()) return;
            const data = this.collectData();
            try {
                const res = await userApi.updateProfile(data);
                this.authStore.setUser(res.data);
                this.$swal.fire('Hoàn tất!', 'Bạn đã cập nhật hồ sơ thành công.', 'success');
            } catch (error) {
                console.error(error);
            }
        },
        async savePassword() {
            if (!this.validatePassword()) return;
            try {
                await userApi.resetPassword(this.oldPassword, this.newPassword);
                this.oldPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
                this.$swal.fire('Hoàn tất!', 'Mật khẩu đã được cập nhật.', 'success');
            } catch (e) {
                console.error(e);
                this.errors.old_password = e.message;
            }
        },
        validatePassword() {
            let isValid = true;
            this.errors = {
                old_password: '',
                new_password: '',
                confirm_password: '',
            }
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;
            if (!this.oldPassword) {
                this.errors.old_password = 'Mật khẩu hiện tại không được để trống';
                isValid = false;
            }
            if (!this.newPassword) {
                this.errors.new_password = 'Mật khẩu mới không được để trống';
                isValid = false;
            }
            else if (this.newPassword.length < 8) {
                this.errors.new_password = 'Mật khẩu mới phải có ít nhất 8 ký tự';
                isValid = false;
            }
            else if (!passwordRegex.test(this.newPassword)) {
                this.errors.new_password = 'Mật khẩu mới phải có ít nhất 1 chữ cái viết hoa, 1 chữ cái viết thường, 1 số và 1 ký tự đặc biệt';
                isValid = false;
            }
            if (this.newPassword !== this.confirmPassword) {
                this.errors.confirm_password = 'Mật khẩu mới và xác nhận mật khẩu không khớp';
                isValid = false;
            }
            return isValid;
        },
        validateProfile() {
            let isValid = true;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^0[0-9]{9}$/;
            this.errors = {
                name: '',
                date_of_birth: '',
                phone: '',
                email: '',
                address: '',
                city: '',
                district: '',
                ward: '',
            }
            if (!this.user.name) {
                this.errors.name = 'Họ và tên không được để trống';
                isValid = false;
            }
            if (!this.user.date_of_birth) {
                this.errors.date_of_birth = 'Ngày sinh không được để trống';
                isValid = false;
            } else if (new Date(this.user.date_of_birth) > new Date()) {
                this.errors.date_of_birth = 'Ngày sinh không hợp lệ';
                isValid = false;
            }
            if (!this.user.phone) {
                this.errors.phone = 'Số điện thoại không được để trống';
                isValid = false;
            } else if (!phoneRegex.test(this.user.phone)) {
                this.errors.phone = 'Số điện thoại không hợp lệ';
                isValid = false;
            }
            if (!this.user.email) {
                this.errors.email = 'Email không được để trống';
                isValid = false;
            } else if (!emailRegex.test(this.user.email)) {
                this.errors.email = 'Email không hợp lệ';
                isValid = false;
            }
            if (!this.user.address) {
                this.errors.address = 'Địa chỉ không được để trống';
                isValid = false;
            }
            if (!this.user.city_id) {
                this.errors.city = 'Tỉnh / Thành phố không được để trống';
                isValid = false;
            }
            if (!this.user.district_id) {
                this.errors.district = 'Quận / Huyện không được để trống';
                isValid = false;
            }
            if (!this.user.ward_id) {
                this.errors.ward = 'Phường / Xã không được để trống';
                isValid = false;
            }
            return isValid;
        }   
    },
    beforeUnmount() {
        if (this.avatarPreview) {
            URL.revokeObjectURL(this.avatarPreview);
        }
    }
}
</script>
<style>
.invalid-feedback {
    font-size: 12px;
}
</style>