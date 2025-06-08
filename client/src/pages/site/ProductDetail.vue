<template>
    <div class="grid">
        <div class="product">
            <div class="product-detail">
                <div class="product-left">
                    <div class="product-left-img-container">
                        <template v-if="isLoading">
                            <div class="product-list-img">
                                <div v-for="i in 6" :key="i">
                                    <SkeletonLoading height="100%" width="120px" class="product-list-img-content" style="overflow: hidden;" />
                                </div>
                            </div>
                            <SkeletonLoading height="100%" width="732px" style="width: 732px !important;" class="product-img-container" />
                        </template>
                        <template v-else>
                            <div class="product-list-img">
                                <div v-for="image in product?.images" :key="image" @click="selectImage(image)">
                                    <img :src="getImageUrl(image.path)" alt="" class="product-list-img-content"
                                        :class="{'product-list-img-content--active': selectedImage?.path === image.path}">
                                </div>
                            </div>
                            <ZoomImage 
                                :imageSrc="getImageUrl(selectedImage?.path)" 
                                containerClass="product-img-container" 
                                imageClass="product-img"
                            />
                        </template>
                    </div>
                    <div class="product-similar">
                        <h2 class="product-similar-text">
                            Sản phẩm liên quan
                        </h2>
                        <div class="product-similar-grid">
                            <template v-if="isLoading">
                                <div v-for="i in 8" :key="i" class="category-product skeleton-product">
                                    <SkeletonLoading height="200px" width="100%" />
                                    <div class="category-product-content">
                                        <div class="category-product-name">
                                            <SkeletonLoading height="18px" width="90%" marginBottom="8px" />
                                        </div>
                                        <div class="category-product-rating">
                                            <SkeletonLoading height="16px" width="60%" marginBottom="8px" />
                                        </div>
                                        <div class="category-product-price">
                                            <SkeletonLoading height="20px" width="40%" />
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <router-link v-for="product in similarProducts" :key="product" :to="'/san-pham/' + product.slug" class="category-product">
                                    <img :src="getImageUrl(product?.images[0]?.path)" alt="" class="category-product-img">
                                    <div class="category-product-content">
                                        <h2 class="category-product-name">
                                            {{ product.name }}
                                        </h2>
                                        <div class="category-product-rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span class="category-product-rate">{{ product.rate }}</span>
                                            <span class="category-product-sold">Đã bán {{ product.sold }} {{ product?.unit?.name }}</span>
                                        </div>
                                        <div class="category-product-price">
                                            <span class="category-product-price-new">
                                                {{ formatPrice(product.price) }}<sup>đ</sup>
                                            </span>
                                            <span v-if="product.price !== product.original_price" class="category-product-price-old">
                                                {{ formatPrice(product.original_price) }}<sup>đ</sup>
                                            </span>
                                        </div>
                                    </div>
                                    <span v-if="product.price !== product.original_price" class="category-onsale">
                                        -{{ product?.promotion?.discount_percent }}%
                                    </span>
                                </router-link>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="product-right">
                    <template v-if="isLoading">
                        <SkeletonLoading height="24px" width="80%" marginBottom="16px" class="product-name" />
                        <div class="product-rate">
                            <SkeletonLoading height="16px" width="120px" marginBottom="0" />
                        </div>
                        <div class="product-price">
                            <SkeletonLoading height="28px" width="50%" marginBottom="16px" class="product-price-current" />
                        </div>
                        <div class="product-desc">
                            <SkeletonLoading height="14px" width="100%" marginBottom="8px" />
                            <SkeletonLoading height="14px" width="90%" marginBottom="8px" />
                            <SkeletonLoading height="14px" width="80%" marginBottom="16px" />
                        </div>
                        <div class="product-quantity">
                            <SkeletonLoading height="16px" width="70px" marginBottom="8px" />
                            <SkeletonLoading height="36px" width="120px" marginBottom="8px" />
                            <SkeletonLoading height="16px" width="150px" marginBottom="16px" />
                        </div>
                        <div class="product-buttons">
                            <SkeletonLoading height="48px" width="100%" marginBottom="16px" />
                            <SkeletonLoading height="48px" width="100%" marginBottom="16px" />
                        </div>
                        <div class="product-labels">
                            <div v-for="i in 3" :key="i" class="product-label">
                                <SkeletonLoading height="60px" marginBottom="12px" />
                                <SkeletonLoading height="12px" width="80%" />
                                <SkeletonLoading height="12px" width="60%" marginBottom="0" />
                            </div>
                        </div>
                        <div class="product-specifications">
                            <SkeletonLoading height="14px" />
                            <SkeletonLoading height="14px" width="90%" />
                            <SkeletonLoading height="14px" width="80%" />
                            <SkeletonLoading height="14px" width="70%" />
                            <SkeletonLoading height="14px" width="60%" />
                        </div>
                        <div class="product-type">
                            <div class="product-type-content">
                                <ul class="product-type-list">
                                    <li v-for="i in 5" :key="i" class="product-type-item">
                                        <SkeletonLoading height="40px" width="80px" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <span class="product-name">
                            {{ product.name }}
                        </span>
                        <div class="product-rate">
                            <span class="product-id">
                                Mã: {{ product.id }}
                            </span>
                            <div class="product-rating">
                                <RatingStars :rating="product.rate" starClass="product-star" />
                                <span class="product-evaluate">
                                    ({{ total }} đánh giá)
                                </span>
                            </div>
                        </div>
                        <div class="product-price">
                            <span class="product-price-current">
                                {{ formatPrice(product.price) }}đ
                            </span>
                            <div v-if="product.price !== product.original_price" class="product-price-right">
                                <h6 class="product-price-discount">
                                    (Giảm {{ product.promotion.discount_percent }}%)
                                </h6>
                                <span class="product-price-old">
                                    {{ formatPrice(product.original_price) }}đ
                                </span>
                            </div>
                        </div>
                        <span class="product-desc">
                            {{ product.description }}
                        </span>
                        <div class="product-quantity">
                            <span class="product-quantity-text">
                                Số lượng:
                            </span>
                            <input type="number" class="product-quantity-input" v-model.number="quantity">
                            <span class="product-quantity-stock">
                                {{ product.quantity }} sản phẩm có sẵn
                            </span>
                        </div>
                        <button @click="buyNow(product)" class="product-btn-buy product-btn button" style="width: 100%;">
                            Mua ngay
                        </button>
                        <button @click="addToCart(product.id)" class="button product-btn-add-to-cart product-btn"
                            style="width: 100%;">
                            Thêm vào giỏ hàng
                        </button>
                        <div class="product-labels">
                            <div class="product-label">
                                <img src="../../assets/images/delivery-truck-2-150x150.png" alt="" class="product-label-img">
                                <span class="product-label-text">
                                    Giao hàng trong 1-4 ngày
                                </span>
                            </div>
                            <div class="product-label">
                                <img src="../../assets/images/medal-150x150.png" alt="" class="product-label-img">
                                <span class="product-label-text">
                                    100% hàng chính hãng và chất lượng
                                </span>
                            </div>
                            <div class="product-label">
                                <img src="../../assets/images/shield-150x150.png" alt="" class="product-label-img">
                                <span class="product-label-text">
                                    Bảo hành mở rộng
                                </span>
                            </div>
                        </div>
                        <div class="product-specifications">
                            <ul class="product-specifications-list">
                                <li class="product-specifications-item" v-if="product?.supplier?.name">
                                    <div class="product-specifications-title">
                                        Nhà cung cấp:
                                    </div>
                                    <div class="product-specifications-content">
                                        <ReadMoreButton> 
                                            <slot>
                                               {{ product?.supplier?.name }}
                                            </slot>
                                        </ReadMoreButton>
                                    </div>
                                </li>
                                <li class="product-specifications-item" v-if="product.dimensions">
                                    <div class="product-specifications-title">
                                        Kích thước:
                                    </div>
                                    <div class="product-specifications-content">
                                        <ReadMoreButton> 
                                            <slot>
                                               {{ product.dimensions }}
                                            </slot>
                                        </ReadMoreButton>
                                    </div>
                                </li>
                                <li class="product-specifications-item" v-if="product.weight">
                                    <div class="product-specifications-title">
                                        Trọng lượng:
                                    </div>
                                    <div class="product-specifications-content">
                                        <ReadMoreButton> 
                                            <slot>
                                               {{ product.weight }}
                                            </slot>
                                        </ReadMoreButton>
                                    </div>
                                </li>
                                <li class="product-specifications-item" v-if="product.color">
                                    <div class="product-specifications-title">
                                        Màu sắc:
                                    </div>
                                    <div class="product-specifications-content">
                                        <ReadMoreButton> 
                                            <slot>
                                               {{ product.color }}
                                            </slot>
                                        </ReadMoreButton>
                                    </div>
                                </li>
                                <li class="product-specifications-item" v-if="product.material">
                                    <div class="product-specifications-title">
                                        Chất liệu:
                                    </div>
                                    <div class="product-specifications-content">
                                        <ReadMoreButton> 
                                            <slot>
                                               {{ product.material }}
                                            </slot>
                                        </ReadMoreButton>
                                    </div>
                                </li>
                                <li class="product-specifications-item" v-if="product.compatibility">
                                    <div class="product-specifications-title">
                                        Tương thích:
                                    </div>
                                    <div class="product-specifications-content">
                                        <ReadMoreButton> 
                                            <slot>
                                               {{ product.compatibility }}
                                            </slot>
                                        </ReadMoreButton>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="product-type">
                            <div class="product-type-content">
                                <ul class="product-type-list">
                                    <li v-for="category in product.categories" :key="category" class="product-type-item">
                                        <router-link 
                                            :to="'/danh-muc/' + category.slug"
                                            class="product-type-btn default-router-link"
                                        >
                                            <img :src="getImageUrl(category?.images[0]?.path)" alt="" class="product-type-img">
                                            <span class="product-type-text">
                                                {{ category.name }}
                                            </span>
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="product-ratings">
                <h2 class="product-similar-text product-ratings-text">
                    Đánh giá sản phẩm
                </h2>
                <template v-if="isLoading">
                    <div class="product-rating-detail">
                        <div class="product-rating-sum">
                            <SkeletonLoading class="product-rating-scores" width="60px" height="60px"/>
                            <SkeletonLoading width="120px" />
                            <SkeletonLoading width="80px" />
                        </div>
                        <div class="product-rating-histogram">
                            <ul class="product-rating-histogram-list">
                                <li v-for="i in 5" :key="i" class="product-rating-histogram-item">
                                    <SkeletonLoading height="12px" width="500px" />
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-comment-list">
                        <div v-for="i in 10" :key="i" class="product-comment-item">
                            <div style="width: 80px;">
                                <SkeletonLoading height="60px" width="60px" class="product-comment-img" />
                            </div>
                            <div class="product-comment-text" style="width: 100%;">
                                <SkeletonLoading height="12px" width="20%" marginBottom="8px" />
                                <SkeletonLoading height="12px" width="80%" marginBottom="8px" />
                                <ul class="product-comment-img-list">
                                    <li v-for="i in 5" :key="i" class="product-comment-img-item">
                                        <SkeletonLoading height="72px" width="72px" marginBottom="8px" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <ul class="pagination category-pagination">
                        <li v-for="i in 8" :key="i" class="page-item category-page-item">
                            <SkeletonLoading height="40px" width="40px" borderRadius="50%" />
                        </li>
                    </ul>
                </template>
                <template v-else>
                    <div class="product-rating-detail">
                        <div class="product-rating-sum">
                            <div class="product-rating-scores">
                                <p class="product-rating-score">
                                    {{ product.rate }}
                                </p>
                                <span class="product-rating-score-max">/5</span>
                            </div>
                            <div class="product-rating-star">
                                <RatingStars :rating="product.rate" starClass="product-rating-icon" />
                            </div>
                            <span class="product-rating-total">
                                {{ total }} đánh giá
                            </span>
                        </div>
                        <div class="product-rating-histogram">
                            <ul class="product-rating-histogram-list">
                                <li v-for="stars in [5,4,3,2,1]" :key="stars" class="product-rating-histogram-item">
                                    <div class="product-rating-histogram-item-l">
                                        <i class="product-rating-icon fa-solid fa-star"></i>
                                        <span class="product-rating-histogram-item-l-star">
                                            {{ stars }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="product-rating-histogram-item-c">
                                            <div class="product-rating-histogram-item-c-bar" 
                                                :style="{
                                                    width: total > 0 ? ((ratingCounts[stars] || 0) / total) * 100 + '%' : '0%'
                                                }"
                                            ></div>
                                        </div>
                                    </div>
                                    <div class="product-rating-histogram-item-r">
                                        {{ ratingCounts[stars] || 0 }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-comment-list">
                        <div v-for="comment in comments" :key="comment.id" class="product-comment-item">
                            <img :src="getImageUrl(comment.user.avatar?.path, '/images/empty-avatar.webp')" alt="Avatar" class="product-comment-img">
                            <div class="product-comment-text">
                                <div class="product-comment-top">
                                    <div class="product-comment-star">
                                        <RatingStars :rating="comment.rating" />
                                    </div>
                                    <span class="product-comment-name">
                                        {{ comment.user?.name || 'Người dùng' }}
                                    </span>
                                    <span class="product-comment-time">
                                        - {{ formatDate(comment.updated_at) }}
                                    </span>
                                </div>
                                <p class="product-comment-content">
                                    {{ comment.comment }}
                                </p>
                                <ul class="product-comment-img-list">
                                    <li v-for="image in comment.images" :key="image" class="product-comment-img-item">
                                        <img alt="" class="product-comment-sub-img"
                                            :src="getImageUrl(image.path)"
                                            @click="toggleMainImage(comment.id, image.path)"
                                            :class="{ 'product-list-img-content--active': selectedCommentImage === image.path }"
                                        >
                                    </li>
                                </ul>
                                <img :src="selectedCommentImage ? getImageUrl(selectedCommentImage) : ''" alt="" class="product-comment-main-img"
                                    v-if="selectedCommentId === comment.id"
                                >
                            </div>
                        </div>
                    </div>
                    <SitePagination :totalPages="totalPages" :currentPage="currentPage"/>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { formatDate, formatPrice, getImageUrl } from '@/utils/helpers';
import { productApi, reviewApi, cartApi } from '@/api';
import SitePagination from '@/components/SitePagination.vue'
import ReadMoreButton from '@/components/ReadMoreButton.vue';
import ZoomImage from '@/components/ZoomImage.vue';
import RatingStars from '@/components/RatingStars.vue';
import SkeletonLoading from '@/components/SkeletonLoading.vue';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useOrderStore } from '@/stores/order';

export default {
    name: 'ProductDetail',
    data() {
        return {
            totalPages: 0, currentPage: 1, total: 0, ratingCounts: {},
            product: {},
            similarProducts: [],
            comments: [],
            isLoading: true, selectedImage: null, selectedCommentImage: null, selectedCommentId: null,
            quantity: 1
        }
    },
    setup() {
        const authStore = useAuthStore();
        const cartStore = useCartStore();
        const orderStore = useOrderStore();
        return {
            authStore,
            cartStore,
            orderStore
        }
    },
    computed: {
        slug() {
            return this.$route.params.slug;
        }
    },
    watch: {
        '$route': {
            handler: 'fetchData',
            immediate: true,
            deep: true,
        },
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            try {
                const slug = this.slug || '';
                const req = [
                    productApi.getBySlug(slug),
                    reviewApi.getByProductSlug(slug, { page: this.$route.query.page }),
                ];

                const res = await Promise.all(req)

                this.product = res[0].data.data;
                this.similarProducts = res[0].data.related
                this.selectedImage = this.product.images[0];
                this.ratingCounts = res[0].data.ratingCounts;
                this.comments = res[1].data.data
                this.totalPages = Math.ceil(res[1].data.pagination.total / res[1].data.pagination.per_page);
                this.currentPage = res[1].data.pagination.current_page;
                this.total = res[1].data.pagination.total;
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        async addToCart(productId) {
            if (!this.authStore.isAuthenticated) {
                this.$swal.fire("Vui lòng đăng nhập", "Vui lòng đăng nhập để thêm vào giỏ hàng", "warning");
                return;
            }
            if (this.quantity < 1 || this.quantity > this.product.quantity) {
                let message = this.quantity < 1
                    ? "Số lượng phải lớn hơn 0"
                    : `Số lượng vượt quá giới hạn. Tối đa: ${this.product.quantity}`;

                this.$swal.fire("Số lượng không hợp lệ", message, "error");
                return;
            }
            try {
                const res = await cartApi.add(productId, this.quantity);
                this.cartStore.addCartItem(res.data);
                this.orderStore.setBuyNow({});     
                this.$router.push('/gio-hang');
            } catch (error) {
                console.error(error);
            }
        },
        buyNow(product) {
            if (!this.authStore.isAuthenticated) {
                this.$swal.fire("Vui lòng đăng nhập", "Bạn cần đăng nhập để mua sản phẩm", "warning");
                return;
            }
            if (this.quantity < 1 || this.quantity > this.product.quantity) {
                let message = this.quantity < 1
                    ? "Số lượng phải lớn hơn 0"
                    : `Số lượng vượt quá giới hạn. Tối đa: ${this.product.quantity}`;

                this.$swal.fire("Số lượng không hợp lệ", message, "error");
                return;
            }
            this.orderStore.setBuyNow({
                quantity: this.quantity,
                subtotal: this.quantity * product.price,
                product_id: product.id,
                product: product
            });
            this.$router.push('/don-hang');
        },
        selectImage(image) {
            this.selectedImage = image;
        },
        toggleMainImage(commentId, path) {
            this.selectedCommentId = commentId;
            this.selectedCommentImage = this.selectedCommentImage === path ? null : path;
        },
        formatPrice, formatDate, getImageUrl
    },
    components: {
        SitePagination, ReadMoreButton, ZoomImage, RatingStars, SkeletonLoading
    },
}

</script>
