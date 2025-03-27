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
                <h3 v-show="!isTrashRoute">Quản lý người dùng</h3>
                <h3 v-show="isTrashRoute">Quản lý thùng rác</h3>
                <router-link to="/admin/user/create" class="admin-content__create">Thêm người dùng</router-link>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4 v-show="!isTrashRoute">Tất cả người dùng</h4>
                    <h4 v-show="isTrashRoute">Người dùng đã xóa</h4>
                    <select ref="selectCheckboxAction" class="form-select admin-content__checkbox-select-all-opts">
                        <option value="" selected>-- Hành động --</option>
                        <template v-if="isTrashRoute">
                            <option value="forceDelete">Xóa vĩnh viễn</option>
                            <option value="restore">Khôi phục</option>
                        </template>
                        <template v-else>
                            <option value="delete">Xóa</option>
                            <option value="setRole">Đặt vai trò</option>
                            <option value="removeRole">Gỡ vai trò</option>
                            <option value="filterByRole">Lọc theo vai trò</option>
                            <option value="filterByStatus">Lọc theo trạng thái</option>
                        </template>
                    </select>
                    <select v-show="!isTrashRoute" ref="selectedRole" class="form-select admin-content__select-attribute admin-content__select-account">
                        <option value="" selected>-- Chọn vai trò --</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
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
                <CheckboxTable ref="checkboxTable" :items="users" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">Tên
                            <SortComponent field="name" :sort="sort"/>
                        </th>
                        <th scope="col">Ngày sinh
                            <SortComponent field="date_of_birth" :sort="sort"/>
                        </th>
                        <th scope="col">Email
                            <SortComponent field="email" :sort="sort"/>
                        </th>
                        <th scope="col">Điện thoại
                            <SortComponent field="phone" :sort="sort"/>
                        </th>
                        <th scope="col">Địa chỉ</th>
                        <th scope="col">Vai trò</th>
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
                        <th v-show="isTrashRoute" scope="col" class="col-3"></th>
                        <th v-show="!isTrashRoute" scope="col" class="col-2"></th>
                    </template>
                    <template #body="{ item }">
                        <th>{{ item.id }}</th>
                        <td>{{ item.name }}</td>
                        <td>{{ item.date_of_birth }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.phone }}</td>
                        <td>{{ formatAddress(item.address, item.ward_id, item.district_id, item.city_id) }}</td>
                        <td>{{ item.role?.name }}</td>
                        <td>{{ getStatusLabel(item.status) }}</td>
                        <template v-if="!isTrashRoute">
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ formatDate(item.updated_at) }}</td>
                        </template>
                        <td v-else>{{ formatDate(item.deleted_at) }}</td>
                        <td>
                            <template v-if="!isTrashRoute">
                                <router-link :to="'/admin/user/edit/' + item.id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
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
                                    <router-link to="/admin/user">Danh sách người dùng</router-link>
                                </span>
                                <span v-show="!isTrashRoute">
                                    Bạn chưa có người dùng nào.
                                    <router-link to="/admin/user/create">Thêm người dùng</router-link>
                                </span>
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer">
                    <template v-if="!isTrashRoute">
                        <router-link to="/admin/user/trash">Thùng rác
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
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';
import { formatDate, formatAddress } from '@/utils/formatter';
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
            users: [], roles: [],
            cities: [], districts: [], wards: [],
            statusOptions: statusService.getOptions('user'),
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
    async mounted() {
        await this.fetchLocations();
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            const [usersRes, ...others] = await Promise.all([
                handleApiCall(() => this.$request.get(apiService.users.get(this.$route.query, this.isTrashRoute))),
                ...(!this.isTrashRoute ? [
                    handleApiCall(() => this.$request.get(apiService.users.get({}, true))),
                    handleApiCall(() => this.$request.get(apiService.roles.get({}, false, true))),
                ] : [])
            ]);

            this.users = usersRes.data;
            this.totalPages = Math.ceil(usersRes.pagination.total / usersRes.pagination.per_page);
            this.currentPage = usersRes.pagination.current_page;
            this.sort = usersRes._sort;

            if (!this.isTrashRoute) {
                this.deletedCount = others[0]?.pagination?.total || 0;
                this.roles = others[1]?.data || []
            }
            this.isLoading = false;
        },
        async fetchLocations() {
            const [cities, districts, wards] = await Promise.all([
                handleApiCall(() => this.$request.get(apiService.getAllCities())),
                handleApiCall(() => this.$request.get(apiService.getAllDistricts())),
                handleApiCall(() => this.$request.get(apiService.getAllWards()))
            ])
            this.cities = cities;
            this.districts = districts;
            this.wards = wards;
        },
        async onDelete(id) {
            await handleApiCall(() => this.$request.delete(apiService.users.delete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa.", "success");
            await this.fetchData();
        },
        async onRestore(id) {
            await handleApiCall(() => this.$request.patch(apiService.users.restore(id)));
            await swalFire("Khôi phục thành công!", "Dữ liệu của bạn đã được khôi phục!", "success");
            await this.fetchData();
        },
        async onForceDelete(id) {
            const result = await swalConfirm("Bạn chắc chắn?", "Bạn sẽ không thể khôi phục lại dữ liệu!", "warning", "Có, tôi muốn xóa!");
            if (!result.isConfirmed) return;
            await handleApiCall(() => this.$request.delete(apiService.users.forceDelete(id)));
            await swalFire("Xóa thành công!", "Dữ liệu của bạn đã được xóa vĩnh viễn khỏi hệ thống.", "success");
            await this.fetchData();
        },
        async handleFormActions() {
            const { action, targetId, isFilterAction } = this.validateAndGetActionData();
            if (isFilterAction) {
                this.$router.push({ query: { action, targetId } });
                return;
            }
            if (this.selectedIds.length === 0) {
                swalFire("Lỗi!", "Vui lòng chọn ít nhất 1 bản ghi để thực hiện hành động.", "error");
                return;
            }
            await handleApiCall(() => this.$request.post(apiService.users.handleActions(), {
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
                case 'setRole':
                case 'filterByRole':
                    targetId = this.$refs.selectedRole.value;
                    if (!targetId) {
                        swalFire("Lỗi!", "Vui lòng chọn vai trò để thực hiện hành động.", "error");
                        return;
                    }
                    break;
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
        formatAddress(address, wardId, districtId, cityId) {
            return formatAddress(address, wardId, districtId, cityId, {
                cities: this.cities,
                districts: this.districts,
                wards: this.wards
            });
        },
        getStatusLabel(status) {
            return statusService.getLabel('user', status);
        }
    }
}
</script>