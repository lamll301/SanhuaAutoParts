<template>
    <div class="grid">
        <div class="cart">
            <h1 class="cart-heading">Giỏ hàng</h1>
                <div class="cart-container">
                    <template v-if="isLoading">
                        <div class="cart-left">
                            <ul class="cart-list">
                                <li v-for="i in 3" :key="i" class="cart-item" style="width: 941px;display: flex;">
                                    <div style="width: 140px;">
                                        <SkeletonLoading width="140px" height="140px" marginBottom="0" />
                                    </div>
                                    <div style="width: 100%;margin-left: 20px;">
                                        <SkeletonLoading width="70%" height="20px" />
                                        <SkeletonLoading width="40%" height="16px" />
                                        <SkeletonLoading width="50px" height="18px" />
                                        <SkeletonLoading width="100px" height="30px" />
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </template>
                    <template v-else>
                        <div class="cart-left">
                            <ul class="cart-list" v-if="cartDetails?.length > 0">
                                <li v-for="cartDetail in cartDetails" :key="cartDetail.id" class="cart-item">
                                    <div class="cart-item-heading">
                                        <span class="cart-item-id">
                                            Mã: {{ cartDetail?.product?.id }}
                                        </span>
                                        <div class="cart-item-heading-info">
                                            <div v-show="cartDetail?.product?.quantity === 0">
                                                <span class="cart-item-status dark-red">
                                                    Sản phẩm này hiện đã hết hàng
                                                </span>
                                                <i class="dark-red fa-solid fa-circle-exclamation"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-item-content">
                                        <div class="cart-item-left">
                                            <img :src="getImageUrl(cartDetail?.product?.images[0]?.path)" alt="" class="cart-item-img">
                                            <div class="cart-item-product">
                                                <router-link :to="'/san-pham/' + cartDetail?.product?.slug" class="cart-item-name">
                                                    {{ cartDetail?.product?.name }}
                                                </router-link>
                                                <div class="cart-item-rate product-rating">
                                                    <RatingStars :rating="cartDetail?.product?.rate" starClass="product-star" />
                                                    <span class="product-evaluate">
                                                        ({{ cartDetail?.product?.reviewCount }})
                                                    </span>
                                                </div>
                                                <span class="cart-item-price-curr">
                                                    {{ formatPrice(cartDetail?.product?.price) }}<sup>đ</sup>
                                                </span>
                                                <span class="cart-item-price-old" v-show="cartDetail?.product?.price !== cartDetail?.product?.original_price">
                                                    {{ formatPrice(cartDetail?.product?.original_price) }}<sup>đ</sup>
                                                </span>
                                                <p class="cart-item-stock">
                                                    {{ cartDetail?.product?.quantity }} sản phẩm có sẵn
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
                                                    :disabled="cartDetail?.product?.quantity === 0"
                                                    min="1"
                                                    :max="cartDetail?.product?.quantity"
                                                    type="number" class="cart-item-quantity-input"
                                                >
                                            </div>
                                            <div class="cart-item-price">
                                                <p class="cart-item-price-total">
                                                    {{ formatPrice(cartDetail?.subtotal) }}<sup>đ</sup>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-item-footer">
                                        <button class="cart-item-btn cart-item-buy-it-now">
                                            Mua ngay
                                        </button>
                                        <button class="cart-item-btn cart-item-remove" @click="removeCart(cartDetail.id)">
                                            Loại bỏ
                                        </button>
                                    </div>
                                </li>
                            </ul>
                            <div style="width: 942px;" v-else>
                                <vue3-lottie :animationData="animationData" :height="400" :width="400" />
                                <p style="font-size: 1.6rem;text-align: center;margin-top: 20px;">Giỏ hàng của bạn đang trống.</p>
                            </div>
                        </div>
                    </template>
                    <div class="cart-right">
                        <div class="cart-summary-line-item">
                            <span>Các mặt hàng ({{ availableItemsCount  }})</span>
                            <span>
                                {{ formatPrice(total) || 0 }}<sup>đ</sup>
                            </span>
                        </div>
                        <div class="cart-total cart-summary-line-item">
                            <span>Tổng cộng</span>
                            <span>
                                {{ formatPrice(total) || 0 }}<sup>đ</sup>
                            </span>
                        </div>
                        <button to="/don-hang" @click="goToCheckout()" class="button cart-btn" style="width: 100%;">
                            Đi tới thanh toán
                        </button>
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

import apiService from '@/utils/apiService';
import { formatPrice, getImageUrl } from '@/utils/helpers'
import RatingStars from '@/components/RatingStars.vue';
import SkeletonLoading from '@/components/SkeletonLoading.vue';
import { Vue3Lottie } from 'vue3-lottie';
import animationData from '@/assets/images/Animation - 1746356684864.json';

export default {
    name: 'ShoppingCart',
    setup() {
        return { animationData };
    },
    computed: {
        total() {
            return this.cartDetails.reduce((sum, item) => {
                if (item.product.quantity > 0) {
                    return sum + (item.product.price * item.quantity);
                }
                return sum;
            }, 0);
        },
        availableItemsCount() {
            return this.cartDetails.filter(item => item.product.quantity > 0).length;
        }
    },
    data() {
        return {
            cartDetails: [],
            isLoading: false,
            deletedIds: [],
        }
    },
    components: {
        RatingStars, SkeletonLoading, Vue3Lottie
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            try {
                const res = await apiService.carts.getCart()
                this.cartDetails = res.data?.details
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        onQuantityChange(cartDetail) {
            if (cartDetail.quantity < 1) {
                cartDetail.quantity = 1;
            } else if (cartDetail.quantity > cartDetail.product.quantity) {
                cartDetail.quantity = cartDetail.product.quantity;
            }
            cartDetail.subtotal = cartDetail.product.price * cartDetail.quantity;
        },
        removeCart(id) {
            this.cartDetails = this.cartDetails.filter(item => item.id !== id);
            this.deletedIds.push(id);
        },
        async save() {
            try {
                const req = this.cartDetails
                    .filter(cartDetail => cartDetail.product.quantity > 0)
                    .map(cartDetail => apiService.carts.updateCart(cartDetail.product.id, cartDetail.quantity))
                if (this.deletedIds.length > 0) {
                    this.deletedIds.forEach(id => {
                        req.push(apiService.carts.removeFromCart(id))
                    })
                }
                await Promise.all(req);
            } catch (error) {
                console.error(error);
            }
        },
        goToCheckout() {
            if (this.total === 0) {
                this.$swal.fire("Giỏ hàng trống!", "Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.", "error")
                return;
            }
            this.$router.push('/don-hang')
        },
        formatPrice, getImageUrl,
    },
    created() {
        this.fetchData();
    },
    beforeRouteLeave (to, from, next) {
        this.save();
        next();
    }
}
</script>