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
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link v-show="!isTrashRoute" to="/admin/permission" class="admin-content__title-link">
                        <h4>Tất cả phân quyền</h4>
                    </router-link>
                    <router-link v-show="isTrashRoute" to="/admin/permission/trash" class="admin-content__title-link">
                        <h4>Phân quyền đã xóa</h4>
                    </router-link>
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
import { swalFire, swalConfirm } from '@/utils/swal';
import { formatDate } from '@/utils/formatter';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';

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
            try {
                const resPermissions = await handleApiCall(() => 
                    this.$request.get(apiService.permissions.get(this.$route.query, this.isTrashRoute))
                );
    
                this.permissions = resPermissions.data;
                this.totalPages = Math.ceil(resPermissions.pagination.total / resPermissions.pagination.per_page);
                this.currentPage = resPermissions.pagination.current_page;
                this.sort = resPermissions._sort;
    
                if (!this.isTrashRoute) {
                    const resDeleted = await handleApiCall(() => 
                        this.$request.get(apiService.permissions.get({}, true))
                    );
                    this.deletedCount = resDeleted?.pagination?.total || 0;
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        async onDelete(id) {
            await handleApiCall(() => this.$request.delete(apiService.permissions.delete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success");
            await this.fetchData();
        },
        async onRestore(id) {
            await handleApiCall(() => this.$request.patch(apiService.permissions.restore(id)));
            await swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success");
            await this.fetchData();
        },
        async onForceDelete(id) {
            const result = await swalConfirm("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", "Có, tôi muốn xóa!");
            if (!result.isConfirmed) return;
            await handleApiCall(() => this.$request.delete(apiService.permissions.forceDelete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success");
            await this.fetchData();
        },
        async handleFormActions() {
            const action = this.$refs.selectCheckboxAction.value;
            if (!action) {
                swalFire("Lỗi!", "Vui lòng chọn hành động.", "error");
                return;
            }
            if (this.selectedIds.length === 0) {
                swalFire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error");
                return;
            }
            await handleApiCall(() => this.$request.post(apiService.permissions.handleActions(), {
                action,
                selectedIds: this.selectedIds,
            }));
            await swalFire("Thực hiện thành công!", "Hành động của bạn đã được thực hiện thành công!", "success");
            await this.fetchData();
            await this.$refs.checkboxTable.resetCheckboxAll();
        },
        handleUpdateIds(ids) {
            this.selectedIds = ids;
        },
        formatDate(date) {
            return formatDate(date);
        },
    }
}
</script>