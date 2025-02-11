<template>
    <header class="header">
        <div class="header__underline">
            <div class="grid">
                <div class="header__top">
                    <ul class="header__top-list">
                        <!-- not logged -->
                        <li class="header__top-item">Hi!
                            <a role="button" class="header-link" data-bs-toggle="modal" data-bs-target="#authModal" data-form="login">
                                Đăng nhập
                            </a>
                            hoặc
                            <a role="button" class="header-link" data-bs-toggle="modal" data-bs-target="#authModal" data-form="register">
                                Đăng ký
                            </a>
                        </li>
                        <!-- logged -->
                        <li class="header__top-item header__top-user">Hi!
                            <span class="header__top-user-name">Lam!</span>
                            <i class="fa-solid fa-chevron-down"></i>
                            <ul class="header__top-user-menu">
                                <li class="header__top-user-item">
                                    <a class="header__top-user-item-info">
                                        <img src="https://photo.znews.vn/w1200/Uploaded/mdf_eioxrd/2021_07_06/1q.jpg" alt="" class="header__top-user-item-avatar">
                                        <p class="header__top-user-item-name">Lam Le</p>
                                    </a>
                                </li>
                                <li class="header__top-user-item header__top-user-item-option">
                                    <router-link to="/cai-dat">Cài đặt tài khoản</router-link>
                                </li>
                                <li class="header__top-user-item header__top-user-item-option">
                                    <router-link to="/theo-doi-don-hang">Đơn mua</router-link>
                                </li>
                                <li class="header__top-user-item header__top-user-item-option">
                                    <a href="">Đăng xuất</a>
                                </li>
                            </ul>
                        </li>
                        <li class="header__top-item">Kết nối
                            <a href="https://www.facebook.com/sanhuavietnam/?locale=vi_VN" class="header__top-icon-link">
                                <i class="fa-brands fa-facebook fa-xl"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="header__top-list">
                        <li class="header__top-item">
                            <a href="" class="header__top-text-link">Hỗ trợ</a>
                        </li>
                        <li class="header__top-item header__top-item-notify">
                            <a href="" class="header__top-icon-link">
                                <i class="fa-regular fa-bell fa-xl"></i>
                                <span class=" header__notice header__notify-notice">{{ notifyDetails.length }}</span>
                            </a>
                            <!--class empty notify: header__notify--empty -->
                            <div v-if="notifyDetails.length === 0" class="header__notify header__notify--empty">
                                <img src="../assets/images/empty-notify.webp" alt="" class="header__notify--empty-img">
                                <p class="header__notify--empty-msg">
                                    Chưa có thông báo
                                </p>
                            </div>
                            <div v-else class="header__notify">
                                <!-- have notify -->
                                
                            </div>
                        </li>
                        <li class="header__top-item header__cart">
                            <router-link to="/gio-hang" class="header__top-icon-link">
                                <i class="fa-solid fa-cart-shopping fa-xl"></i>
                                <span class="header__notice header__cart-notice">{{ cartDetails.length }}</span>
                            </router-link>
                            <!--class empty cart: header__cart-list--empty-cart-->
                            <div v-if="cartDetails.length === 0" class="header__cart-list header__cart-list--empty-cart">
                                <img src="../assets/images/empty-cart.png" alt="" class="header__cart-list--empty-cart-img">
                                <p class="header__cart-list--empty-cart-msg">
                                    Chưa có sản phẩm
                                </p>
                            </div>
                            <div v-else class="header__cart-list ">
                                <!-- have cart -->
                                <ul class="header__cart-list-item">
                                    <li v-for="cartDetail in cartDetails" :key="cartDetail" class="header__cart-item">
                                        <img :src="cartDetail.image" alt="">
                                        <div class="header__cart-item-content">
                                            <div class="header__cart-item-title">
                                                <a href="">{{ cartDetail.name }}</a>
                                                <h4>{{ cartDetail.quantity }} x {{ formatPrice(cartDetail.price) }}đ</h4>
                                            </div>
                                            <div class="header__cart-item-delete">
                                                <i class="fa-solid fa-x"></i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class=" header__cart-footer">
                                    <div class="header__cart-footer-total">
                                        <h4>Tổng tiền</h4>
                                        <span>{{ formatPrice(totalPrice(cartDetails)) }}đ</span>
                                    </div>
                                    <div class="header__cart-btn">
                                        <router-link to="/gio-hang" class="button header__cart-btn--view-cart">Xem giỏ hàng</router-link>
                                        <router-link to="/don-hang" class="button header__cart-btn--checkout">Thanh toán</router-link>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- header with search -->
        <div class="header__underline">
            <div class="header-with-search">
                <router-link to="/" class="header__logo">
                    <img src="../assets/images/logo.jpg" alt="" class="header__logo-img">
                </router-link>
                <div class="header__search">
                    <input type="text" class="header__search-input" placeholder="Nhập để tìm kiếm sản phẩm">
                    <i class="header__search-icon fa-solid fa-magnifying-glass"></i>
                    <button class="button header__search-btn">Tìm kiếm</button>
                </div>
            </div>
        </div>
        <!-- header func bar -->
        <div class="grid header-function-bar">
            <ul class="header-function-bar-list">
                <li class="header-function-bar-item">
                    <router-link to="/" class="header-function-bar-name">
                        Trang chủ
                    </router-link>
                </li>
                <li class="header-function-bar-item">
                    <router-link to="/ve-chung-toi" class="header-function-bar-name">
                        Về chúng tôi
                    </router-link>
                </li>
                <li class="header-function-bar-item header-function-bar-menu-block">
                    <router-link to="/danh-muc" class="header-function-bar-name">
                       Danh mục phụ tùng
                    </router-link>
                    <div class="header-function-bar-menu">
                        <div class="header-function-bar-menu-content">
                            <div class="header-function-bar-menu-left">
                                <div class="header-function-bar-menu-section">
                                    <ul class="header-function-bar-menu-list">
                                        <li v-for="category in categories.slice(0, 9)" :key="category" class="header-function-bar-menu-item">
                                            <router-link :to="'/danh-muc/' + category.slug">{{ category.name }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="header-function-bar-menu-section">
                                    <ul class="header-function-bar-menu-list">
                                        <li v-for="category in categories.slice(9, 18)" :key="category" class="header-function-bar-menu-item">
                                            <router-link :to="'/danh-muc/' + category.slug">{{ category.name }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="header-function-bar-menu-right">
                                <a href="#">
                                    <img src="https://ir.ebaystatic.com/cr/v/c01/ROW-19399_Fallback_PandA_770x270.png" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="header-function-bar-item header-function-bar-menu-block">
                    <router-link to="/danh-muc" class="header-function-bar-name">
                        Phụ tùng theo hãng
                    </router-link>
                    <div class="header-function-bar-menu">
                        <div class="header-function-bar-menu-content">
                            <div class="header-function-bar-menu-left">
                                <div class="header-function-bar-menu-section">
                                    <ul class="header-function-bar-menu-list">
                                        <li v-for="category in categoriesByBrand.slice(0, 9)" :key="category" class="header-function-bar-menu-item">
                                            <router-link :to="'/danh-muc/' + category.slug">{{ category.name }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="header-function-bar-menu-section">
                                    <ul class="header-function-bar-menu-list">
                                        <li v-for="category in categoriesByBrand.slice(9, 18)" :key="category" class="header-function-bar-menu-item">
                                            <router-link :to="'/danh-muc/' + category.slug">{{ category.name }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="header-function-bar-menu-right">
                                <a href="#">
                                    <img src="https://ir.ebaystatic.com/cr/v/c01/ROW-19399_Fallback_PandA_770x270.png" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="header-function-bar-item">
                    <router-link to="" class="header-function-bar-name">
                        Dịch vụ
                    </router-link>
                </li>
                <li class="header-function-bar-item">
                    <router-link to="/tin-tuc" class="header-function-bar-name">
                        Tin tức và sự kiện
                    </router-link>
                </li>
                <li class="header-function-bar-item">
                    <router-link to="/lien-he" class="header-function-bar-name">
                        Liên hệ
                    </router-link>
                </li>
            </ul>
        </div>
    </header>

    <div class="app">
        <router-view></router-view>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="grid__row">
                <div class="grid__column-2-4">
                    <h3 class="footer__heading">
                        Thông tin công ty
                    </h3>
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Giới thiệu về chúng tôi</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Sứ mệnh & tầm nhìn</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Liên hệ</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Địa chỉ cửa hàng</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Giờ mở cửa</a>
                        </li>
                    </ul>
                </div>
                <div class="grid__column-2-4">
                    <h3 class="footer__heading">
                        Hỗ trợ khách hàng
                    </h3>
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Chính sách giao hàng</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Chính sách đổi trả</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Câu hỏi thường gặp (FAQ)</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Hướng dẫn đặt hàng</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Liên hệ hỗ trợ</a>
                        </li>
                    </ul>
                </div>
                <div class="grid__column-2-4">
                    <h3 class="footer__heading">
                        Kết nối với chúng tôi
                    </h3>
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <i class="fa-brands fa-facebook footer__list-item-icon"></i>
                            <a href="https://www.facebook.com/sanhuavietnam/?locale=vi_VN" class="footer__list-item-link">Facebook</a>
                        </li>
                    </ul>
                </div>
                <div class="grid__column-2-4">
                    <h3 class="footer__heading">
                        Chính sách và quy định
                    </h3>
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Điều khoản sử dụng</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Chính sách bảo mật thông tin</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Chính sách thanh toán</a>
                        </li>
                    </ul>
                </div>
                <div class="grid__column-2-4">
                    <h3 class="footer__heading">
                        Thông tin khác
                    </h3>
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Tin tức & Blog</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Tuyển dụng</a>
                        </li>
                        <li class="footer__list-item">
                            <a href="#" class="footer__list-item-link">Đối tác & nhà cung cấp</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-copyright">
                <br>Địa chỉ: Thửa đất B16, B17, B18, B19, B20, B21 thuộc lô đất CN6 Khu công nghiệp An Dương, Xã Hồng Phong, Huyện An Dương, Thành phố Hải Phòng, Việt Nam.<br> 
                <br> Số điện thoại: 02256259777 - Email: Hrvn@ic.sanhuagroup.com<br>
                <br>© 2025 - Bản quyền thuộc về công ty TNHH Sanhua Việt Nam.<br>
            </div>
        </div>
    </footer>
    <AuthModal />
    <ChatComponent />
</template>

<script>
import { onMounted } from 'vue';
import AuthModal from '@/components/AuthModal.vue';
import ChatComponent from '@/components/ChatComponent.vue';
import { formatPrice, totalPrice } from '@/helpers/helpers.js'

export default {
    name: 'SiteLayout',
    components: {
        AuthModal, ChatComponent
    },
    data() {
        return {
            notifyDetails: [

            ],
            cartDetails: [
                { 
                    name: 'Daissy Casual Bag', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-3.jpg', 
                    quantity: 1, 
                    price: 800000,
                    description: 'A stylish casual bag for everyday use.'
                },
                { 
                    name: 'Leather Wallet', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-4.jpg', 
                    quantity: 2, 
                    price: 500000,
                    description: 'A premium leather wallet with multiple compartments.'
                },
                { 
                    name: 'Classic Watch', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-5.jpg', 
                    quantity: 1, 
                    price: 1200000,
                    description: 'A classic watch with a leather strap.'
                },
                { 
                    name: 'Daissy Casual Bag', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-3.jpg', 
                    quantity: 1, 
                    price: 800000,
                    description: 'A stylish casual bag for everyday use.'
                },
                { 
                    name: 'Leather Wallet', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-4.jpg', 
                    quantity: 2, 
                    price: 500000,
                    description: 'A premium leather wallet with multiple compartments.'
                },
                { 
                    name: 'Classic Watch', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-5.jpg', 
                    quantity: 1, 
                    price: 1200000,
                    description: 'A classic watch with a leather strap.'
                },
                { 
                    name: 'Daissy Casual Bag', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-3.jpg', 
                    quantity: 1, 
                    price: 800000,
                    description: 'A stylish casual bag for everyday use.'
                },
                { 
                    name: 'Leather Wallet', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-4.jpg', 
                    quantity: 2, 
                    price: 500000,
                    description: 'A premium leather wallet with multiple compartments.'
                },
                { 
                    name: 'Classic Watch', 
                    image: 'https://nest-frontend-v6.netlify.app/assets/imgs/shop/thumbnail-5.jpg', 
                    quantity: 1, 
                    price: 1200000,
                    description: 'A classic watch with a leather strap.'
                },
            ],
            categoriesByBrand: [
                { name: 'Toyota', slug: 'toyota' }, { name: 'Honda', slug: 'honda' }, { name: 'Ford', slug: 'ford' }, { name: 'Chevrolet', slug: 'chevrolet' }, { name: 'Nissan', slug: 'nissan' }, { name: 'BMW', slug: 'bmw' }, { name: 'Mercedes-Benz', slug: 'mercedes-benz' }, { name: 'Volkswagen', slug: 'volkswagen' }, { name: 'Audi', slug: 'audi' }, { name: 'Lexus', slug: 'lexus' }, { name: 'Hyundai', slug: 'hyundai' }, { name: 'Kia', slug: 'kia' }, { name: 'Mazda', slug: 'mazda' }, { name: 'Mitsubishi', slug: 'mitsubishi' }, { name: 'Subaru', slug: 'subaru' }, { name: 'Suzuki', slug: 'suzuki' }, { name: 'Porsche', slug: 'porsche' }, { name: 'Jaguar', slug: 'jaguar' }, { name: 'Land Rover', slug: 'land-rover' }, { name: 'Volvo', slug: 'volvo' }
            ],
            categories: [
                { id: 1, name: "Phụ tùng động cơ", slug: "phu-tung-dong-co" },
                { id: 2, name: "Phụ tùng gầm xe", slug: "phu-tung-gam-xe" },
                { id: 3, name: "Phụ tùng thân & vỏ", slug: "phu-tung-than-vo" },
                { id: 4, name: "Phụ tùng điện", slug: "phu-tung-dien" },
                { id: 5, name: "Phụ tùng điều hòa", slug: "phu-tung-dieu-hoa" },
                { id: 6, name: "Hệ thống phanh", slug: "he-thong-phanh" },
                { id: 7, name: "Hệ thống treo", slug: "he-thong-treo" },
                { id: 8, name: "Hệ thống làm mát", slug: "he-thong-lam-mat" },
                { id: 9, name: "Hệ thống nhiên liệu", slug: "he-thong-nhien-lieu" },
                { id: 10, name: "Hệ thống chiếu sáng", slug: "he-thong-chieu-sang" },
                { id: 11, name: "Phụ tùng lốp xe", slug: "phu-tung-lop-xe" },
                { id: 12, name: "Gương và kính xe", slug: "guong-va-kinh-xe" },
                { id: 13, name: "Bộ ly hợp và hộp số", slug: "bo-ly-hop-va-hop-so" },
                { id: 14, name: "Hệ thống lái", slug: "he-thong-lai" },
                { id: 15, name: "Hệ thống xả", slug: "he-thong-xa" },
                { id: 16, name: "Phụ kiện trang trí nội thất", slug: "phu-kien-trang-tri-noi-that" },
                { id: 17, name: "Phụ kiện trang trí ngoại thất", slug: "phu-kien-trang-tri-ngoai-that" },
                { id: 18, name: "Phụ kiện công nghệ và tiện ích", slug: "phu-kien-cong-nghe-va-tien-ich" }
            ]
        }
    },
    methods: {
        formatPrice, totalPrice,
    },
    setup() {
        const centerHeaderMenus = () => {
            const menuBlocks = document.querySelectorAll('.header-function-bar-menu-block');
            let value = -142;
            menuBlocks.forEach((block, i) => {
                if (i === 0) {
                    block.querySelector('.header-function-bar-menu').style.left = `${value}px`;
                } else {
                    const prevBlock = menuBlocks[i - 1];
                    const prevWidth = prevBlock.offsetWidth;
                    value -= prevWidth;
                    block.querySelector('.header-function-bar-menu').style.left = `${value}px`;
                }
            });
        }
        onMounted(() => {
            centerHeaderMenus();
        })
    },
}
</script>

