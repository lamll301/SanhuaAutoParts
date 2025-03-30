<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý vai trò</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa vai trò</h4>
                        <h4 v-else>Form thêm vai trò</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã vai trò</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="role.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên vai trò</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên vai trò" v-model="role.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>

                        <div class="mb-20" v-if="this.$route.params.id">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="admin-content__form-text m-0">Phân quyền hiện tại</h3>
                                <span class="badge bg-primary rounded-pill admin-content__form-grid-count">
                                    {{ role.permissions ? role.permissions.length : 0 }} quyền
                                </span>
                            </div>
                            
                            <div class="admin-content__form-grid">
                                <div v-if="!role.permissions || role.permissions.length === 0" 
                                    class="admin-content__form-grid-empty text-center py-4">
                                    <img src="../../../assets/images/search-no-result.jpg" alt="" class="admin-content__form-grid-empty-img">
                                    <p class="text-muted mb-0 fs-14">Vai trò chưa được cấp quyền nào</p>
                                </div>
                                <div v-else class="row g-3">
                                    <div v-for="permission in role.permissions" :key="permission.id" class="col-md-6 col-lg-4">
                                        <div class="admin-content__form-grid-card card h-100 border-0 shadow-sm">
                                            <div class="admin-content__form-grid-card-body card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="admin-content__form-grid-card-title text-primary">
                                                            <i class="fas fa-key me-2"></i>
                                                            {{ permission.name }}
                                                        </h5>
                                                        <p class="admin-content__form-grid-card-text text-muted small">
                                                            {{ permission.description || 'Không có mô tả' }}
                                                        </p>
                                                    </div>
                                                    <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle"
                                                        @click="removePermission(permission.id)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex align-items-center mt-2">
                                                    <span class="admin-content__form-grid-date badge bg-light text-dark me-2">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ formatDate(permission.updated_at) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="admin-content__form-grid-section mt-4 p-3 bg-light rounded">
                                <h4 class="d-flex align-items-center">
                                    <i class="fas fa-plus-circle text-success me-2 mt-2"></i>
                                    Thêm quyền mới
                                </h4>
                                <div class="d-flex gap-3 align-items-center">
                                    <select class="valid-elm form-select" v-model="selectedPermission"
                                        :disabled="permissions.length === 0"
                                    >
                                        <option disabled value="" selected>Chọn phân quyền</option>
                                        <option v-for="permission in permissions" :key="permission.id" :value="permission.id"
                                            :disabled="role.permissions && role.permissions.some(p => p.id === permission.id)"
                                        >
                                            {{ permission.name }}
                                            <template v-if="permission.description">- {{ permission.description }}</template>
                                        </option>
                                    </select>
                                    <button type="button" 
                                        class="btn btn-success px-4 shadow-sm fs-16"
                                        @click="addPermission(selectedPermission)"
                                        :disabled="!selectedPermission">
                                        Thêm
                                    </button>
                                </div>
                                <small class="text-muted d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Chọn quyền từ danh sách để thêm vào vai trò này
                                </small>
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
import { formatDate } from '@/utils/formatter';
import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';

export default {
    data() {
        return {
            role: {}, 
            permissions: [], selectedPermission: '',
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
            if (!this.role.name) {
                this.errors.name = 'Tên vai trò không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            const resRole = await handleApiCall(() => this.$request.get(apiService.roles.view(this.$route.params.id)));
            this.role = resRole;
            const resPermissions = await handleApiCall(() => this.$request.get(apiService.permissions.get({}, false, true)));
            this.permissions = resPermissions.data;
        },
        async save() {
            if (!this.validate()) return;

            if (this.role.id) {
                await handleApiCall(() => this.$request.put(apiService.roles.update(this.role.id), this.role));
                await swalFire("Cập nhật thành công!", "Thông tin về người dùng đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.roles.create(), this.role));
                await swalFire("Thêm thành công!", "Người dùng mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.roles' });
        },
        async addPermission(permissionId) {
            if (!permissionId) return;
            await handleApiCall(() => this.$request.post(apiService.roles.addPermission(this.role.id), {
                permissionId,
            }));
            await swalFire("Thêm thành công!", "Quyền đã được thêm vào vai trò!", "success");

            const permission = this.permissions.find(p => p.id === permissionId);
            if (permission) {
                this.role.permissions = this.role.permissions || [];
                this.role.permissions.push(permission);
                this.selectedPermission = '';
            }
        },
        async removePermission(permissionId) {
            if (!permissionId) return;
            await handleApiCall(() => this.$request.delete(apiService.roles.removePermission(this.role.id, permissionId)));
            await swalFire("Xóa thành công!", "Quyền đã bị xóa khỏi vai trò!", "success");

            this.role.permissions = this.role.permissions.filter(p => p.id !== permissionId);
        },
        formatDate(date) {
            return formatDate(date);
        },
    }
}
</script>