<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý nhà cung cấp</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa nhà cung cấp</h4>
                        <h4 v-else>Form thêm nhà cung cấp</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã nhà cung cấp</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="supplier.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên nhà cung cấp</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên nhà cung cấp" v-model="supplier.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên người liên hệ</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên người liên hệ" v-model="supplier.contact_name"
                                v-bind:class="{'is-invalid': errors.contact_name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.contact_name">{{ errors.contact_name }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Email</h3>
                            <div class="valid-elm input-group">
                                <input type="email" class="fs-16 form-control" placeholder="Nhập email" v-model="supplier.email"
                                v-bind:class="{'is-invalid': errors.email}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Số điện thoại</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập số điện thoại" v-model="supplier.phone"
                                v-bind:class="{'is-invalid': errors.phone}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.phone">{{ errors.phone }}</div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Địa chỉ</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập địa chỉ" v-model="supplier.address"
                                v-bind:class="{'is-invalid': errors.address}" @blur="validate()"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.address">{{ errors.address }}</div>
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
import { isValidEmail, isValidPhone } from '@/utils/helpers';

export default {
    data() {
        return {
            supplier: {},
            errors: {
                name: '',
                contact_name: '',
                email: '',
                phone: '',
                address: '',
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
                name: '',
                contact_name: '',
                email: '',
                phone: '',
                address: '',
            }
            if (!this.supplier.name) {
                this.errors.name = 'Tên nhà cung cấp không được để trống.';
                isValid = false;
            }
            if (!this.supplier.contact_name) {
                this.errors.contact_name = 'Tên người liên hệ không được để trống.';
                isValid = false;
            }
            if (!this.supplier.email) {
                this.errors.email = 'Email không được để trống.';
                isValid = false;
            } else if (!isValidEmail(this.supplier.email)) {
                this.errors.email = 'Email không hợp lệ.';
                isValid = false;
            }
            if (!this.supplier.phone) {
                this.errors.phone = 'Số điện thoại không được để trống.';
                isValid = false;
            } else if (!isValidPhone(this.supplier.phone)) {
                this.errors.phone = 'Số điện thoại không hợp lệ.';
                isValid = false;
            }
            if (!this.supplier.address) {
                this.errors.address = 'Địa chỉ không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            const res = await handleApiCall(() => this.$request.get(apiService.suppliers.view(this.$route.params.id)));
            this.supplier = res;
        },
        async save() {
            if (!this.validate()) return;

            if (this.supplier.id) {
                await handleApiCall(() => this.$request.put(apiService.suppliers.update(this.supplier.id), this.supplier));
                await swalFire("Cập nhật thành công!", "Thông tin về nhà cung cấp đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.suppliers.create(), this.supplier));
                await swalFire("Thêm thành công!", "Nhà cung cấp mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.suppliers' });
        },
    }
}
</script>