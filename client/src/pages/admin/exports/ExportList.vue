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
                <h3 v-show="!isTrashRoute">Quản lý phiếu xuất</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/export/create" class="admin-content__create">Thêm phiếu xuất</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4 v-show="!isTrashRoute">Tất cả phiếu xuất</h4>
                    <h4 v-show="isTrashRoute">Phiếu xuất đã xóa</h4>
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
                <CheckboxTable ref="checkboxTable" :items="exports" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Người nhận
                            <SortComponent field="receiver" :sort="sort"/>
                        </th>
                        <th scope="col">Địa chỉ
                            <SortComponent field="address" :sort="sort"/>
                        </th>
                        <th scope="col">Lý do
                            <SortComponent field="reason" :sort="sort"/>
                        </th>
                        <th scope="col">Ngày xuất
                            <SortComponent field="export_date" :sort="sort"/>
                        </th>
                        <th scope="col">Tổng tiền
                            <SortComponent field="total_amount" :sort="sort"/>
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
                        <td>{{ item.receiver }}</td>
                        <td>{{ item.address }}</td>
                        <td>{{ item.reason }}</td>
                        <td>{{ item.export_date }}</td>
                        <td>{{ formatPrice(item.total_amount) }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/export/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/export">Danh sách phiếu xuất</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có phiếu xuất nào.
                                    <router-link to="/admin/export/create">Thêm phiếu xuất</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/export/trash">Thùng rác
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
import { formatDate, formatPrice } from '@/utils/formatter';
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
            exports: [],
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
                const res = await handleApiCall(() => 
                    this.$request.get(apiService.exports.get(this.$route.query, this.isTrashRoute))
                );

                this.exports = res.data;
                this.totalPages = Math.ceil(res.pagination.total / res.pagination.per_page);
                this.currentPage = res.pagination.current_page;
                this.sort = res._sort;

                if (!this.isTrashRoute) {
                    const resDeleted = await handleApiCall(() => 
                        this.$request.get(apiService.exports.get({}, true))
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
            await handleApiCall(() => this.$request.delete(apiService.exports.delete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success");
            await this.fetchData();
        },
        async onRestore(id) {
            await handleApiCall(() => this.$request.patch(apiService.exports.restore(id)));
            await swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success");
            await this.fetchData();
        },
        async onForceDelete(id) {
            const result = await swalConfirm("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", "Có, tôi muốn xóa!");
            if (!result.isConfirmed) return;
            await handleApiCall(() => this.$request.delete(apiService.exports.forceDelete(id)));
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
            await handleApiCall(() => this.$request.post(apiService.exports.handleActions(), {
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
        formatPrice(price) {
            return formatPrice(price);
        },
    }
}
</script>