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
                                    <span v-if="order.status === 'completed'">Hoàn thành</span>
                                    <span v-else-if="order.status === 'canceled'">Đã hủy</span>
                                    <span v-else-if="order.status === 'approved'">Chờ thanh toán</span>
                                    <span v-else-if="order.status === 'shipping'">Chờ giao hàng</span>
                                    <span v-else-if="order.status === 'returned'">Trả hàng/Hoàn tiền</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="settings-order-tracking-top order-tracking-detail-top-2">
                        <div v-if="order.status !== 'canceled'" class="order-tracking-detail-stepper">
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon order-tracking-detail-step-icon--completed">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    Đơn hàng đã đặt
                                </p>
                                <span class="order-tracking-detail-step-text-sub">{{ order.createdAt }}</span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon order-tracking-detail-step-icon--active"
                                :class="{
                                    'order-tracking-detail-step-icon--completed': 'approved' in statusHistoryMap
                                }"    
                                >
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        'approved' in statusHistoryMap
                                        ? 'Đã xác nhận thông tin thanh toán'
                                        : 'Đơn hàng chưa được thanh toán' 
                                    }}
                                </p>
                                <span v-if="'approved' in statusHistoryMap" class="order-tracking-detail-step-text-sub">
                                    {{ statusHistoryMap.approved }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon"
                                :class="{
                                    'order-tracking-detail-step-icon--active': 'approved' in statusHistoryMap && !('shipping' in statusHistoryMap),
                                    'order-tracking-detail-step-icon--completed': 'shipping' in statusHistoryMap
                                }"
                                >
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        'shipping' in statusHistoryMap
                                        ? 'Đã giao cho đơn vị vận chuyển'
                                        : 'Đang trong quá trình đóng gói' 
                                    }}
                                </p>
                                <span v-if="'shipping' in statusHistoryMap" class="order-tracking-detail-step-text-sub">
                                    {{ statusHistoryMap.shipping }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon"
                                :class="{
                                    'order-tracking-detail-step-icon--active': 'shipping' in statusHistoryMap && !('completed' in statusHistoryMap),
                                    'order-tracking-detail-step-icon--completed': 'completed' in statusHistoryMap
                                }"
                                >
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <p class="order-tracking-detail-step-text">
                                    {{ 
                                        'completed' in statusHistoryMap
                                        ? 'Đã nhận được hàng'
                                        : 'Chờ giao hàng' 
                                    }}
                                </p>
                                <span v-if="'completed' in statusHistoryMap" class="order-tracking-detail-step-text-sub">
                                    {{ statusHistoryMap.completed }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step">
                                <div class="order-tracking-detail-step-icon"
                                :class="{
                                    'order-tracking-detail-step-icon--active': !areAllReviewed 
                                        && 'completed' in statusHistoryMap,
                                    'order-tracking-detail-step-icon--completed': areAllReviewed && 'completed' in statusHistoryMap
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
                                <span v-if="areAllReviewed" class="order-tracking-detail-step-text-sub">
                                    {{ order.updatedAt }}
                                </span>
                            </div>
                            <div class="order-tracking-detail-step-line" :style="{ '--progress-width': progressWidth + '%' }"></div>
                        </div>
                        <div v-else>
                            <p style="font-size: 2rem; color: #ee4d2d;">Đã hủy đơn hàng</p>
                            <span style="font-size: 1.4rem;">Vào {{ order.updatedAt }}</span>
                        </div>
                    </div>
                    <div class="settings-order-tracking-bottom">
                        <div class="settings-order-tracking-bottom-content">
                            <div class="settings-order-tracking-bottom-left">
                                <span v-if="order.status === 'completed'">
                                    <span v-if="isRefundable">
                                        Nếu hàng nhận được có vấn đề, bạn có thể gửi yêu cầu Trả hàng/Hoàn tiền trước {{ order.refundDeadlineAt }}
                                    </span>
                                    <span v-else>
                                        Cảm ơn bạn đã mua sắm tại Sanhua!
                                    </span>
                                </span>
                                <span v-else-if="order.status === 'canceled'">
                                    Lý do: {{ order.note }}
                                </span>
                            </div>
                            <div class="settings-order-tracking-bottom-right">
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="!order.status"
                                >
                                    Thanh toán
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.status === 'approved' || !order.status"
                                >
                                    Hủy Đơn Hàng
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.status === 'completed' && !areAllReviewed"
                                >
                                    Đánh giá
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1"
                                    v-if="order.status === 'completed' && isRefundable"
                                >
                                    Yêu cầu Trả hàng/Hoàn tiền
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-2">
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
                                {{ order.address }}
                            </p>
                        </div>
                        <ul class="settings-order-tracking-list">
                            <li v-for="item in order.autoParts" :key="item.id" class="settings-order-tracking-item">
                                <a class="settings-order-tracking-details">
                                    <div class="settings-order-tracking-left">
                                        <img :src="item.image" alt="" class="settings-order-tracking-img">
                                        <div class="settings-order-tracking-left-content">
                                            <p class="settings-order-tracking-text settings-order-tracking-name">
                                                {{ item.name }}
                                            </p>
                                            <p class="settings-order-tracking-text settings-order-tracking-type">
                                                Phân loại hàng: {{ item.type }}
                                            </p>
                                            <p class="settings-order-tracking-text">x{{ item.quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="settings-order-tracking-right">
                                        <span class="settings-order-tracking-price-old">
                                            <sup>đ</sup>{{ item.oldPrice.toLocaleString() }}
                                        </span>
                                        <span class="settings-order-tracking-price-curr">
                                            <sup>đ</sup>{{ item.price.toLocaleString() }}
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
                                        <sup>đ</sup>35.000
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Phí vận chuyển:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-price-curr">
                                        <sup>đ</sup>35.000
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Giảm giá:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-price-curr">
                                        <sup>đ</sup>-35.000
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-total">
                                <p class="settings-order-tracking-total-text">
                                    Thành tiền:
                                    <span class="order-tracking-detail-bottom-right-content settings-order-tracking-total-content">
                                        <sup>đ</sup>35.000
                                    </span>
                                </p>
                            </div>
                            <div class="settings-order-tracking-bottom-content">
                                <div class="settings-order-tracking-bottom-left">
                                    <span>
                                        Vui lòng thanh toán <sup>đ</sup>52.500 khi nhận hàng.
                                    </span>
                                </div>
                                <div class="order-tracking-detail-bottom-right">
                                    <div class="order-tracking-detail-bottom-right-title">
                                        <span>Phương thức Thanh toán:</span>
                                    </div>
                                    <div class="order-tracking-detail-bottom-right-content">
                                        <span>Thanh toán khi nhận hàng</span>
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

export default {
    data() {
        return {
            order: {
                id: "1234",
                name: "Lê Lý Lâm",
                phone: "0343343434",
                address: "Số 8 Tập thể Hanel, phường Thành Tô, quận Hải An, thành phố Hải Phòng",
                note: "Muốn thay đổi địa chỉ giao hàng",
                createdAt: '09:22 08-01-2022',
                updatedAt: '12:52 30-01-2025',
                refundDeadlineAt: '09:22 25-02-2025',   // hạn đổi trả/hoàn tiền
                statusHistory: "approved=09:22 08-01-2021;shipping=09:25 08-01-2022;completed=09:25 08-01-2022;",
                // phải theo thứ tự: approved=09:22 08-01-2021;shipping=09:25 08-01-2022;completed=09:25 08-01-2022;
                // status: 'approved' // duyệt thanh toán và thông tin đơn, 'shipping', 'completed', 'canceled', 'returned'
                status: "canceled",
                autoParts: [
                    {
                        id: 1,
                        image: "https://picsum.photos/200",
                        name: "Sách - Đắc nhân tâm",
                        type: "Màu mẫu trắng mới, 15x50",
                        quantity: 10,
                        oldPrice: 100000,
                        price: 80000,
                        isReviewed: false,
                    },
                    {
                        id: 2,
                        image: "https://picsum.photos/200",
                        name: "Sách - Không là sói cũng đừng là cừu",
                        type: "Màu mẫu trắng mới, 15x21",
                        quantity: 5,
                        oldPrice: 35000,
                        price: 30000,
                        isReviewed: true,
                    },
                ]
            }
        }
    },
    computed: {
        statusHistoryMap() {
            return this.order.statusHistory.split(";").reduce((acc, pair) => {
                if (pair.trim()) {
                    const [key, value] = pair.split("=");
                    if (key && value) {
                        acc[key.trim()] = value.trim();
                    }
                }
                return acc;
            }, {});
        },
        progressWidth() {
            const n = Object.keys(this.statusHistoryMap).length + 1;
            return (n > 4 ? 4 : n) * 25;
        },
        areAllReviewed() {
            return this.order.autoParts.every(part => part.isReviewed);
        },
        isRefundable() {
            const completedDate = this.convertDate(this.order.refundDeadlineAt)
            return new Date() <= completedDate;
        }
    },
    methods: {
        convertDate(dateString) {
            const [time, date] = dateString.split(' ');
            const [hours, minutes] = time.split(':');
            const [day, month, year] = date.split('-');
            return new Date(year, month - 1, day, hours, minutes);
        }
    }
}
</script>

<style>
.order-tracking-detail-step-line::before {
    width: var(--progress-width, 0%);
}
</style>