<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phân quyền</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phân quyền</h4>
                        <h4 v-else>Form thêm phân quyền</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã phân quyền</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="permission.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên phân quyền</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên phân quyền" v-model="permission.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập mô tả phân quyền" v-model="permission.description"
                                v-bind:class="{'is-invalid': errors.description}" @blur="validate()">
                                </textarea>
                                <div class="invalid-feedback" v-if="errors.description">{{ errors.description }}</div>
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

export default {
    data() {
        return {
            permission: {},
            errors: {
                name: '',
                description: ''
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
                description: ''
            }
            if (!this.permission.name) {
                this.errors.name = 'Tên phân quyền không được để trống.';
                isValid = false;
            } else if (this.permission.name.length > 64) {
                this.errors.name = 'Tên phân quyền không được vượt quá 64 ký tự.';
                isValid = false;
            }
            if (this.permission.description?.length > 255) {
                this.errors.description = 'Mô tả phân quyền không được vượt quá 255 ký tự.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                const res = await handleApiCall(() => this.$request.get(apiService.permissions.view(this.$route.params.id)));
                this.permission = res;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;

            if (this.permission.id) {
                await handleApiCall(() => this.$request.put(apiService.permissions.update(this.permission.id), this.permission));
                await swalFire("Cập nhật thành công!", "Thông tin về phân quyền đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.permissions.create(), this.permission));
                await swalFire("Thêm thành công!", "Phân quyền mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.permissions' });
        },
    }
}
</script>