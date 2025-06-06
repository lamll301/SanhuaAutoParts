<template>
    <div class="admin-page">
        <div class="admin-content">
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
                        </template>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedProduct" class="form-select admin-content__select-attribute admin-content__select-promotion">
                        <option value="" selected>-- Chọn sản phẩm --</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">
                            {{ product.name }}
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
                        <th scope="col">Số lô
                            <SortComponent field="batch_number" :sort="sort"/>
                        </th>
                        <th scope="col">Số lượng
                            <SortComponent field="quantity" :sort="sort"/>
                        </th>
                        <th scope="col">Đơn vị tính</th>
                        <th scope="col">Giá
                            <SortComponent field="price" :sort="sort"/>
                        </th>
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
                        <td>{{ item.batch_number }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.product?.unit?.name }}</td>
                        <td>{{ formatPrice(item.price) }}</td>
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
import { formatDate, formatPrice } from '@/utils/helpers';
import { inventoryApi, productApi } from '@/api';

export default {
    components: {
        AdminPagination, SortComponent, CheckboxTable
    },
    data() {
        return {
            deletedCount: 0,
            sort: {}, totalPages: 0, currentPage: 1,
            selectedIds: [],
            inventories: [], products: [],
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
        formatDate, formatPrice,
        async fetchData() {
            try {
                const req = [
                    this.isTrashRoute
                        ? inventoryApi.getTrashed(this.$route.query)
                        : inventoryApi.get(this.$route.query)
                ];

                if (!this.isTrashRoute) {
                    req.push(
                        inventoryApi.getTrashed(),
                        productApi.getAll(),
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req))
    
                this.inventories = res[0].data.data;
                this.totalPages = Math.ceil(res[0].data.pagination.total / res[0].data.pagination.per_page);
                this.currentPage = res[0].data.pagination.current_page;
                this.sort = res[0].data._sort;
    
                if (!this.isTrashRoute) {
                    this.deletedCount = res[1].data?.pagination?.total || 0;
                    this.products = res[2].data?.data || []
                }
            } catch (error) {
                console.error(error);
            }
        },
        async onDelete(id) {
            try {
                await inventoryApi.delete(id)
                await this.$swal.fire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success")
                await this.fetchData()
            } catch (error) {
                console.error(error)
            }
        },
        async onRestore(id) {
            try {
                await inventoryApi.restore(id)
                await this.$swal.fire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success")
                await this.fetchData()
            } catch (error) {
                console.error(error)
            }
        },
        async onForceDelete(id) {
            const result = await this.$swal.fire("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", {
                showCancelButton: true,
                confirmButtonText: "Có, tôi muốn xóa!",
                cancelButtonText: "Hủy"
            })
            if (!result.isConfirmed) return;
            try {
                await inventoryApi.forceDelete(id);
                await this.$swal.fire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success")
                await this.fetchData();
            } catch (error) {
                console.error(error)
            }
        },
        async handleFormActions() {
            const actionData = this.validateAndGetActionData();
            if (!actionData) return;

            const { action, targetId, isFilterAction } = actionData;
            if (isFilterAction) {
                this.$router.push({ 
                    query: { 
                        ...this.$route.query,
                        action, 
                        targetId 
                    } 
                });
                return;
            }
            if (this.selectedIds.length === 0) {
                this.$swal.fire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error")
                return;
            }
            try {
                await inventoryApi.handleFormActions({
                    action,
                    selectedIds: this.selectedIds,
                    targetId
                })
                await this.$swal.fire("Thực hiện thành công!", "Hành động của bạn đã được thực hiện thành công!", "success")
                await this.fetchData()
                await this.$refs.checkboxTable.resetCheckboxAll()
            } catch (error) {
                console.error(error)
            }
        },
        validateAndGetActionData() {
            const action = this.$refs.selectCheckboxAction.value;
            let targetId;
            if (!action) {
                this.$swal.fire("Lỗi!", "Vui lòng chọn hành động.", "error");
                return;
            }
            switch (action) {
                case 'setProduct':
                case 'filterByProduct':
                    targetId = this.$refs.selectedProduct.value;
                    if (!targetId) {
                        this.$swal.fire("Lỗi!", "Vui lòng chọn sản phẩm để thực hiện hành động.", "error");
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
    }
}
</script>