<template>
    <div class="flex">
        <header class="header-admin" v-show="isSidebarActive" ref="sidebar">
            <div class="header-admin__label">
                <router-link to="/admin" class="header-admin__text">Admin Dashboard</router-link>
            </div>
            <router-link to="/admin" class="header-admin__dash">
                <i class="header-admin__icon fa-solid fa-house"></i>
                Trang chủ
            </router-link>
            <div class="header-admin__label1">HỆ THỐNG & NGƯỜI DÙNG</div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-key header-admin__dropdown-icon"></i>
                        Quản lý phân quyền
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/permission">Tất cả phân quyền</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/permission/create">Thêm phân quyền</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-medal header-admin__dropdown-icon"></i>
                        Quản lý vai trò
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/role">Tất cả vai trò</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/role/create">Thêm vai trò</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-user header-admin__dropdown-icon"></i>
                        Quản lý người dùng
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/user">Tất cả người dùng</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-message header-admin__dropdown-icon"></i>
                        Quản lý tin nhắn
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/chat">Tất cả tin nhắn</router-link></li>
                </ul>
            </div>
            <div class="header-admin__label1">DANH MỤC CƠ BẢN</div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-folder-open header-admin__dropdown-icon"></i>
                        Quản lý danh mục
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/category">Tất cả danh mục</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/category/create">Thêm danh mục</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-cube header-admin__dropdown-icon"></i>
                        Quản lý sản phẩm
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/product">Tất cả sản phẩm</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/product/create">Thêm sản phẩm</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-scale-balanced header-admin__dropdown-icon"></i>
                        Quản lý đơn vị tính
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/unit">Tất cả đơn vị tính</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/unit/create">Thêm đơn vị tính</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-industry header-admin__dropdown-icon"></i>
                        Quản lý nhà cung cấp
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/supplier">Tất cả nhà cung cấp</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/supplier/create">Thêm nhà cung cấp</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-newspaper header-admin__dropdown-icon"></i>
                        Quản lý tin tức
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/article">Tất cả tin tức</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/article/create">Thêm tin tức</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-tags header-admin__dropdown-icon"></i>
                        Quản lý khuyến mãi
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/promotion">Tất cả khuyến mãi</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/promotion/create">Thêm khuyến mãi</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-ticket header-admin__dropdown-icon"></i>
                        Quản lý voucher
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/voucher">Tất cả voucher</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/voucher/create">Thêm voucher</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-box header-admin__dropdown-icon"></i>
                        Quản lý đơn hàng
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/order">Tất cả đơn hàng</router-link></li>
                </ul>
            </div>
            <div class="header-admin__label1">QUẢN LÝ KHO</div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-boxes-stacked header-admin__dropdown-icon"></i>
                        Quản lý hàng tồn kho
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/inventory">Tất cả hàng tồn kho</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/inventory/create">Thêm hàng tồn kho</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-map header-admin__dropdown-icon"></i>
                        Quản lý vị trí kho
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/location">Tất cả vị trí kho</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/location/create">Thêm vị trí kho</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-truck-ramp-box header-admin__dropdown-icon"></i>
                        Quản lý phiếu nhập
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/import">Tất cả phiếu nhập</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/import/create">Thêm phiếu nhập</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-truck-moving header-admin__dropdown-icon"></i>
                        Quản lý phiếu xuất
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/export">Tất cả phiếu xuất</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/export/create">Thêm phiếu xuất</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-file-alt header-admin__dropdown-icon"></i>
                        Quản lý phiếu kiểm kê
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/check">Tất cả phiếu kiểm kê</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/check/create">Thêm phiếu kiểm kê</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-recycle header-admin__dropdown-icon"></i>
                        Quản lý phiếu thanh lý
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/disposal">Tất cả phiếu thanh lý</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/disposal/create">Thêm phiếu thanh lý</router-link></li>
                </ul>
            </div>
            <div class="header-admin__dropdown">
                <div class="header-admin__dropdown-content">
                    <div class="header-admin__dropdown-content-text">
                        <i class="fa-solid fa-minus-circle header-admin__dropdown-icon"></i>
                        Quản lý phiếu hủy
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="header-admin__dropdown-menu">
                    <li class="header-admin__dropdown-item"><router-link to="/admin/cancel">Tất cả phiếu hủy</router-link></li>
                    <li class="header-admin__dropdown-item"><router-link to="/admin/cancel/create">Thêm phiếu hủy</router-link></li>
                </ul>
            </div>
        </header>
        <div class="container-admin" ref="container">
            <nav class="nav-admin">
                <div class="nav-admin__left">
                    <i class="fa-solid fa-bars nav-admin__icon" @click="toggleSidebar"></i>
                    <input type="text" class="nav-admin__search" placeholder="Tìm kiếm" @keyup.enter="search">
                </div>
                <div class="nav-admin__right">
                    <div class="nav-admin__circle">
                        <i class="fa-regular fa-bell nav-admin__icon"></i>
                        <div class="nav-admin__notify">
                            <header class="nav-admin__notify-header">
                                <h3>
                                    Thông báo
                                    <i class="fa-solid fa-gear nav-admin__icon"></i>
                                </h3>
                            </header>
                            <ul class="nav-admin__notify-list">
                                <template v-if="notifications.length === 0">
                                    <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;height: 362px;">
                                        <img src="../assets/images/empty-notify.webp" alt="" class="header__notify--empty-img" style="margin-top: 0;">
                                        <p class="header__notify--empty-msg">
                                            Chưa có thông báo
                                        </p>
                                    </div>
                                </template>
                                <template v-else>
                                    <li v-for="notify in notifications" :key="notify.id" class="nav-admin__notify-item nav-admin__notify-item-active">
                                        <a href="#" class="nav-admin__notify-link">
                                            <p class="nav-admin__notify-name">{{ notify.name }}</p>
                                            <p class="nav-admin__notify-describe">{{ notify.content }}</p>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                            <div class="nav-admin__notify-footer">
                                <a href="#">Xem tất cả thông báo</a>
                            </div>
                        </div>
                    </div>
                    <!-- admin avatar -->
                    <div class="nav-admin__avatar">
                        <img class="nav-admin__avatar-img" :src="getImageUrl(user?.avatar?.path, '/images/empty-avatar.webp')" alt="">
                        <div class="nav-admin__avatar-content">
                            <header class="nav-admin__avatar-header">
                                <h3>{{ user.name || 'N/A' }}</h3>
                                <p>{{ user.email || 'N/A' }}</p>
                            </header>
                            <ul class="nav-admin__avatar-list">
                                <li class="nav-admin__avatar-item">
                                    <router-link to="/cai-dat" class="nav-admin__avatar-link">
                                        <i class="fa-solid fa-gear nav-admin__icon"></i>
                                        <span>Cài đặt tài khoản</span>
                                    </router-link>
                                </li>
                                <li class="nav-admin__avatar-item">
                                    <button @click.prevent="logout" class="nav-admin__avatar-link" style="background: none;padding: 0;border: none;">
                                        <i class="fa-solid fa-power-off nav-admin__icon"></i>
                                        <span>Đăng xuất</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="admin-background"></div>
            <router-view></router-view>
            <div class="footer-admin">
                <p>Ghé thăm website
                    <router-link to="/" class="footer-admin-link">tại đây</router-link>
                </p>
                <p>Made by lamlely</p>
            </div>
        </div>
    </div>
</template>

<script>
import gsap from "gsap";
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { getImageUrl } from "@/utils/helpers";
import { authApi } from "@/api";

export default {
    setup() {
        const authStore = useAuthStore();
        const cartStore = useCartStore();
        return {
            authStore,
            cartStore
        }
    },
    mounted() {
        this.checkAdminAccess();
    },
    data() {
        return {
            isSidebarActive: true,
            notifications: []
        }
    },
    computed: {
        user() {
            return this.authStore.user;
        }
    },
    methods: {
        getImageUrl,
        checkAdminAccess() {
            if (!this.user || !this.user.role_id) {
                this.$router.replace({ name: "AccessDenied" })
                return;
            }
        },
        toggleSidebar() {
            if (this.isSidebarActive) {
                gsap.to(this.$refs.sidebar, {
                    x: '-100%',
                    duration: 0.2,
                    onComplete: () => {
                        this.isSidebarActive = false;
                    },
                });
            } else {
                this.isSidebarActive = true;
                this.$nextTick(() => {
                    gsap.fromTo(
                        this.$refs.sidebar,
                        { x: '-100%' },
                        {
                            x: '0%',
                            duration: 0.2,
                            ease: 'power2.out',
                        }
                    );
                });
            }
        },
        search(event) {
            const key = event.target.value
            const params = new URLSearchParams(this.$route.query)
            params.delete('page')
            if (key) {
                params.set('key', key)
            } else {
                params.delete('key')
            }
            this.$router.push(this.$route.path + '?' + params.toString())
        },
        async logout() {
            try {
                this.cartStore.setCart([]);
                this.authStore.removeUser();
                this.$swal.fire('Đăng xuất thành công', '', 'success', {
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                });
                authApi.logout().then(() => {
                    this.authStore.removeToken();
                    this.$router.push('/');
                }).catch(e => console.error(e));
            } catch (e) {
                console.error(e)
                this.cartStore.setCart([]);
                this.authStore.removeToken();
                this.authStore.removeUser();
                this.$router.push('/');
            }
        },
    }
}
</script>