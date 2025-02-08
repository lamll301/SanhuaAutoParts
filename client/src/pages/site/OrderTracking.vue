<template>
    <div class="grid">
        <div class="settings">
            <h1 class="settings-heading">
                Theo dõi đơn mua
            </h1>
            <div class="settings-tab-container">
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === '' }"
                    @click="activeTab = ''"
                >
                    Tất cả
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'pending_payment' }"
                    @click="activeTab = 'pending_payment'"
                >
                    Chờ thanh toán
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'pending_delivery' }"
                    @click="activeTab = 'pending_delivery'"
                >
                    Chờ giao hàng
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'completed' }"
                    @click="activeTab = 'completed'"
                >
                    Hoàn thành
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'cancelled' }"
                    @click="activeTab = 'cancelled'"
                >
                    Đã hủy
                </div>
                <div class="settings-tab-text"
                    :class="{ 'settings-tab-text--active': activeTab === 'refund' }"
                    @click="activeTab = 'refund'"
                >
                    Trả hàng/Hoàn tiền
                </div>
            </div>
            <div class="settings-order-tracking settings-tab-content">
                <input type="text" class="settings-order-tracking-search" placeholder="Bạn có thể tìm kiếm theo ID đơn hàng hoặc Tên sản phẩm">
                <i class="settings-order-tracking-search-icon fa-solid fa-magnifying-glass"></i>
                <div class="settings-order-tracking-container"
                    v-for="order in filteredOrders" :key="order.id"
                >
                    <router-link class="default-router-link" :to="'/theo-doi-don-hang/' + order.id">
                        <div class="settings-order-tracking-top">
                            <div class="settings-order-tracking-heading">
                                <span>Mã đơn hàng: {{ order.id }}</span>
                                <div class="settings-order-tracking-status">
                                    <i class="fa-solid fa-truck-fast"></i>
                                    <span class="settings-order-tracking-status-desc cart-item-status">
                                        {{ getStatusText(order.status) }}
                                    </span>
                                    <div class="settings-order-tracking-icon">
                                        <i class="fa-regular fa-circle-question"></i>
                                        <div class="settings-order-tracking-info">
                                            <p>
                                                Cập Nhật Mới Nhất
                                            </p>
                                            <span>
                                                {{ order.date }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="settings-order-tracking-status-text">{{ getStatusText(order.status) }}</span>
                                </div>
                            </div>
                            <ul class="settings-order-tracking-list">
                                <li class="settings-order-tracking-item"
                                    v-for="product in order.products" :key="product.name"
                                >
                                    <a class="settings-order-tracking-details">
                                        <div class="settings-order-tracking-left">
                                            <img :src="product.image" alt="" class="settings-order-tracking-img">
                                            <div class="settings-order-tracking-left-content">
                                                <p class="settings-order-tracking-text settings-order-tracking-name">
                                                    {{ product.name }}
                                                </p>
                                                <p class="settings-order-tracking-text settings-order-tracking-type">
                                                    Phân loại hàng: {{ product.type }}
                                                </p>
                                                <p class="settings-order-tracking-text">x{{ product.quantity }}</p>
                                            </div>
                                        </div>
                                        <div class="settings-order-tracking-right">
                                            <span class="settings-order-tracking-price-old">
                                                <sup>đ</sup>{{ product.oldPrice.toLocaleString() }}
                                            </span>
                                            <span class="settings-order-tracking-price-curr">
                                                <sup>đ</sup>{{ product.price.toLocaleString() }}
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
                                    <sup>đ</sup>{{ order.total.toLocaleString() }}
                                </span>
                            </p>
                        </div>
                        <div class="settings-order-tracking-bottom-content">
                            <div class="settings-order-tracking-bottom-left">
                                <span>
                                    Đơn hàng sẽ được chuẩn bị và chuyển đi trước {{ order.deliveryDate }}
                                </span>
                            </div>
                            <div class="settings-order-tracking-bottom-right">
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-1">
                                    Mua lại
                                </button>
                                <button class="button settings-order-tracking-btn settings-order-tracking-btn-2">
                                    Hủy Đơn Hàng
                                </button>
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
            orders: [
                {
        id: 1234,
        status: "completed",
        date: "02-02-2025",
        products: [
            {
                name: "Sách - ABC",
                image: "https://picsum.photos/500/500",
                type: "Màu mẫu trắng mới, 15x21",
                quantity: 5,
                price: 30000,
                oldPrice: 35000
            },
            {
                name: "Sách - Nhà giả kim",
                image: "https://picsum.photos/500/500",
                type: "Bản đặc biệt, 20x25",
                quantity: 2,
                price: 50000,
                oldPrice: 60000
            }
        ],
        total: 35000,
        deliveryDate: "04-02-2025"
    },
    {
        id: 5678,
        status: "pending_payment",
        date: "01-02-2025",
        products: [
            {
                name: "Sách - Đắc nhân tâm",
                image: "https://picsum.photos/500/500",
                type: "Bản tiêu chuẩn, 15x21",
                quantity: 1,
                price: 40000,
                oldPrice: 45000
            }
        ],
        total: 40000,
        deliveryDate: "05-02-2025"
    },
    {
        id: 9101,
        status: "pending_delivery",
        date: "01-02-2025",
        products: [
            {
                name: "Sách - 1984",
                image: "https://picsum.photos/500/500",
                type: "Bản cổ điển, 15x21",
                quantity: 3,
                price: 45000,
                oldPrice: 50000
            }
        ],
        total: 135000,
        deliveryDate: "06-02-2025"
    },
    {
        id: 1122,
        status: "completed",
        date: "03-02-2025",
        products: [
            {
                name: "Sách - Tự lực văn đoàn",
                image: "https://picsum.photos/500/500",
                type: "Bản tái bản, 15x21",
                quantity: 4,
                price: 20000,
                oldPrice: 25000
            }
        ],
        total: 80000,
        deliveryDate: "06-02-2025"
    },
    {
        id: 3344,
        status: "cancelled",
        date: "01-02-2025",
        products: [
            {
                name: "Sách - Nghệ thuật sống",
                image: "https://picsum.photos/500/500",
                type: "Bản thường, 14x20",
                quantity: 2,
                price: 35000,
                oldPrice: 40000
            }
        ],
        total: 70000,
        deliveryDate: "07-02-2025"
    },
    {
        id: 5566,
        status: "refund",
        date: "04-02-2025",
        products: [
            {
                name: "Sách - Kỹ năng lãnh đạo",
                image: "https://picsum.photos/500/500",
                type: "Bản đặc biệt, 18x24",
                quantity: 2,
                price: 60000,
                oldPrice: 70000
            }
        ],
        total: 120000,
        deliveryDate: "08-02-2025"
    },
    {
        id: 7890,
        status: "completed",
        date: "02-02-2025",
        products: [
            {
                name: "Sách - Tâm lý học màu sắc",
                image: "https://picsum.photos/500/500",
                type: "Bản hardcover, 16x24",
                quantity: 3,
                price: 25000,
                oldPrice: 30000
            }
        ],
        total: 75000,
        deliveryDate: "06-02-2025"
    },
    {
        id: 1023,
        status: "pending_payment",
        date: "03-02-2025",
        products: [
            {
                name: "Sách - Triết học phương Đông",
                image: "https://picsum.photos/500/500",
                type: "Bản tái bản, 15x21",
                quantity: 1,
                price: 45000,
                oldPrice: 50000
            }
        ],
        total: 45000,
        deliveryDate: "07-02-2025"
    },
    {
        id: 4488,
        status: "pending_delivery",
        date: "02-02-2025",
        products: [
            {
                name: "Sách - Con đường dẫn tới thành công",
                image: "https://picsum.photos/500/500",
                type: "Bản đặc biệt, 15x22",
                quantity: 4,
                price: 35000,
                oldPrice: 40000
            }
        ],
        total: 140000,
        deliveryDate: "09-02-2025"
    },
    {
        id: 2123,
        status: "completed",
        date: "01-02-2025",
        products: [
            {
                name: "Sách - Lịch sử thế giới",
                image: "https://picsum.photos/500/500",
                type: "Bản tổng hợp, 18x25",
                quantity: 2,
                price: 60000,
                oldPrice: 70000
            }
        ],
        total: 120000,
        deliveryDate: "05-02-2025"
    },
    {
        id: 3445,
        status: "completed",
        date: "03-02-2025",
        products: [
            {
                name: "Sách - Khám phá vũ trụ",
                image: "https://picsum.photos/500/500",
                type: "Bản thường, 15x21",
                quantity: 3,
                price: 25000,
                oldPrice: 30000
            },
            {
                name: "Sách - Vũ trụ bao la",
                image: "https://picsum.photos/500/500",
                type: "Bản đặc biệt, 20x25",
                quantity: 2,
                price: 55000,
                oldPrice: 65000
            }
        ],
        total: 250000,
        deliveryDate: "06-02-2025"
    },
    {
        id: 5567,
        status: "pending_delivery",
        date: "05-02-2025",
        products: [
            {
                name: "Sách - Quản trị doanh nghiệp",
                image: "https://picsum.photos/500/500",
                type: "Bản tiêu chuẩn, 14x20",
                quantity: 4,
                price: 40000,
                oldPrice: 45000
            }
        ],
        total: 160000,
        deliveryDate: "09-02-2025"
    }
            ],
            activeTab: '',
        }
    },
    computed: {
        filteredOrders() {
            if (!this.activeTab) return this.orders;
            return this.orders.filter(order => order.status === this.activeTab);
        },
    },
    created() {
        
    },
    methods: {
        getStatusText(status) {
            switch (status) {
                case 'completed':
                    return 'Hoàn thành';
                case 'pending_payment':
                    return 'Chờ thanh toán';
                case 'pending_delivery':
                    return 'Chờ giao hàng';
                case 'cancelled':
                    return 'Đã hủy';
                case 'refund':
                    return 'Trả hàng/Hoàn tiền';
                default:
                    return 'Không xác định';
            }
        },
    }
}
</script>