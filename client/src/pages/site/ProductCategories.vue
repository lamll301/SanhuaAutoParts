<template>
    <div class="category">
        <div class="category-container">
            <div class="category-left">
                <div class="category-left-content">
                    <h2 class="category-left-title">
                        Danh mục sản phẩm
                        <i class="fa-solid fa-chevron-down category-left-icon"></i>
                    </h2>
                    <ul class="category-left-list">
                        <template v-if="isLoading">
                            <li class="category-left-item" v-for="i in 5" :key="i">
                                <SkeletonLoading height="14px"/>
                            </li>
                        </template>
                        <template v-else>
                            <li v-for="category in parentCategories" :key="category" class="category-left-item">
                                <router-link :to="'/danh-muc' + category.to" class="category-left-link"
                                    @click="setDefaultCategoryText(category.name)">
                                    {{ category.name }}
                                </router-link>
                            </li>
                            <li class="category-left-item">
                                <router-link to="/danh-muc" class="category-left-link" @click="setDefaultCategoryText()">
                                    Tất cả sản phẩm
                                </router-link>
                            </li>
                        </template>
                    </ul>
                </div>
                <div v-if="this.slug" class="category-left-content">
                    <h2 class="category-left-title">
                        Danh mục liên quan
                        <i class="fa-solid fa-chevron-down category-left-icon"></i>
                    </h2>
                    <ul class="category-left-list category-left-list-scroll">
                        <template v-if="isLoading">
                            <li class="category-left-item" v-for="i in 5" :key="i">
                                <SkeletonLoading height="14px"/>
                            </li>
                        </template>
                        <template v-else>
                            <li v-for="category in relatedCategories" :key="category" class="category-left-item">
                                <router-link :to="'/danh-muc/' + category.slug" class="category-left-link">
                                    {{ category.name }}
                                </router-link>
                            </li>
                        </template>
                    </ul>
                </div>
                <div class="category-left-content">
                    <h2 class="category-left-title">
                        Giá
                        <i class="fa-solid fa-chevron-down category-left-icon"></i>
                    </h2>
                    <ul class="category-left-list">
                        <template v-if="isLoading">
                            <li class="category-left-item" v-for="i in 5" :key="i">
                                <SkeletonLoading height="14px"/>
                            </li>
                        </template>
                        <template v-else>
                            <li v-for="(range, index) in priceRanges" :key="index" class="category-left-item category-left-item-checkbox">
                                <input 
                                    class="category-left-checkbox" 
                                    type="checkbox" 
                                    :value="range.value" 
                                    :checked="selectedPriceRange === range.value"
                                    @change="selectPriceRange(range.value)"
                                >
                                <span class="category-left-checkbox-text">{{ range.label }}</span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="categoty-right">
                <div class="categoty-right-title">
                    <span v-if="this.$route.params.slug">
                        <template v-if="isLoading">
                            <SkeletonLoading width="200px" height="24px" />
                        </template>
                        <template v-else>
                            {{ category.name }}
                        </template>
                    </span>
                    <span v-else>{{ defaultCategoryText }}</span>
                </div>
                <div class="categoty-right-header">
                    <p class="categoty-right-header-text">
                        <template v-if="isLoading">
                            <SkeletonLoading width="150px" height="16px" />
                        </template>
                        <template v-else>
                            Đã tìm thấy {{ total }} sản phẩm
                            <span v-if="this.$route.query.key">cho "{{ this.$route.query.key }}"</span>
                        </template>
                    </p>
                    <div class="categoty-right-sort">
                        <span class="categoty-right-sort-by">
                            Sắp xếp theo:
                        </span>
                        <span class="categoty-right-sort-dropdown">
                            {{ currentSortText }}
                            <i class="fa-solid fa-chevron-down"></i>
                            <div class="categoty-right-sort-content">
                                <ul class="categoty-right-sort-list">
                                    <li class="categoty-right-sort-item">
                                        <a href="#" @click.prevent="clearParams" class="categoty-right-sort-link">
                                            Liên quan
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="#" @click.prevent="sort('price', 'asc', 'Giá từ thấp đến cao')" class="categoty-right-sort-link">
                                            Giá từ thấp đến cao
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="#" @click.prevent="sort('price', 'desc', 'Giá từ cao xuống thấp')" class="categoty-right-sort-link">
                                            Giá từ cao xuống thấp
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="#" @click.prevent="sort('created_at', 'desc', 'Mới nhất')" class="categoty-right-sort-link">
                                            Mới nhất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </span>
                    </div>
                </div>
                <template v-if="isLoading">
                    <div class="category-grid">
                        <div v-for="i in 20" :key="i" class="category-product skeleton-product">
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
                    </div>
                    <ul class="pagination category-pagination">
                        <li v-for="i in 8" :key="i" class="page-item category-page-item">
                            <SkeletonLoading height="40px" width="40px" borderRadius="50%" />
                        </li>
                    </ul>
                </template>
                <template v-else>
                    <div class="category-grid">
                        <router-link v-for="product in products" :key="product" :to="'/san-pham/' + product.slug" class="category-product">
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
                    </div>
                    <SitePagination v-show="products.length > 0" :totalPages="totalPages" :currentPage="currentPage"/>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { formatPrice, getImageUrl } from '@/utils/helpers'
import SitePagination from '@/components/SitePagination.vue'
import apiService from '@/utils/apiService';
import SkeletonLoading from '@/components/SkeletonLoading.vue';

export default {
    name: 'ProductCategories',
    components: {
        SitePagination, SkeletonLoading
    },
    data() {
        return {
            isLoading: true, currentSortText: 'Liên quan', selectedPriceRange: null, defaultCategoryText: 'Tất cả sản phẩm',
            priceRanges: [
                { 
                    value: 'under_500k', 
                    label: 'Dưới 500.000đ' 
                },
                { 
                    value: '500k_2m', 
                    label: '500.000đ - 2.000.000đ' 
                },
                { 
                    value: '2m_5m', 
                    label: '2.000.000đ - 5.000.000đ' 
                },
                { 
                    value: '5m_10m', 
                    label: '5.000.000đ - 10.000.000đ' 
                },
                { 
                    value: 'above_10m', 
                    label: 'Trên 10.000.000đ' 
                }
            ],
            totalPages: 0, currentPage: 1, total: 0,
            products: [],
            category: {},
            relatedCategories: [],
            parentCategories: [
                { name: "Đang được giảm giá", to: "?sort_by=on_sale" },
                { name: "Mới nhất", to: "?_sort=true&column=created_at&type=desc" },
            ],
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
                    apiService.products.getByCategorySlug(slug, this.$route.query),
                ];
                
                if (slug) {
                    req.push(apiService.categories.getBySlug(slug))
                }
                
                const res = await Promise.all(req)

                this.products = res[0].data.data;
                this.totalPages = Math.ceil(res[0].data.pagination.total / res[0].data.pagination.per_page);
                this.currentPage = res[0].data.pagination.current_page;
                this.total = res[0].data.pagination.total;
                if (slug) {
                    this.category = res[1].data.data
                    this.relatedCategories = res[1].data.related
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        clearParams() {
            const currentQuery = { ...this.$route.query };
            delete currentQuery._sort;
            delete currentQuery.column;
            delete currentQuery.type;
            
            this.$router.push({ 
                path: this.$route.path, 
                query: currentQuery 
            });
            this.currentSortText = 'Liên quan';
        },
        sort(column, type, text) {
            this.currentSortText = text;
            const currentQuery = { ...this.$route.query };
            currentQuery._sort = true;
            currentQuery.column = column;
            currentQuery.type = type;
            this.$router.push({ 
                path: this.$route.path, 
                query: currentQuery 
            });
        },
        selectPriceRange(value) {
            this.selectedPriceRange = (this.selectedPriceRange === value) ? null : value;
            const currentQuery = { ...this.$route.query };
            if (this.selectedPriceRange) {
                currentQuery.price_range = this.selectedPriceRange;
            } else {
                delete currentQuery.price_range;
            }

            this.$router.push({ 
                path: this.$route.path, 
                query: currentQuery 
            });
        },
        setDefaultCategoryText(name = 'Tất cả sản phẩm') {
            this.defaultCategoryText = name;
        },
        formatPrice, getImageUrl,
    },
}
</script>