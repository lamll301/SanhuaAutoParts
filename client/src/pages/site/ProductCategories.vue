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
                { id: 1, name: "Bọc ghế da xe HONDA", rating: 4.9, sold: 100, price: 2200000, discount: 10, image: "https://dandoxe.com//storage/uploads/images/B%E1%BB%8Dc%20gh%E1%BA%BF%20da/city/boc-ghe-da-city.jpg" },
                { id: 2, name: "Ốp tay nắm cửa xe TOYOTA ALTIS", rating: 4.9, sold: 150, price: 850000, discount: 5, image: "https://img.lazcdn.com/g/p/5c4b9edf1f0e79126eeb2eeeda4953e2.jpg_720x720q80.jpg" },
                { id: 3, name: "Ốp nắp xăng xe ALTIS", rating: 4.5, sold: 50, price: 400000, discount: 5, image: "https://bcarautobinhduong.com/Uploads/origin/op-nap-binh-xang-xe-altis-20190220163834777.jpg" },
                { id: 4, name: "Bọc ghế da xe FORD", rating: 4.8, sold: 200, price: 2500000, discount: null, image: "https://akauto.com.vn/wp-content/uploads/2022/06/Boc-ghe-da-Ford-Ranger-mau-2.jpg" },
                { id: 5, name: "Gạt mưa Mercedes E200, E300, E250, E63 A2128201700", rating: 4.7, sold: 120, price: 1500000, discount: 10, image: "https://acquylamanh.com/upload/images/gat-mua-mercedes-E200-E250-E300-E350-E63.jpg" },
                { id: 6, name: "Bọc ghế da xe HONDA", rating: 4.6, sold: 180, price: 1500000, discount: 8, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTzVetORIjPyPMFoV6jfvdclXM_RHuxXOlobw&s" },
                { id: 7, name: "Ắc Quy Varta 65 Ah Q85/95D23L EFB I-Stop", rating: 5.0, sold: 250, price: 1000000, discount: 10, image: "https://bizweb.dktcdn.net/100/338/974/files/binh-ac-quy-varta-q85-95d23l.jpg?v=1625886276789" },
                { id: 8, name: "Chổi than máy phát Suzuki Swift 2018", rating: 4.9, sold: 95, price: 350000, discount: 5, image: "https://bizweb.dktcdn.net/thumb/1024x1024/100/144/901/products/z4749574770928-2d38c11660b4d23808093f51acd2430c-1.jpg?v=1737436319833" },
                { id: 9, name: "Máy phát điện 2 giắc điện Hyundai i10 Grand 2014-2016", rating: 4.4, sold: 300, price: 1800000, discount: null, image: "https://hunglongauto.vn/wp-content/uploads/2019/03/may-phat-elantra-2016-373002B910-hyundai-07.jpg" },
                { id: 10, name: "Ống nước sau thân van hằng nhiệt Suzuki Celerio 2017-2024", rating: 4.5, sold: 180, price: 280000, discount: 10, image: "https://bizweb.dktcdn.net/100/369/489/products/img-20220212-191919.jpg?v=1644747253877" },
                { id: 11, name: "Máy phát điện 6PK Hyundai Tucson Model 2005-2009", rating: 4.3, sold: 500, price: 1600000, discount: null, image: "https://hunglongauto.vn/wp-content/uploads/2019/03/may-phat-santafe-373002f100-hyundai-04.jpg" },
                { id: 12, name: "LỐC ĐIỀU HÒA MÁY XĂNG 10PA17C CARENS", rating: 4.6, sold: 250, price: 1000000, discount: 10, image: "https://cdn1611.cdn4s4.io.vn/media/products/loc-dieu-hoa/l%E1%BB%91c%20%C4%91i%E1%BB%81u%20h%C3%B2a%20kia%20carens%20m%C3%A1y%20x%C4%83ng%20hanon.jpg" },
                { id: 13, name: "Lốc lạnh xe Toyota Yaris chính hãng Nhật Bản", rating: 4.8, sold: 210, price: 2200000, discount: 12, image: "https://product.hstatic.net/200000536179/product/3360_338316cf25eb6bcc7507eead048ef640_c936ff861e024ef2a5f47c6bf1abea14_070e675c668b4b398bcb1ab99bdefeb8.jpg" },
                { id: 14, name: "Lốc lạnh xe Hyundai Porter 2 chính hãng Hàn Quốc", rating: 4.9, sold: 70, price: 3800000, discount: 5, image: "https://phutung169.com/wp-content/uploads/2022/07/Loc-dong-lanh-Porter-2a.jpg" },
                { id: 15, name: "Lốc lạnh xe Hino truck nhập khẩu chính hãng Hàn Quốc", rating: 5.0, sold: 90, price: 2700000, discount: 10, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSfA15MmNvC1XNDnrVr7z2pJ6k6Gv3PWuvK0BSSeISNXJznYjFdIy87I94xPN3FtWuOADA&usqp=CAU" },
                { id: 16, name: "Túi khí chính Hyundai Grand I10", rating: 4.7, sold: 130, price: 500000, discount: 10, image: "https://pos.nvncdn.com/7e21ec-18050/ps/20170731_wsFr09nxENIYQpAE7MbKmJyU.jpg" },
                { id: 17, name: "Cảm biến va chạm Hyundai Grand i10", rating: 4.9, sold: 40, price: 3500000, discount: 8, image: "https://file.hstatic.net/1000189343/file/95920b4000_1_grande.jpg" },
                { id: 18, name: "Cảm biến nhiệt độ ngoài trời Kia Morning Model 2012-2015", rating: 4.8, sold: 300, price: 700000, discount: 7, image: "https://pos.nvncdn.com/7e21ec-18050/ps/20190425_R5Y7KWDBefYGnqqSrp3hSgoq.jpg" },
                { id: 19, name: "Mô tơ nâng kính trái Hyundai Santafe Model 2007 chính hãng", rating: 4.4, sold: 150, price: 300000, discount: 20, image: "https://pos.nvncdn.com/7e21ec-18050/ps/20230224_ESZTX5seAF4Ju3nP.png" },
                { id: 20, name: "Tổng phanh ABS Kia Morning nhập khẩu chính hãng Hàn Quốc", rating: 4.6, sold: 110, price: 1200000, discount: 15, image: "https://hunglongauto.vn/wp-content/uploads/2019/08/tong-phanh-khong-abs-morning-5851007000-kia-04.jpg" }
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