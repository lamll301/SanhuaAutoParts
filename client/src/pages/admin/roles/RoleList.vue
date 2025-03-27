<template>
    <div class="admin-page">
        <div v-show="isLoading" class="loading-overlay">
            <div class="loader">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg>
            </div>
        </div>
        <div class="admin-content" :class="{ 'loading-blur': isLoading }">
            <div class="admin-content__heading">
                <h3 v-show="!isTrashRoute">Quản lý vai trò</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/role/create" class="admin-content__create">Thêm vai trò</router-link>
            </div>
            <!-- admin table -->
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4 v-show="!isTrashRoute">Tất cả vai trò</h4>
                    <h4 v-show="isTrashRoute">Vai trò đã xóa</h4>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="addPermission">Thêm phân quyền</option>
                            <option value="removePermission">Xóa phân quyền</option>
                            <option value="filterByPermission">Lọc theo phân quyền</option>
                        </template>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedPermission" class="form-select admin-content__select-attribute admin-content__select-permission">
                        <option value="" selected>-- Chọn phân quyền --</option>
                        <option v-for="permission in permissions" :key="permission.id" :value="permission.id">
                            {{ permission.name }}
                        </option>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="roles" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Phân quyền</th>
                        <template v-if="!isTrashRoute">
                            <th scope="col">Ngày tạo
                                <SortComponent field="created_at" :sort="sort"/>
                            </th>
                            <th scope="col">Ngày cập nhật
                                <SortComponent field="updated_at" :sort="sort"/>
                            </th>
                        </template>
                        <th v-else scope="col">Ngày xóa
                            <SortComponent field="deleted_at" :sort="sort"/>
                        </th>
                        <th v-show="isTrashRoute" scope="col" class="col-3"></th>
                        <th v-show="!isTrashRoute" scope="col" class="col-2"></th>
                    </template>
                    <template #body="{ item }">
                        <th>{{ item.id }}</th>
                        <td>{{ item.name }}</td>
                        <td>{{ item.permissions.map(permission => permission.name).join(', ') }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/role/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
                                <button class="fs-16 btn btn-danger" @click="onDelete(item.id)">Xóa</button>
                            </template>
                            <template v-else>
                                <button class="fs-16 btn btn-primary" @click="onRestore(item.id)">Khôi phục</button>&nbsp;
                                <button class="fs-16 btn btn-danger" @click="onForceDelete(item.id)">Xóa vĩnh viễn</button>
                            </template>
                        </td>
                    </template>
                    <template #empty>
                        <tr>
                            <td colspan="13" class="text-center">
                                <span v-show="isTrashRoute">
                                    Thùng rác trống.
                                    <router-link to="/admin/role">Danh sách vai trò</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có vai trò nào.
                                    <router-link to="/admin/role/create">Thêm vai trò</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/role/trash">Thùng rác
                            <i class="fa-solid fa-trash admin-content__trash"></i>
                        </router-link>
                        <span class="header__notice admin-content__trash-notice">{{ deletedCount }}</span>
                    </template>
                </div>
            </div>
            <AdminPagination :totalPages="totalPages" :currentPage="currentPage"/>
        </div>
    </div>
</template>

<script>
import AdminPagination from '@/components/AdminPagination.vue';
import CheckboxTable from '@/components/CheckboxTable.vue';
import SortComponent from '@/components/SortComponent.vue';
import { swalFire, swalMixin } from '@/helpers/swal.js'
import { format, parseISO } from 'date-fns';

export default {
    components: {
        AdminPagination, SortComponent, CheckboxTable
    },
    data() {
        return {
            deletedCount: 0,
            sort: {}, totalPages: 0, currentPage: 1,
            selectedIds: [],
            isLoading: false,
            roles: [], permissions: []
        }
    },
    computed: {
        isTrashRoute() {
            return this.$route.path.includes('/trash');
        },
    },
    watch: {
        '$route': {
            handler: 'fetchData',
            immediate: true,
            deep: true,
        },
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            const endpoint = this.isTrashRoute ? '/trashed' : '';
            const params = new URLSearchParams(this.$route.query).toString();

            try {
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/roles${endpoint}?${params}`);
                this.roles = res.data.data;
                this.totalPages = Math.ceil(res.data.pagination.total / res.data.pagination.per_page);
                this.currentPage = res.data.pagination.current_page;
                this.sort = res.data._sort;
                if (!this.isTrashRoute) {
                    await Promise.all([
                        this.fetchDeletedCount(), 
                        this.fetchPermissions()
                    ]);
                }
            } catch (error) {
                swalFire("Lỗi!", error, "error");
            } finally {
                this.isLoading = false;
            }
        },
        async fetchPermissions() {
            try {
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/permissions?all=true`);
                this.permissions = res.data.data;
            } catch (error) {
                swalFire("Lỗi!", error, "error");
            }
        },
        async fetchDeletedCount() {
            try {
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/roles/trashed`);
                this.deletedCount = res.data.pagination.total;
            } catch (error) {
                swalFire("Lỗi!", error, "error");
            }
        },
        onDelete(id) {
            this.$request.delete(`${process.env.VUE_APP_API_BASE_URL}/api/roles/${id}`).then(() => {
                this.swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success")
                .then(() => {
                    this.fetchData();
                })
                .catch(error => {
                    swalFire("Lỗi!", error, "error");
                })
            })
        },
        onRestore(id) {
            this.$request.patch(`${process.env.VUE_APP_API_BASE_URL}/api/roles/${id}/restore`).then(() => {
                this.swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success")
                .then(() => {
                    this.fetchData();
                })
                .catch(error => {
                    swalFire("Lỗi!", error, "error");
                })
            })
        },
        onForceDelete(id) {
            this.$swal.fire({
                title: "Bạn chắc chắn?",
                text: "Bạn sẽ không thể khôi phục lại dữ liệu!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Có, tôi muốn xóa!"
            }).then((result) => {
            if (result.isConfirmed) {
                this.$request.delete(`${process.env.VUE_APP_API_BASE_URL}/api/roles/${id}/force-delete`).then(() => {
                    this.swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success")
                    .then(() => {
                        this.fetchData();
                    })
                    .catch(error => {
                        swalFire("Lỗi!", error, "error");
                    })
                })
            }
            });
        },
        handleUpdateIds(ids) {
            this.selectedIds = ids;
        },
        handleFormActions() {
            const action = this.$refs.selectCheckboxAction.value;
            let targetId;
            let isFiltered = action.startsWith("filterBy");

            if (!action) {
                this.swalFire("Lỗi!", "Vui lòng chọn hành động.", "error");
                return;
            }
            switch (action) {
                case 'addPermission':
                case 'removePermission':
                case 'filterByPermission':
                    targetId = this.$refs.selectedPermission.value;
                    if (!targetId) {
                        this.swalFire("Lỗi!", "Vui lòng chọn phân quyền để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                default:
                    break;
            }
            if (isFiltered) {
                this.$router.push({ query: { action, targetId } });
                return;
            }
            if (this.selectedIds.length === 0) {
                this.swalFire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error");
                return;
            }
            this.$request.post(`${process.env.VUE_APP_API_BASE_URL}/api/roles/handle-form-actions`, {
                action,
                selectedIds: this.selectedIds,
                targetId
            }).then(() => {
                this.swalFire("Thực hiện thành công!", "Hành động của bạn đã được thực hiện thành công!", "success")
                .then(() => {
                    this.fetchData();
                    this.$refs.checkboxTable.resetCheckboxAll();
                });
            });
        },
        swalFire, swalMixin, 
        formatDate(dateString) {
            if (!dateString) {
                return;
            }
            return format(parseISO(dateString), 'yyyy-MM-dd HH:mm:ss');
        }
    }
}
</script>