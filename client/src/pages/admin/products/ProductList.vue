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
                <h3 v-show="!isTrashRoute">Quản lý sản phẩm</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/product/create" class="admin-content__create">Thêm sản phẩm</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link v-show="!isTrashRoute" to="/admin/product" class="admin-content__title-link">
                        <h4>Tất cả sản phẩm</h4>
                    </router-link>
                    <router-link v-show="isTrashRoute" to="/admin/product/trash" class="admin-content__title-link">
                        <h4>Sản phẩm đã xóa</h4>
                    </router-link>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts" @change="updateCurrentAction">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="addCategory">Thêm danh mục</option>
                            <option value="removeCategory">Xóa danh mục</option>
                            <option value="filterByCategory">Lọc theo danh mục</option>
                            <option value="setSupplier">Đặt nhà cung cấp</option>
                            <option value="filterBySupplier">Lọc theo nhà cung cấp</option>
                            <option value="setPromotion">Đặt khuyến mãi</option>
                            <option value="removePromotion">Gỡ khuyến mãi</option>
                            <option value="filterByPromotion">Lọc theo khuyến mãi</option>
                            <option value="setUnit">Đặt đơn vị tính</option>
                            <option value="filterByUnit">Lọc theo đơn vị tính</option>
                            <option value="setStatus">Đặt trạng thái</option>
                            <option value="filterByStatus">Lọc theo trạng thái</option>
                        </template>
                    </select>
                    <select v-show="['addCategory', 'removeCategory', 'filterByCategory'].includes(currentAction)"
                        ref="selectedCategory"
                        class="form-select admin-content__select-attribute admin-content__select-category"
                    >
                        <option value="" selected>-- Chọn danh mục --</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-show="['setSupplier', 'filterBySupplier'].includes(currentAction)"
                        ref="selectedSupplier" 
                        class="form-select admin-content__select-attribute admin-content__select-supplier"
                    >
                        <option value="" selected>-- Chọn nhà cung cấp --</option>
                        <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                            {{ supplier.name }}
                        </option>
                    </select>
                    <select v-show="['setPromotion', 'filterByPromotion'].includes(currentAction)" 
                        ref="selectedPromotion" 
                        class="form-select admin-content__select-attribute admin-content__select-promotion"
                    >
                        <option value="" selected>-- Chọn khuyến mãi --</option>
                        <option v-for="promotion in promotions" :key="promotion.id" :value="promotion.id">
                            {{ promotion.name }}
                        </option>
                    </select>
                    <select v-show="['setUnit', 'filterByUnit'].includes(currentAction)" 
                        ref="selectedUnit" 
                        class="form-select admin-content__select-attribute admin-content__select-promotion"
                    >
                        <option value="" selected>-- Chọn đơn vị tính --</option>
                        <option v-for="unit in units" :key="unit.id" :value="unit.id">
                            {{ unit.name }}
                        </option>
                    </select>
                    <select v-show="['setStatus', 'filterByStatus'].includes(currentAction)"  
                        ref="selectedStatus" 
                        class="form-select admin-content__select-attribute admin-content__select-status"
                    >
                        <option value="" selected>-- Chọn trạng thái --</option>
                        <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="products" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Nhà cung cấp</th>
                        <th scope="col">Giá bán
                            <SortComponent field="price" :sort="sort"/>
                        </th>
                        <th scope="col">Giá gốc
                            <SortComponent field="original_price" :sort="sort"/>
                        </th>
                        <th scope="col">Khuyến mãi</th>
                        <th scope="col">Số lượng
                            <SortComponent field="quantity" :sort="sort"/>
                        </th>
                        <th scope="col">Đơn vị tính</th>
                        <th scope="col">Trạng thái</th>
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
                        <th scope="col" :class="isTrashRoute ? 'col-3' : 'col-2'"></th>
                    </template>
                    <template #body="{ item }">
                        <th>{{ item.id }}</th>
                        <td>{{ item.name }}</td>
                        <td>{{ item.categories.map(category => category.name).join(', ') }}</td>
                        <td>{{ item.supplier?.name }}</td>
                        <td>{{ formatPrice(item.price) }}</td>
                        <td>{{ formatPrice(item.original_price) }}</td>
                        <td>{{ item.promotion?.discount_percent ? `-${item.promotion.discount_percent}%` : '0%' }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.unit?.name }}</td>
                        <td>{{ getStatusLabel(item.status) }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/product/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                            <td colspan="14" class="text-center">
                                <span v-show="isTrashRoute">
                                    Thùng rác trống.
                                    <router-link to="/admin/product">Danh sách sản phẩm</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có sản phẩm nào.
                                    <router-link to="/admin/product/create">Thêm sản phẩm</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/product/trash">Thùng rác
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
            currentAction: '',
            deletedCount: 0,
            sort: {}, totalPages: 0, currentPage: 1,
            selectedIds: [],
            isLoading: false,
            products: [], categories: [], suppliers: [], promotions: [], units: [],
            statusOptions: statusService.getOptions('product'),
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
                    handleApiCall(() => this.$request.get(apiService.products.get(this.$route.query, this.isTrashRoute))),
                    ...(!this.isTrashRoute ? [
                        handleApiCall(() => this.$request.get(apiService.products.get({}, true))),
                        handleApiCall(() => this.$request.get(apiService.categories.get({}, false, true))),
                        handleApiCall(() => this.$request.get(apiService.suppliers.get({}, false, true))),
                        handleApiCall(() => this.$request.get(apiService.promotions.get({}, false, true))),
                        handleApiCall(() => this.$request.get(apiService.units.get({}, false, true))),
                    ] : [])
                ]);

                this.products = res.data;
                this.totalPages = Math.ceil(res.pagination.total / res.pagination.per_page);
                this.currentPage = res.pagination.current_page;
                this.sort = res._sort;

                if (!this.isTrashRoute) {
                    this.deletedCount = others[0]?.pagination?.total || 0;
                    this.categories = others[1]?.data || []
                    this.suppliers = others[2]?.data || []
                    this.promotions = others[3]?.data || []
                    this.units = others[4]?.data || []
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        async onDelete(id) {
            await handleApiCall(() => this.$request.delete(apiService.products.delete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success");
            await this.fetchData();
        },
        async onRestore(id) {
            await handleApiCall(() => this.$request.patch(apiService.products.restore(id)));
            await swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success");
            await this.fetchData();
        },
        async onForceDelete(id) {
            const result = await swalConfirm("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", "Có, tôi muốn xóa!");
            if (!result.isConfirmed) return;
            await handleApiCall(() => this.$request.delete(apiService.products.forceDelete(id)));
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
            await handleApiCall(() => this.$request.post(apiService.products.handleActions(), {
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
                case 'addCategory':
                case 'removeCategory':
                case 'filterByCategory':
                    targetId = this.$refs.selectedCategory.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn danh mục để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                case 'setSupplier':
                case 'filterBySupplier':
                    targetId = this.$refs.selectedSupplier.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn nhà cung cấp để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                case 'setPromotion':
                case 'filterByPromotion':
                    targetId = this.$refs.selectedPromotion.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn khuyến mãi để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                case 'setUnit':
                case 'filterByUnit':
                    targetId = this.$refs.selectedUnit.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn đơn vị tính để thực hiện hành động.", "error");
                        return;
                    }
                    break;
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
            return statusService.getLabel('product', status);
        },
        updateCurrentAction() {
            this.currentAction = this.$refs.selectCheckboxAction.value;
        },
    }
}
</script>