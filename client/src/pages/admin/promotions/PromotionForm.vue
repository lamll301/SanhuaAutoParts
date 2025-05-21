<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý khuyến mãi</h3>
                <router-link v-show="this.$route.params.id" to="/admin/promotion/create" class="admin-content__create">
                    Thêm khuyến mãi
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa khuyến mãi</h4>
                        <h4 v-else>Form thêm khuyến mãi</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã khuyến mãi</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="promotion.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên khuyến mãi</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên khuyến mãi" v-model="promotion.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Phần trăm giảm</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập phần trăm giảm" v-model="promotion.discount_percent"
                                v-bind:class="{'is-invalid': errors.discount_percent}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.discount_percent">{{ errors.discount_percent }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tiền giảm tối đa</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập tiền giảm tối đa" v-model="promotion.max_discount_amount"
                                v-bind:class="{'is-invalid': errors.max_discount_amount}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.max_discount_amount">{{ errors.max_discount_amount }}</div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày bắt đầu</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="promotion.start_date"
                                    v-bind:class="{'is-invalid': errors.start_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.start_date">{{ errors.start_date }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày kết thúc</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="promotion.end_date"
                                    v-bind:class="{'is-invalid': errors.end_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.end_date">{{ errors.end_date }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="promotion.status">
                                    <option value="" disabled selected>Chọn trạng thái</option>
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
import apiService from '@/utils/apiService';
import { statusService } from '@/utils/statusMap';

export default {
    data() {
        return {
            promotion: {
                status: '',
            },
            statusOptions: statusService.getOptions('promotion'),
            errors: {
                name: '',
                discount_percent: '',
                max_discount_amount: '',
                start_date: '',
                end_date: '',
            },
        }
    },
    watch: {
        '$route'(to, from) {
            if (from.params.id && !to.params.id) {
                this.resetForm();
            } else {
                this.fetchData();
            }
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        validate() {
            let isValid = true;
            this.errors = {
                name: '',
                discount_percent: '',
                max_discount_amount: '',
                start_date: '',
                end_date: '',
            }
            if (!this.promotion.name) {
                this.errors.name = 'Tên khuyến mãi không được để trống.';
                isValid = false;
            } else if (this.promotion.name.length > 255) {
                this.errors.name = 'Tên khuyến mãi không được vuợt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.promotion.discount_percent) {
                this.errors.discount_percent = 'Phần trăm giảm không được để trống.';
                isValid = false;
            } else if (this.promotion.discount_percent < 0 || this.promotion.discount_percent > 100) {
                this.errors.discount_percent = 'Phần trăm giảm phải nằm trong khoảng từ 0 đến 100.';
                isValid = false;
            }
            if (!this.promotion.max_discount_amount) {
                this.errors.max_discount_amount = 'Tiền giảm tối đa không được để trống.';
                isValid = false;
            } else if (this.promotion.max_discount_amount < 0) {
                this.errors.max_discount_amount = 'Tiền giảm tối đa không được nhỏ hơn 0.';
                isValid = false;
            }
            if (!this.promotion.start_date) {
                this.errors.start_date = 'Ngày bắt đầu không được để trống.';
                isValid = false;
            }
            if (!this.promotion.end_date) {
                this.errors.end_date = 'Ngày kết thúc không được để trống.';
                isValid = false;
            } else if (new Date(this.promotion.start_date) >= new Date(this.promotion.end_date)) {
                this.errors.end_date = 'Ngày kết thúc phải lớn hơn ngày bắt đầu.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                if (this.$route.params.id) {
                    const res = await this.$swal.withLoading(apiService.promotions.getOne(this.$route.params.id));
                    this.promotion = res.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            try {
                if (this.promotion.id) {
                    await apiService.promotions.update(this.promotion.id, this.promotion);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về khuyến mãi đã được cập nhật!", "success");
                }
                else {
                    await apiService.promotions.create(this.promotion);
                    await this.$swal.fire("Thêm thành công!", "Khuyến mãi mới đã được thêm vào hệ thống!", "success");
                }
                this.$router.push({ name: 'admin.promotions' });
            } catch (error) {
                console.error(error)
            }
        },
        resetForm() {
            this.promotion = {
                name: '',
                discount_percent: '',
                max_discount_amount: '',
                status: '',
            };
            this.errors = {
                name: '',
                discount_percent: '',
                max_discount_amount: '',
                start_date: '',
                end_date: '',
            };
        },
    }
}
</script>