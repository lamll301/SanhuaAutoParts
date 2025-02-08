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
                            <input type="text" class="order-form-input">
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Ngày sinh
                            </p>
                            <input type="date" class="order-form-input">
                        </div>
                    </div>
                    <div class="admin-content__form-divided">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Số điện thoại
                            </p>
                            <input type="text" class="order-form-input">
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Email
                            </p>
                            <input type="text" class="order-form-input">
                        </div>
                    </div>
                    <div class="admin-content__form-divided-3">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Tỉnh / Thành phố
                            </p>
                            <select v-model="selectedCity" @change="fetchDistricts" class="order-form-select order-form-input">
                                <option v-for="city in cities" :key="city.code" :value="city.code">
                                    {{ city.name }}
                                </option>
                            </select>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Quận / Huyện
                            </p>
                            <select v-model="selectedDistrict" @change="fetchWards" class="order-form-select order-form-input">
                                <option v-for="district in districts" :key="district.code" :value="district.code">
                                    {{ district.name }}
                                </option>
                            </select>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Phường / Xã
                            </p>
                            <select v-model="selectedWard" class="order-form-select order-form-input">
                                <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                    {{ ward.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Địa chỉ cụ thể
                        </p>
                        <textarea class="order-form-area" rows="3"></textarea>
                    </div>
                    <div class="settings-my-profile-btn">
                        <button class="settings-btn">
                            Lưu
                        </button>
                    </div>
                </div>
                <div class="settings-avatar">
                    <img src="" alt="" class="settings-avatar-img">
                    <button class="settings-avatar-btn">
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
                        <input type="text" class="order-form-input">
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Mật khẩu mới
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <input type="text" class="order-form-input">
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Nhập lại mật khẩu mới
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <input type="text" class="order-form-input">
                    </div>
                    <div class="settings-my-profile-btn">
                        <button class="settings-btn">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            account: {},
            cities: [], selectedCity: '',
            districts: [], selectedDistrict: '',
            wards: [], selectedWard: '',
            activeTab: 'profile',
        }
    },
    created() {
        this.fetchCities();
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
    methods: {
        async fetchCities() {
            try {
                const response = await this.$request.get('https://provinces.open-api.vn/api/p/');
                this.cities = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchDistricts() {
            try {
                const response = await this.$request.get(`https://provinces.open-api.vn/api/p/${this.selectedCity}?depth=2`);
                this.districts = response.data.districts;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchWards() {
            try {
                const response = await this.$request.get(`https://provinces.open-api.vn/api/d/${this.selectedDistrict}?depth=2`);
                this.wards = response.data.wards;
            } catch (error) {
                console.error(error);
            }
        },
    }
}
</script>