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
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Phân quyền</h3>
                            <ItemDashboard
                                ref="itemDashboard"
                                :items="role.permissions"
                                :options="permissions"
                                @remove="removePermission"
                            />
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
import ItemDashboard from '@/components/ItemDashboard.vue';

export default {
    components: {
        ItemDashboard
    },
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
        await this.fetchInitialData();
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
            } else if (this.role.name.length > 64) {
                this.errors.name = 'Tên vai trò không được vượt quá 64 ký tự.';
                isValid = false;
            }
            return isValid;
        },
        async fetchInitialData() {
            try {
                const resPermissions = await handleApiCall(() => this.$request.get(apiService.permissions.get({}, false, true)));
                this.permissions = resPermissions.data;
            } catch (error) {
                console.log(error);
            }
        },
        async fetchData() {
            try {
                const resRole = await handleApiCall(() => this.$request.get(apiService.roles.view(this.$route.params.id)));
                this.role = resRole;
            } catch (error) {
                console.log(error);
            }
        },
        async save() {
            if (!this.validate()) return;

            const { addedIds, deletedIds } = this.$refs.itemDashboard.getIds();
            const payload = {
                ...this.role,
                addedIds,
                deletedIds
            };

            if (this.role.id) {
                await handleApiCall(() => this.$request.put(apiService.roles.update(this.role.id), payload));
                await swalFire("Cập nhật thành công!", "Thông tin về người dùng đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.roles.create(), payload));
                await swalFire("Thêm thành công!", "Người dùng mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.roles' });
        },
        formatDate(date) {
            return formatDate(date);
        },
        removePermission(id) {
            this.role.permissions = this.role.permissions.filter(permission => permission.id !== id);
        },
    }
}
</script>