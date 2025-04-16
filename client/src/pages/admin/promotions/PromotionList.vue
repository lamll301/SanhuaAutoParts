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
                <h3 v-show="!isTrashRoute">Quản lý khuyến mãi</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/promotion/create" class="admin-content__create">Thêm khuyến mãi</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4 v-show="!isTrashRoute">Tất cả khuyến mãi</h4>
                    <h4 v-show="isTrashRoute">Khuyến mãi đã xóa</h4>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="setStatus">Đặt trạng thái</option>
                            <option value="filterByStatus">Lọc theo trạng thái</option>
                        </template>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedStatus" class="form-select admin-content__select-attribute admin-content__select-status">
                        <option value="" selected>-- Chọn trạng thái --</option>
                        <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="promotions" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Phần trăm giảm
                            <SortComponent field="discount_percent" :sort="sort"/>
                        </th>
                        <th scope="col">Tiền giảm tối đa
                            <SortComponent field="max_discount_amount" :sort="sort"/>
                        </th>
                        <th scope="col">Ngày bắt đầu
                            <SortComponent field="start_date" :sort="sort"/>
                        </th>
                        <th scope="col">Ngày kết thúc
                            <SortComponent field="end_date" :sort="sort"/>
                        </th>
                        <th scope="col">Trạng thái
                            <SortComponent field="status" :sort="sort"/>
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
                        <td>{{ item.discount_percent }}</td>
                        <td>{{ formatPrice(item.max_discount_amount) }}</td>
                        <td>{{ item.start_date }}</td>
                        <td>{{ item.end_date }}</td>
                        <td>{{ getStatusLabel(item.status) }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/promotion/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/promotion">Danh sách khuyến mãi</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có khuyến mãi nào.
                                    <router-link to="/admin/promotion/create">Thêm khuyến mãi</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/promotion/trash">Thùng rác
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
import { statusService } from '@/utils/statusService';

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
            promotions: [],
            statusOptions: statusService.getOptions('promotion'),
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
                const responseData = await handleApiCall(() => 
                    this.$request.get(apiService.promotions.get(this.$route.query, this.isTrashRoute))
                );
    
                this.promotions = responseData.data;
                this.totalPages = Math.ceil(responseData.pagination.total / responseData.pagination.per_page);
                this.currentPage = responseData.pagination.current_page;
                this.sort = responseData._sort;
    
                if (!this.isTrashRoute) {
                    const resDeleted = await handleApiCall(() => 
                        this.$request.get(apiService.promotions.get({}, true))
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
            await handleApiCall(() => this.$request.delete(apiService.promotions.delete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success");
            await this.fetchData();
        },
        async onRestore(id) {
            await handleApiCall(() => this.$request.patch(apiService.promotions.restore(id)));
            await swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success");
            await this.fetchData();
        },
        async onForceDelete(id) {
            const result = await swalConfirm("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", "Có, tôi muốn xóa!");
            if (!result.isConfirmed) return;
            await handleApiCall(() => this.$request.delete(apiService.promotions.forceDelete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success");
            await this.fetchData();
        },
        async handleFormActions() {
            const actionData = this.validateAndGetActionData();
            if (!actionData) return;
            const { action, targetId, isFilterAction } = actionData;
            if (isFilterAction) {
                this.$router.push({ query: { action, targetId } });
                return;
            }
            if (this.selectedIds.length === 0) {
                swalFire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error");
                return;
            }
            await handleApiCall(() => this.$request.post(apiService.promotions.handleActions(), {
                action,
                selectedIds: this.selectedIds,
                targetId
            }));
            await swalFire("Thực hiện thành công!", "Hành động của bạn đã được thực hiện thành công!", "success");
            await this.fetchData();
            await this.$refs.checkboxTable.resetCheckboxAll();
        },
        validateAndGetActionData() {
            const action = this.$refs.selectCheckboxAction.value;
            let targetId;
            if (!action) {
                swalFire("Lỗi!", "Vui lòng chọn hành động.", "error");
                return;
            }
            switch (action) {
                case 'setStatus':
                case 'filterByStatus':
                    targetId = this.$refs.selectedStatus.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn trạng thái để thực hiện hành động.", "error");
                        return;
                    }
                    break;
            }
            return {
                action,
                targetId,
                isFilterAction: action.startsWith("filterBy")
            };
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
        getStatusLabel(status) {
            return statusService.getLabel('promotion', status);
        }
    }
}
</script>