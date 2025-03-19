<template>
    <div class="home">
        <div class="swiper__slider slider">
            <ul class="swiper-wrapper swiper-list">
                <li v-for="(slider, index) in sliders" :key="index" class="swiper-slide slider-item">
                    <!-- :src="require(`@/assets/images/test/${slider.image}`)" -->
                    <img :src="getImageUrl(slider.image)" alt="" class="slider-image">
                    <div class="slider-info">
                        <h1 class="slider-highlight">
                            {{ slider.highlight }}
                        </h1>
                        <h4 class="slider-desc">
                            {{ slider.highlightDesc }}
                        </h4>
                        <a href="" class="slider-shop-now">SHOP NOW</a>
                    </div>
                </li>
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
            <h2 class="brew-product-text">WHAT’S BREWING</h2>
            <div class="swiper__brew-product brew-product-container">
                <ul class="swiper-wrapper brew-product-list swiper-list">
                    <li v-for="(report, index) in reports" :key="index" class="swiper-slide swiper-item brew-product-item show">
                        <a href="" class="brew-product-link">
                            <div class="brew-product-img">
                                <img :src="report.image" alt="">
                            </div>
                            <div class="brew-product-info">
                                <p class="brew-product-desc">
                                    {{ report.title }}
                                </p>
                                <span class="brew-product-time">
                                    {{ report.createdAt }}
                                </span>
                            </div>
                        </a>
                    </li>
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
                    <li id="best-sellers" class="tabs-item__online-store tabs-item">
                        <div class="tabs-item-text">
                            BÁN CHẠY
                        </div>
                    </li>
                    <li id="newest" class="tabs-item__online-store tabs-item">
                        <div class="tabs-item-text">
                            MỚI NHẤT
                        </div>
                    </li>
                    <li id="on-sale" class="tabs-item__online-store tabs-item">
                        <div class="tabs-item-text">
                            ĐANG GIẢM GIÁ
                        </div>
                    </li>
                    <li id="seasonal" class="tabs-item__online-store tabs-item">
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
            <!-- bán chạy - best-sellers -->
            <div id="best-sellers" class="swiper__online-store-best-sellers online-store-container brew-product-container">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="(product, index) in bestSellersProducts" :key="index" class="swiper-slide swiper-item online-store-item">
                        <a href="" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="product.image" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <a href="" class="online-store-buy">Mua ngay</a>
                                </div>
                            </div>
                        </a>
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
            <div id="newest" class="hidden swiper__online-store-newest online-store-container brew-product-container">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="(product, index) in newestProducts" :key="index" class="swiper-slide swiper-item online-store-item">
                        <a href="" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="product.image" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <a href="" class="online-store-buy">Mua ngay</a>
                                </div>
                            </div>
                        </a>
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
            <div id="on-sale" class="hidden swiper__online-store-on-sale online-store-container brew-product-container">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="(product, index) in onSaleProducts" :key="index" class="swiper-slide swiper-item online-store-item">
                        <a href="" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="product.image" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <a href="" class="online-store-buy">Mua ngay</a>
                                </div>
                            </div>
                        </a>
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
            <div id="seasonal" class="hidden swiper__online-store-seasonal online-store-container brew-product-container">
                <ul class="swiper-wrapper swiper-list">
                    <li v-for="(product, index) in highClassProducts" :key="index" class="swiper-slide swiper-item online-store-item">
                        <a href="" class="online-store-link">
                            <div class="online-store-img">
                                <img :src="product.image" alt="">
                            </div>
                            <div class="online-store-info">
                                <p class="online-store-desc">
                                    {{ product.name }}
                                </p>
                                <div class="online-store-details">
                                    <h4 class="online-store-price">
                                        {{ formatPrice(product.price) }}<sup>đ</sup>
                                    </h4>
                                    <a href="" class="online-store-buy">Mua ngay</a>
                                </div>
                            </div>
                        </a>
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
                    <li v-for="(report, index) in reports" :key="index" class="swiper-slide swiper-item brew-product-item show">
                        <a href="" class="brew-product-link">
                            <div class="brew-product-img">
                                <img :src="report.image" alt="">
                            </div>
                            <div class="brew-product-info">
                                <p class="brew-product-desc">
                                    {{ report.title }}
                                </p>
                                <span class="stories-text-left">
                                    PLANET
                                </span>
                                <span class="brew-product-time">
                                    {{ report.createdAt }}
                                </span>
                            </div>
                        </a>
                    </li>
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
import { formatPrice, getImageUrl } from '@/helpers/helpers.js'
import useSwiper from '@/composables/useSwiper';
import useTab from '@/composables/useTab';

export default {
    name: 'SiteHome',
    data() {
        return {
            reports: [
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Dầu nhớt tổng hợp mới giúp bảo vệ động cơ tối ưu. Gia tăng hiệu suất xe của bạn!",
                    "createdAt": "2024.12.16"
                },
                {
                    "image": "https://anthaiautoparts.com/wp-content/uploads/2024/08/ma-phanh-Antek.jpg",
                    "title": "Bộ má phanh cao cấp - Đảm bảo an toàn trên mọi hành trình.",
                    "createdAt": "2024.12.20"
                },
                {
                    "image": "https://broauto.vn/uploads/source/phu-kien-tien-ich/loc-gio-k-n/loc-gio-kn.jpg",
                    "title": "Lọc gió động cơ cải tiến giúp tăng hiệu suất và tiết kiệm nhiên liệu.",
                    "createdAt": "2025.01.05"
                },
                {
                    "image": "https://acquychinhhang.vn/upload/sanpham/xjx1558410011-7156.jpg",
                    "title": "Ắc quy dung lượng lớn - Khởi động mạnh mẽ trong mọi điều kiện thời tiết.",
                    "createdAt": "2024.12.25"
                },
                {
                    "image": "https://phuocxehoi.com/upload/images/tin-tuc/Phu%E1%BB%99c%20TEIN%20EnduraPro%20Plus.jpg",
                    "title": "Bộ treo giảm xóc thế hệ mới, mang lại sự êm ái khi di chuyển.",
                    "createdAt": "2024.12.30"
                },
                {
                    "image": "https://autoparts24.com.vn/wp-content/uploads/2020/08/CHAMPION-Iridium-RC11WYPB4-9003.jpg",
                    "title": "Bugi bạch kim giúp đánh lửa ổn định, tối ưu hóa hiệu suất động cơ.",
                    "createdAt": "2025.01.10"
                },
                {
                    "image": "https://phukienpeugeot.com/wp-content/uploads/2022/02/boc-vo-lang-cacbon-peugeot-3.jpg",
                    "title": "Vô lăng thể thao bọc da cao cấp, nâng tầm trải nghiệm lái xe.",
                    "createdAt": "2025.01.15"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Ống xả thể thao - Tăng công suất và cải thiện âm thanh động cơ.",
                    "createdAt": "2025.01.20"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Bộ lọc khí thải giúp giảm ô nhiễm và nâng cao hiệu suất xe.",
                    "createdAt": "2025.02.01"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Đèn pha LED siêu sáng - Cải thiện tầm nhìn và tăng độ an toàn.",
                    "createdAt": "2025.02.10"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Dây curoa cam chính hãng - Đảm bảo động cơ vận hành trơn tru.",
                    "createdAt": "2025.02.15"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Gạt mưa silicon bền bỉ - Giúp kính chắn gió luôn sạch và rõ ràng.",
                    "createdAt": "2025.03.01"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Bơm nhiên liệu điện tử - Cung cấp nhiên liệu ổn định, nâng cao hiệu suất động cơ.",
                    "createdAt": "2025.03.10"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Gương chiếu hậu chỉnh điện - Giúp quan sát tốt hơn, tăng độ an toàn.",
                    "createdAt": "2025.03.15"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Tay nắm cửa mạ chrome sang trọng - Nâng cấp diện mạo xe của bạn.",
                    "createdAt": "2025.03.20"
                },
                {
                    "image": "https://bizweb.dktcdn.net/100/459/013/articles/dau-nhot-o-to.jpg?v=1741855601180",
                    "title": "Cảm biến lùi thông minh - Hỗ trợ đỗ xe an toàn và chính xác hơn.",
                    "createdAt": "2025.03.25"
                }
            ],
            bestSellersProducts: [
                {
                    "image": "https://media.loveitopcdn.com/4083/160853-z-phin-ong-loc-dau-thuy-luc-loc-dau-dong-co-loc-nhot.jpg",
                    "name": "Lọc dầu cao cấp - Kéo dài tuổi thọ động cơ",
                    "price": 250000
                },
                {
                    "image": "https://exciter.vn/images/2022/07/20220711_127b46244cac45bbce8be96132e27e00_1657520695.jpg",
                    "name": "Đĩa phanh hiệu suất cao",
                    "price": 1200000
                },
                {
                    "image": "https://product.hstatic.net/200000758097/product/bugi-ngk-cr5hgp__2__40cce05cc9fb4d3a955f9c15abe87797_grande.jpg",
                    "name": "Bugi bạch kim cao cấp",
                    "price": 350000
                },
                {
                    "image": "https://images2.thanhnien.vn/528068263637045248/2025/2/25/binh-ac-quy-1-17404457851932065820973.jpeg",
                    "name": "Ắc quy ô tô dung lượng lớn",
                    "price": 2800000
                },
                {
                    "image": "https://img1.banxehoi.com/2015/05/11/eHjsR9Gq/locgio-9c49.jpg",
                    "name": "Lọc gió động cơ giúp xe vận hành mượt mà",
                    "price": 450000
                },
                {
                    "image": "https://chanhphutungxemay.vn/upload/product/phuoc-nhun-giam-xoc-truoc-airblade-125-chinh-hang-honda-1-460.jpg",
                    "name": "Phuộc nhún giảm chấn chính hãng",
                    "price": 1800000
                },
                {
                    "image": "https://vinalube.com/images/products/2020/10/03/large/ket-nuoc-lam-mat-dong-co-may-xuc-dao-1-_1601662612.jpg",
                    "name": "Két nước tản nhiệt động cơ",
                    "price": 2200000
                },
                {
                    "image": "https://product.hstatic.net/200000536179/product/z6337952149162_366bd64d281cca357284afbee952bbae_d5f2a2c6e7b54e36af0d53da8ea2538b_large.jpg",
                    "name": "Bộ má phanh cao cấp",
                    "price": 900000
                },
                {
                    "image": "https://bacnam.vn/wp-content/uploads/2024/03/Giam-xoc-truoc-ben-phai-1.jpg",
                    "name": "Bộ treo giảm xóc trước",
                    "price": 3000000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Dây curoa cam chính hãng",
                    "price": 750000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Dầu nhớt tổng hợp 5W-30",
                    "price": 550000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Đèn pha LED siêu sáng",
                    "price": 1600000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Bơm nhiên liệu điện tử",
                    "price": 2000000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Gạt mưa silicon bền bỉ",
                    "price": 300000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Bộ lọc khí thải Catalytic Converter",
                    "price": 3500000
                },
                {
                    "image": "https://vinfastbinhbaominh.vn/wp-content/uploads/2024/10/tin-tuc-22.jpg",
                    "name": "Gương chiếu hậu chỉnh điện",
                    "price": 950000
                },
            ],
            newestProducts: [
                {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình giữ nhiệt logo thép không gỉ 473ml",
        "price": 50000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình giữ nhiệt thiết kế hiện đại 500ml",
        "price": 60000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình thân thiện môi trường 450ml",
        "price": 45000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình di động tiện dụng 700ml",
        "price": 70000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Phiên bản giới hạn mùa lễ hội 500ml",
        "price": 80000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình giữ nhiệt hai lớp 450ml",
        "price": 55000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình nhẹ nhàng dễ mang theo 600ml",
        "price": 48000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình thiết kế cổ điển 400ml",
        "price": 52000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình giữ nhiệt tùy chỉnh khắc tên 500ml",
        "price": 57000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình đơn giản hiện đại 400ml",
        "price": 45000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình thiết kế màu sắc nổi bật 500ml",
        "price": 60000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình cao cấp chất liệu sang trọng 450ml",
        "price": 75000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình thiết kế độc đáo 550ml",
        "price": 68000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình có tay cầm tiện lợi 500ml",
        "price": 52000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình phiên bản đặc biệt holographic 600ml",
        "price": 90000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình eco thân thiện môi trường 500ml",
        "price": 65000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình năng động mọi hoàn cảnh 450ml",
        "price": 47000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình nhẹ dễ dàng mang theo 500ml",
        "price": 50000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình thiết kế cổ điển 450ml",
        "price": 48000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785580619/4524785580619_1_s.jpg",
        "name": "Bình thép không gỉ hai lớp giữ nhiệt 500ml",
        "price": 60000
    }
            ],
            onSaleProducts: [
                {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình giữ nhiệt logo thép không gỉ phiên bản chuyển màu độc đáo 473ml",
        "price": 50000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình giữ nhiệt thiết kế hiện đại màu đỏ với dung tích lớn 500ml",
        "price": 60000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình thân thiện môi trường được làm từ chất liệu tái chế 450ml",
        "price": 45000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình di động tiện dụng dành cho các hoạt động ngoài trời 700ml",
        "price": 70000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Phiên bản giới hạn mùa lễ hội với thiết kế lộng lẫy 500ml",
        "price": 80000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình giữ nhiệt hai lớp giúp giữ nhiệt lâu và ổn định 450ml",
        "price": 55000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình nhẹ nhàng dễ mang theo, phù hợp cho mọi hoạt động 600ml",
        "price": 48000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình thiết kế cổ điển pha chút hiện đại, dễ dàng sử dụng 400ml",
        "price": 52000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình giữ nhiệt tùy chỉnh khắc tên theo ý thích của bạn 500ml",
        "price": 57000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình đơn giản hiện đại nhưng mang đến cảm giác tinh tế 400ml",
        "price": 45000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình thiết kế màu sắc nổi bật thu hút ánh nhìn từ xa 500ml",
        "price": 60000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình cao cấp với chất liệu sang trọng, lý tưởng làm quà tặng 450ml",
        "price": 75000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình thiết kế độc đáo, pha trộn giữa thời trang và tiện dụng 550ml",
        "price": 68000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình có tay cầm tiện lợi, dễ dàng mang theo khi di chuyển 500ml",
        "price": 52000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình phiên bản đặc biệt holographic với ánh sáng lung linh 600ml",
        "price": 90000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình eco thân thiện môi trường, đồng hành cùng thiên nhiên 500ml",
        "price": 65000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình năng động với thiết kế phù hợp mọi hoàn cảnh sử dụng 450ml",
        "price": 47000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình nhẹ dễ dàng mang theo, lý tưởng cho du lịch và thể thao 500ml",
        "price": 50000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình thiết kế cổ điển, lựa chọn an toàn và đáng tin cậy 450ml",
        "price": 48000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785549746/4524785549746_1_s.jpg",
        "name": "Bình thép không gỉ hai lớp giữ nhiệt hiệu quả lâu dài 500ml",
        "price": 60000
    }
            ],
            highClassProducts: [
                {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt phiên bản theo mùa, mang đậm phong cách lễ hội 473ml",
        "price": 50000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt sang trọng, thiết kế độc quyền theo mùa 500ml",
        "price": 60000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt phong cách hiện đại, gắn liền với mùa lễ hội 450ml",
        "price": 55000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình theo mùa với thiết kế thanh lịch, thích hợp làm quà tặng 500ml",
        "price": 70000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Phiên bản giới hạn cho dịp lễ hội, mẫu mã độc đáo 500ml",
        "price": 80000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt hai lớp giúp giữ nhiệt lâu, phong cách mùa đông 450ml",
        "price": 55000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình theo mùa nhẹ nhàng, phù hợp cho mọi hoạt động 600ml",
        "price": 48000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình phiên bản lễ hội, phong cách cổ điển pha hiện đại 450ml",
        "price": 52000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt theo mùa, khắc tên tạo dấu ấn riêng 500ml",
        "price": 57000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt đơn giản nhưng đậm chất mùa lễ hội 400ml",
        "price": 45000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình mùa lễ hội với màu sắc nổi bật, thu hút mọi ánh nhìn 500ml",
        "price": 60000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình cao cấp theo mùa, thiết kế hoàn hảo cho mùa đông 450ml",
        "price": 75000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình giữ nhiệt độc đáo, phong cách mùa đông với sự tiện dụng 550ml",
        "price": 68000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình theo mùa có tay cầm tiện lợi, dễ dàng mang theo 500ml",
        "price": 52000
    },
    {
        "image": "https://asset.menu.starbucks.co.jp/public/sku_images/4524785579132/4524785579132_1_s.jpg",
        "name": "Bình lễ hội thiết kế holographic lung linh độc đáo 600ml",
        "price": 90000
    }
            ],
            sliders: [
                {
        "image": "slider4.jpg",
        "highlight": "Khuyến mãi lớn!",
        "highlightDesc": "Giảm giá 50% cho tất cả các dòng lốp Michelin hôm nay. Đây là cơ hội tuyệt vời để thay thế lốp xe của bạn với giá cực kỳ hấp dẫn. Đừng bỏ lỡ ưu đãi này, chỉ áp dụng trong ngày hôm nay!"
    },
    {
        "image": "slider2.jpg",
        "highlight": "Siêu giảm giá!",
        "highlightDesc": "Siêu khuyến mãi trong ngày, chỉ hôm nay thôi! Mua các sản phẩm chăm sóc xe và nhận giảm giá lên tới 60%. Nhanh tay lựa chọn sản phẩm phù hợp với xe của bạn trước khi chương trình kết thúc!"
    },
    {
        "image": "slider3.jpg",
        "highlight": "Chăm sóc xe dễ dàng",
        "highlightDesc": "Giảm giá đến 70% cho các sản phẩm bảo dưỡng xe. Chúng tôi cung cấp những dịch vụ chăm sóc xe nhanh chóng và hiệu quả, giúp xe của bạn luôn trong tình trạng tốt nhất. Cơ hội tiết kiệm lớn chỉ có trong hôm nay!"
    },
    {
        "image": "slider1.jpg",
        "highlight": "Mua 1 tặng 1",
        "highlightDesc": "Chương trình khuyến mãi hấp dẫn: Mua 1 tặng 1 các sản phẩm chăm sóc xe, giúp chiếc xe của bạn luôn mới mẻ và sáng bóng. Chỉ áp dụng hôm nay, đừng bỏ lỡ cơ hội tuyệt vời này!"
    },
    {
        "image": "slider2.jpg",
        "highlight": "Ưu đãi cực khủng",
        "highlightDesc": "Chương trình siêu giảm giá trong ngày: Giảm giá đến 50% cho các loại phụ tùng và dịch vụ sửa chữa xe. Đây là cơ hội tuyệt vời để bạn tiết kiệm chi phí bảo dưỡng xe. Nhanh tay lên nhé!"
    },
    {
        "image": "slider3.jpg",
        "highlight": "Giảm giá cao",
        "highlightDesc": "Giảm giá lên tới 60% cho các sản phẩm bảo dưỡng xe. Các sản phẩm của chúng tôi giúp xe của bạn luôn bền bỉ và vận hành trơn tru. Chương trình chỉ diễn ra trong hôm nay, nhanh tay chọn lựa!"
    },
    {
        "image": "slider1.jpg",
        "highlight": "Khuyến mãi hấp dẫn",
        "highlightDesc": "Giảm giá 50% cho tất cả các dòng lốp Michelin. Cơ hội tuyệt vời để nâng cấp lốp xe với giá siêu ưu đãi. Đừng bỏ lỡ khuyến mãi này, chỉ có hôm nay!"
    },
    {
        "image": "slider2.jpg",
        "highlight": "Ưu đãi không thể bỏ qua",
        "highlightDesc": "Siêu khuyến mãi chỉ trong ngày hôm nay, giảm giá đến 40% cho tất cả các sản phẩm chăm sóc xe hơi. Cơ hội tuyệt vời để tiết kiệm và chăm sóc xe tốt hơn!"
    },
    {
        "image": "slider4.jpg",
        "highlight": "Chăm sóc xe tiết kiệm",
        "highlightDesc": "Giảm giá đến 70% cho các sản phẩm bảo dưỡng xe. Đây là dịp lý tưởng để bạn chăm sóc xe mà không lo về giá. Hãy đến cửa hàng và tận dụng ngay chương trình giảm giá này!"
    },
    {
        "image": "slider11.jpg",
        "highlight": "Siêu khuyến mãi",
        "highlightDesc": "Cơ hội duy nhất trong ngày: Giảm giá 50% cho tất cả các dòng lốp Michelin. Hãy đến ngay và thay thế lốp xe của bạn để đảm bảo an toàn trên mọi chuyến đi!"
    }
            ],
        }
    },
    methods: {
        formatPrice, getImageUrl,
    },
    setup() {
        useTab(), 
        useSwiper()
    },
}
</script>