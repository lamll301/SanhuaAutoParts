<template>
    <div class="grid">
        <div class="settings">
            <h1 class="settings-heading">
                Chi tiết đơn mua
            </h1>
            <div class="settings-order-tracking settings-tab-content">
                <div class="settings-order-tracking-container">
                    <div class="order-tracking-detail-top-1 settings-order-tracking-top">
                        <div class="order-tracking-detail-heading settings-order-tracking-heading">
                            <router-link to="/theo-doi-don-hang" class="order-tracking-detail-back">
                                <i class="fa-solid fa-chevron-left"></i>
                                TRỞ LẠI
                            </router-link>
                            <div class="settings-order-tracking-status">
                                <span class="order-tracking-detail-id settings-order-tracking-status-desc cart-item-status">
                                    Mã đơn hàng: {{ order.id }}
                                </span>
                                <span class="settings-order-tracking-status-text">
                                    <span v-if="order.status === 1">Đang đóng gói</span>
                                    <span v-else-if="order.status === 2">Đang giao hàng</span>
                                    <span v-else-if="order.status === 3">Hoàn thành</span>
                                    <span v-else-if="order.status === 4">Đã hủy</span>
                                    <span v-else-if="order.payment_status === 0">Chờ thanh toán</span>
                                    <span v-else-if="!order.approved_by">Chờ duyệt</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="settings-order-tracking-top order-tracking-detail-top-2">
                        <div v-if="order.status !== 4" class="order-tracking-detail-stepper">
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon order-tracking-detail-step-icon--completed">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    Đơn hàng đã đặt
                                </p>
                                <span class="order-tracking-detail-step-text-sub">{{ formatDate(order.created_at) }}</span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon order-tracking-detail-step-icon--active"
                                :class="{
                                    'order-tracking-detail-step-icon--completed': order.status >= 1
                                }"    
                                >
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        order.payment_status === 1
                                        ? 'Đã xác nhận thông tin thanh toán'
                                        : 'Đơn hàng chưa được thanh toán' 
                                    }}
                                </p>
                                <span v-if="order.payment_status === 1" class="order-tracking-detail-step-text-sub">
                                    {{ order?.payment_info?.match(/Thời gian: ([\d:-\s]+)/)?.[1] }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon"
                                :class="{
                                    'order-tracking-detail-step-icon--active': order.status === 1,
                                    'order-tracking-detail-step-icon--completed': order.status > 1
                                }"
                                >
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        order.status > 1
                                        ? 'Đã giao cho đơn vị vận chuyển'
                                        : 'Đang trong quá trình đóng gói' 
                                    }}
                                </p>
                                <span v-if="order.status > 1" class="order-tracking-detail-step-text-sub">
                                    {{ order.shipped_at }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon"
                                :class="{
                                    'order-tracking-detail-step-icon--active': order.status === 2,
                                    'order-tracking-detail-step-icon--completed': order.status > 2
                                }"
                                >
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        order.status > 2
                                        ? 'Đã nhận được hàng'
                                        : 'Chờ giao hàng' 
                                    }}
                                </p>
                                <span v-if="order.status > 2" class="order-tracking-detail-step-text-sub">
                                    {{ order.completed_at }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon"
                                :class="{
                                    'order-tracking-detail-step-icon--active': !areAllReviewed 
                                        && order.status === 3,
                                    'order-tracking-detail-step-icon--completed': areAllReviewed && order.status === 3
                                }"
                                >
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        areAllReviewed
                                        ? 'Đơn hàng đã được đánh giá'
                                        : 'Đánh giá'
                                    }}
                                </p>
                            </div>
                            <div class="order-tracking-detail-step-line" :style="{ '--progress-width': progressWidth + '%' }"></div>
                        </div>
                        <div v-else>
                            <p style="font-size: 2rem; color: #ee4d2d;">Đã hủy đơn hàng</p>
                            <span style="font-size: 1.4rem;">Vào {{ formatDate(order.cancelled_at) }}</span>
                        </div>
                    </div>
                    <div class="settings-order-tracking-bottom">
                        <div class="settings-order-tracking-bottom-content">
                            <div class="settings-order-tracking-bottom-left">
                                <span v-if="order.status === 3">
                                    <span v-if="!isReturnExpired(order.completed_at)">
                                        Nếu hàng nhận được có vấn đề, bạn có thể gửi yêu cầu Trả hàng/Hoàn tiền trước 14 ngày kể từ ngày nhận hàng
                                    </span>
                                    <span v-else>
                                        Cảm ơn bạn đã mua sắm tại Sanhua!
                                    </span>
                                </span>
                                <span v-else-if="order.status === 4">
                                    Lý do: {{ order.cancel_reason }}
                                </span>
                            </div>
                            <div class="settings-order-tracking-bottom-right">
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.payment_status === 0 && order.status !== 4" 
                                    @click="payOrder(order.id)"
                                >
                                    Thanh toán
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.status < 1"
                                    @click="cancelOrder(order.id)"
                                >
                                    Hủy Đơn Hàng
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.status === 3 && !areAllReviewed" @click="reviewOrder(order)"
                                >
                                    Đánh giá
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.status === 3"
                                >
                                    Yêu cầu Trả hàng/Hoàn tiền
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-2"
                                    @click="reorder(order.details)"
                                >
                                    Mua lại
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="order-tracking-detail-address-container settings-order-tracking-top">
                        <div class="settings-order-tracking-heading">
                            <span class="order-tracking-detail-address">Địa Chỉ Nhận Hàng</span>
                        </div>
                        <div class="order-tracking-detail-address-content">
                            <p class="order-tracking-detail-address-name">
                                {{ order.name }}
                            </p>
                            <p class="order-tracking-detail-address-sub">{{ order.phone }}</p>
                            <p class="order-tracking-detail-address-sub">
                                {{ order.address_type }} - {{ address }}
                            </p>
                        </div>
                        <ul class="settings-order-tracking-list">
                            <li v-for="detail in order.details" :key="detail.id" class="settings-order-tracking-item">
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
                        <div class="order-tracking-detail-bottom settings-order-tracking-bottom">
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Tổng tiền hàng:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-price-curr">
                                        <sup>đ</sup>{{ formatPrice(order.product_total) }}
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Phí vận chuyển:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-price-curr">
                                        <sup>đ</sup>{{ formatPrice(order.shipping_fee) }}
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-total" v-show="order.voucher">
                                <p class="settings-order-tracking-total-text">
                                    Giảm giá:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-price-curr">
                                        <sup>đ</sup>-{{ formatPrice(order.voucher?.value) }}
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Thành tiền:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-total-content">
                                        <sup>đ</sup>{{ formatPrice(order.total_amount) }}
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-bottom-content" v-show="order.payment_method === 'Thanh toán khi nhận hàng' && order.status !== 4">
                                <div class="settings-order-tracking-bottom-left">
                                    <span>
                                        Vui lòng thanh toán <sup>đ</sup>{{ formatPrice(order.total_amount) }} khi nhận hàng.
                                    </span>
                                </div>
                                <div class="order-tracking-detail-bottom-right">
                                    <div class="order-tracking-detail-bottom-right-title">
                                        <span>Phương thức Thanh toán:</span>
                                    </div>
                                    <div class="order-tracking-detail-bottom-right-content">
                                        <span>{{ order.payment_method }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { orderApi, paymentApi } from '@/api';
import { formatDate, formatPrice, getImageUrl } from '@/utils/helpers';
import { useOrderStore } from '@/stores/order';
import { useReviewStore } from '@/stores/review';

export default {
    setup() {
        const orderStore = useOrderStore();
        const reviewStore = useReviewStore();
        return {
            orderStore,
            reviewStore,
        };
    },
    data() {
        return {
            isLoading: false,
            order: {},
            city: '',
            district: '',
            ward: '',
        }
    },
    computed: {
        progressWidth() {
            let n = this.order.status + 1;
            return (n > 4 ? 4 : n) * 25;
        },
        address() {
            const parts = [
                this.order.shipping_address,
                this.order.ward?.name,
                this.order.district?.name,
                this.order.city?.name
            ];
            return parts.filter(part => !!part).join(', ');
        },
        areAllReviewed() {
            return this.order.details?.every(detail => detail.isReviewed) || false;
        }
    },
    beforeRouteEnter(to, from, next) {
        const token = localStorage.getItem('token');
        if (!token) {
            next({ name: 'NotFound' });
            return;
        }
        next();
    },
    created() {
        this.fetchData();
    },
    methods: {
        formatDate, formatPrice, getImageUrl,
        async fetchData() {
            this.isLoading = true;
            try {
                const response = await orderApi.getOrderByUser(this.$route.params.id);
                this.order = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        reorder(details) {
            this.orderStore.setReorder(details);
            this.$router.push('/don-hang');
        },
        isReturnExpired(completedAt) {
            if (!completedAt) return true;
            const completedDate = new Date(completedAt * 1000);
            const now = new Date();
            const diffTime = now - completedDate;
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            return diffDays > 14;
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
                    window.location.href = '/theo-doi-don-hang/' + id;
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
                this.order = res.data;
                this.$swal.fire('Đã hủy đơn hàng', 'Đơn hàng đã được hủy thành công!', 'success');
            } catch (error) {
                console.error(error);
            }
        },
        reviewOrder(order) {
            this.reviewStore.setReview(order);
            this.$router.push({ name: 'orderReview' })
        }
    }
}
</script>

<style>
.order-tracking-detail-step-line::before {
    width: var(--progress-width, 0%);
}
</style>