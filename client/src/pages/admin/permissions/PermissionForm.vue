<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phân quyền</h3>
                <router-link v-show="this.$route.params.id" to="/admin/permission/create" class="admin-content__create">
                    Thêm phân quyền
                </router-link>
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
import apiService from '@/utils/apiService';

export default {
    data() {
        return {
            permission: {},
            errors: {
                name: '', description: ''
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
                name: '', description: ''
            }
            if (!this.permission.name) {
                this.errors.name = 'Tên phân quyền không được để trống.';
                isValid = false;
            } else if (this.permission.name.length > 255) {
                this.errors.name = 'Tên phân quyền không được vượt quá 255 ký tự.';
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
                if (this.$route.params.id) {
                    const res = await this.$swal.withLoading(apiService.permissions.getOne(this.$route.params.id));
                    this.permission = res.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;

            try {
                if (this.permission.id) {
                    await apiService.permissions.update(this.permission.id, this.permission);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về phân quyền kho đã được cập nhật!", "success")
                }
                else {
                    await apiService.permissions.create(this.permission);
                    await this.$swal.fire("Thêm thành công!", "Phân quyền kho mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.permissions' });
            } catch (error) {
                console.error(error);
            }
        },
        resetForm() {
            this.permission = {
                name: '', description: ''
            };
            this.errors = {
                name: '', description: ''
            };
        },
    }
}
</script>