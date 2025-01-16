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
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Có thể tùy chỉnh 343 cách kết hợp trên chiếc chai của bạn và giờ đây còn có thể khắc tên. Tô điểm năm mới bằng phong cách riêng của bạn!",
                    "createdAt": "2024.12.16"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Thiết kế chai phong cách hiện đại, giúp bạn thêm vui vẻ trong cuộc sống thường nhật. Có thể khắc tên theo ý muốn.",
                    "createdAt": "2024.12.20"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai thân thiện với môi trường được làm từ chất liệu mới. Lựa chọn phù hợp để bảo vệ thiên nhiên.",
                    "createdAt": "2025.01.05"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai kích thước tiện dụng và di động. Lý tưởng cho các hoạt động ngoài trời và văn phòng.",
                    "createdAt": "2024.12.25"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Phiên bản thiết kế giới hạn cho mùa lễ hội đã ra mắt. Sản phẩm đặc biệt chỉ có trong dịp này.",
                    "createdAt": "2024.12.30"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai thép không gỉ hai lớp giúp giữ nhiệt xuất sắc. Giữ ấm trong thời gian dài.",
                    "createdAt": "2025.01.10"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Thiết kế năng động, phù hợp với mọi hoàn cảnh. Một lựa chọn không thể bỏ qua.",
                    "createdAt": "2025.01.15"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai nhẹ, dễ dàng mang theo. Phù hợp cho các hoạt động thể thao hoặc du lịch.",
                    "createdAt": "2025.01.20"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai thiết kế cổ điển. Dịch vụ khắc tên cũng được cung cấp.",
                    "createdAt": "2025.02.01"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Thiết kế chai đơn giản và hiện đại. Dễ dàng hòa hợp với mọi không gian.",
                    "createdAt": "2025.02.10"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai tùy chỉnh với màu sắc mới. Tự tạo phong cách riêng của bạn!",
                    "createdAt": "2025.02.15"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai cao cấp với chất liệu sang trọng. Lựa chọn tuyệt vời cho quà tặng.",
                    "createdAt": "2025.03.01"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai thiết kế độc đáo, thu hút sự chú ý. Kết hợp giữa phong cách và tiện dụng.",
                    "createdAt": "2025.03.10"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai có tay cầm tiện lợi. Dễ dàng mang theo bên mình.",
                    "createdAt": "2025.03.15"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Phiên bản đặc biệt với thiết kế holographic nổi bật. Chắc chắn sẽ gây ấn tượng.",
                    "createdAt": "2025.03.20"
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "title": "Chai eco được làm từ vật liệu bền vững. Lựa chọn thân thiện với môi trường.",
                    "createdAt": "2025.03.25"
                }
            ],
            bestSellersProducts: [
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình giữ nhiệt logo thép không gỉ chuyển màu xanh hồng 473ml",
                    "price": 50000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình giữ nhiệt thiết kế hiện đại màu đỏ 500ml",
                    "price": 60000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình thân thiện môi trường màu xanh lá 450ml",
                    "price": 45000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình di động tiện dụng cho hoạt động ngoài trời màu đen 700ml",
                    "price": 70000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Phiên bản giới hạn mùa lễ hội 500ml",
                    "price": 80000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình giữ nhiệt hai lớp màu đen 450ml",
                    "price": 55000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình nhẹ nhàng dễ mang theo màu xanh dương 600ml",
                    "price": 48000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình thiết kế cổ điển thép không gỉ 400ml",
                    "price": 52000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình giữ nhiệt tùy chỉnh khắc tên 500ml",
                    "price": 57000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình đơn giản hiện đại 400ml",
                    "price": 45000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình thiết kế màu sắc nổi bật 500ml",
                    "price": 60000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình cao cấp chất liệu sang trọng 450ml",
                    "price": 75000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình thiết kế độc đáo 550ml",
                    "price": 68000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình có tay cầm tiện lợi 500ml",
                    "price": 52000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình phiên bản đặc biệt với thiết kế holographic 600ml",
                    "price": 90000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình eco thân thiện với môi trường 500ml",
                    "price": 65000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình năng động phù hợp mọi hoàn cảnh 450ml",
                    "price": 47000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình nhẹ dễ dàng mang theo 500ml",
                    "price": 50000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình thiết kế cổ điển 450ml",
                    "price": 48000
                },
                {
                    "image": "https://www.starbucks.co.jp/assets/images/web2/images/wb/images/25Holiday2_naire_costum_wb.jpg",
                    "name": "Bình thép không gỉ hai lớp giữ nhiệt 500ml",
                    "price": 60000
                }
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