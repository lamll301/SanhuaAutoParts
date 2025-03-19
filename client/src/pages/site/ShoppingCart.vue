<template>
    <div class="grid">
        <div class="cart">
            <h1 class="cart-heading">Giỏ hàng</h1>
            <div class="cart-container">
                <div class="cart-left">
                    <ul class="cart-list">
                        <li v-for="cartDetail in cartDetails" :key="cartDetail" class="cart-item">
                            <div class="cart-item-heading">
                                <span class="cart-item-id">
                                    Mã: {{ cartDetail.autoPartId }}
                                </span>
                                <div class="cart-item-heading-info">
                                    <div v-if="cartDetail.stockQuantity === 0">
                                        <span class="cart-item-status dark-red">
                                            Sản phẩm này hiện đã hết hàng
                                        </span>
                                        <i class="dark-red fa-solid fa-circle-exclamation"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-item-content">
                                <div class="cart-item-left">
                                    <img :src="cartDetail.image" alt="" class="cart-item-img">
                                    <div class="cart-item-product">
                                        <router-link :to="'/san-pham/' + cartDetail.autoPartId" class="cart-item-name">
                                            {{ cartDetail.name }}
                                        </router-link>
                                        <div class="cart-item-rate product-rating">
                                            <RatingStars :rating="cartDetail.rating" starClass="product-star" />
                                            <span class="product-evaluate">
                                                ({{ cartDetail.rating }})
                                            </span>
                                        </div>
                                        <span class="cart-item-price-curr">
                                            {{ formatPrice(cartDetail.price * (1 - cartDetail.discount * 0.01)) }}<sup>đ</sup>
                                        </span>
                                        <span class="cart-item-price-old">
                                            {{ formatPrice(cartDetail.price) }}<sup>đ</sup>
                                        </span>
                                        <p class="cart-item-stock">
                                            {{ cartDetail.stockQuantity }} sản phẩm có sẵn
                                        </p>
                                    </div>
                                </div>
                                <div class="cart-item-right">
                                    <div class="cart-item-quantity">
                                        <span class="cart-item-quantity-text">
                                            Số lượng: 
                                        </span>
                                        <input 
                                            v-model="cartDetail.quantity" 
                                            @change="onQuantityChange(cartDetail)" 
                                            :disabled="cartDetail.stockQuantity === 0"
                                            min="1"
                                            :max="cartDetail.stockQuantity"
                                            type="number" class="cart-item-quantity-input"
                                        >
                                        <p class="cart-item-type">
                                            Loại: {{ cartDetail.type }}
                                        </p>
                                    </div>
                                    <div class="cart-item-price">
                                        <p class="cart-item-price-total">
                                            {{ formatPrice(cartDetail.total) }}<sup>đ</sup>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-item-footer">
                                <button class="cart-item-btn cart-item-buy-it-now">
                                    Mua ngay
                                </button>
                                <button class="cart-item-btn cart-item-remove">
                                    Loại bỏ
                                </button>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="cart-right">
                    <div class="cart-summary-line-item">
                        <span>Các mặt hàng ({{ cartDetails.length }})</span>
                        <span>
                            {{ formatPrice(cart.subTotal) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-summary-line-item">
                        <span>Phí vận chuyển</span>
                        <span>
                            {{ formatPrice(cart.shippingFee) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-summary-line-item">
                        <span>
                            Thuế (VAT)
                        </span>
                        <span>
                            {{ formatPrice(cart.subTotal * 0.1) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-total cart-summary-line-item">
                        <span>Total</span>
                        <span>
                            {{ formatPrice(cart.totalPrice) }}<sup>đ</sup>
                        </span>
                    </div>
                    <router-link to="/don-hang" class="button cart-btn">
                        Đi tới thanh toán
                    </router-link>
                    <span class="cart-label">
                        <img src="@/assets/images/secure.png" alt="">
                        Được bảo vệ bởi
                        <a href="#">
                            chính sách hoàn tiền của Sanhua
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { formatPrice } from '@/helpers/helpers.js'
import { swalMixin } from '@/helpers/swal.js'
import RatingStars from '@/components/RatingStars.vue';

export default {
    name: 'ShoppingCart',
    data() {
        return {
            cartDetails: [
                {
                    autoPartId: 'SP001',
                    name: "Bạt phủ xe ô tô chống nước, chống nắng",
                    image: "https://autoca.shop/uploads/5-3-1jpg.webp",
                    price: 550000,
                    discount: 18,
                    rating: 50,
                    quantity: 1,
                    total: 451000,
                    type: "Đồ bảo vệ xe",
                    stockQuantity: 500
                },
                {
                    autoPartId: 'SP002',
                    name: "Màn hình Android ô tô Vinfast Fadil 9 inch",
                    image: "https://pandaauto.vn/wp-content/uploads/2022/04/man-hinh-vinfast-fadil.jpg",
                    price: 950000,
                    discount: 21,
                    rating: 40,
                    quantity: 1,
                    total: 750500,
                    type: "Thiết bị giải trí",
                    stockQuantity: 100
                },
                {
                    autoPartId: 'SP003',
                    name: "Nệm hơi ô tô du lịch cao cấp",
                    image: "https://bhmart.vn/wp-content/uploads/2023/07/1-27.jpg",
                    price: 450000,
                    discount: 15,
                    rating: 50,
                    quantity: 1,
                    total: 382500,
                    type: "Tiện ích xe hơi",
                    stockQuantity: 3
                },
                {
                    autoPartId: 'SP004',
                    name: "Camera hành trình Xiaomi 70mai Pro GPS",
                    image: "https://dinhvitoancau.net/wp-content/uploads/2021/02/A500-pro-plus.png",
                    price: 1500000,
                    discount: 14,
                    rating: 48,
                    quantity: 1,
                    total: 1290000,
                    type: "Thiết bị an toàn",
                    stockQuantity: 20
                },
                {
                    autoPartId: 'SP005',
                    name: "Bơm lốp xe ô tô mini 12V",
                    image: "https://tamsonshop.vn/wp-content/uploads/2023/05/may-bom-hoi-lop-xe-oto-mini-12v-ky-thuat-so-p1100-5.jpg",
                    price: 800000,
                    discount: 19,
                    rating: 45,
                    quantity: 1,
                    total: 648000,
                    type: "Dụng cụ sửa chữa",
                    stockQuantity: 30
                },
                {
                    autoPartId: 'SP006',
                    name: "Cảm biến áp suất lốp ô tô TPMS",
                    image: "https://phukiendochoixehoi.vn/wp-content/uploads/2020/09/cam-bien-ap-suat-lop-van-ngoai-1.jpg",
                    price: 1400000,
                    discount: 15,
                    rating: 47,
                    quantity: 1,
                    total: 1190000,
                    type: "Công nghệ xe",
                    stockQuantity: 25
                }
            ],
            cart: {
                subTotal: 4711000,
                shippingFee: 70000,
                totalPrice: 4781000
            }
        }
    },
    components: {
        RatingStars,
    },
    methods: {
        validateQuantity(cartDetail) {
            let isValid = true;
            if (cartDetail.quantity <= 0 || !Number.isInteger(cartDetail.quantity)) {
                swalMixin('error', 'Giá trị nhập vào không hợp lệ.')
                isValid = false;
            }
            if (cartDetail.quantity > cartDetail.stockQuantity) {
                swalMixin('error', 'Không đủ hàng trong kho. Vui lòng chọn lại số lượng.')
                isValid = false;
            }
            return isValid;
        },
        onQuantityChange(cartDetail) {
            if (!this.validateQuantity(cartDetail)) {
                return;
            }
            cartDetail.total = cartDetail.price * (1 - cartDetail.discount * 0.01) * cartDetail.quantity;
            this.calculateCart();
        },
        calculateCart() {   // tính tiền sản phẩm, tiền thuế, tiền vận chuyển & tổng tiền
            let subTotal = 0;
            this.cartDetails.forEach(detail => {
                subTotal += detail.total;
            });
            this.cart.subTotal = subTotal;
            this.cart.totalPrice = subTotal + this.cart.shippingFee + (subTotal * 0.1);
        },
        saveCart() {
            console.log("OK")
        },
        swalMixin, formatPrice,
    },
    created() {
        this.cartDetails.forEach(cartDetail => {    // các trường hợp hết hàng
            if (cartDetail.stockQuantity === 0) {
                cartDetail.total = 0;
                cartDetail.quantity = 0;
            }
        });
    },
    beforeRouteLeave (to, from, next) {     // gọi phương thức trước khi rời khỏi trang
        this.saveCart();
        next();
    }
}
</script>