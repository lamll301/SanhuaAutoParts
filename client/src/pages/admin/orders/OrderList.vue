<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3 v-show="!isTrashRoute">Quản lý đơn hàng</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link v-show="!isTrashRoute" to="/admin/order" class="admin-content__title-link">
                        <h4>Tất cả đơn hàng</h4>
                    </router-link>
                    <router-link v-show="isTrashRoute" to="/admin/order/trash" class="admin-content__title-link">
                        <h4>Đơn hàng đã xóa</h4>
                    </router-link>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts" @change="updateCurrentAction">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="filterByUnapproved">Lọc đơn chưa duyệt</option>
                            <option value="filterByStatus">Lọc theo trạng thái</option>
                            <option value="filterByPaymentStatus">Lọc theo thanh toán</option>
                            <option value="filterByVoucher">Lọc theo voucher</option>
                        </template>
                    </select>
                    <select class="form-select admin-content__select-attribute admin-content__select-status" ref="selectedStatus">
                        <option value="" selected>-- Chọn trạng thái --</option>
                        <option v-for="([key, status]) in Object.entries(getAllStatusOptions('order'))" :key="key" :value="key">
                            {{ status }}
                        </option>
                    </select>
                    <select class="form-select admin-content__select-attribute" style="width: 15%;" ref="selectedPaymentStatus">
                        <option value="" selected>-- Chọn thanh toán --</option>
                        <option v-for="([key, status]) in Object.entries(getAllStatusOptions('payment'))" :key="key" :value="key">
                            {{ status }}
                        </option>
                    </select>
                    <select class="form-select admin-content__select-attribute" style="width: 14%;" ref="selectedVoucher">
                        <option value="" selected>-- Chọn voucher --</option>
                        <option v-for="voucher in vouchers" :key="voucher.id" :value="voucher.id">
                            {{ voucher.code }}
                        </option>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="orders" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Số điện thoại
                            <SortComponent field="phone" :sort="sort"/>
                        </th>
                        <th scope="col">Tiền hàng
                            <SortComponent field="product_total" :sort="sort"/>
                        </th>
                        <th scope="col">Phí ship
                            <SortComponent field="shipping_fee" :sort="sort"/>
                        </th>
                        <th scope="col">Giảm giá</th>
                        <th scope="col">Tổng tiền
                            <SortComponent field="total_amount" :sort="sort"/>
                        </th>
                        <th scope="col">Phương thức TT
                            <SortComponent field="payment_method" :sort="sort"/>
                        </th>
                        <th scope="col">Trạng thái TT
                            <SortComponent field="payment_status" :sort="sort"/>
                        </th>
                        <th scope="col">Thông tin TT</th>
                        <th scope="col">Trạng thái đơn hàng
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
                        <th scope="col" :class="isTrashRoute ? 'col-3' : 'col-2'"></th>
                    </template>
                    <template #body="{ item }">
                        <th>{{ item.id }}</th>
                        <td>{{ item.name }}</td>
                        <td>{{ item.phone }}</td>
                        <td>{{ formatPrice(item.product_total) }}</td>
                        <td>{{ formatPrice(item.shipping_fee) }}</td>
                        <td>{{ item.voucher ? formatPrice(item.voucher.value) + ' (' + item.voucher.code + ')' : 'Không có' }}</td>
                        <td>{{ formatPrice(item.total_amount) }}</td>
                        <td>{{ item.payment_method }}</td>
                        <td>{{ item.payment_method === 'Thanh toán khi nhận hàng' ? 'TT khi nhận' : getStatusText('payment', item.payment_status) }}</td>
                        <td>{{ item.payment_info }}</td>
                        <td>{{ getStatusText('order', item.status) }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="`/admin/order/view/${item.id}`" class="fs-16 btn btn-primary">Xem</router-link>&nbsp;
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
                            <td colspan="20" class="text-center">
                                <span v-show="isTrashRoute">
                                    Thùng rác trống.
                                    <router-link to="/admin/order">Danh sách đơn hàng</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có đơn hàng nào
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/order/trash">Thùng rác
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
import apiService from '@/utils/apiService';
import { getAllStatusOptions, getStatusText } from '@/utils/statusMap';

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
            orders: [], vouchers: [],
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
        formatDate, formatPrice, getStatusText, getAllStatusOptions,
        async fetchData() {
            try {
                const req = [
                    this.isTrashRoute
                        ? apiService.orders.getTrashed(this.$route.query)
                        : apiService.orders.get(this.$route.query)
                ];

                if (!this.isTrashRoute) {
                    req.push(
                        apiService.orders.getTrashed(),
                        apiService.vouchers.getAll()
                    );
                }
                
                const res = await this.$swal.withLoading(Promise.all(req))

                this.orders = res[0].data.data;
                this.totalPages = Math.ceil(res[0].data.pagination.total / res[0].data.pagination.per_page);
                this.currentPage = res[0].data.pagination.current_page;
                this.sort = res[0].data._sort;

                if (!this.isTrashRoute) {
                    this.deletedCount = res[1].data?.pagination?.total || 0;
                    this.vouchers = res[2].data.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async onDelete(id) {
            try {
                await apiService.orders.delete(id)
                await this.$swal.fire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success")
                await this.fetchData()
            } catch (error) {
                console.error(error)
            }
        },
        async onRestore(id) {
            try {
                await apiService.orders.restore(id)
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
                await apiService.orders.forceDelete(id);
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
                this.$router.push({ query: { action, targetId } });
                return;
            }
            if (this.selectedIds.length === 0) {
                this.$swal.fire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error");
                return;
            }
            try {
                await apiService.orders.handleFormActions({
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
                case 'filterByUnapproved':
                    targetId = null;
                    break;
                case 'filterByVoucher':
                    targetId = this.$refs.selectedVoucher.value;
                    if (!targetId) {
                        this.$swal.fire("Lỗi!", "Vui lòng chọn voucher để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                case 'filterByPaymentStatus':
                    targetId = this.$refs.selectedPaymentStatus.value;
                    if (!targetId) {
                        this.$swal.fire("Lỗi!", "Vui lòng chọn thanh toán để thực hiện hành động.", "error");
                        return;
                    }
                    break;
                case 'filterByStatus':
                    targetId = this.$refs.selectedStatus.value;
                    if (!targetId) {
                        this.$swal.fire("Lỗi!", "Vui lòng chọn trạng thái để thực hiện hành động.", "error");
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
        updateCurrentAction() {
            this.currentAction = this.$refs.selectCheckboxAction.value;
        },
    }
}
</script>