<template>
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true" ref="authModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Register form -->
                    <form v-if="activeForm === 'register'" @submit.prevent="register" class="form" id="form-1">
                        <div class="auth-form">
                            <div class="auth-form__container">
                                <div class="auth-form__header">
                                    <h3 class="auth-form__heading">Đăng ký</h3>
                                    <button ref="closeButton" type="button" class="auth-form__close-button" data-bs-dismiss="modal">X</button>
                                </div>
                                <div class="auth-form__form">
                                    <div class="auth-form__group">
                                        <input type="text" class="order-form-input" placeholder="Tên đăng nhập" v-model="username">
                                        <span class="auth-form__msg" v-if="errors.username">{{ errors.username }}</span>
                                    </div>
                                    <div class="auth-form__group">
                                        <input type="password" class="order-form-input" placeholder="Mật khẩu" v-model="password">
                                        <span class="auth-form__msg" v-if="errors.password">{{ errors.password }}</span>
                                    </div>
                                </div>
                                <div class="auth-form__controls-1">
                                    <button class="button auth-form__controls-btn">ĐĂNG KÝ</button>
                                </div>
                                <span class="auth-form__option">
                                    <p class="auth-form__option-line">_______________________</p>
                                    <p class="auth-form__option-text">HOẶC</p>
                                    <p class="auth-form__option-line">_______________________</p>
                                </span>
                                <div class="auth-form__socials">
                                    <a href="" class="auth-form__socials-text">
                                        <i class="fa-brands fa-facebook fa-xl auth-form__socials-icon-fb"></i>
                                        Facebook
                                    </a>
                                    <a href="" class="auth-form__socials-text">
                                        <i class="fa-brands fa-google fa-xl"></i>
                                        Google
                                    </a>
                                </div>
                                <div class="auth-form__aside">
                                    <p class="auth-form__policy-text">Bằng việc đăng ký, bạn đồng ý với chúng tôi về
                                        <a href="" class="auth-form__text-link">Điều khoản dịch vụ</a> &
                                        <a href="" class="auth-form__text-link">Chính sách bảo mật</a>
                                    </p>
                                </div>
                                <div class="auth-form__login-text">Bạn đã có tài khoản?
                                    <a role="button" @click.prevent="switchForm('login')" class="auth-form__login-text-btn" id="login-link">Đăng nhập</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- login form -->
                    <form v-if="activeForm === 'login'" @submit.prevent="login" class="form" id="form-2" >
                        <div class="auth-form">
                            <div class="auth-form__container">
                                <div class="auth-form__header">
                                    <h3 class="auth-form__heading">Đăng nhập</h3>
                                    <button ref="closeButton" type="button" class="auth-form__close-button" data-bs-dismiss="modal">X</button>
                                </div>
                                <div class="auth-form__form">
                                    <div class="auth-form__group">
                                        <input v-model="username" type="text" class="order-form-input" placeholder="Tên đăng nhập">
                                        <span class="auth-form__msg" v-if="errors.username">{{ errors.username }}</span>
                                    </div>
                                    <div class="auth-form__group">
                                        <input v-model="password" type="password" class="order-form-input" placeholder="Mật khẩu">
                                        <span class="auth-form__msg" v-if="errors.password">{{ errors.password }}</span>
                                    </div>
                                </div>
                                <div class="auth-form__controls-2">
                                    <button class="button auth-form__controls-btn">ĐĂNG NHẬP</button>
                                    <div class="auth-form__aside">
                                        <div class="auth-form__help">
                                            <a href="" class="auth-form__help-link">Quên mật khẩu</a>
                                            <a href="" class="auth-form__help-link">Cần trợ giúp</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="auth-form__option">
                                    <p class="auth-form__option-line">_______________________</p>
                                    <p class="auth-form__option-text">HOẶC</p>
                                    <p class="auth-form__option-line">_______________________</p>
                                </span>
                                <div class="auth-form__socials">
                                    <a href="" class="auth-form__socials-text">
                                        <i class="fa-brands fa-facebook fa-xl auth-form__socials-icon-fb"></i>
                                        Facebook
                                    </a>
                                    <a href="" class="auth-form__socials-text">
                                        <i class="fa-brands fa-google fa-xl"></i>
                                        Google
                                    </a>
                                </div>
                                <div class="auth-form__login-text">Bạn chưa có tài khoản?
                                    <a role="button" @click.prevent="switchForm('register')" class="auth-form__login-text-btn" id="register-link">Đăng ký</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth';
import { authApi } from '@/api';

export default {
    name: 'AuthModal',
    data() {
        return {
            activeForm: 'register',
            username: '',
            password: '',
            errors: {
                username: '',
                password: ''
            }
        };
    },
    setup() {
        const authStore = useAuthStore();
        return {
            authStore
        }
    },
    watch: {
        username() {
            this.errors.username = '';
        },
        password() {
            this.errors.password = '';
        }
    },
    methods: {
        resetForm() {
            this.username = '';
            this.password = '';
            this.errors = {
                username: '',
                password: ''
            }
        },
        switchForm(form) {
            this.activeForm = form;
            this.resetForm();
        },
        validate() {
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;
            let isValid = true;
            this.errors = {
                username: '',
                password: ''
            }
            if (!this.username) {
                this.errors.username = 'Tên đăng nhập không được để trống';
                isValid = false;
            }
            if (!this.password) {
                this.errors.password = 'Mật khẩu không được để trống';
                isValid = false;
            } 
            else if (this.password.length < 8 && this.activeForm === 'register') {
                this.errors.password = 'Mật khẩu phải có ít nhất 8 ký tự';
                isValid = false;
            }
            else if (!passwordRegex.test(this.password) && this.activeForm === 'register') {
                this.errors.password = 'Mật khẩu phải có ít nhất 1 chữ cái viết hoa, 1 chữ cái viết thường, 1 số và 1 ký tự đặc biệt';
                isValid = false;
            }
            return isValid;
        },
        async register() {
            if (!this.validate()) {
                return;
            }
            try {
                const res = await authApi.register(this.username, this.password);
                const token = res.data.token;
                this.authStore.setToken(token);
                this.$emit('fetchIfAuth');
                
                this.$swal.fire('Đăng ký thành công', 'Tài khoản của bạn đã được tạo!', 'success', {
                    confirmButtonText: 'Đóng',
                    willClose: () => {
                        this.$refs.closeButton.click();
                    }
                });
            } catch (e) {
                console.error(e);
                this.errors.password = e.message;
            }
        },
        async login() {
            if (!this.validate()) {
                return;
            }
            try {
                const res = await authApi.login(this.username, this.password);
                const token = res.data.token;
                this.authStore.setToken(token);
                this.$emit('fetchIfAuth');
                
                this.$swal.fire('Đăng nhập thành công', 'Chào mừng bạn quay trở lại!', 'success', {
                    confirmButtonText: 'Đóng',
                    willClose: () => {
                        this.$refs.closeButton.click();
                    }
                });
            } catch (e) {
                console.error(e);
                this.errors.password = e.message;
            }
        }
    },
    mounted() {
        const modalEl = document.getElementById('authModal');
        modalEl.addEventListener('show.bs.modal', (event) => {
            const triggerButton = event.relatedTarget;
            if (triggerButton) {
                const formType = triggerButton.getAttribute('data-form');
                if (formType) {
                    this.activeForm = formType;
                }
            }
        });
    }
};
</script>
