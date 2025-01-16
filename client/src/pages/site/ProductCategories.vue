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
                        <li v-for="category in parentCategories" :key="category" class="category-left-item">
                            <router-link :to="'/danh-muc/' + category.slug" class="category-left-link">
                                {{ category.name }}
                            </router-link>
                        </li>
                        <li class="category-left-item">
                            <router-link to="/danh-muc" class="category-left-link">
                                Tất cả sản phẩm
                            </router-link>
                        </li>
                    </ul>
                </div>
                <div class="category-left-content">
                    <h2 class="category-left-title">
                        Danh mục liên quan
                        <i class="fa-solid fa-chevron-down category-left-icon"></i>
                    </h2>
                    <ul class="category-left-list category-left-list-scroll">
                        <li v-for="category in relatedCategories" :key="category" class="category-left-item">
                            <router-link :to="'/danh-muc/' + category.slug" class="category-left-link">
                                {{ category.name }}
                            </router-link>
                        </li>
                    </ul>
                </div>
                <div class="category-left-content">
                    <h2 class="category-left-title">
                        Giá
                        <i class="fa-solid fa-chevron-down category-left-icon"></i>
                    </h2>
                    <ul class="category-left-list">
                        <li class="category-left-item category-left-item-checkbox">
                            <input class="category-left-checkbox" type="checkbox">
                            <span class="category-left-checkbox-text">
                                Dưới 500.000<sup>đ</sup>
                            </span>
                        </li>
                        <li class="category-left-item category-left-item-checkbox">
                            <input class="category-left-checkbox" type="checkbox">
                            <span class="category-left-checkbox-text">
                                500.000<sup>đ</sup> - 2.000.000<sup>đ</sup>
                            </span>
                        </li>
                        <li class="category-left-item category-left-item-checkbox">
                            <input class="category-left-checkbox" type="checkbox">
                            <span class="category-left-checkbox-text">
                                2.000.000<sup>đ</sup> - 5.000.000<sup>đ</sup>
                            </span>
                        </li>
                        <li class="category-left-item category-left-item-checkbox">
                            <input class="category-left-checkbox" type="checkbox">
                            <span class="category-left-checkbox-text">
                                5.000.000<sup>đ</sup> - 10.000.000<sup>đ</sup>
                            </span>
                        </li>
                        <li class="category-left-item category-left-item-checkbox">
                            <input class="category-left-checkbox" type="checkbox">
                            <span class="category-left-checkbox-text">
                                Trên 10.000.000<sup>đ</sup>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="categoty-right">
                <div class="categoty-right-title">
                    <span v-if="this.$route.params.slug">{{ category.name }}</span>
                    <span v-else>Tất cả sản phẩm</span>
                </div>
                <div class="categoty-right-header">
                    <p class="categoty-right-header-text">
                        Đã tìm thấy {{ autoParts.length }} sản phẩm
                        <span v-if="this.$route.query.key">cho "{{ this.$route.query.key }}"</span>
                    </p>
                    <div class="categoty-right-sort">
                        <span class="categoty-right-sort-by">
                            Sắp xếp theo:
                        </span>
                        <span class="categoty-right-sort-dropdown">
                            Liên quan
                            <i class="fa-solid fa-chevron-down"></i>
                            <div class="categoty-right-sort-content">
                                <ul class="categoty-right-sort-list">
                                    <li class="categoty-right-sort-item">
                                        <a href="" class="categoty-right-sort-link">
                                            Liên quan
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="" class="categoty-right-sort-link">
                                            Giá từ thấp đến cao
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="" class="categoty-right-sort-link">
                                            Giá từ cao xuống thấp
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="" class="categoty-right-sort-link">
                                            Mới nhất
                                        </a>
                                    </li>
                                    <li class="categoty-right-sort-item">
                                        <a href="" class="categoty-right-sort-link">
                                            Bán chạy
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </span>
                    </div>
                </div>
                <div class="category-grid">
                    <router-link v-for="autoPart in autoParts" :key="autoPart" :to="'/san-pham/' + autoPart.id" class="category-product">
                        <img :src="autoPart.image" alt="" class="category-product-img">
                        <div class="category-product-content">
                            <h2 class="category-product-name">
                                {{ autoPart.name }}
                            </h2>
                            <div class="category-product-rating">
                                <i class="fa-solid fa-star"></i>
                                <span class="category-product-rate">{{ autoPart.rating }}</span>
                                <span class="category-product-sold">Đã bán {{ autoPart.sold }}</span>
                            </div>
                            <div class="category-product-price">
                                <span v-if="autoPart.discount" class="category-product-price-new">
                                    {{ formatPrice(autoPart.price * (1 - autoPart.discount * 0.01)) }}<sup>đ</sup>
                                </span>
                                <span v-else class="category-product-price-new">
                                    {{ formatPrice(autoPart.price) }}<sup>đ</sup>
                                </span>
                                <span v-if="autoPart.discount" class="category-product-price-old">
                                    {{ formatPrice(autoPart.price) }}<sup>đ</sup>
                                </span>
                            </div>
                        </div>
                        <span v-if="autoPart.discount" class="category-onsale">
                            -{{ autoPart.discount }}%
                        </span>
                    </router-link>
                </div>
                <SitePagination :totalPages="10" :currentPage="1"/>
            </div>
        </div>
    </div>
</template>

<script>
import { formatPrice } from '@/helpers/helpers.js'
import SitePagination from '@/components/SitePagination.vue'

export default {
    name: 'ProductCategories',
    data() {
        return {
            autoParts: [
                { id: 1, name: "Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun", rating: 4.9, sold: 100, price: 100000000, discount: 15, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg" },
                { id: 2, name: "Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun", rating: 4.9, sold: 150, price: 1000000, discount: null, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785062870/4524785062870_11_s.jpg" },
                { id: 3, name: "Sữa rửa mặt Sakura 150g", rating: 4.5, sold: 50, price: 350000, discount: null, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785324732/4524785324732_11_s.jpg" },
                { id: 4, name: "Kem dưỡng da Kiehl's 200ml", rating: 4.8, sold: 200, price: 700000, discount: 15, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785046856/4524785046856_11_s.jpg" },
                { id: 5, name: "Tinh dầu dưỡng tóc L'Oréal Tinh dầu dưỡng tóc L'Oréal Tinh dầu dưỡng tóc L'Oréal", rating: 4.7, sold: 120, price: 150000, discount: 10, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000179/4524785000179_11_s.jpg" },
                { id: 6, name: "Xịt khoáng Vichy 200ml", rating: 4.6, sold: 180, price: 250000, discount: null, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785410183/4524785410183_11_s.jpg" },
                { id: 7, name: "Mặt nạ ngủ Laneige 70g", rating: 5.0, sold: 250, price: 450000, discount: 10, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg" },
                { id: 8, name: "Dầu tẩy trang Shu Uemura", rating: 4.9, sold: 95, price: 850000, discount: 5, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785062870/4524785062870_11_s.jpg" },
                { id: 9, name: "Sữa tắm Dove 500ml", rating: 4.4, sold: 300, price: 130000, discount: null, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785324732/4524785324732_11_s.jpg" },
                { id: 10, name: "Sữa dưỡng thể Vaseline 600ml", rating: 4.5, sold: 180, price: 220000, discount: 10, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785046856/4524785046856_11_s.jpg" },
                { id: 11, name: "Mặt nạ giấy My Beauty Diary", rating: 4.3, sold: 500, price: 50000, discount: 20, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000179/4524785000179_11_s.jpg" },
                { id: 12, name: "Kem chống nắng Biore UV Aqua Rich SPF 50", rating: 4.6, sold: 250, price: 180000, discount: 10, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785410183/4524785410183_11_s.jpg" },
                { id: 13, name: "Gel dưỡng ẩm Neutrogena 250ml", rating: 4.8, sold: 210, price: 350000, discount: 12, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg" },
                { id: 14, name: "Nước hoa Dior Sauvage 100ml", rating: 4.9, sold: 70, price: 3500000, discount: 5, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785062870/4524785062870_11_s.jpg" },
                { id: 15, name: "Nước hoa Chanel No.5 50ml", rating: 5.0, sold: 90, price: 2500000, discount: 10, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785324732/4524785324732_11_s.jpg" },
                { id: 16, name: "Sữa rửa mặt Cetaphil 200ml", rating: 4.7, sold: 130, price: 250000, discount: 10, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785046856/4524785046856_11_s.jpg" },
                { id: 17, name: "Nước hoa Lancôme La Vie Est Belle 50ml", rating: 4.9, sold: 40, price: 3200000, discount: 8, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000179/4524785000179_11_s.jpg" },
                { id: 18, name: "Son môi MAC Ruby Woo", rating: 4.8, sold: 300, price: 600000, discount: 7, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785410183/4524785410183_11_s.jpg" },
                { id: 19, name: "Dầu gội Head & Shoulders 600ml", rating: 4.4, sold: 150, price: 130000, discount: 20, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg" },
                { id: 20, name: "Kem dưỡng ẩm Olay 50g", rating: 4.6, sold: 110, price: 250000, discount: 15, image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785062870/4524785062870_11_s.jpg" }
            ],
            category: {
                slug: "phu-tung-dong-co",
                name: "Phụ tùng động cơ",
            },
            relatedCategories: [
                { slug: "phu-tung-oto", name: "Phụ tùng ô tô" },
                { slug: "phu-tung-xe-may", name: "Phụ tùng xe máy" },
                { slug: "dau-nhot", name: "Dầu nhớt xe" },
                { slug: "bac-lai", name: "Bạc lót xe" },
                { slug: "do-gia-dinh", name: "Đồ gia đình ô tô" },
                { slug: "banh-xe", name: "Bánh xe" },
                { slug: "dau-may", name: "Dầu máy ô tô" },
                { slug: "binh-acquy", name: "Bình ắc quy" },
                { slug: "phu-tung-xe-tai", name: "Phụ tùng xe tải" },
                { slug: "chong-luoi-xe", name: "Chống lũi xe" },
            ],
            parentCategories: [
                { slug: "mua-nhieu-gan-day", name: "Được mua nhiều gần đây" },
                { slug: "moi-nhat", name: "Mới nhất" },
                { slug: "dang-duoc-giam-gia", name: "Đang được giảm giá" },
            ],
        }
    },
    components: {
        SitePagination
    },
    created() {
        let categoryId = this.$route.params.id;
        if (categoryId) {
            console.log(categoryId)
        }
    },
    methods: {
        formatPrice,
    },
}
</script>