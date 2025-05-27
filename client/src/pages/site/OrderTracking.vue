<template>
    <div class="grid">
        <div class="settings">
            <h1 class="settings-heading">
                Theo dõi đơn mua
            </h1>
            <div class="settings-tab-container">
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === '' }"
                    @click="changeTab('')"
                >
                    Tất cả
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'pending_pay' }"
                    @click="changeTab('pending_pay')"
                >
                    Chờ thanh toán
                </div>
                <!-- <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'pending_approve' }"
                    @click="changeTab('pending_approve')"
                >
                    Chờ duyệt
                </div> -->
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'packaging' }"
                    @click="changeTab('packaging')"
                >
                    Đóng gói
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'shipping' }"
                    @click="changeTab('shipping')"
                >
                    Vận chuyển
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'completed' }"
                    @click="changeTab('completed')"
                >
                    Hoàn thành
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'cancelled' }"
                    @click="changeTab('cancelled')"
                >
                    Đã hủy
                </div>
                <!-- <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'refund' }"
                    @click="changeTab('refund')"
                >
                    Trả hàng/Hoàn tiền
                </div> -->
            </div>
            <div class="settings-order-tracking settings-tab-content">
                <input type="text" class="settings-order-tracking-search" placeholder="Bạn có thể tìm kiếm theo ID đơn hàng hoặc Tên sản phẩm"  @keyup.enter="search" v-model="key">
                <i class="settings-order-tracking-search-icon fa-solid fa-magnifying-glass"></i>
                <template v-if="isLoading">
                    <div style="margin-bottom: 60px;">
                        <div class="loading-dots">Đang tải dữ liệu</div>
                        <vue3-lottie :animationData="animationData" width="280px" height="280px"/>
                    </div>
                </template>
                <template v-else>
                    <div class="settings-order-tracking-container"
                        v-for="order in orders" :key="order.id"
                    >
                        <router-link class="default-router-link" :to="'/theo-doi-don-hang/' + order.id">
                            <div class="settings-order-tracking-top">
                                <div class="settings-order-tracking-heading">
                                    <span>Mã đơn hàng: {{ order.id }}</span>
                                    <div class="settings-order-tracking-status">
                                        <i :class="getStatusIcon(order.status, order.payment_status, order.approved_by)"></i>
                                        <span class="settings-order-tracking-status-desc cart-item-status" :class="getStatusClass(order.status, order.payment_status, order.approved_by)">
                                            {{ getStatusDisplayText(order.status, order.payment_status, order.approved_by) }}
                                        </span>
                                        <div class="settings-order-tracking-icon">
                                            <i class="fa-regular fa-circle-question"></i>
                                            <div class="settings-order-tracking-info">
                                                <p>
                                                    Cập Nhật Mới Nhất
                                                </p>
                                                <span>
                                                    {{ formatDate(order.updated_at) }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="settings-order-tracking-status-text">{{ handleStatusText(order.status, order.payment_status, order.approved_by) }}</span>
                                    </div>
                                </div>
                                <ul class="settings-order-tracking-list">
                                    <li class="settings-order-tracking-item"
                                        v-for="detail in order.details" :key="detail.id"
                                    >
                                        <a class="settings-order-tracking-details">
                                            <div class="settings-order-tracking-left">
                                                <img :src="getImageUrl(detail.product.images[0].path)" alt="" class="settings-order-tracking-img">
                                                <div class="settings-order-tracking-left-content">
                                                    <p class="settings-order-tracking-text settings-order-tracking-name">
                                                        {{ detail.product.name }}
                                                    </p>
                                                    <p class="settings-order-tracking-text settings-order-tracking-type">
                                                        {{ detail.product.description }}
                                                    </p>
                                                    <p class="settings-order-tracking-text">x{{ detail.quantity }}</p>
                                                </div>
                                            </div>
                                            <div class="settings-order-tracking-right">
                                                <span class="settings-order-tracking-price-curr">
                                                    <sup>đ</sup>{{ formatPrice(detail.price) }}
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </router-link>
                        <div class="settings-order-tracking-bottom">
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Thành tiền:
                                    <span class="settings-order-tracking-total-content">
                                        <sup>đ</sup>{{ formatPrice(order.total_amount) }}
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-bottom-content">
                                <div class="settings-order-tracking-bottom-left">
                                    <span v-show="order.payment_status === 0">
                                        Đơn hàng chưa được thanh toán. Vui lòng thanh toán để chúng tôi có thể xử lý và giao hàng sớm nhất!
                                    </span>
                                    <span v-show="order.status === 3">
                                        Đơn hàng đã được giao thành công vào {{ formatDate(order.completed_at) }}. Cảm ơn bạn đã mua sắm cùng chúng tôi!
                                    </span>
                                    <span v-show="order.status === 4">
                                        Lý do hủy đơn hàng: {{ order.cancel_reason }}
                                    </span>
                                </div>
                                <div class="settings-order-tracking-bottom-right">
                                    <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                        v-if="order.payment_status === 0 && order.status !== 4" @click="payOrder(order.id)"
                                    >
                                        Thanh toán
                                    </button>
                                    <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                        v-if="order.status < 1" @click="cancelOrder(order.id)"
                                    >
                                        Hủy Đơn Hàng
                                    </button>
                                    <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                        v-if="order.status === 3 && !areAllReviewed(order.details)" @click="reviewOrder(order)"
                                    >
                                        Đánh giá
                                    </button>
                                    <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                        v-if="order.status === 3"
                                    >
                                        Yêu cầu Trả hàng/Hoàn tiền
                                    </button>
                                    <button class="button settings-order-tracking-btn settings-order-tracking-btn-2" @click="reorder(order.details)">
                                        Mua lại
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <SitePagination v-show="orders.length > 0" :totalPages="totalPages" :currentPage="currentPage"/>
                    <div class="settings-order-tracking-empty" v-show="orders.length === 0">
                        <p class="settings-order-tracking-empty-text">Không tìm thấy đơn hàng nào.</p>
                        <img src="../../assets/images/Empty-Order--Streamline-London.png" alt="" class="settings-order-tracking-empty-img">
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import SitePagination from '@/components/SitePagination.vue';
import { formatDate, formatPrice, getImageUrl } from '@/utils/helpers';
import { Vue3Lottie } from 'vue3-lottie';
import animationData from '@/assets/images/Animation - 1747389560930.json';
import { useOrderStore } from '@/stores/order';
import { paymentApi, orderApi } from '@/api';
import { getStatusText } from '@/utils/statusMap';
import { useReviewStore } from '@/stores/review';

export default {
    components: {
        SitePagination, Vue3Lottie
    },
    beforeRouteEnter(to, from, next) {
        const token = localStorage.getItem('token');
        if (!token) {
            next({ name: 'NotFound' });
            return;
        }
        next();
    },
    setup() {
        const orderStore = useOrderStore();
        const reviewStore = useReviewStore();
        return {
            orderStore,
            reviewStore,
            animationData
        };
    },
    watch: {
        '$route': {
            handler: 'fetchData',
            immediate: true,
            deep: true,
        },
    },
    data() {
        return {
            isLoading: false,
            orders: [],
            activeTab: '',
            totalPages: 0, currentPage: 1,
            key: ''
        }
    },
    computed: {
        filteredOrders() {
            if (!this.activeTab) return this.orders;
            return this.orders.filter(order => order.status === this.activeTab);
        },
    },
    created() {
        this.fetchData();
    },
    methods: {
        formatDate, formatPrice, getImageUrl, getStatusText,
        async fetchData() {
            this.isLoading = true;
            try {
                const res = await orderApi.getOrdersByUser(this.$route.query);
                this.orders = res.data.data;
                this.totalPages = Math.ceil(res.data.pagination.total / res.data.pagination.per_page);
                this.currentPage = res.data.pagination.current_page;
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        reviewOrder(order) {
            this.reviewStore.setReview(order);
            this.$router.push({ name: 'orderReview' })
        },
        async cancelOrder(id) {
            const { value: reason } = await this.$swal.fire('Huỷ đơn hàng', '', '', {
                input: 'textarea',
                inputLabel: 'Lý do huỷ đơn hàng',
                inputPlaceholder: 'Nhập lý do tại đây...',
                inputAttributes: {
                    'aria-label': 'Nhập lý do hủy'
                },
                showCancelButton: true,
                confirmButtonText: 'Xác nhận hủy',
                cancelButtonText: 'Quay lại',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Bạn phải nhập lý do huỷ đơn hàng!';
                    }
                }
            })
            if (!reason) return;

            try {
                const res = await orderApi.cancelOrderByUser(id, reason);
                const order = res.data;
                this.orders = this.orders.map(o => o.id === id ? order : o);
                this.$swal.fire('Đã hủy đơn hàng', 'Đơn hàng đã được hủy thành công!', 'success');
            } catch (error) {
                console.error(error);
            }
        },
        async payOrder(id) {
            const { value: paymentMethod } = await this.$swal.fire('Chọn phương thức thanh toán', '', '', {
                input: 'radio',
                inputOptions: {
                    qr: 'Mã QR',
                    credit: 'Thẻ tín dụng/Ghi nợ',
                    ewallet: 'Ví điện tử',
                    cash: 'Tiền mặt'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'Bạn phải chọn một phương thức thanh toán!'
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Tiếp tục',
                cancelButtonText: 'Huỷ'
            });

            try {
                if (paymentMethod) {
                    switch (paymentMethod) {
                        case 'qr':
                            await this.handleQRPayment(id);
                            break;
                        case 'credit':
                            await this.handleCardPayment(id);
                            break;
                        case 'ewallet':
                            await this.handleEwalletPayment(id);
                            break;
                        case 'cash':
                            await this.handleCashPayment(id);
                            break;
                    }
                }
            } catch (error) {
                console.error(error);
            }
        },
        async handleEwalletPayment(id) {
            const { value: wallet } = await this.$swal.fire('Chọn ví điện tử', '', '', {
                input: 'radio',
                inputOptions: {
                    momo: 'Momo',
                    vnpay: 'VNPay',
                    zalo: 'ZaloPay'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'Bạn phải chọn một ví điện tử!'
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Quay lại'
            });
            if (!wallet || !id) return;
            try {
                if (wallet === 'momo') {
                    const res = await paymentApi.createMomoPayment(id);
                    const momo = res.data;
                    if (momo.resultCode === 0) {
                        window.location.href = momo.payUrl;
                    } else {
                        this.$swal.fire('Lỗi', momo.message, 'error');
                    }
                } else if (wallet === 'vnpay') {
                    const res = await paymentApi.createVNPayPayment(id);
                    const vnpay = res.data;
                    if (vnpay.message === "success") {
                        window.location.href = vnpay.data;
                    } else {
                        this.$swal.fire('Lỗi', vnpay.message, 'error');
                    }
                } else if (wallet === 'zalo') {
                    const res = await paymentApi.createZaloPayPayment(id);
                    const zalo = res.data;
                    if (zalo.return_code === 1) {
                        window.location.href = zalo.order_url;
                    } else {
                        this.$swal.fire(zalo.return_message, zalo.sub_return_message, 'error');
                    }
                }
            } catch (error) {
                console.error(error);
            }
        },
        async handleCardPayment(id) {
            const { value: formValues } = await this.$swal.fire('Thông tin thẻ', '', '', {
                html: `
                    <input id="cardNumber" class="swal2-input" placeholder="Số thẻ" type="number">
                    <input id="cardName" class="swal2-input" placeholder="Họ tên chủ thẻ">
                    <input id="expiryDate" class="swal2-input" placeholder="MM/YY">
                    <input id="cvv" class="swal2-input" placeholder="CVV" type="number">
                    <input id="zipCode" class="swal2-input" placeholder="Mã bưu chính" type="number">
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Thanh toán',
                cancelButtonText: 'Hủy',
                preConfirm: () => {
                    const number = document.getElementById('cardNumber').value;
                    const expiry = document.getElementById('expiryDate').value;
                    const cvv = document.getElementById('cvv').value;
                    const holder = document.getElementById('cardName').value;
                    const postal = document.getElementById('zipCode').value;
                    if (!number || !expiry || !cvv || !holder || !postal) {
                        return false;
                    }
                    return { number, expiry, cvv, holder, postal };
                },
            });
            if (!formValues || !id) return;
            await this.$swal.fire('Đã gửi thông tin thanh toán', 'Thông tin thẻ của bạn đã được gửi. Vui lòng chờ nhân viên xác nhận và xử lý trong thời gian sớm nhất.', 'info');
        },
        async handleCashPayment(id) {
            try {
                const res = await paymentApi.createCODPayment(id);
                if (res.data.message === "success") {
                    this.$swal.fire('Thành công', 'Thanh toán tiền mặt (COD) đã được tạo.', 'success');
                    this.$router.push(`/theo-doi-don-hang/${id}`);
                } else {
                    this.$swal.fire('Lỗi', 'Đã xảy ra lỗi khi tạo thanh toán COD', 'error');
                }
            } catch (error) {
                console.error(error);
            }
        },
        async handleQRPayment(id) {
            try {
                const res = await paymentApi.createQRCodePayment(id);
                const qrCode = res.data;
                if (qrCode.code === "00") {
                    this.$swal.fire('Quét mã QR để thanh toán', 'Vui lòng sử dụng ứng dụng ngân hàng hoặc ví điện tử để quét mã', '', {
                        imageUrl: qrCode.data.qrDataURL,
                        imageAlt: 'QR Code',
                        imageWidth: 250,
                        confirmButtonText: 'Tôi đã thanh toán',
                    })
                } else {
                    this.$swal.fire('Lỗi', qrCode.desc, 'error');
                }
            } catch (error) {
                console.error(error);
            }
        },
        async reorder(details) {
            await this.orderStore.setReorder(details);
            this.$router.push('/don-hang');
        },
        search() {
            this.$router.push({
                query: {
                    key: this.key
                }
            })
        },
        handleStatusText(status, paymentStatus) {
            let text = '';
            if (status > 0) {
                text = getStatusText('order', status);
            } else if (paymentStatus === 0) {
                text = 'Chờ thanh toán';
            } else {
                text = 'Chờ duyệt';
            }
            return text;
        },
        changeTab(tab) {
            this.activeTab = tab;
            let query = {};
            switch (tab) {
                case 'pending_pay':
                    query = {
                        action: 'filterUnpaid'
                    }
                    break;
                case 'packaging':
                    query = {
                        action: 'filterPacking'
                    }
                    break;
                case 'shipping':
                    query = {
                        action: 'filterShipping'
                    }
                    break;
                case 'completed':
                    query = {
                        action: 'filterCompleted'
                    }
                    break;
                case 'cancelled':
                    query = {
                        action: 'filterCancelled'
                    }
                    break;
                default:
                    break;
            }
            this.$router.push({
                query
            })
        },
        getStatusDisplayText(status, paymentStatus) {
            if (status === 1) return 'Đơn hàng đang được đóng gói để chuẩn bị giao';
            if (status === 2) return 'Đơn hàng đang trên đường vận chuyển đến bạn';
            if (status === 3) return 'Đơn hàng đã được giao thành công';
            if (status === 4) return 'Đơn hàng đã bị hủy';
            if (paymentStatus === 0) return 'Đơn hàng đang chờ thanh toán';
            return '';
        },
        getStatusClass(status, paymentStatus) {
            if (status === 1) return 'status-packaging';
            if (status === 2) return 'status-shipping';
            if (status === 3) return 'status-completed';
            if (status === 4) return 'status-cancelled';
            if (paymentStatus === 0) return 'status-pending-payment';
            // if (!approvedBy) return 'status-pending-approval';
            return '';
        },
        getStatusIcon(status, paymentStatus) {
            if (status === 1) return 'fa-solid fa-box status-packaging';
            if (status === 2) return 'fa-solid fa-truck-fast status-shipping';
            if (status === 3) return 'fa-solid fa-check-circle status-completed';
            if (status === 4) return 'fa-solid fa-times-circle status-cancelled';
            if (paymentStatus === 0) return 'fa-solid fa-money-bill-wave status-pending-payment';
            // if (!approvedBy) return 'fa-solid fa-clipboard-check status-pending-approval';
            return '';
        },
        areAllReviewed(details) {
            return details.every(detail => detail.isReviewed);
        }
    }
}
</script>

<style scoped>

.status-pending-payment {
    color: #eab308;
}

.status-pending-approval {
    color: #eab308;
}

.status-packaging {
    color: #2563EB;
}

.status-shipping {
    color: #2563EB;
}

.status-completed {
    color: #2e8b57;
}

.status-cancelled {
    color: #dc3545;
}

.settings-order-tracking-empty-img {
    width: 200px;
    margin: 0 auto;
    display: block;
    margin-bottom: 60px;
}

.settings-order-tracking-empty-text {
    font-size: 1.6rem;
    text-align: center;
    margin: 20px 0 28px 0;
}

.loading-dots {
    font-size: 1.6rem;
    text-align: center;
    margin: 20px 0 28px 0;
    color: black;
}

.loading-dots::after {
    content: '...';
    display: inline-block;
    animation: dots 1.5s infinite;
    width: 24px;
}

@keyframes dots {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60%, 100% { content: '...'; }
}
</style>

