<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3 v-show="!isTrashRoute">Quản lý vị trí kho</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/location/create" class="admin-content__create">Thêm vị trí kho</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link v-show="!isTrashRoute" to="/admin/location" class="admin-content__title-link">
                        <h4>Tất cả vị trí kho</h4>
                    </router-link>
                    <router-link v-show="isTrashRoute" to="/admin/location/trash" class="admin-content__title-link">
                        <h4>Vị trí kho đã xóa</h4>
                    </router-link>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="setCategory">Đặt danh mục</option>
                            <option value="filterByCategory">Lọc theo danh mục</option>
                            <option value="setStatus">Đặt trạng thái</option>
                            <option value="filterByStatus">Lọc theo trạng thái</option>
                        </template>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedCategory" class="form-select admin-content__select-attribute admin-content__select-category">
                        <option value="" selected>-- Chọn danh mục --</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedStatus" class="form-select admin-content__select-attribute admin-content__select-status">
                        <option value="" selected>-- Chọn trạng thái --</option>
                        <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="handleFormActions()">Thực hiện</button>
                </div>
                <CheckboxTable ref="checkboxTable" :items="locations" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Khu
                            <SortComponent field="zone" :sort="sort"/>
                        </th>
                        <th scope="col">Lối
                            <SortComponent field="aisle" :sort="sort"/>
                        </th>
                        <th scope="col">Kệ
                            <SortComponent field="rack" :sort="sort"/>
                        </th>
                        <th scope="col">Tầng
                            <SortComponent field="shelf" :sort="sort"/>
                        </th>
                        <th scope="col">Ngăn
                            <SortComponent field="bin" :sort="sort"/>
                        </th>
                        <th scope="col">Danh mục</th>
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
                        <td>{{ item.zone }}</td>
                        <td>{{ item.aisle }}</td>
                        <td>{{ item.rack }}</td>
                        <td>{{ item.shelf }}</td>
                        <td>{{ item.bin }}</td>
                        <td>{{ item.category?.name }}</td>
                        <td>{{ getStatusLabel(item.status) }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/location/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/location">Danh sách vị trí kho</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có vị trí kho nào.
                                    <router-link to="/admin/location/create">Thêm vị trí kho</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/location/trash">Thùng rác
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
import apiService from '@/utils/apiService';
import { formatDate } from '@/utils/formatter';
import { statusService } from '@/utils/statusMap';

export default {
    components: {
        AdminPagination, SortComponent, CheckboxTable
    },
    data() {
        return {
            deletedCount: 0,
            sort: {}, totalPages: 0, currentPage: 1,
            selectedIds: [],
            locations: [], categories: [],
            statusOptions: statusService.getOptions('location'),
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
                        ? apiService.locations.getTrashed(this.$route.query)
                        : apiService.locations.get(this.$route.query)
                ];

                if (!this.isTrashRoute) {
                    req.push(
                        apiService.locations.getTrashed(),
                        apiService.categories.getAll(),
                    );
                }
                
                const res = await this.$swal.withLoading(Promise.all(req))

                this.locations = res[0].data.data;
                this.totalPages = Math.ceil(res[0].data.pagination.total / res[0].data.pagination.per_page);
                this.currentPage = res[0].data.pagination.current_page;
                this.sort = res[0].data._sort;
    
                if (!this.isTrashRoute) {
                    this.deletedCount = res[1].data?.pagination?.total || 0;
                    this.categories = res[2].data?.data || []
                }
            } catch (error) {
                console.error(error)
            }
        },
        async onDelete(id) {
            try {
                await apiService.locations.delete(id)
                await this.$swal.fire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success")
                await this.fetchData()
            } catch (error) {
                console.error(error)
            }
        },
        async onRestore(id) {
            try {
                await apiService.locations.restore(id)
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
                await apiService.locations.forceDelete(id);
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
                this.$swal.fire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error")
                return;
            }
            try {
                await apiService.locations.handleFormActions({
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
                this.$swal.fire("Lỗi!", "Vui lòng chọn hành động.", "error")
                return;
            }
            switch (action) {
                case 'setCategory':
                case 'filterByCategory':
                    targetId = this.$refs.selectedCategory.value;
                    if (!targetId) {
                        this.$swal.fire("Lỗi!", "Vui lòng chọn danh mục để thực hiện hành động.", "error")
                        return;
                    }
                    break;
                case 'setStatus':
                case 'filterByStatus':
                    targetId = this.$refs.selectedStatus.value;
                    if (!targetId) {
                        this.$swal.fire("Lỗi!", "Vui lòng chọn trạng thái để thực hiện hành động.", "error")
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
        getStatusLabel(status) {
            return statusService.getLabel('location', status);
        },
    }
}
</script>