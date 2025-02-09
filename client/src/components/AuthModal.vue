<template>
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Register form -->
                    <form v-if="activeForm === 'register'" action="" method="post" class="form" id="form-1">
                        <div class="auth-form">
                            <div class="auth-form__container">
                                <div class="auth-form__header">
                                    <h3 class="auth-form__heading">Đăng ký</h3>
                                    <button type="button" class="auth-form__close-button" data-bs-dismiss="modal">X</button>
                                </div>
                                <div class="auth-form__form">
                                    <div class="auth-form__group">
                                        <input type="text" class="order-form-input" placeholder="Tên đăng nhập">
                                        <span class="auth-form__msg"></span>
                                    </div>
                                    <div class="auth-form__group">
                                        <input type="password" class="order-form-input" placeholder="Mật khẩu">
                                        <span class="auth-form__msg"></span>
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
                    <form v-if="activeForm === 'login'" action="" method="post" class="form" id="form-2" >
                        <div class="auth-form">
                            <div class="auth-form__container">
                                <div class="auth-form__header">
                                    <h3 class="auth-form__heading">Đăng nhập</h3>
                                    <button type="button" class="auth-form__close-button" data-bs-dismiss="modal">X</button>
                                </div>
                                <div class="auth-form__form">
                                    <div class="auth-form__group">
                                        <input type="text" class="order-form-input" placeholder="Tên đăng nhập">
                                        <span class="auth-form__msg"></span>
                                    </div>
                                    <div class="auth-form__group">
                                        <input type="password" class="order-form-input" placeholder="Mật khẩu">
                                        <span class="auth-form__msg"></span>
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
import { ref, onMounted } from 'vue';

export default {
    name: 'AuthModal',
    setup() {
        const activeForm = ref('register');

        const switchForm = (form) => {
            activeForm.value = form;
        };

        onMounted(() => {
            const modalEl = document.getElementById('authModal');
            modalEl.addEventListener('show.bs.modal', (event) => {
                const triggerButton = event.relatedTarget;
                if (triggerButton) {
                    const formType = triggerButton.getAttribute('data-form');
                    if (formType) {
                        activeForm.value = formType;
                    }
                }
            });
        });

        return {
            activeForm, switchForm
        };
    },
};
</script>