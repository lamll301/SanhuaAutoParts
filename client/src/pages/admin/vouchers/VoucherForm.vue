<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý voucher</h3>
                <router-link v-show="this.$route.params.id" to="/admin/voucher/create" class="admin-content__create">
                    Thêm voucher
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa voucher</h4>
                        <h4 v-else>Form thêm voucher</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã voucher</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="voucher.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Code</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập code voucher" v-model="voucher.code"
                                v-bind:class="{'is-invalid': errors.code}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.code">{{ errors.code }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Giá trị voucher</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập giá trị voucher" v-model="voucher.value"
                                v-bind:class="{'is-invalid': errors.value}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.value">{{ errors.value }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Giới hạn sử dụng</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập giới hạn sử dụng" v-model="voucher.usage_limit"
                                v-bind:class="{'is-invalid': errors.usage_limit}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.usage_limit">{{ errors.usage_limit }}</div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày bắt đầu</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="voucher.start_date"
                                    v-bind:class="{'is-invalid': errors.start_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.start_date">{{ errors.start_date }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày kết thúc</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="voucher.end_date"
                                    v-bind:class="{'is-invalid': errors.end_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.end_date">{{ errors.end_date }}</div>

                                </div>
                            </div>
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!voucher.approved_by && voucher.id" type="button" class="fs-16 btn btn-secondary" @click="approve()">Duyệt</button>
                            <button type="submit" class="fs-16 btn btn-primary">Xác nhận</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="admin-content__table">
                    <div class="admin-content__header d-flex align-items-center">
                        <h4>Lịch sử sử dụng voucher</h4>
                    </div>
                    <CheckboxTable :items="voucherHistory">
                        <template #header>
                            <th scope="col">ID</th>
                            <th scope="col">ID người dùng</th>
                            <th scope="col">ID đơn hàng</th>
                            <th scope="col">Tên người mua</th>
                            <th scope="col">Tiền sản phẩm</th>
                            <th scope="col">Tiền vận chuyển</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Ngày sử dụng</th>
                        </template>
                        <template #body="{ item }">
                            <td>{{ item.id }}</td>
                            <td>{{ item.user_id }}</td>
                            <td>{{ item.order_id }}</td>
                            <td>{{ item.order.name }}</td>
                            <td>{{ formatPrice(item.order.product_total) }}</td>
                            <td>{{ formatPrice(item.order.shipping_fee) }}</td>
                            <td>{{ formatPrice(item.order.total_amount) }}</td>
                            <td>{{ formatDate(item.created_at) }}</td>
                        </template>
                        <template #empty>
                            <tr>
                                <td colspan="20" class="text-center">
                                    Bạn chưa có lịch sử sử dụng voucher nào
                                </td>
                            </tr>
                        </template>
                    </CheckboxTable>
                    <div class="admin-content__table-footer"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { voucherApi } from '@/api';
import CheckboxTable from '@/components/CheckboxTable.vue';
import { formatPrice, formatDate } from '@/utils/helpers';

export default {
    components: {
        CheckboxTable
    },
    data() {
        return {
            voucher: {}, 
            voucherHistory: [],
            errors: {
                value: '',
                code: '',
                usage_limit: '',
                start_date: '',
                end_date: '',
            },
        }
    },
    watch: {
        '$route'(to, from) {
            if (from.params.id && !to.params.id) {
                this.resetForm();
            } else {
                this.fetchData();
            }
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        formatPrice,
        formatDate,
        async approve() {
            try {
                await voucherApi.approve(this.$route.params.id);
                await this.$swal.fire("Duyệt thành công!", "Voucher đã được duyệt!", "success");
                this.$router.push({ name: 'admin.vouchers' });
            } catch (e) {
                console.error(e);
                this.$swal.fire("Lỗi!", e.message, "error");
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                value: '',
                code: '',
                usage_limit: '',
                start_date: '',
                end_date: '',
            }
            if (!this.voucher.code) {
                this.errors.code = 'Code voucher không được để trống.';
                isValid = false;
            } else if (this.voucher.code.length > 255) {
                this.errors.code = 'Code voucher không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.voucher.value) {
                this.errors.value = 'Giá trị voucher không được để trống.';
                isValid = false;
            } else if (this.voucher.value <= 0) {
                this.errors.value = 'Giá trị voucher phải lớn hơn 0.';
                isValid = false;
            }
            if (!this.voucher.usage_limit) {
                this.errors.usage_limit = 'Giới hạn sử dụng không được để trống.';
                isValid = false;
            } else if (this.voucher.usage_limit <= 0) {
                this.errors.usage_limit = 'Giới hạn sử dụng phải lớn hơn 0.';
                isValid = false;
            }
            if (!this.voucher.start_date) {
                this.errors.start_date = 'Ngày bắt đầu không được để trống.';
                isValid = false;
            }
            if (!this.voucher.end_date) {
                this.errors.end_date = 'Ngày kết thúc không được để trống.';
                isValid = false;
            } else if (new Date(this.voucher.end_date) <= new Date(this.voucher.start_date)) {
                this.errors.end_date = 'Ngày kết thúc phải lớn hơn ngày bắt đầu.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                if (this.$route.params.id) {
                    const req = [
                        voucherApi.getOne(this.$route.params.id),
                        voucherApi.getVoucherUsage(this.$route.params.id),
                    ]
                    const res = await this.$swal.withLoading(Promise.all(req));
                    this.voucher = res[0].data || {};
                    this.voucherHistory = res[1].data || [];
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            try {
                if (this.voucher.id) {
                    await voucherApi.update(this.voucher.id, this.voucher);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về voucher đã được cập nhật!", "success");
                }
                else {
                    await voucherApi.create(this.voucher);
                    await this.$swal.fire("Thêm thành công!", "Voucher mới đã được thêm vào hệ thống!", "success");
                }
                this.$router.push({ name: 'admin.vouchers' });
            } catch (error) {
                console.error(error)
            }
        },
        resetForm() {
            this.voucher = {
                value: '',
                code: '',
                usage_limit: '',
            };
            this.errors = {
                value: '',
                code: '',
                usage_limit: '',
                start_date: '',
                end_date: '',
            };
        },
    }
}
</script>