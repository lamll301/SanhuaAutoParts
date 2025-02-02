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
                                            {{ formatPrice(cartDetail.priceCurrent) }}<sup>đ</sup>
                                        </span>
                                        <span class="cart-item-price-old">
                                            {{ formatPrice(cartDetail.priceOld) }}<sup>đ</sup>
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
                    name: "Macbook 12-inch Retina Display 1.2 GHz Dual-Core Intel Core M Processor",
                    image: "https://images.gr-assets.com/authors/1430676293p8/17212.jpg",
                    priceCurrent: 10000000,
                    priceOld: 12000000,
                    discount: 16.67,
                    rating: 5,
                    quantity: 1,
                    total: 10000000,
                    type: "Laptop",
                    stockQuantity: 5
                },
                {
                    autoPartId: 'SP002',
                    name: "Điện thoại Apple iPhone 13 Pro Max 256GB Chính hãng VN/A",
                    image: "https://images.gr-assets.com/authors/1615483024p5/676.jpg",
                    priceCurrent: 25000000,
                    priceOld: 27000000,
                    discount: 7.41,
                    rating: 4,
                    quantity: 2,
                    total: 50000000,
                    type: "Điện thoại",
                    stockQuantity: 0
                },
                {
                    autoPartId: 'SP003',
                    name: "Laptop Dell XPS 13 9310 Thin and Light Touchscreen Laptop",
                    image: "https://images.gr-assets.com/authors/1615483024p5/676.jpg",
                    priceCurrent: 22000000,
                    priceOld: 25000000,
                    discount: 12.00,
                    rating: 5,
                    quantity: 1,
                    total: 22000000,
                    type: "Laptop",
                    stockQuantity: 3
                },
                {
                    autoPartId: 'SP004',
                    name: "Tai nghe chống ồn Sony WH-1000XM4 Wireless Over-Ear Headphones",
                    image: "https://images.gr-assets.com/authors/1300822269p5/102824.jpg",
                    priceCurrent: 8000000,
                    priceOld: 9500000,
                    discount: 15.79,
                    rating: 4,
                    quantity: 3,
                    total: 24000000,
                    type: "Phụ kiện",
                    stockQuantity: 0
                },
                {
                    autoPartId: 'SP005',
                    name: "Máy tính bảng Samsung Galaxy Tab S6 Wi-Fi, Super AMOLED Display",
                    image: "https://images.gr-assets.com/authors/1430676293p8/17212.jpg",
                    priceCurrent: 12000000,
                    priceOld: 15000000,
                    discount: 20.00,
                    rating: 4,
                    quantity: 1,
                    total: 12000000,
                    type: "Máy tính bảng",
                    stockQuantity: 7
                },
                {
                    autoPartId: 'SP006',
                    name: "Đồng hồ thông minh Apple Watch Series 7 GPS 45mm",
                    image: "https://images.gr-assets.com/authors/1300822269p5/102824.jpg",
                    priceCurrent: 8000000,
                    priceOld: 9500000,
                    discount: 15.79,
                    rating: 5,
                    quantity: 2,
                    total: 16000000,
                    type: "Phụ kiện",
                    stockQuantity: 4
                }
            ],
            cart: {
                subTotal: 186000000,
                shippingFee: 5000000,
                totalPrice: 191000000
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
            cartDetail.total = cartDetail.priceCurrent * cartDetail.quantity;
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