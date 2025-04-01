<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý danh mục</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa danh mục</h4>
                        <h4 v-else>Form thêm danh mục</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã danh mục</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="category.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên danh mục</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên danh mục" v-model="category.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập mô tả danh mục" v-model="category.description"></textarea>
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
            category: {},
            errors: {
                name: '',
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
            }
            if (!this.category.name) {
                this.errors.name = 'Tên danh mục không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            const res = await handleApiCall(() => this.$request.get(apiService.categories.view(this.$route.params.id)));
            this.category = res;
        },
        async save() {
            if (!this.validate()) return;

            if (this.category.id) {
                await handleApiCall(() => this.$request.put(apiService.categories.update(this.category.id), this.category));
                await swalFire("Cập nhật thành công!", "Thông tin về danh mục đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.categories.create(), this.category));
                await swalFire("Thêm thành công!", "Danh mục mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.categories' });
        },
    }
}
</script>