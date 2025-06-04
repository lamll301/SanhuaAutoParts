<template>    <div class="admin-page">
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
                                <h3 class="admin-content__form-text">Mã đơn hàng</h3>
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
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Trạng thái hoàn tiền</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="order.is_refunded ? 'Chưa hoàn tiền' : order.refunded_at ? 'Đã hoàn tiền' : 'Không cần hoàn tiền'">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Thời gian hoàn tiền</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" class="fs-16 form-control" readonly :value="formatDate(order.refunded_at)">
                                </div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Lý do hủy đơn (nếu có)</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập lý do hủy đơn" readonly v-model="order.cancel_reason"></textarea>
                            </div>
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="isShowApproveButton" class="fs-16 btn btn-primary" @click="approve()">Duyệt</button>
                            <button v-if="isShowShippedButton" class="fs-16 btn btn-primary" @click="onUpdateStatus(order.id, 'shipped')">Giao hàng</button>
                            <button v-if="isShowCompletedButton" class="fs-16 btn btn-success" @click="onUpdateStatus(order.id, 'completed')">Hoàn tất</button>
                            <button v-if="isShowPaidButton" class="fs-16 btn btn-success" @click="onUpdateStatus(order.id, 'paid')">Đã thanh toán</button>
                            <button v-if="isShowRefundButton" class="fs-16 btn btn-success" @click="onUpdateStatus(order.id, 'refunded')">Đã hoàn tiền</button>
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
import { orderApi, addressApi, inventoryApi } from '@/api';
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
            inventories: [],
            details: [],
            cities: [], districts: [], wards: [],
            errors: {
                payment_info: '',
            },
        }
    },
    computed: {
        isShowApproveButton() {
            return !this.order.approved_by && this.order.status === 0;
        },
        isShowRefundButton() {
            return this.order.is_refunded && this.order.status === 4 && this.order.payment_status === 1 && this.order.payment_method !== 'Thanh toán khi nhận hàng';
        },
        isShowShippedButton() {
            return this.order.status === 1;
        },
        isShowCompletedButton() {
            return this.order.status === 2;
        },
        isShowPaidButton() {
            return this.order.payment_status === 0 && this.order.status === 0;
        },
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
        async approve() {
            try {
                const formHtml = `
                    <div class="order-approval-form">
                        <div class="order-items">
                            ${this.details.map((detail, index) => `
                                <div class="order-item mb-4">
                                    <div class="order-item-header">
                                        <span class="order-item-number">#${detail.id}</span>
                                        <h5 class="order-item-title">${detail.product.name}</h5>
                                    </div>
                                    <div class="order-item-content">
                                        <div class="product-info">
                                            <div class="product-image">
                                                <img src="${this.getImageUrl(detail.product.images[0].path)}" 
                                                     alt="${detail.product.name}">
                                            </div>
                                            <div class="product-details">
                                                <div class="info-group">
                                                    <div class="quantity-info">
                                                        <i class="fas fa-box"></i>
                                                        <span>Số lượng: </span>
                                                        <span class="highlight-text">${detail.quantity}</span>
                                                    </div>
                                                    <div class="price-info">
                                                        <i class="fas fa-tag"></i>
                                                        <span>Đơn giá: </span>
                                                        <span class="highlight-text">${this.formatPrice(detail.price)} đ</span>
                                                    </div>
                                                    <div class="total-info">
                                                        <i class="fas fa-money-bill"></i>
                                                        <span>Thành tiền: </span>
                                                        <span class="highlight-text highlight-text--primary">${this.formatPrice(detail.subtotal)} đ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inventory-section">
                                            <div class="inventory-header">
                                                <h6 class="mb-0"></h6>
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-lg add-inventory"
                                                        data-container="inventory-container-${index}">
                                                    <i class="fas fa-plus me-1"></i> Thêm
                                                </button>
                                            </div>
                                            <div class="inventory-items" id="inventory-container-${index}">
                                                <div class="inventory-item">
                                                    <div class="row g-3">
                                                        <div class="col-7">
                                                            <select class="form-select form-select-lg inventory-select" 
                                                                    data-detail-id="${detail.id}">
                                                                <option value="">-- Chọn hàng tồn --</option>
                                                                ${this.inventories.filter(i => i.product_id === detail.product_id)
                                                                    .map(i => `
                                                                        <option value="${i.id}">
                                                                            ID: ${i.id} - Lô hàng ${i.batch_number} - SL: ${i.quantity}
                                                                        </option>
                                                                    `).join('')}
                                                            </select>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="quantity-input">
                                                                <input type="number" 
                                                                       class="form-control form-control-lg inventory-quantity" 
                                                                       placeholder="Số lượng"
                                                                       min="1"
                                                                       max="${detail.quantity}">
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-lg remove-inventory"
                                                                    style="display: none;">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <style>
                        .add-inventory {
                            width: 100px !important;
                            height: 33px !important;
                        }
                        .remove-inventory {
                            height: 100%;
                            width: 100%;
                        }
                        .order-approval-form {
                            max-height: 80vh;
                            overflow-y: auto;
                            padding: 1.5rem;
                            background: #f8f9fa;
                            border-radius: 12px;
                        }
                        .approval-header h4 {
                            font-size: 1.5rem;
                            font-weight: 600;
                        }
                        .order-item {
                            background: white;
                            border-radius: 12px;
                            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                            overflow: hidden;
                        }
                        .order-item-header {
                            background: #4a90e2;
                            color: white;
                            padding: 1rem 1.5rem;
                            display: flex;
                            align-items: center;
                        }
                        .order-item-number {
                            background: rgba(255,255,255,0.2);
                            padding: 0.3rem 0.8rem;
                            border-radius: 20px;
                            font-weight: 500;
                            margin-right: 1rem;
                        }
                        .order-item-title {
                            margin: 0;
                            font-size: 1.4rem;
                            font-weight: 500;
                        }
                        .order-item-content {
                            padding: 1.5rem;
                        }
                        .product-info {
                            display: flex;
                            gap: 1.5rem;
                            padding: 1rem;
                            background: #fff;
                            border: 1px solid #e0e0e0;
                            border-radius: 8px;
                            margin-bottom: 1.5rem;
                        }
                        .product-image {
                            width: 120px;
                            height: 120px;
                            border-radius: 6px;
                            overflow: hidden;
                            border: 1px solid #eee;
                        }
                        .product-image img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }
                        .product-details {
                            flex: 1;
                            display: flex;
                            align-items: center;
                        }
                        .info-group {
                            width: 100%;
                        }
                        .quantity-info,
                        .price-info,
                        .total-info {
                            display: flex;
                            align-items: center;
                            gap: 0.5rem;
                            padding: 0.5rem 0;
                            color: #666;
                        }
                        .quantity-info i,
                        .price-info i,
                        .total-info i {
                            width: 20px;
                            color: #888;
                        }
                        .highlight-text {
                            font-weight: 600;
                            color: #333;
                        }
                        .highlight-text--primary {
                            color: #2196f3;
                        }
                        .inventory-section {
                            background: #f8f9fa;
                            border-radius: 8px;
                            padding: 1.2rem;
                        }
                        .inventory-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 1rem;
                        }
                        .inventory-header h6 {
                            font-size: 1.1rem;
                            color: #2c3e50;
                        }
                        .inventory-item {
                            background: white;
                            padding: 1rem;
                            border-radius: 8px;
                            margin-bottom: 1rem;
                            border: 1px solid #e0e0e0;
                        }
                        .inventory-item:last-child {
                            margin-bottom: 0;
                        }
                        .form-select-lg, .form-control-lg {
                            font-size: 1.4rem;
                        }
                        .quantity-input {
                            position: relative;
                        }
                    </style>
                `;
                const result = await this.$swal.fire('Duyệt đơn hàng', '', '', {
                    html: formHtml,
                    showCancelButton: true,
                    confirmButtonText: 'Duyệt',
                    cancelButtonText: 'Hủy',
                    allowOutsideClick: false,
                    didOpen: () => {
                        const popup = document.querySelector('.swal2-popup');
                        if (popup) {
                            popup.style.setProperty('width', '900px', 'important');
                            popup.style.setProperty('max-width', '95vw', 'important');
                        }

                        document.querySelectorAll('.add-inventory').forEach(button => {
                            button.addEventListener('click', () => {
                                const containerId = button.dataset.container;
                                const container = document.getElementById(containerId);
                                const template = container.querySelector('.inventory-item').cloneNode(true);
                                
                                template.querySelector('.remove-inventory').style.display = 'block';
                                template.querySelector('.remove-inventory').addEventListener('click', () => {
                                    template.remove();
                                });
                                
                                container.appendChild(template);
                            });
                        });
                    },
                    preConfirm: () => {
                        const inventoryData = [];
                        const orderDetailQuantities = {};
                        const requiredQuantities = {};
                        this.details.forEach(detail => {
                            requiredQuantities[detail.id] = detail.quantity;
                            orderDetailQuantities[detail.id] = 0;
                        });
                        document.querySelectorAll('.inventory-item').forEach(item => {
                            const select = item.querySelector('.inventory-select');
                            const quantityInput = item.querySelector('.inventory-quantity');
                            if (select.value && quantityInput.value) {
                                const orderDetailId = select.dataset.detailId;
                                const quantity = parseInt(quantityInput.value);
                                orderDetailQuantities[orderDetailId] = (orderDetailQuantities[orderDetailId] || 0) + quantity;
                                inventoryData.push({
                                    order_detail_id: select.dataset.detailId,
                                    inventory_id: select.value,
                                    quantity: quantity
                                });
                            }
                        });
                        const errors = [];
                        for (const detailId in requiredQuantities) {
                            const requiredQty = requiredQuantities[detailId];
                            const selectedQty = orderDetailQuantities[detailId] || 0;
                            const detail = this.details.find(d => d.id == detailId);
                            
                            if (selectedQty === 0) {
                                errors.push(`Chưa chọn hàng tồn cho sản phẩm: ${detail.product.name}`);
                            } else if (selectedQty < requiredQty) {
                                errors.push(`Thiếu ${requiredQty - selectedQty} sản phẩm "${detail.product.name}" (cần ${requiredQty}, đã chọn ${selectedQty})`);
                            } else if (selectedQty > requiredQty) {
                                errors.push(`Thừa ${selectedQty - requiredQty} sản phẩm "${detail.product.name}" (cần ${requiredQty}, đã chọn ${selectedQty})`);
                            }
                        }
                        if (errors.length > 0) {
                            this.$swal.showValidationMessage(errors.join('<br>'));
                            return false;
                        }
                        if (inventoryData.length === 0) {
                            this.$swal.showValidationMessage('Vui lòng chọn ít nhất một hàng tồn');
                            return false;
                        }
                        return inventoryData;
                    }
                });

                if (result.isConfirmed && result.value) {
                    const res = await orderApi.approve(this.$route.params.id, result.value)
                    await this.$swal.fire('Thành công', 'Đơn hàng đã được duyệt thành công!', 'success');
                    this.order = res.data;
                }
            } catch (e) {
                console.error(e);
            }
        },
        async onUpdateStatus(id, status) {
            if (!this.validate(status)) return;
            try {
                const res = await orderApi.changeOrderStatus(id, status, this.order.payment_info);
                await this.$swal.fire('Thành công', 'Thông tin đơn hàng đã được cập nhật', 'success');
                this.order = res.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchData() {
            try {
                const req = [
                    orderApi.getOne(this.$route.params.id),
                    inventoryApi.getAll()
                ]
                const res = await this.$swal.withLoading(Promise.all(req));
                this.order = res[0].data || {};
                this.details = this.order.details || [];
                this.inventories = res[1].data.data || [];
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
