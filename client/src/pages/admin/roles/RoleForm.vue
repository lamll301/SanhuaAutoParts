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
import apiService from '@/utils/apiService';
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
            }
            if (!this.role.name) {
                this.errors.name = 'Tên vai trò không được để trống.';
                isValid = false;
            } else if (this.role.name.length > 255) {
                this.errors.name = 'Tên vai trò không được vượt quá 255 ký tự.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                const req = [
                    apiService.permissions.getAll(),
                ];

                if (this.$route.params.id) {
                    req.push(
                        apiService.roles.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.permissions = res[0].data.data;
                if (this.$route.params.id) this.role = res[1].data;
            } catch (error) {
                console.log(error);
            }
        },
        cleanData(role) {
            const { addedIds, deletedIds } = this.$refs.itemDashboard.getIds();

            return {
                ...role,
                addedIds,
                deletedIds
            };
        },
        async save() {
            if (!this.validate()) return;
            const data = this.cleanData(this.role);

            try {
                if (this.role.id) {
                    await apiService.roles.update(this.role.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về vai trò đã được cập nhật!", "success")
                }
                else {
                    await apiService.roles.create(data);
                    await this.$swal.fire("Thêm thành công!", "Vai trò mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.roles' });
            } catch (error) {
                console.error(error);
            }
        },
        formatDate(date) {
            return formatDate(date);
        },
        removePermission(id) {
            this.role.permissions = this.role.permissions.filter(permission => permission.id !== id);
        },
        resetForm() {
            this.role = {
                name: ''
            };
            this.errors = {
                name: ''
            };
        },
    }
}
</script>