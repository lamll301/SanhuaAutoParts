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
                <h3 v-show="!isTrashRoute">Quản lý hàng tồn kho</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/inventory/create" class="admin-content__create">Thêm hàng tồn kho</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link v-show="!isTrashRoute" to="/admin/inventory" class="admin-content__title-link">
                        <h4>Tất cả hàng tồn kho</h4>
                    </router-link>
                    <router-link v-show="isTrashRoute" to="/admin/inventory/trash" class="admin-content__title-link">
                        <h4>Hàng tồn kho đã xóa</h4>
                    </router-link>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="setProduct">Đặt sản phẩm</option>
                            <option value="filterByProduct">Lọc theo sản phẩm</option>
                            <option value="setImport">Đặt phiếu nhập</option>
                            <option value="filterByImport">Lọc theo phiếu nhập</option>
                        </template>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedProduct" class="form-select admin-content__select-attribute admin-content__select-promotion">
                        <option value="" selected>-- Chọn sản phẩm --</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">
                            {{ product.name }}
                        </option>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedImport" class="form-select admin-content__select-attribute admin-content__select-promotion">
                        <option value="" selected>-- Chọn phiếu nhập --</option>
                        <option v-for="importReceipt in imports" :key="importReceipt.id" :value="importReceipt.id">
                            {{ importReceipt.id }}
                        </option>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="inventories" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">ID phiếu nhập</th>
                        <th scope="col">Vị trí
                            <SortComponent field="location" :sort="sort"/>
                        </th>
                        <th scope="col">Số lô
                            <SortComponent field="batch_number" :sort="sort"/>
                        </th>
                        <th scope="col">Số lượng
                            <SortComponent field="quantity" :sort="sort"/>
                        </th>
                        <th scope="col">Đơn vị tính</th>
                        <th scope="col">Ngày sản xuất
                            <SortComponent field="manufacture_date" :sort="sort"/>
                        </th>
                        <th scope="col">Ngày hết hạn
                            <SortComponent field="expiry_date" :sort="sort"/>
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
                        <td>{{ item.product?.name }}</td>
                        <td>{{ item.import?.id }}</td>
                        <td>{{ item.location }}</td>
                        <td>{{ item.batch_number }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.product?.unit?.name }}</td>
                        <td>{{ item.manufacture_date }}</td>
                        <td>{{ item.expiry_date }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/inventory/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/inventory">Danh sách hàng tồn kho</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có hàng tồn kho nào.
                                    <router-link to="/admin/inventory/create">Thêm hàng tồn kho</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/inventory/trash">Thùng rác
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
            inventories: [], products: [], imports: [],
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
                const [res, ...others] = await Promise.all([
                    handleApiCall(() => this.$request.get(apiService.inventories.get(this.$route.query, this.isTrashRoute))),
                    ...(!this.isTrashRoute ? [
                        handleApiCall(() => this.$request.get(apiService.inventories.get({}, true))),
                        handleApiCall(() => this.$request.get(apiService.products.get({}, false, true))),
                        handleApiCall(() => this.$request.get(apiService.imports.get({}, false, true))),
                    ] : [])
                ]);
    
                this.inventories = res.data;
                this.totalPages = Math.ceil(res.pagination.total / res.pagination.per_page);
                this.currentPage = res.pagination.current_page;
                this.sort = res._sort;
    
                if (!this.isTrashRoute) {
                    this.deletedCount = others[0]?.pagination?.total || 0;
                    this.products = others[1]?.data || []
                    this.imports = others[2]?.data || []
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        async onDelete(id) {
            await handleApiCall(() => this.$request.delete(apiService.inventories.delete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success");
            await this.fetchData();
        },
        async onRestore(id) {
            await handleApiCall(() => this.$request.patch(apiService.inventories.restore(id)));
            await swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success");
            await this.fetchData();
        },
        async onForceDelete(id) {
            const result = await swalConfirm("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", "Có, tôi muốn xóa!");
            if (!result.isConfirmed) return;
            await handleApiCall(() => this.$request.delete(apiService.inventories.forceDelete(id)));
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
            await handleApiCall(() => this.$request.post(apiService.inventories.handleActions(), {
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
                case 'setProduct':
                case 'filterByProduct':
                    targetId = this.$refs.selectedProduct.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn sản phẩm để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                case 'setImport':
                case 'filterByImport':
                    targetId = this.$refs.selectedImport.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn đơn vị tính để thực hiện hành động.", "error");
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
    }
}
</script>