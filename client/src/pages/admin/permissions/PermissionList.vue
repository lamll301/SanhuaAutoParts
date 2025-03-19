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
                <h3 v-show="!isTrashRoute">Quản lý phân quyền</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/permission/create" class="admin-content__create">Thêm phân quyền</router-link>
            </div>
            <!-- admin table -->
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4 v-show="!isTrashRoute">Tất cả phân quyền</h4>
                    <h4 v-show="isTrashRoute">Phân quyền đã xóa</h4>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                        </template>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="permissions" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Mô tả
                            <SortComponent field="description" :sort="sort"/>
                        </th>
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
                        <td>{{ item.description }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/permission/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/permission">Danh sách phân quyền</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có phân quyền nào.
                                    <router-link to="/admin/permission/create">Thêm phân quyền</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/permission/trash">Thùng rác
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
            permissions: [],
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
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/permissions${endpoint}?${params}`);
                this.permissions = res.data.data;
                this.totalPages = Math.ceil(res.data.pagination.total / res.data.pagination.per_page);
                this.currentPage = res.data.pagination.current_page;
                this.sort = res.data._sort;
                if (!this.isTrashRoute) {
                    await this.fetchDeletedCount();
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },
        async fetchDeletedCount() {
            try {
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/trashed?count=true`);
                this.deletedCount = res.data.count;
            } catch (error) {
                console.log(error);
            }
        },
        onDelete(id) {
            this.$request.delete(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/${id}`).then(() => {
                this.swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success")
                .then(() => {
                    this.fetchData();
                })
                .catch(error => {
                    console.log(error)
                })
            })
        },
        onRestore(id) {
            this.$request.patch(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/${id}/restore`).then(() => {
                this.swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success")
                .then(() => {
                    this.fetchData();
                })
                .catch(error => {
                    console.log(error)
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
                this.$request.delete(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/${id}/force-delete`).then(() => {
                    this.swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success")
                    .then(() => {
                        this.fetchData();
                    })
                    .catch(error => {
                        console.log(error)
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

            if (!action || this.selectedIds.length === 0) {
                return;
            }

            this.$request.post(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/handle-form-actions`, {
                action,
                selectedIds: this.selectedIds,
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
                return
            }
            return format(parseISO(dateString), 'yyyy-MM-dd HH:mm:ss');
        }
    }
}
</script>