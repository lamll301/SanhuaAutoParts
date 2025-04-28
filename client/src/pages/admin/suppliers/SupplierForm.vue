<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý nhà cung cấp</h3>
                <router-link v-show="this.$route.params.id" to="/admin/supplier/create" class="admin-content__create">
                    Thêm nhà cung cấp
                </router-link>
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
import apiService from '@/utils/apiService';
import { isValidEmail, isValidPhone } from '@/utils/helpers';

export default {
    data() {
        return {
            supplier: {},
            errors: {
                name: '',
                email: '',
                phone: '',
                address: '',
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
                email: '',
                phone: '',
                address: '',
            }
            if (!this.supplier.name) {
                this.errors.name = 'Tên nhà cung cấp không được để trống.';
                isValid = false;
            } else if (this.supplier.name.length > 255) {
                this.errors.name = 'Tên nhà cung cấp không được vượt quá 255 ký tự.';
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
            } else if (this.supplier.address.length > 255) {
                this.errors.address = 'Địa chỉ không được vượt quá 255 ký tự.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                if (this.$route.params.id) {
                    const res = await this.$swal.withLoading(apiService.suppliers.getOne(this.$route.params.id));
                    this.supplier = res.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            try {
                if (this.supplier.id) {
                    await apiService.suppliers.update(this.supplier.id, this.supplier);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về nhà cung cấp đã được cập nhật!", "success");
                }
                else {
                    await apiService.suppliers.create(this.supplier);
                    await this.$swal.fire("Thêm thành công!", "Nhà cung cấp mới đã được thêm vào hệ thống!", "success");
                }
                this.$router.push({ name: 'admin.suppliers' });
            } catch (error) {
                console.error(error)
            }
        },
        resetForm() {
            this.supplier = {
                name: '', email: '', phone: '', address: ''
            };
            this.errors = {
                name: '', email: '', phone: '', address: ''
            };
        },
    }
}
</script>