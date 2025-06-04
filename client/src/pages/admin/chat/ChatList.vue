<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý tin nhắn</h3>
            </div>
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <router-link to="/admin/chat" class="admin-content__title-link">
                        <h4>Tất cả tin nhắn</h4>
                    </router-link>
                </div>
                <CheckboxTable ref="checkboxTable" :items="chats" @update:ids="handleUpdateIds">
                    <template #header>
                        <th scope="col">ID
                            <SortComponent field="id" :sort="sort"/>
                        </th>
                        <th scope="col">ID khách hàng</th>
                        <th scope="col">Tên tài khoản</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Email</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Tin nhắn cuối cùng
                            <SortComponent field="last_message_at" :sort="sort"/>
                        </th>
                        <th scope="col">Số tin nhắn chưa đọc</th>
                        <th scope="col" class="col-1"></th>
                    </template>
                    <template #body="{ item }">
                        <th>{{ item.id }}</th>
                        <td>{{ item.customer_id }}</td>
                        <td>{{ item?.customer?.username }}</td>
                        <td>{{ item?.customer?.name }}</td>
                        <td>{{ item?.customer?.email }}</td>
                        <td>{{ item?.customer?.phone }}</td>
                        <td>{{ item.last_message_at }}</td>
                        <td>{{ item.unread_count_by_staff }}</td>
                        <td>
                            <button class="fs-16 btn btn-primary" @click="onChat(item.id)">
                                <i class="fas fa-comments"></i> 
                            </button>
                        </td>
                    </template>
                    <template #empty>
                        <tr>
                            <td colspan="13" class="text-center">
                                Bạn chưa có tin nhắn nào.
                            </td>
                        </tr>
                    </template>
                </CheckboxTable>
                <div class="admin-content__table-footer"></div>
            </div>
            <AdminPagination :totalPages="totalPages" :currentPage="currentPage"/>
        </div>
    </div>
</template>

<script>
import AdminPagination from '@/components/AdminPagination.vue';
import CheckboxTable from '@/components/CheckboxTable.vue';
import SortComponent from '@/components/SortComponent.vue';
import { chatApi } from '@/api';

export default {
    components: {
        AdminPagination, SortComponent, CheckboxTable
    },
    data() {
        return {
            sort: {}, totalPages: 0, currentPage: 1,
            selectedIds: [],
            chats: [],
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
                const res = await this.$swal.withLoading(chatApi.get(this.$route.query))
                this.chats = res.data.data;
                this.totalPages = Math.ceil(res.data.pagination.total / res.data.pagination.per_page);
                this.currentPage = res.data.pagination.current_page;
                this.sort = res.data._sort;
            } catch (error) {
                console.error(error);
            }
        },
        async onChat(id) {
            try {
                await chatApi.connect(id)
                this.$router.push(`/admin/chat/view/${id}`)
            } catch (error) {
                console.error(error)
            }
        },
        handleUpdateIds(ids) {
            this.selectedIds = ids;
        },
    }
}
</script>