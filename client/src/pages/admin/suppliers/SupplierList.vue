<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3 v-show="!isTrashRoute">Quản lý nhà cung cấp</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/supplier/create" class="admin-content__create">Thêm nhà cung cấp</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link v-show="!isTrashRoute" to="/admin/supplier" class="admin-content__title-link">
                        <h4>Tất cả nhà cung cấp</h4>
                    </router-link>
                    <router-link v-show="isTrashRoute" to="/admin/supplier/trash" class="admin-content__title-link">
                        <h4>Nhà cung cấp đã xóa</h4>
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
                <CheckboxTable ref="checkboxTable" :items="suppliers" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Email
                            <SortComponent field="email" :sort="sort"/>
                        </th>
                        <th scope="col">Số điện thoại
                            <SortComponent field="phone" :sort="sort"/>
                        </th>
                        <th scope="col">Địa chỉ
                            <SortComponent field="address" :sort="sort"/>
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
                        <td>{{ item.email }}</td>
                        <td>{{ item.phone }}</td>
                        <td>{{ item.address }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/supplier/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/supplier">Danh sách nhà cung cấp</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có nhà cung cấp nào.
                                    <router-link to="/admin/supplier/create">Thêm nhà cung cấp</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/supplier/trash">Thùng rác
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
import { formatDate } from '@/utils/helpers';
import { supplierApi } from '@/api';

export default {
    components: {
        AdminPagination, SortComponent, CheckboxTable
    },
    data() {
        return {
            deletedCount: 0,
            sort: {}, totalPages: 0, currentPage: 1,
            selectedIds: [],
            suppliers: [],
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
            try {
                const req = [
                    this.isTrashRoute
                        ? supplierApi.getTrashed(this.$route.query)
                        : supplierApi.get(this.$route.query)
                ];

                if (!this.isTrashRoute) {
                    req.push(
                        supplierApi.getTrashed(),
                    );
                }
                
                const res = await this.$swal.withLoading(Promise.all(req))

                this.suppliers = res[0].data.data;
                this.totalPages = Math.ceil(res[0].data.pagination.total / res[0].data.pagination.per_page);
                this.currentPage = res[0].data.pagination.current_page;
                this.sort = res[0].data._sort;
    
                if (!this.isTrashRoute) {
                    this.deletedCount = res[1].data?.pagination?.total || 0;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async onDelete(id) {
            try {
                await supplierApi.delete(id)
                await this.$swal.fire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success")
                await this.fetchData()
            } catch (error) {
                console.error(error)
            }
        },
        async onRestore(id) {
            try {
                await supplierApi.restore(id)
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
                await supplierApi.forceDelete(id);
                await this.$swal.fire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success")
                await this.fetchData();
            } catch (error) {
                console.error(error)
            }
        },
        async handleFormActions() {
            const action = this.$refs.selectCheckboxAction.value;
            if (!action) {
                this.$swal.fire("Lỗi!", "Vui lòng chọn hành động.", "error");
                return;
            }
            if (this.selectedIds.length === 0) {
                this.$swal.fire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error");
                return;
            }
            try {
                await supplierApi.handleFormActions({
                    action,
                    selectedIds: this.selectedIds,
                })
                await this.$swal.fire("Thực hiện thành công!", "Hành động của bạn đã được thực hiện thành công!", "success")
                await this.fetchData()
                await this.$refs.checkboxTable.resetCheckboxAll()
            } catch (e) {
                console.error(e)
                this.$swal.fire("Lỗi!", e.message, "error")
            }
        },
        handleUpdateIds(ids) {
            this.selectedIds = ids;
        },
        formatDate,
    }
}
</script>