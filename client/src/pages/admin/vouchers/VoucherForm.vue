<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý voucher</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa voucher</h4>
                        <h4 v-else>Form thêm voucher</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã voucher</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="voucher.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Code</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập code voucher" v-model="voucher.code"
                                v-bind:class="{'is-invalid': errors.code}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.code">{{ errors.code }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Giá trị voucher</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập giá trị voucher" v-model="voucher.value"
                                v-bind:class="{'is-invalid': errors.value}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.value">{{ errors.value }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Giới hạn sử dụng</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập giới hạn sử dụng" v-model="voucher.usage_limit"
                                v-bind:class="{'is-invalid': errors.usage_limit}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.usage_limit">{{ errors.usage_limit }}</div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày bắt đầu</h3>
                                <div class="valid-elm input-group">
                                    <input type="datetime-local" class="fs-16 form-control" v-model="voucher.start_date"
                                    v-bind:class="{'is-invalid': errors.start_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.start_date">{{ errors.start_date }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày kết thúc</h3>
                                <div class="valid-elm input-group">
                                    <input type="datetime-local" class="fs-16 form-control" v-model="voucher.end_date"
                                    v-bind:class="{'is-invalid': errors.end_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.end_date">{{ errors.end_date }}</div>

                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="voucher.status">
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
import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';
import { statusService } from '@/utils/statusService';

export default {
    data() {
        return {
            voucher: {
                status: '',
            }, 
            statusOptions: statusService.getOptions('voucher'),
            errors: {
                value: '',
                code: '',
                usage_limit: '',
                start_date: '',
                end_date: '',
            },
        }
    },
    async created() {
        if (this.$route.params.id) {
            await this.fetchData();
        }
    },
    methods: {
        validate() {
            let isValid = true;
            this.errors = {
                value: '',
                code: '',
                usage_limit: '',
                start_date: '',
                end_date: '',
            }
            if (!this.voucher.code) {
                this.errors.code = 'Code voucher không được để trống.';
                isValid = false;
            }
            if (!this.voucher.value) {
                this.errors.value = 'Giá trị voucher không được để trống.';
                isValid = false;
            } else if (this.voucher.value <= 0) {
                this.errors.value = 'Giá trị voucher phải lớn hơn 0.';
                isValid = false;
            }
            if (!this.voucher.usage_limit) {
                this.errors.usage_limit = 'Giới hạn sử dụng không được để trống.';
                isValid = false;
            } else if (this.voucher.usage_limit <= 0) {
                this.errors.usage_limit = 'Giới hạn sử dụng phải lớn hơn 0.';
                isValid = false;
            }
            if (!this.voucher.start_date) {
                this.errors.start_date = 'Ngày bắt đầu không được để trống.';
                isValid = false;
            }
            if (!this.voucher.end_date) {
                this.errors.end_date = 'Ngày kết thúc không được để trống.';
                isValid = false;
            } else if (new Date(this.voucher.end_date) <= new Date(this.voucher.start_date)) {
                this.errors.end_date = 'Ngày kết thúc phải lớn hơn ngày bắt đầu.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            const res = await handleApiCall(() => this.$request.get(apiService.vouchers.view(this.$route.params.id)));
            this.voucher = res;
        },
        async save() {
            if (!this.validate()) return;
            const payload = Object.fromEntries(
                Object.entries(this.voucher).filter(([, value]) => 
                    value !== null && value !== undefined && value !== ''
                )
            );
            if (this.voucher.id) {
                await handleApiCall(() => this.$request.put(apiService.vouchers.update(this.voucher.id), payload));
                await swalFire("Cập nhật thành công!", "Thông tin về voucher đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.vouchers.create(), payload));
                await swalFire("Thêm thành công!", "Voucher mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.vouchers' });
        },
    }
}
</script>