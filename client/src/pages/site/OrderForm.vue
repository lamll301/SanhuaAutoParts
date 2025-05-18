<template>
<form @submit.prevent="save">
    <div class="grid">
        <div class="cart">
            <h1 class="cart-heading">Đơn hàng</h1>
            <div class="cart-container">
                <div class="cart-left">
                    <div class="admin-content__form-divided">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Họ và tên
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <input type="text" class="order-form-input" v-model="name" 
                            v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                            <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Số điện thoại
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <input type="text" class="order-form-input" v-model="phone" 
                            v-bind:class="{'is-invalid': errors.phone}" @blur="validate()">
                            <div class="invalid-feedback" v-if="errors.phone">{{ errors.phone }}</div>
                        </div>
                    </div>
                    <div class="admin-content__form-divided-3">
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Tỉnh / Thành phố
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <select v-model="selectedCity" @change="fetchDistricts" class="order-form-select order-form-input"
                            v-bind:class="{'is-invalid': errors.city}" @blur="validate()">
                                <option v-for="city in cities" :key="city.code" :value="city.code">
                                    {{ city.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.city">{{ errors.city }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Quận / Huyện
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <select v-model="selectedDistrict" @change="fetchWards" class="order-form-select order-form-input"
                            v-bind:class="{'is-invalid': errors.district}" @blur="validate()">
                                <option v-for="district in districts" :key="district.code" :value="district.code">
                                    {{ district.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.district">{{ errors.district }}</div>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Phường / Xã
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <select v-model="selectedWard" class="order-form-select order-form-input"
                            v-bind:class="{'is-invalid': errors.ward}" @blur="validate()">
                                <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                    {{ ward.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.ward">{{ errors.ward }}</div>
                        </div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Địa chỉ cụ thể
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <textarea class="order-form-area" rows="3" v-model="address" 
                        v-bind:class="{'is-invalid': errors.address}" @blur="validate()">
                        </textarea>
                        <div class="invalid-feedback" v-if="errors.address">{{ errors.address }}</div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Loại địa chỉ
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <select v-model="selectedAddressType" value="" class="order-form-select order-form-input">
                            <option value="Nhà riêng">Nhà riêng</option>
                            <option value="Văn phòng">Văn phòng</option>
                        </select>
                    </div>
                    
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Mã giảm giá (nếu có)
                        </p>
                        <div class="order-form-coupon-container" :class="{ 'is-invalid': couponErrorMessage, 'is-valid': couponSuccessMessage }">
                            <input type="text" class="order-form-coupon order-form-input" v-model="couponCode">
                            <button class="order-form-btn" @click.prevent="applyCoupon">
                                Áp dụng mã
                            </button>
                        </div>
                        <div class="invalid-feedback" v-if="couponErrorMessage">
                            {{ couponErrorMessage }}
                        </div>
                        <div class="valid-feedback text-success" v-if="couponSuccessMessage">
                            {{ couponSuccessMessage }}
                        </div>
                    </div>
                    <!-- payment method -->
                    <h1 class="order-heading cart-heading">Phương thức thanh toán</h1>
                    <div class="order-payment">
                        <div class="order-form-radio-container">
                            <input type="radio" name="payment-method" class="order-form-radio"
                                v-model="selectedPaymentMethod" value="Mã QR"
                            >
                            <span class="order-form-radio-text">
                                Mã QR
                            </span>
                        </div>
                        <div class="payment-method-content order-form-radio-content"
                            :class="{ 'hidden': selectedPaymentMethod !== 'Mã QR' }"
                        >
                            <div v-if="this.payment.qrcode" class="mb-16">
                                <p class="order-form-qr-text">Quét mã QR dưới đây bằng ứng dụng Internet Banking để thanh toán</p>
                                <img :src="payment.qrcode.image" alt="" class="order-form-qr">
                                <p class="order-form-qr-sub-text">Số hoá đơn: {{ payment.id }}</p>
                                <p class="order-form-qr-sub-text">Lưu ý: Mã QR này sẽ hết hạn sau {{ countdown }} kể từ lúc tạo</p>
                            </div>
                        </div>
                        <div class="order-form-radio-container">
                            <input type="radio" name="payment-method" class="order-form-radio"
                                v-model="selectedPaymentMethod" value="Thẻ tín dụng / thẻ ghi nợ"
                            >
                            <span class="order-form-radio-text">
                                Thẻ tín dụng / thẻ ghi nợ
                            </span>
                        </div>
                        <div class="payment-method-content"
                            :class="{ 'hidden': selectedPaymentMethod !== 'Thẻ tín dụng / thẻ ghi nợ' }"
                        >
                            <div class="mb-16">
                                <div class="order-form-container">
                                    <p class="order-form-title">
                                        Số thẻ
                                        <span class="order-form-required">
                                            *
                                        </span>
                                    </p>
                                    <input v-model="payment.creditCard.cardNumber" type="text" class="order-form-input">
                                </div>
                                <div class="admin-content__form-divided">
                                    <div class="order-form-container">
                                        <p class="order-form-title">
                                            Ngày hết hạn (MM/YY)
                                            <span class="order-form-required">
                                                *
                                            </span>
                                        </p>
                                        <input v-model="payment.creditCard.expiryDate" type="month" class="order-form-input">
                                    </div>
                                    <div class="order-form-container">
                                        <p class="order-form-title">
                                            Mã CVV
                                            <span class="order-form-required">
                                                *
                                            </span>
                                        </p>
                                        <input v-model="payment.creditCard.cvv" type="password" class="order-form-input">
                                    </div>
                                </div>
                                <div class="order-form-container">
                                    <p class="order-form-title">
                                        Họ và tên chủ thẻ
                                        <span class="order-form-required">
                                            *
                                        </span>
                                    </p>
                                    <input v-model="payment.creditCard.cardholderName" type="text" class="order-form-input">
                                </div>
                                <div class="order-form-container">
                                    <p class="order-form-title">
                                        Mã bưu chính
                                        <span class="order-form-required">
                                            *
                                        </span>
                                    </p>
                                    <input v-model="payment.creditCard.postalCode" type="text" class="order-form-input">
                                </div>
                            </div>
                        </div>
                        <div class="order-form-radio-container">
                            <input type="radio" name="payment-method" class="order-form-radio"
                                v-model="selectedPaymentMethod" value="Ví điện tử"
                            >
                            <span class="order-form-radio-text">
                                Ví điện tử
                            </span>
                        </div>
                        <div class="payment-method-content order-form-e-wallet-content"
                            :class="{ 'hidden': selectedPaymentMethod !== 'Ví điện tử' }"
                        >
                            <div v-for="eWallet in eWallets" :key="eWallet.name" 
                                class="order-form-e-wallet"
                                :class="{ 'order-form-e-wallet--selected': selectedEWallet === eWallet.name }"
                                @click="onEWalletSelect(eWallet.name)"
                            >
                                <img :src="eWallet.image" alt="" class="order-form-e-wallet-img">
                                <span class="order-form-e-wallet-text">
                                    {{ eWallet.name }}
                                </span>
                            </div>
                        </div>
                        <div class="order-form-radio-container">
                            <input type="radio" name="payment-method" class="order-form-radio"
                                v-model="selectedPaymentMethod" value="Thanh toán khi nhận hàng"
                            >
                            <span class="order-form-radio-text">
                                Thanh toán khi nhận hàng
                            </span>
                        </div>
                        <div class="hidden payment-method-content"></div>
                    </div>
                    <!-- product -->
                    <h1 class="order-heading cart-heading">Sản phẩm</h1>
                    <ul class="cart-list">
                        <li v-for="detail in details" :key="detail" class="cart-item">
                            <div class="cart-item-content">
                                <div class="cart-item-left">
                                    <img :src="getImageUrl(detail.product.images[0].path)" alt="" class="cart-item-img order-product-img">
                                    <div class="cart-item-product">
                                        <router-link :to="'/san-pham/' + detail.product.slug" class="order-product-name cart-item-name">
                                            {{ detail.product.name }}
                                        </router-link>
                                        <p class="order-product-price-curr cart-item-price-curr">
                                            {{ formatPrice(detail.product.price) }}<sup>đ</sup>
                                        </p>
                                        <p class="cart-item-price-old" v-show="detail.product.original_price !== detail.product.price">
                                            {{ formatPrice(detail.product.original_price) }}<sup>đ</sup>
                                        </p>
                                    </div>
                                </div>
                                <div class="cart-item-right">
                                    <div class="cart-item-quantity">
                                        <span class="order-product-quantity">
                                            Số lượng: {{ detail.quantity }}
                                        </span>
                                    </div>
                                    <div class="cart-item-price">
                                        <p class="order-product-price-total cart-item-price-total">
                                            {{ formatPrice(detail.subtotal) }}<sup>đ</sup>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="order-right cart-right" style="width: 522px;">
                    <span class="order-summary-text">
                        Tóm tắt đơn hàng
                    </span>
                    <div class="order-summary-item-total cart-summary-line-item">
                        <span>Các mặt hàng ({{ details.length }})</span>
                        <span>
                            {{ formatPrice(subtotal) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-summary-line-item">
                        <span>Phí vận chuyển</span>
                        <span>
                            {{ formatPrice(shippingFee) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-summary-line-item" v-if="voucherValue > 0">
                        <span>Giảm giá (mã {{ voucherCode }})</span>
                        <span>
                            -{{ formatPrice(voucherValue) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-total cart-summary-line-item">
                        <span>Tổng cộng</span>
                        <span>
                            {{ formatPrice(total) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="order-user-agreement">
                        <span>
                            Bằng cách xác nhận đơn hàng của bạn, bạn đồng ý với các
                        </span>
                        <a href="">
                            điều khoản dịch vụ vận chuyển của Sanhua.
                        </a>
                    </div>
                    <button type="submit" class="button cart-btn" style="width: 100%;">
                        Xác nhận và thanh toán
                    </button>
                    <span class="cart-label">
                        <img src="@/assets/images/secure.png" alt="">
                        Được bảo vệ bởi
                        <a href="#">
                            chính sách hoàn tiền của Sanhua
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>
</template>

<script>
import { getImageUrl, formatPrice } from '@/utils/helpers';
import apiService from '@/utils/apiService';
import { useOrderStore } from '@/stores/order';
import { useCartStore } from '@/stores/cart';
import { useAuthStore } from '@/stores/auth';

export default {
    name: 'OrderForm',
    setup() {
        const orderStore = useOrderStore();
        const cartStore = useCartStore();
        const authStore = useAuthStore();
        return {
            orderStore,
            cartStore,
            authStore
        }
    },
    beforeRouteEnter(to, from, next) {
        const token = localStorage.getItem('token');
        if (!token) {
            next({ name: 'NotFound' });
            return;
        }
        next();
    },
    computed: {
        total() {
            return this.subtotal + this.shippingFee - this.voucherValue;
        },
        details() {
            const buyNow = this.orderStore.buyNow;
            const reorder = this.orderStore.reorder;
            if (Object.keys(buyNow).length === 0 && reorder.length === 0) {
                return this.cartStore.details.filter(detail => detail.product.quantity > 0)
            }
            if (Object.keys(buyNow).length > 0) {
                return [buyNow];
            }
            return reorder;
        },
        subtotal() {
            const buyNow = this.orderStore.buyNow;
            const reorder = this.orderStore.reorder;
            if (Object.keys(buyNow).length === 0 && reorder.length === 0) {
                return this.cartStore.total
            }
            if (Object.keys(buyNow).length > 0) {
                return buyNow.subtotal;
            }
            return reorder.reduce((acc, detail) => acc + detail.subtotal, 0);
        },
    },
    data() {
        return {
            name: '', phone: '', address: '', couponCode: '', couponSuccessMessage: '', voucherId: '', couponErrorMessage: '', voucherCode: '',
            order: {},
            payment: {
                creditCard: {
                    cardNumber: "",
                    cvv: "",
                    expiryDate: "",
                    cardholderName: "",
                    postalCode: ""
                },
            },
            selectedAddressType: 'Nhà riêng',
            selectedPaymentMethod: '',
            selectedEWallet: '', 
            eWallets: [
                { name: 'MOMO', image: 'https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png' },
                { name: 'VN-Pay', image: 'https://vinadesign.vn/uploads/thumbnails/800/2023/05/vnpay-logo-vinadesign-25-12-59-16.jpg' },
                { name: 'ZaloPay', image: 'https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png' },
            ],
            shippingFee: 50000, voucherValue: 0,
            cities: [], selectedCity: '',
            districts: [], selectedDistrict: '',
            wards: [], selectedWard: '',
            countdown: '15:00',
            errors: {
                name: '',
                phone: '',
                city: '',
                district: '',
                ward: '',
                address: '',
            },
        }
    },
    created() {
        this.fetchData();
    },
    watch: {
        selectedCity() {
            this.districts = [];
            this.wards = [];
            this.fetchDistricts();
        },
        selectedDistrict() {
            this.wards = [];
            this.fetchWards();
        }
    },
    methods: {
        getImageUrl, formatPrice,
        async fetchData() {
            try {
                const res = await apiService.provinces.getProvinces()
                this.cities = res.data;
            } catch (err) {
                console.error(err);
            }
        },
        async fetchDistricts() {
            try {
                const res = await apiService.provinces.getProvinceWithDistricts(this.selectedCity);
                this.districts = res.data.districts;
            } catch (err) {
                console.error(err);
            }
        },
        async fetchWards() {
            try {
                const res = await apiService.provinces.getDistrictWithWards(this.selectedDistrict);
                this.wards = res.data.wards;
            } catch (err) {
                console.error(err);
            }
        },
        async fetchPaymentQR() {
            try {
                const response = await apiService.vietQR.generateQR(this.order.id, this.order.total_amount);
                this.payment.qrcode = {
                    image: response.data.data.qrDataURL,
                };
                this.startCountdown();
            } catch (error) {
                console.log(error);
                this.$swal.fire('Lỗi', 'Đã xảy ra lỗi khi tạo mã QR. Vui lòng thử lại.', 'error')
            }
        },
        async createMomoPayment() {
            try {
                const response = await apiService.payments.createMomoPayment(this.order);
                const momo = response.data;
                if (momo.resultCode === 0) {
                    window.location.href = momo.payUrl;
                } else {
                    this.$swal.fire('Lỗi', momo.message, 'error');
                }
            } catch (error) {
                console.log(error);
            }
        },
        async createVNPayPayment() {
            try {
                const response = await apiService.payments.createVNPayPayment(this.order);
                const vnpay = response.data;
                if (vnpay.message === "success") {
                    window.location.href = vnpay.data;
                } else {
                    this.$swal.fire('Lỗi', vnpay.message, 'error');
                }
            } catch (error) {
                console.log(error);
            }
        },
        async createZaloPayPayment() {
            try {
                const response = await apiService.payments.createZaloPayPayment(this.order);
                const zaloPay = response.data;
                if (zaloPay.return_code === 1) {
                    window.location.href = zaloPay.order_url;
                } else {
                    this.$swal.fire(zaloPay.return_message, zaloPay.sub_return_message, 'error');
                }
            } catch (error) {
                console.log(error);
            }
        },
        async createCODPayment() {
            try {
                const response = await apiService.payments.createCODPayment(this.order.id);
                if (response.data.message === "success") {
                    window.location.href = '/theo-doi-don-hang/' + this.order.id;
                } else {
                    this.$swal.fire('Lỗi', 'Đã xảy ra lỗi khi tạo thanh toán COD', 'error');
                }
            } catch (error) {
                console.log(error);
            }
        },
        async applyCoupon() {
            if (!this.couponCode || this.couponCode.trim() === '') {
                this.couponErrorMessage = 'Mã giảm giá không được để trống';
                this.couponSuccessMessage = '';
                return;
            }
            try {
                const response = await apiService.vouchers.applyCoupon(this.couponCode);
                this.voucherValue = response.data.value;
                this.voucherId = response.data.id;
                this.couponSuccessMessage = response.data.message;
                this.voucherCode = this.couponCode;
                this.couponErrorMessage = '';
            } catch (error) {
                this.couponSuccessMessage = '';
                if (error.response?.data?.message) {
                    this.couponErrorMessage = error.response.data.message;
                } else {
                    this.$swal.fire('Lỗi', 'Đã xảy ra lỗi khi áp dụng mã giảm giá', 'error');
                }
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                name: '',
                phone: '',
                city: '',
                district: '',
                ward: '',
                address: '',
            };
            if (!this.name || this.name.trim() === '') {
                this.errors.name = 'Tên không được để trống';
                isValid = false;
            }
            if (!this.phone || this.phone.trim() === '') {
                this.errors.phone = 'Số điện thoại không được để trống';
                isValid = false;
            }
            if (!this.selectedCity) {
                this.errors.city = 'Tỉnh/Thành phố không được để trống';
                isValid = false;
            }
            if (!this.selectedDistrict) {
                this.errors.district = 'Quận/Huyện không được để trống';
                isValid = false;
            }
            if (!this.selectedWard) {
                this.errors.ward = 'Phường/Xã không được để trống';
                isValid = false;
            }
            if (!this.address || this.address.trim() === '') {
                this.errors.address = 'Địa chỉ không được để trống';
                isValid = false;
            }
            return isValid;
        },
        async save() {
            if (!this.validate()) {
                return;
            }
            if (this.selectedPaymentMethod === '') {
                this.$swal.fire('Lỗi', 'Vui lòng chọn phương thức thanh toán', 'error');
                return;
            }
            const details = this.details.map(detail => ({
                product_id: detail.product.id,
                quantity: detail.quantity,
                price: detail.product.price,
            }));
            try {
                const response = await apiService.orders.create({
                    name: this.name,
                    phone: this.phone,
                    city_id: this.selectedCity,
                    district_id: this.selectedDistrict,
                    ward_id: this.selectedWard,
                    shipping_address: this.address,
                    address_type: this.selectedAddressType,
                    payment_method: this.selectedPaymentMethod,
                    shipping_fee: this.shippingFee,
                    details: details,
                    voucher_id: this.voucherId,
                });
                this.order = response.data;
                if (this.order.id) {
                    if (this.selectedPaymentMethod === 'Mã QR') {
                        await this.fetchPaymentQR();
                    } else if (this.selectedPaymentMethod === 'Ví điện tử') {
                        if (this.selectedEWallet === 'MOMO') {
                            await this.createMomoPayment();
                        } else if (this.selectedEWallet === 'VN-Pay') {
                            await this.createVNPayPayment();
                        } else if (this.selectedEWallet === 'ZaloPay') {
                            await this.createZaloPayPayment();
                        }
                    } else if (this.selectedPaymentMethod === 'Thanh toán khi nhận hàng') {
                        await this.createCODPayment();
                    }
                }
            } catch (error) {
                console.log(error);
                this.$swal.fire('Lỗi', 'Đã xảy ra lỗi khi tạo đơn hàng', 'error');
            }
        },
        onEWalletSelect(eWalletName) {
            this.selectedEWallet = eWalletName;
        },
        startCountdown() {
            let time = 15 * 60;
            this.countdownInterval = setInterval(() => {
                if (time <= 0) {
                    clearInterval(this.countdownInterval);
                    this.$swal.fire('Hết thời gian', 'Thời gian quét mã QR đã hết. Vui lòng thử lại.', 'error');
                    this.payment.qrcode = null;
                    this.selectedPaymentMethod = '';
                    this.countdown = '15:00';
                    return;
                }

                const minutes = Math.floor(time / 60);
                const seconds = time % 60;
                this.countdown = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
                time--;
            }, 1000);
        },
    },
}
</script>

<style>
.invalid-feedback {
    font-size: 12px;
}
.valid-feedback {
    font-size: 12px;
}
</style>