<template>
    <div class="home">
        <div class="swiper__slider slider">
            <ul class="swiper-wrapper swiper-list">
                <template v-if="isLoading">
                    <li v-for="i in 5" :key="i" class="swiper-slide slider-item">
                        <SkeletonLoading height="600px" class="slider-image" style="filter: none;" />
                        <div class="slider-info">
                            <h1 class="slider-highlight">
                                <SkeletonLoading height="40px" width="100%" marginBottom="8px" />
                            </h1>
                            <h4 class="slider-desc">
                                <SkeletonLoading height="20px" width="100%" marginBottom="8px" />
                            </h4>
                        </div>
                    </li>
                </template>
                <template v-else>
                    <li v-for="slider in sliders" :key="slider.id" class="swiper-slide slider-item">
                        <img :src="getImageUrl(slider.images[0].path)" alt="" class="slider-image">
                        <div class="slider-info">
                            <h1 class="slider-highlight">
                                {{ slider.title }}
                            </h1>
                            <h4 class="slider-desc">
                                {{ slider.highlight }}
                            </h4>
                            <router-link :to="`/tin-tuc/${slider.slug}`" class="slider-shop-now">XEM NGAY</router-link>
                        </div>
                    </li>
                </template>
            </ul>
            <div class="swiper-btn-2 slider-btn-2">
                <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__slider-btn-prev">
                    <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                </div>
                <div class="swiper-btn-next swiper-btn btn-next swiper__slider-btn-next">
                    <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                </div>
            </div>
        </div>
        <div class="brew-product">
            <h2 class="brew-product-text">WHAT'S BREWING</h2>
            <div class="swiper__brew-product brew-product-container">
                <ul class="swiper-wrapper brew-product-list swiper-list">
                    <template v-if="isLoading">
                        <li v-for="i in 7" :key="i" class="swiper-slide swiper-item brew-product-item show" style="box-shadow: none;">
                            <SkeletonLoading height="224px" class="brew-product-img" />
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
                        </li>
                    </template>
                    <template v-else>
                        <li v-for="article in saleArticles" :key="article.id" class="swiper-slide swiper-item brew-product-item show">
                            <router-link :to="`/tin-tuc/${article.slug}`" class="brew-product-link">
                                <div class="brew-product-img">
                                    <img :src="getImageUrl(article.images[0].path)" alt="">
                                </div>
                                <div class="brew-product-info">
                                    <p class="brew-product-desc">
                                        {{ article.highlight }}
                                    </p>
                                    <span class="brew-product-time">
                                        {{ formatDate(article.created_at) }}
                                    </span>
                                </div>
                            </router-link>
                        </li>
                    </template>
                </ul>
                <div class="swiper-btn-2 brew-product-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__brew-product-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__brew-product-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <div class="brew-product-bottom">
                <a href="#" class="brew-product-bottom-item">
                    <div class="brew-product-bottom-img">
                        <img src="../../assets/images/payment.png" alt="">
                    </div>
                    <p class="brew-product-bottom-text">
                        Phụ tùng chính hãng, chất lượng đảm bảo, thanh toán dễ dàng không cần tiền mặt.
                    </p>
                </a>
                <a href="#" class="brew-product-bottom-item">
                    <div class="brew-product-bottom-img">
                        <img src="../../assets/images/office workers.png" alt="">
                    </div>
                    <p class="brew-product-bottom-text">
                        [Thông tin việc làm] Bạn có muốn trở thành một phần đội ngũ Sanhua không?
                    </p>
                </a>
            </div>
        </div>
    </div>
    <div class="line-gray"></div>
    <div class="home">
        <div class="online-store">
            <h2 class="online-store-title">ONLINE STORE</h2>
            <h4 class="online-store-text">
                SANHUA - ĐỒNG HÀNH CÙNG BẠN TRÊN MỌI NẺO ĐƯỜNG.
            </h4>
            <div class="online-store-content">
                <ul class="online-store-tabs tabs">
                    <li class="tabs-item__online-store tabs-item" :class="{ 'tabs-item-active': activeTab === 'best-sellers' }" @click="activeTab = 'best-sellers'">
                        <div class="tabs-item-text">
                            BÁN CHẠY
                        </div>
                    </li>
                    <li class="tabs-item__online-store tabs-item" :class="{ 'tabs-item-active': activeTab === 'newest' }" @click="activeTab = 'newest'">
                        <div class="tabs-item-text">
                            MỚI NHẤT
                        </div>
                    </li>
                    <li class="tabs-item__online-store tabs-item" :class="{ 'tabs-item-active': activeTab === 'on-sale' }" @click="activeTab = 'on-sale'">
                        <div class="tabs-item-text">
                            ĐANG GIẢM GIÁ
                        </div>
                    </li>
                    <li class="tabs-item__online-store tabs-item" :class="{ 'tabs-item-active': activeTab === 'seasonal' }" @click="activeTab = 'seasonal'">
                        <div class="tabs-item-text">
                            CAO CẤP
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="online-store-content-tab">
        <div class="home">
            <div class="online-store-container brew-product-container"
                :class="{ 'hidden': activeTab !== '' }">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="i in 9" :key="i" class="swiper-slide swiper-item online-store-item">
                        <div class="online-store-img" style="border-top-left-radius: 12px; border-top-right-radius: 12px; overflow: hidden;">
                            <SkeletonLoading height="152px" borderRadius="0" />
                        </div>
                        <div class="online-store-info">
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
                            <div class="online-store-details">
                                <router-link to="#" class="online-store-buy" style="margin-bottom: 10px;">Mua ngay</router-link>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="swiper-btn-2 online-store-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__online-store-best-sellers-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__online-store-best-sellers-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <div class="swiper__online-store-best-sellers online-store-container brew-product-container"
                :class="{ 'hidden': activeTab !== 'best-sellers' }">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="product in bestSellersProducts" :key="product.id" class="swiper-slide swiper-item online-store-item">
                        <router-link :to="`/san-pham/${product.slug}`" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="getImageUrl(product.images[0].path)" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <router-link :to="`/san-pham/${product.slug}`" class="online-store-buy" style="margin-bottom: 10px;">Mua ngay</router-link>
                                </div>
                            </div>
                        </router-link>
                    </li>
                </ul>
                <div class="swiper-btn-2 online-store-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__online-store-best-sellers-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__online-store-best-sellers-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <!-- mới nhất - newest -->
            <div class="swiper__online-store-newest online-store-container brew-product-container"
                :class="{ 'hidden': activeTab !== 'newest' }">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="product in newestProducts" :key="product.id" class="swiper-slide swiper-item online-store-item">
                        <router-link :to="`/san-pham/${product.slug}`" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="getImageUrl(product.images[0].path)" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <router-link :to="`/san-pham/${product.slug}`" class="online-store-buy">Mua ngay</router-link>
                                </div>
                            </div>
                        </router-link>
                    </li>
                </ul>
                <div class="swiper-btn-2 online-store-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__online-store-newest-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__online-store-newest-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <!-- đang giảm giá - on-sale -->
            <div class="swiper__online-store-on-sale online-store-container brew-product-container"
                :class="{ 'hidden': activeTab !== 'on-sale' }">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="product in onSaleProducts" :key="product.id" class="swiper-slide swiper-item online-store-item">
                        <router-link :to="`/san-pham/${product.slug}`" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="getImageUrl(product.images[0].path)" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <router-link :to="`/san-pham/${product.slug}`" class="online-store-buy">Mua ngay</router-link>
                                </div>
                            </div>
                        </router-link>
                    </li>
                </ul>
                <div class="swiper-btn-2 online-store-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__online-store-on-sale-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__online-store-on-sale-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <div class="swiper__online-store-seasonal online-store-container brew-product-container"
                :class="{ 'hidden': activeTab !== 'seasonal' }">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="product in highClassProducts" :key="product.id" class="swiper-slide swiper-item online-store-item">
                        <router-link :to="`/san-pham/${product.slug}`" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="getImageUrl(product.images[0].path)" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <router-link :to="`/san-pham/${product.slug}`" class="online-store-buy">Mua ngay</router-link>
                                </div>
                            </div>
                        </router-link>
                    </li>
                </ul>
                <div class="swiper-btn-2 online-store-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__online-store-seasonal-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__online-store-seasonal-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="line-gray"></div>
    <div class="home">
        <div class="stories">
            <div class="stories-title">
                <img src="../../assets/images/STORIES logo.png" alt="">
            </div>
            <div class="swiper__stories stories-container">
                <ul class="swiper-wrapper brew-product-list swiper-list">
                    <template v-if="isLoading">
                        <li v-for="i in 7" :key="i" class="swiper-slide swiper-item brew-product-item show" style="box-shadow: none;">
                            <SkeletonLoading height="224px" class="brew-product-img" />
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
                        </li>
                    </template>
                    <template v-else>
                        <li v-for="article in companyArticles" :key="article.id" class="swiper-slide swiper-item brew-product-item show">
                            <router-link :to="`/tin-tuc/${article.slug}`" class="brew-product-link">
                                <div class="brew-product-img">
                                    <img :src="getImageUrl(article.images[0].path)" alt="">
                                </div>
                                <div class="brew-product-info">
                                    <p class="brew-product-desc">
                                        {{ article.highlight }}
                                    </p>
                                    <span class="stories-text-left">
                                        SANHUA
                                    </span>
                                    <span class="brew-product-time">
                                        {{ formatDate(article.created_at) }}
                                    </span>
                                </div>
                            </router-link>
                        </li>
                    </template>
                </ul>
                <div class="swiper-btn-2 brew-product-btn-2">
                    <div class="hidden swiper-btn-prev swiper-btn btn-prev swiper__stories-btn-prev">
                        <i class="swiper-btn-icon fa-solid fa-chevron-left"></i>
                    </div>
                    <div class="swiper-btn-next swiper-btn btn-next swiper__stories-btn-next">
                        <i class="swiper-btn-icon fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.tabs-item-active {
    border-bottom: 4px solid var(--primary-color);
}
</style>

<script>
import { getImageUrl, formatPrice, formatDate } from '@/utils/helpers';
import { articleApi, productApi } from '@/api';
import useSwiper from '@/composables/useSwiper';
import SkeletonLoading from '@/components/SkeletonLoading.vue';
import { useHomeStore } from '@/stores/home';

export default {
    name: 'SiteHome',
    components: {
        SkeletonLoading
    },
    setup() {
        useSwiper()
        const homeStore = useHomeStore();
        return {
            homeStore
        }
    },
    data() {
        return {
            isLoading: true,
            bestSellersProducts: [],
            newestProducts: [],
            onSaleProducts: [],
            highClassProducts: [],
            sliders: [],
            companyArticles: [],
            saleArticles: [],
            activeTab: ''
        }
    },
    created() {
        this.loadDataFromStore();
        if (this.isLoading) {
            this.fetchData();
        }
    },
    methods: {
        formatPrice, getImageUrl, formatDate,
        async fetchData() {
            this.isLoading = true;
            try {
                const req = [
                    articleApi.home(),
                    productApi.home()
                ]
                const res = await Promise.all(req);
                this.sliders = res[0].data.featured_news;
                this.companyArticles = res[0].data.company_news;
                this.saleArticles = res[0].data.sales_news;
                this.newestProducts = res[1].data.newest;
                this.onSaleProducts = res[1].data.onSale;
                this.highClassProducts = res[1].data.highClass;
                this.bestSellersProducts = res[1].data.bestSeller;
                this.activeTab = 'best-sellers';
                this.saveDataToStore();
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoading = false;
            }
        },
        getActiveTab() {
            return this.activeTab;
        },
        saveDataToStore() {
            this.homeStore.setSliders(this.sliders);
            this.homeStore.setCompanyArticles(this.companyArticles);
            this.homeStore.setSaleArticles(this.saleArticles);
            this.homeStore.setNewestProducts(this.newestProducts);
            this.homeStore.setOnSaleProducts(this.onSaleProducts);
            this.homeStore.setHighClassProducts(this.highClassProducts);
            this.homeStore.setBestSellersProducts(this.bestSellersProducts);
        },
        loadDataFromStore() {
            if (this.homeStore.sliders.length > 0 
                && this.homeStore.companyArticles.length > 0 
                && this.homeStore.saleArticles.length > 0 
                && this.homeStore.newestProducts.length > 0 
                && this.homeStore.onSaleProducts.length > 0 
                && this.homeStore.highClassProducts.length > 0 
                && this.homeStore.bestSellersProducts.length > 0
            ) {
                this.sliders = this.homeStore.sliders;
                this.companyArticles = this.homeStore.companyArticles;
                this.saleArticles = this.homeStore.saleArticles;
                this.newestProducts = this.homeStore.newestProducts;
                this.onSaleProducts = this.homeStore.onSaleProducts;
                this.highClassProducts = this.homeStore.highClassProducts;
                this.bestSellersProducts = this.homeStore.bestSellersProducts;
                this.activeTab = 'best-sellers';
                this.isLoading = false;
            }
        }
    },
}
</script>