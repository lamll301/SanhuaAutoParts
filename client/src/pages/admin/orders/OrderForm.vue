<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý sản phẩm</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4>Form xem đơn hàng</h4>
                    </div>
                    <form @submit.prevent="">
                    <div class="admin-content__form-body">
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Mã sản phẩm</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly v-model="order.id">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Mã tài khoản đặt hàng</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly v-model="order.user_id">
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tên khách hàng</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly v-model="order.name">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Số điện thoại</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly v-model="order.phone">
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tỉnh / Thành phố</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="selectedCity">
                                        <option value="">Chọn thành phố</option>
                                        <option v-for="city in cities" :key="city.code" :value="city.code">
                                            {{ city.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Quận / Huyện</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="selectedDistrict">
                                        <option value="">Chọn quận huyện</option>
                                        <option v-for="district in districts" :key="district.code" :value="district.code">
                                            {{ district.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phường / Xã</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="selectedWard">
                                        <option value="">Chọn phường xã</option>
                                        <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                            {{ ward.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Địa chỉ</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập địa chỉ" readonly v-model="order.shipping_address"></textarea>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Loại địa chỉ</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập loại địa chỉ" readonly v-model="order.address_type">
                            </div>
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tiền hàng</h3>
                                <div class="valid-elm input-group">
                                    <input type="number" class="fs-16 form-control" placeholder="Nhập tiền hàng" readonly v-model="order.product_total">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phí vận chuyển</h3>
                                <div class="valid-elm input-group">
                                    <input type="number" class="fs-16 form-control" placeholder="Nhập phí vận chuyển" readonly v-model="order.shipping_fee">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Giảm giá (nếu có)</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" placeholder="Nhập giảm giá" readonly
                                    :value="order.voucher ? order.voucher.value + ' (' + order.voucher.code + ')' : '0'">
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tổng tiền</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập tổng tiền" readonly v-model="order.total_amount">
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phương thức thanh toán</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" placeholder="Nhập phương thức thanh toán" readonly v-model="order.payment_method">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Trạng thái thanh toán</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="order.payment_status">
                                        <option value="" selected>Chọn trạng thái thanh toán</option>
                                        <option v-for="([key, status]) in Object.entries(getAllStatusOptions('payment'))" :key="key" :value="key">
                                            {{ status }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div :class="{'textarea-invalid': errors.payment_info}" class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Thông tin thanh toán</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập thông tin thanh toán" v-model="order.payment_info"
                                v-bind:class="{'is-invalid': errors.payment_info}" @blur="validate()">
                                </textarea>
                                <div class="invalid-feedback" v-if="errors.payment_info">{{ errors.payment_info }}</div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Trạng thái đơn hàng</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="order.status">
                                        <option value="" selected>Chọn trạng thái đơn hàng</option>
                                        <option v-for="([key, status]) in Object.entries(getAllStatusOptions('order'))" :key="key" :value="key">
                                            {{ status }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Người duyệt đơn</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="order.approver?.name">
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày tạo đơn</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="formatDate(order.created_at)">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Cập nhật lần cuối</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="formatDate(order.updated_at)">
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày giao hàng</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="formatDate(order.shipped_at)">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày nhận hàng</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="formatDate(order.completed_at)">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày hủy đơn</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="formatDate(order.cancelled_at)">
                                </div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Lý do hủy đơn (nếu có)</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập lý do hủy đơn" readonly v-model="order.cancel_reason"></textarea>
                            </div>
                        </div>
                        <div v-if="order.payment_status === 1 && order.status === 4" class="mb-20">
                            <h3 class="admin-content__form-text">Hoàn tiền</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" readonly :value="order.is_refunded ? 'Đã hoàn tiền' : 'Chưa hoàn tiền'">
                            </div>
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!order.approved_by && order.status !== 4" class="fs-16 btn btn-primary" @click="onUpdateStatus(order.id, 'approved')">Duyệt</button>
                            <button v-if="order.status === 4 && order.payment_status === 1 && !order.is_refunded && order.payment_method !== 'Thanh toán khi nhận hàng'" class="fs-16 btn btn-warning" @click="onUpdateStatus(order.id, 'refunded')">Hoàn tiền</button>
                            <button v-if="order.status === 1" class="fs-16 btn btn-primary" @click="onUpdateStatus(order.id, 'shipped')">Giao hàng</button>
                            <button v-if="order.status === 2" class="fs-16 btn btn-success" @click="onUpdateStatus(order.id, 'completed')">Hoàn tất</button>
                            <button v-if="order.payment_status === 0" class="fs-16 btn btn-success" @click="onUpdateStatus(order.id, 'paid')">Đã thanh toán</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="admin-content__table">
                    <div class="admin-content__header d-flex align-items-center">
                        <h4>Chi tiết đơn hàng</h4>
                    </div>
                    <CheckboxTable :items="details">
                        <template #header>
                            <th scope="col">ID</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Tổng tiền</th>
                        </template>
                        <template #body="{ item }">
                            <td>{{ item.id }}</td>
                            <td>{{ item.product.name }}</td>
                            <td><img :src="getImageUrl(item.product.images[0].path)" alt="Hình ảnh sản phẩm" class="admin-content__table-img-cell"></td>
                            <td>{{ item.quantity }}</td>
                            <td>{{ formatPrice(item.price) }}</td>
                            <td>{{ formatPrice(item.subtotal) }}</td>
                        </template>
                        <template #empty>
                            <tr>
                                <td colspan="20" class="text-center">
                                    Bạn chưa có chi tiết đơn hàng nào
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
import CheckboxTable from '@/components/CheckboxTable.vue';
import { orderApi, addressApi } from '@/api';
import { formatDate, formatPrice, getImageUrl } from '@/utils/helpers';
import { getAllStatusOptions } from '@/utils/statusMap';

export default {
    components: {
        CheckboxTable,
    },
    data() {
        return {
            order: {
                voucher: {
                    code: '',
                    value: 0,
                },
            },
            details: [],
            cities: [], districts: [], wards: [],
            errors: {
                payment_info: '',
            },
        }
    },
    computed: {
        selectedCity: {
            get() {
                return this.order.city_id;
            },
            set(value) {
                this.order.city_id = value;
            }
        },
        selectedDistrict: {
            get() {
                return this.order.district_id;
            },
            set(value) {
                this.order.district_id = value;
            }
        },
        selectedWard: {
            get() {
                return this.order.ward_id;
            },
            set(value) {
                this.order.ward_id = value;
            }
        },
    },
    watch: {
        selectedCity() {
            this.districts = [];
            this.wards = [];
            this.fetchDistricts();
        },
        selectedDistrict() {
            this.wards = [];
            this.fetchWards();
        }
    },
    created() {
        this.fetchCities();
        this.fetchData();
    },
    methods: {
        getAllStatusOptions, formatDate, formatPrice, getImageUrl,
        validate(status) {
            let isValid = true;
            this.errors = {
                payment_info: '',
            }
            if (status === 'paid') {
                if (!this.order.payment_info) {
                    this.errors.payment_info = 'Thông tin thanh toán là bắt buộc';
                    isValid = false;
                }
            }
            return isValid;
        },
        async onUpdateStatus(id, status) {
            if (!this.validate(status)) return;
            try {
                const res = await orderApi.changeOrderStatus(id, status, this.order.payment_info);
                await this.$swal.fire('Thành công', status === 'refunded' ? 'Hoàn tiền thành công' : 'Trạng thái đơn hàng đã được cập nhật', 'success');
                this.order = res.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchData() {
            try {
                const res = await this.$swal.withLoading(orderApi.getOne(this.$route.params.id));
                this.order = res.data;
                this.details = this.order.details;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchCities() {
            try {
                const response = await addressApi.getProvinces()
                this.cities = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchDistricts() {
            if (!this.selectedCity) return;
            try {
                const response = await addressApi.getProvinceWithDistricts(this.selectedCity);
                this.districts = response.data.districts;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchWards() {
            if (!this.selectedDistrict) return;
            try {
                const response = await addressApi.getDistrictWithWards(this.selectedDistrict);
                this.wards = response.data.wards;
            } catch (error) {
                console.error(error);
            }
        },
    }
}
</script>
<style>
.textarea-invalid {
    height: 116px !important;
}
</style>