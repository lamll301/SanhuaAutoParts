<template>
    <header class="header">
        <div class="header__underline">
            <div class="grid">
                <div class="header__top">
                    <ul class="header__top-list">
                        <!-- not logged -->
                        <li v-if="!isAuthenticated" class="header__top-item">Hi!
                            <a role="button" class="header-link" data-bs-toggle="modal" data-bs-target="#authModal" data-form="login">
                                Đăng nhập
                            </a>
                            hoặc
                            <a role="button" class="header-link" data-bs-toggle="modal" data-bs-target="#authModal" data-form="register">
                                Đăng ký
                            </a>
                        </li>
                        <!-- logged -->
                        <li v-else class="header__top-item header__top-user">Chào bạn!
                            <span class="header__top-user-name">
                                {{ user.username }}!
                            </span>
                            <i class="fa-solid fa-chevron-down"></i>
                            <ul class="header__top-user-menu">
                                <li class="header__top-user-item">
                                    <a class="header__top-user-item-info">
                                        <img :src="getImageUrl(user.avatar?.path, '/images/empty-avatar.webp')" alt="" class="header__top-user-item-avatar">
                                        <p class="header__top-user-item-name">{{ user.name }}</p>
                                    </a>
                                </li>
                                <li class="header__top-user-item header__top-user-item-option">
                                    <router-link to="/cai-dat">Cài đặt tài khoản</router-link>
                                </li>
                                <li class="header__top-user-item header__top-user-item-option">
                                    <router-link to="/theo-doi-don-hang">Đơn mua</router-link>
                                </li>
                                <li class="header__top-user-item header__top-user-item-option">
                                    <a @click.prevent="logout">Đăng xuất</a>
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
                            <button @click="goToCart" class="header__top-icon-link">
                                <i class="fa-solid fa-cart-shopping fa-xl"></i>
                                <span class="header__notice header__cart-notice">{{ cartDetails.length }}</span>
                            </button>
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
                                        <img :src="getImageUrl(cartDetail.product.images[0].path)" alt="">
                                        <div class="header__cart-item-content">
                                            <div class="header__cart-item-title">
                                                <router-link :to="'/san-pham/' + cartDetail.product.slug">{{ cartDetail.product.name }}</router-link>
                                                <h4>{{ cartDetail.quantity }} x {{ formatPrice(cartDetail.product.price) }}đ</h4>
                                            </div>
                                            <div class="header__cart-item-delete" @click="removeCart(cartDetail.id)">
                                                <i class="fa-solid fa-x"></i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class=" header__cart-footer">
                                    <div class="header__cart-footer-total">
                                        <h4>Tổng tiền</h4>
                                        <span>{{ formatPrice(total) }}đ</span>
                                    </div>
                                    <div class="header__cart-btn">
                                        <router-link to="/gio-hang" class="button header__cart-btn--view-cart">Xem giỏ hàng</router-link>
                                        <button @click="goToCheckout" class="button header__cart-btn--checkout">Thanh toán</button>
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
                    <input type="text" class="header__search-input" placeholder="Nhập để tìm kiếm sản phẩm"
                    v-model="searchKeyword" @keyup.enter="findByKeyword">
                    <i class="header__search-icon fa-solid fa-magnifying-glass"></i>
                    <button class="button header__search-btn" @click="findByKeyword">Tìm kiếm</button>
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
                                        <li v-for="category in (categories || []).slice(0, 9)" :key="category" class="header-function-bar-menu-item">
                                            <router-link :to="'/danh-muc/' + category.slug">{{ category.name }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="header-function-bar-menu-section">
                                    <ul class="header-function-bar-menu-list">
                                        <li v-for="category in (categories || []).slice(9, 18)" :key="category" class="header-function-bar-menu-item">
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
                                        <li v-for="category in (categoriesByBrand || []).slice(0, 9)" :key="category" class="header-function-bar-menu-item">
                                            <router-link :to="'/danh-muc/' + category.slug">{{ category.name }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="header-function-bar-menu-section">
                                    <ul class="header-function-bar-menu-list">
                                        <li v-for="category in (categoriesByBrand || []).slice(9, 18)" :key="category" class="header-function-bar-menu-item">
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
                        <li class="footer__list-item">
                            <i class="fa-brands fa-youtube footer__list-item-icon"></i>
                            <a href="https://www.youtube.com" class="footer__list-item-link">Youtube</a>
                        </li>
                        <li class="footer__list-item">
                            <i class="fa-brands fa-google footer__list-item-icon"></i>
                            <a href="https://www.google.com" class="footer__list-item-link">Website</a>
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
    <AuthModal @fetchIfAuth="fetchIfAuth" />
    <ChatComponent v-if="isAuthenticated" />
</template>

<script>
import { onMounted } from 'vue';
import AuthModal from '@/components/AuthModal.vue';
import ChatComponent from '@/components/ChatComponent.vue';
import { getImageUrl, formatPrice } from '@/utils/helpers';
import { useCartStore } from '@/stores/cart';
import { useAuthStore } from '@/stores/auth';
import { useOrderStore } from '@/stores/order';
import { categoryApi, authApi, cartApi } from '@/api';

export default {
    name: 'SiteLayout',
    components: {
        AuthModal, ChatComponent
    },
    setup() {
        const cartStore = useCartStore();
        const authStore = useAuthStore();
        const orderStore = useOrderStore();
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
        return {
            cartStore, authStore, orderStore
        }
    },
    data() {
        return {
            isLoading: true, searchKeyword: '',
            notifyDetails: [],
            categoriesByBrand: [],
            categories: [],
        }
    },
    computed: {
        cartDetails() {
            return this.cartStore.details.filter(detail => detail.product.quantity > 0);
        },
        total() {
            return this.cartStore.total;
        },
        isAuthenticated() {
            return this.authStore.isAuthenticated;
        },
        user() {
            return this.authStore.user;
        },
    },
    created() {
        const token = localStorage.getItem('token');
        const user = this.authStore.user;
        if (token && Object.keys(user).length === 0) {
            this.authStore.setToken(token)
            this.fetchIfAuth()
        }
        this.fetchData();
    },
    methods: { 
        getImageUrl, formatPrice,
        async fetchData() {
            this.isLoading = true;
            try {
                const res = await categoryApi.getCategoryProduct();

                this.categories = res.data.part;
                this.categoriesByBrand = res.data.brand;

                if (this.isAuthenticated) {
                    await this.fetchIfAuth()
                }
            } catch (err) {
                console.error(err)
            } finally {
                this.isLoading = false
            }
        },
        async fetchIfAuth() {
            try {
                const token = this.authStore.token
                if (token) {
                    const res = await Promise.all([
                        cartApi.getCart()
                        // authApi.me(),
                    ])
                    const cart = res[0].data;
                    if (cart?.details) {
                        this.cartStore.setCart(cart.details)
                    }
                    // const user = res[1].data;
                    // if (user) {
                    //     this.authStore.setUser(user)
                    // }
                }
            } catch (e) {
                console.error(e)
            }
        },
        async logout() {
            try {
                await authApi.logout()
                this.cartStore.setCart([]);
                this.authStore.removeToken();
                this.authStore.removeUser();
                await this.$swal.fire('Đăng xuất thành công', '', 'success', {
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                });
                this.$router.push('/');
            } catch (err) {
                console.error(err)
            }
        },
        async goToCart() {
            if (!this.isAuthenticated) {
                await this.$swal.fire("Vui lòng đăng nhập", "Bạn cần đăng nhập để xem giỏ hàng", "warning");
                return;
            }
            this.$router.push('/gio-hang');
        },
        removeCart(id) {
            this.cartStore.removeCartItem(id);
            cartApi.remove(id).catch(err => console.error(err));
        },
        findByKeyword() {
            if (this.searchKeyword.trim()) {
                this.$router.push({ path: '/danh-muc', query: { key: this.searchKeyword.trim() } })
            }
        },
        goToCheckout() {
            this.orderStore.setBuyNow({});
            this.$router.push('/don-hang')
        }
    },
}
</script>

