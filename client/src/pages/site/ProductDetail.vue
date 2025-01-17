<template>
    <div class="grid">
        <div class="product">
            <div class="product-detail">
                <div class="product-left">
                    <div class="product-left-img-container">
                        <div class="product-list-img">
                            <img class="product-list-img-content--active product-list-img-content" :src="autoPart.images[0]" alt="">
                            <div v-for="image in autoPart.images.slice(1)" :key="image">
                                <img :src="image" alt="" class="product-list-img-content">
                            </div>
                        </div>
                        <ZoomImage 
                            :imageSrc="autoPart.images[0]" 
                            containerClass="product-img-container" 
                            imageClass="product-img"
                        />
                    </div>
                    <div class="product-similar">
                        <h2 class="product-similar-text">
                            Sản phẩm liên quan
                        </h2>
                        <div class="product-similar-grid">
                            <router-link v-for="autoPart in similarAutoParts.slice(0, 8)" :key="autoPart" :to="'/san-pham/' + autoPart.id" class="category-product">
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
                    </div>
                </div>
                <div class="product-right">
                    <span class="product-name">
                        {{ autoPart.name }}
                    </span>
                    <div class="product-rate">
                        <span class="product-id">
                            Mã: {{ autoPart.id }}
                        </span>
                        <div class="product-rating">
                            <RatingStars :rating="autoPart.rating" starClass="product-star" />
                            <span class="product-evaluate">
                                ({{ autoPart.reviewCount }} đánh giá)
                            </span>
                        </div>
                    </div>
                    <div class="product-price">
                        <span v-if="autoPart.discount" class="product-price-current">
                            {{ formatPrice(autoPart.price * (1 - autoPart.discount * 0.01)) }}đ
                        </span>
                        <span v-else class="product-price-current">
                            {{ formatPrice(autoPart.price) }}đ
                        </span>
                        <div v-if="autoPart.discount" class="product-price-right">
                            <h6 class="product-price-discount">
                                (Giảm {{ autoPart.discount }}%)
                            </h6>
                            <span class="product-price-old">
                                {{ formatPrice(autoPart.price) }}đ
                            </span>
                        </div>
                    </div>
                    <span class="product-desc">
                        {{ autoPart.description }}
                    </span>
                    <div class="product-type">
                        <span class="product-quantity-text">
                            Loại:
                        </span>
                        <div class="product-type-content">
                            <ul class="product-type-list">
                                <li v-for="type in autoPart.types" :key="type" class="product-type-item">
                                    <button class="product-type-btn">
                                        <img :src="type.image" alt="" class="product-type-img">
                                        <span class="product-type-text">
                                            {{ type.name }}
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-quantity">
                        <span class="product-quantity-text">
                            Số lượng:
                        </span>
                        <input type="number" class="product-quantity-input">
                        <span class="product-quantity-stock">
                            {{ autoPart.quantity }} sản phẩm có sẵn
                        </span>
                    </div>
                    <router-link to="/don-hang" class="product-btn-buy product-btn button">
                        Mua ngay
                    </router-link>
                    <router-link to="/gio-hang" class="product-btn-add-to-cart product-btn button">
                        Thêm vào giỏ hàng
                    </router-link>
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
                            <li v-for="specification in autoPart.specifications" :key="specification" class="product-specifications-item">
                                <div class="product-specifications-title">
                                    {{ specification.name }}:
                                </div>
                                <div class="product-specifications-content">
                                    <ReadMoreButton> 
                                        <slot>
                                           {{ specification.content }}
                                        </slot>
                                    </ReadMoreButton>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="product-ratings">
                <h2 class="product-similar-text product-ratings-text">
                    Đánh giá sản phẩm
                </h2>
                <div class="product-rating-detail">
                    <div class="product-rating-sum">
                        <div class="product-rating-scores">
                            <p class="product-rating-score">
                                {{ autoPart.rating }}
                            </p>
                            <span class="product-rating-score-max">/5</span>
                        </div>
                        <div class="product-rating-star">
                            <RatingStars :rating="autoPart.rating" starClass="product-rating-icon" />
                        </div>
                        <span class="product-rating-total">
                            {{ autoPart.reviewCount }} đánh giá
                        </span>
                    </div>
                    <div class="product-rating-histogram">
                        <ul class="product-rating-histogram-list">
                            <li v-for="star in autoPart.starCount" :key="star" class="product-rating-histogram-item">
                                <div class="product-rating-histogram-item-l">
                                    <i class="product-rating-icon fa-solid fa-star"></i>
                                    <span class="product-rating-histogram-item-l-star">
                                        {{ star.stars }}
                                    </span>
                                </div>
                                <div>
                                    <div class="product-rating-histogram-item-c">
                                        <div class="product-rating-histogram-item-c-bar" 
                                            :style="{
                                                width: (star.count / autoPart.reviewCount) * 100 + '%'
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                <div class="product-rating-histogram-item-r">
                                    {{ star.count }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="product-comment-list">
                    <div v-for="(comment, index) in comments" :key="index" class="product-comment-item">
                        <img :src="comment.avatar" alt="" class="product-comment-img">
                        <div class="product-comment-text">
                            <div class="product-comment-top">
                                <div class="product-comment-star">
                                    <RatingStars :rating="comment.rating" />
                                </div>
                                <span class="product-comment-name">
                                    {{ comment.name }}
                                </span>
                                <span class="product-comment-time">
                                    - {{ comment.time }}
                                </span>
                            </div>
                            <p class="product-comment-content">
                                {{ comment.content }}
                            </p>
                            <ul class="product-comment-img-list">
                                <li v-for="image in comment.images" :key="image" class="product-comment-img-item">
                                    <img alt="" class="product-comment-sub-img"
                                        :class="'product-comment-sub-img_' + index"
                                        :src="image"
                                    >
                                </li>
                            </ul>
                            <img :class="'product-comment-main-img_' + index" src="" alt="" class="product-comment-main-img">
                        </div>
                    </div>
                </div>
                <SitePagination :totalPages="10" :currentPage="1"/>
            </div>
        </div>
    </div>
</template>

<script>
import { formatPrice } from '@/helpers/helpers.js'
import SitePagination from '@/components/SitePagination.vue'
import ReadMoreButton from '@/components/ReadMoreButton.vue';
import ZoomImage from '@/components/ZoomImage.vue';
import RatingStars from '@/components/RatingStars.vue';
import useImageGallery from '@/composables/useImageGallery.js';

export default {
    name: 'ProductDetail',
    data() {
        return {
            autoPart: {
                id: 1,
                name: "New Henley Men's Watches Luxury Fashion Glamour Boxed Lowest Price on eBay",
                rating: 3.5,
                description: "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquam rem officia, corrupti reiciendis minima nisi modi, quasi, odio minus dolore impedit fuga eum eligendi.",
                price: 1500000,
                discount: 26,
                quantity: 3399,
                reviewCount: 663,
                starCount: [
                    {
                        stars: 5,
                        count: 400
                    },
                    {
                        stars: 4,
                        count: 150
                    },
                    {
                        stars: 3,
                        count: 70
                    },
                    {
                        stars: 2,
                        count: 30
                    },
                    {
                        stars: 1,
                        count: 13
                    }
                ],
                types: [
                    {
                        name: "Xanh - Hương Chanh",
                        image: "https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lrel5wbt69w9ee@resize_w24_nl.webp"
                    },
                    {
                        name: "Đỏ - Hương Đào",
                        image: "https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lrel5wbt69w9ee@resize_w24_nl.webp"
                    },
                    {
                        name: "Vàng - Hương Xoài",
                        image: "https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lrel5wbt69w9ee@resize_w24_nl.webp"
                    },
                    {
                        name: "Xanh - Hương Chanh",
                        image: "https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lrel5wbt69w9ee@resize_w24_nl.webp"
                    },
                    {
                        name: "Đỏ - Hương Đào",
                        image: "https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lrel5wbt69w9ee@resize_w24_nl.webp"
                    },
                    {
                        name: "Vàng - Hương Xoài",
                        image: "https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lrel5wbt69w9ee@resize_w24_nl.webp"
                    }
                ],
                images: [
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_1_m.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785333253/4524785333253_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785354555/4524785354555_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785333079/4524785333079_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785353435/4524785353435_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785352636/4524785352636_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785333253/4524785333253_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785354555/4524785354555_11_s.jpg",
                    "https://asset.menu.starbucks.co.jp/public/sku_images/4524785333079/4524785333079_11_s.jpg",
                    "https://images.gr-assets.com/authors/1282396130p5/1744830.jpg"
                ],
                specifications: [
                    {
                        name: "Tình trạng",
                        content: "Mới: Đây là một sản phẩm hoàn toàn mới, chưa từng được sử dụng, không có dấu hiệu bị hư hỏng hay thay đổi. Sản phẩm được bảo quản trong bao bì nguyên vẹn, chưa được mở ra hoặc sử dụng qua bất kỳ hình thức nào."
                    },
                    {
                        name: "Mã sản phẩm",
                        content: "SKS-1: Mã sản phẩm này đại diện cho một dòng sản phẩm cụ thể, giúp phân biệt sản phẩm này với các sản phẩm khác trong cùng danh mục."
                    },
                    {
                        name: "Kích thước và độ lệch vành",
                        content: "6.5 inch: Độ lệch vành là thông số quan trọng đối với bánh xe, ảnh hưởng trực tiếp đến khả năng vận hành và sự ổn định của xe."
                    },
                    {
                        name: "Chất liệu",
                        content: "Sản phẩm được làm từ chất liệu thép không gỉ cao cấp, bền bỉ và có khả năng chống chịu với tác động của môi trường, nước và các yếu tố thời tiết khắc nghiệt."
                    },
                    {
                        name: "Màu sắc",
                        content: "Sản phẩm có sẵn trong các màu sắc đa dạng, bao gồm đen, bạc và vàng đồng, giúp khách hàng có thể chọn lựa theo sở thích cá nhân."
                    },
                    {
                        name: "Độ bền",
                        content: "Sản phẩm có độ bền vượt trội, có thể chịu được áp lực cao và sử dụng trong các điều kiện thời tiết khắc nghiệt."
                    },
                    {
                        name: "Tương thích",
                        content: "Sản phẩm này hoàn toàn tương thích với hầu hết các dòng xe thể thao và xe mô tô có đường kính bánh xe từ 17 đến 21 inch."
                    },
                    {
                        name: "Bảo hành",
                        content: "Sản phẩm đi kèm với chế độ bảo hành dài hạn lên đến 24 tháng kể từ ngày mua, bảo hành cho các lỗi kỹ thuật từ nhà sản xuất."
                    },
                    {
                        name: "Hướng dẫn",
                        content: "Trước khi sử dụng sản phẩm, khách hàng cần đảm bảo bánh xe đã được lắp đặt đúng cách và kiểm tra áp suất lốp thường xuyên."
                    },
                    {
                        name: "Bảo vệ môi trường",
                        content: "Sản phẩm được sản xuất bằng các nguyên liệu thân thiện với môi trường, đảm bảo không có các chất độc hại ảnh hưởng đến sức khỏe người dùng và môi trường."
                    }
                ]
            },
            similarAutoParts: [
                {
                    image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg",
                    name: "Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 100000000,
                    discount: 15
                },
                {
                    image: "https://content-prod-live.cert.starbucks.com/binary/v2/asset/137-95715.jpg",
                    name: "Sữa rửa mặt Hatomugi 120g - Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 1000000,
                    discount: 99
                },
                {
                    image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg",
                    name: "Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 100000000,
                    discount: 1
                },
                {
                    image: "https://content-prod-live.cert.starbucks.com/binary/v2/asset/137-95715.jpg",
                    name: "Sữa rửa mặt Hatomugi 120g - Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 1000000,
                    discount: 0
                },
                {
                    image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg",
                    name: "Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 100000000,
                    discount: 15
                },
                {
                    image: "https://content-prod-live.cert.starbucks.com/binary/v2/asset/137-95715.jpg",
                    name: "Sữa rửa mặt Hatomugi 120g - Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 1000000,
                    discount: 99
                },
                {
                    image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg",
                    name: "Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 100000000,
                    discount: 1
                },
                {
                    image: "https://content-prod-live.cert.starbucks.com/binary/v2/asset/137-95715.jpg",
                    name: "Sữa rửa mặt Hatomugi 120g - Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 1000000,
                    discount: 0
                },
                {
                    image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg",
                    name: "Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 100000000,
                    discount: 15
                },
                {
                    image: "https://content-prod-live.cert.starbucks.com/binary/v2/asset/137-95715.jpg",
                    name: "Sữa rửa mặt Hatomugi 120g - Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 1000000,
                    discount: 99
                },
                {
                    image: "https://asset.menu.starbucks.co.jp/public/sku_images/4524785000018/4524785000018_11_s.jpg",
                    name: "Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi Sữa rửa mặt Hatomugi 120g Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 100000000,
                    discount: 1
                },
                {
                    image: "https://content-prod-live.cert.starbucks.com/binary/v2/asset/137-95715.jpg",
                    name: "Sữa rửa mặt Hatomugi 120g - Hada Labo Gokujyun",
                    rating: 4.9,
                    sold: 100,
                    price: 1000000,
                    discount: 0
                }
            ],
            comments: [
                {
                    id: 1,
                    avatar: "https://images.gr-assets.com/photos/1375466820p8/817846.jpg",
                    name: "Nguyễn Văn A",
                    time: "15/01/2025",
                    rating: 5,
                    content: "Tôi rất hài lòng với sản phẩm này. Chất lượng vượt mong đợi, thiết kế đẹp và rất tiện dụng. Dịch vụ giao hàng cũng rất nhanh chóng và chuyên nghiệp. Tôi chắc chắn sẽ mua thêm và giới thiệu cho bạn bè!",
                    images: [
                        "https://images.gr-assets.com/authors/1233458107p5/1943477.jpg",
                        "https://images.gr-assets.com/authors/1337988298p5/1050.jpg",
                        "https://images.gr-assets.com/authors/1429114964p5/9810.jpg"
                    ]
                },
                {
                    id: 2,
                    avatar: "https://images.gr-assets.com/authors/1673611182p5/3565.jpg",
                    name: "Trần Thị B",
                    time: "10/01/2025",
                    rating: 4,
                    content: "Giao hàng nhanh, sản phẩm đúng như mô tả. Tuy nhiên, hộp đóng gói hơi móp, điều này làm tôi hơi thất vọng. Nhưng nhìn chung, sản phẩm bên trong vẫn nguyên vẹn và hoạt động tốt.",
                    images: [
                        "https://images.gr-assets.com/photos/1314296949p8/326680.jpg",
                        "https://images.gr-assets.com/photos/1314296973p8/326684.jpg"
                    ]
                },
                {
                    id: 3,
                    avatar: "https://images.gr-assets.com/authors/1696236573p5/22302.jpg",
                    name: "Lê Minh C",
                    time: "08/01/2025",
                    rating: 3,
                    content: "Sản phẩm có chất lượng trung bình. Thiết kế không như kỳ vọng, và một số chi tiết chưa được gia công tỉ mỉ. Nếu cải thiện những điểm này, tôi tin sản phẩm sẽ được yêu thích hơn.",
                    images: []
                },
                {
                    id: 4,
                    avatar: "https://images.gr-assets.com/authors/1198518558p5/23924.jpg",
                    name: "Phạm Thị D",
                    time: "05/01/2025",
                    rating: 5,
                    content: "Đây là sản phẩm tốt nhất mà tôi từng mua trên trang này. Thiết kế đẹp mắt, chất liệu cao cấp, và cách đóng gói rất cẩn thận. Dịch vụ hỗ trợ khách hàng rất nhiệt tình và nhanh chóng. Tôi rất khuyến khích mọi người nên thử.",
                    images: [
                        "https://images.gr-assets.com/authors/1233458107p5/1943477.jpg",
                        "https://images.gr-assets.com/photos/1314296949p8/326680.jpg",
                        "https://images.gr-assets.com/photos/1314296973p8/326684.jpg"
                    ]
                },
                {
                    id: 5,
                    avatar: "https://images.gr-assets.com/photos/1375466820p8/817846.jpg",
                    name: "Đặng Hữu E",
                    time: "01/01/2025",
                    rating: 4,
                    content: "Giá cả hợp lý, sản phẩm phù hợp với nhu cầu sử dụng. Tôi nghĩ rằng đây là lựa chọn tốt cho những ai đang tìm kiếm một sản phẩm tương tự. Tuy nhiên, việc giao hàng cần được cải thiện về tốc độ.",
                    images: [
                        "https://images.gr-assets.com/authors/1429114964p5/9810.jpg",
                        "https://images.gr-assets.com/authors/1337988298p5/1050.jpg"
                    ]
                },
                {
                    id: 6,
                    avatar: "",
                    name: "Vũ Hoàng F",
                    time: "30/12/2024",
                    rating: 2,
                    content: "Tôi không hài lòng với sản phẩm này. Chất lượng không tốt như quảng cáo, và sản phẩm bị lỗi ngay từ khi nhận. Tôi đã liên hệ để đổi trả nhưng quá trình xử lý rất chậm.",
                    images: []
                },
                {
                    id: 7,
                    avatar: "https://images.gr-assets.com/authors/1233458107p5/1943477.jpg",
                    name: "Hoàng Thị G",
                    time: "25/12/2024",
                    rating: 5,
                    content: "Mình mua sản phẩm này để làm quà tặng, và người nhận rất hài lòng. Thiết kế sang trọng, hộp đựng đẹp mắt và chất lượng sản phẩm cực kỳ tốt. Đây là món quà tuyệt vời cho mọi dịp.",
                    images: [
                        "https://images.gr-assets.com/photos/1314296949p8/326680.jpg",
                        "https://images.gr-assets.com/photos/1314296973p8/326684.jpg",
                        "https://images.gr-assets.com/authors/1233458107p5/1943477.jpg"
                    ]
                },
                {
                    id: 8,
                    avatar: "https://images.gr-assets.com/authors/1337988298p5/1050.jpg",
                    name: "Phan Văn H",
                    time: "20/12/2024",
                    rating: 4,
                    content: "Sản phẩm sử dụng tốt, nhưng đóng gói không được chắc chắn. Nếu cải thiện vấn đề này, tôi nghĩ sản phẩm sẽ hoàn hảo hơn.",
                    images: [
                        "https://images.gr-assets.com/authors/1429114964p5/9810.jpg"
                    ]
                },
                {
                    id: 9,
                    avatar: "https://images.gr-assets.com/photos/1314296949p8/326680.jpg",
                    name: "Ngô Thị I",
                    time: "15/12/2024",
                    rating: 5,
                    content: "Tôi cực kỳ ấn tượng với chất lượng của sản phẩm này. Từ thiết kế đến tính năng, mọi thứ đều rất hoàn hảo. Tôi chắc chắn sẽ quay lại mua thêm trong tương lai.",
                    images: [
                        "https://images.gr-assets.com/photos/1314296973p8/326684.jpg",
                        "https://images.gr-assets.com/authors/1233458107p5/1943477.jpg"
                    ]
                },
                {
                    id: 10,
                    avatar: "",
                    name: "Đỗ Minh J",
                    time: "10/12/2024",
                    rating: 3,
                    content: "Sản phẩm tạm được, không quá xuất sắc nhưng cũng không quá tệ. Tôi hy vọng lần sau sẽ có trải nghiệm tốt hơn.",
                    images: []
                }
            ]
        }
    },
    methods: {
        formatPrice,
    },
    setup() {
        useImageGallery();
    },
    components: {
        SitePagination, ReadMoreButton, ZoomImage, RatingStars
    },
}

</script>
