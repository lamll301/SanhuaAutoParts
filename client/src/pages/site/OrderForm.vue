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
                            <input type="text" class="order-form-input">
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Số điện thoại
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <input type="text" class="order-form-input">
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
                            <select v-model="selectedCity" @change="fetchDistricts" class="order-form-select order-form-input">
                                <option v-for="city in cities" :key="city.code" :value="city.code">
                                    {{ city.name }}
                                </option>
                            </select>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Quận / Huyện
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <select v-model="selectedDistrict" @change="fetchWards" class="order-form-select order-form-input">
                                <option v-for="district in districts" :key="district.code" :value="district.code">
                                    {{ district.name }}
                                </option>
                            </select>
                        </div>
                        <div class="order-form-container">
                            <p class="order-form-title">
                                Phường / Xã
                                <span class="order-form-required">
                                    *
                                </span>
                            </p>
                            <select v-model="selectedWard" class="order-form-select order-form-input">
                                <option v-for="ward in wards" :key="ward.code" :value="ward.code">
                                    {{ ward.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Địa chỉ cụ thể
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <textarea class="order-form-area" rows="3"></textarea>
                    </div>
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Loại địa chỉ
                            <span class="order-form-required">
                                *
                            </span>
                        </p>
                        <select value="" class="order-form-select order-form-input">
                            <option value="1">Nhà riêng</option>
                            <option value="2">Văn phòng</option>
                        </select>
                    </div>
                    
                    <div class="order-form-container">
                        <p class="order-form-title">
                            Mã giảm giá (nếu có)
                        </p>
                        <div class="order-form-coupon-container">
                            <input type="text" class="order-form-coupon order-form-input">
                            <button class="order-form-btn">
                                Áp dụng mã
                            </button>
                        </div>
                    </div>
                    <!-- payment method -->
                    <h1 class="order-heading cart-heading">Phương thức thanh toán</h1>
                    <div class="order-payment">
                        <div class="order-form-radio-container">
                            <input type="radio" name="payment-method" class="order-form-radio"
                                v-model="selectedPaymentMethod" value="qrcode"
                            >
                            <span class="order-form-radio-text">
                                Mã QR
                            </span>
                        </div>
                        <div class="payment-method-content order-form-radio-content"
                            :class="{ 'hidden': selectedPaymentMethod !== 'qrcode' }"
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
                                v-model="selectedPaymentMethod" value="creditCard"
                            >
                            <span class="order-form-radio-text">
                                Thẻ tín dụng / thẻ ghi nợ
                            </span>
                        </div>
                        <div class="payment-method-content"
                            :class="{ 'hidden': selectedPaymentMethod !== 'creditCard' }"
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
                                v-model="selectedPaymentMethod" value="ewallet"
                            >
                            <span class="order-form-radio-text">
                                Ví điện tử
                            </span>
                        </div>
                        <div class="payment-method-content order-form-e-wallet-content"
                            :class="{ 'hidden': selectedPaymentMethod !== 'ewallet' }"
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
                                v-model="selectedPaymentMethod" value="cash"
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
                        <li v-for="cartDetail in cartDetails" :key="cartDetail" class="cart-item">
                            <div class="cart-item-content">
                                <div class="cart-item-left">
                                    <img :src="cartDetail.image" alt="" class="cart-item-img order-product-img">
                                    <div class="cart-item-product">
                                        <a href="" class="order-product-name cart-item-name">
                                            {{ cartDetail.name }}
                                        </a>
                                        <p class="order-product-price-curr cart-item-price-curr">
                                            {{ formatPrice(cartDetail.priceCurrent) }}<sup>đ</sup>
                                        </p>
                                        <p class="cart-item-price-old">
                                            {{ formatPrice(cartDetail.priceOld) }}<sup>đ</sup>
                                        </p>
                                    </div>
                                </div>
                                <div class="cart-item-right">
                                    <div class="cart-item-quantity">
                                        <span class="order-product-quantity">
                                            Số lượng: {{ cartDetail.quantity }}
                                        </span>
                                        <p class="order-product-type cart-item-type">
                                            Loại: {{ cartDetail.type }}
                                        </p>
                                    </div>
                                    <div class="cart-item-price">
                                        <p class="order-product-price-total cart-item-price-total">
                                            {{ formatPrice(cartDetail.total) }}<sup>đ</sup>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="order-right cart-right">
                    <span class="order-summary-text">
                        Tóm tắt đơn hàng
                    </span>
                    <div class="order-summary-item-total cart-summary-line-item">
                        <span>Các mặt hàng ({{ cartDetails.length }})</span>
                        <span>
                            {{ formatPrice(cart.subTotal) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-summary-line-item">
                        <span>Phí vận chuyển</span>
                        <span>
                            {{ formatPrice(cart.shippingFee) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-summary-line-item">
                        <span>
                            Thuế (VAT)
                        </span>
                        <span>
                            {{ formatPrice(cart.subTotal * 0.1) }}<sup>đ</sup>
                        </span>
                    </div>
                    <div class="cart-total cart-summary-line-item">
                        <span>Tổng cộng</span>
                        <span>
                            {{ formatPrice(cart.totalAmount) }}<sup>đ</sup>
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
import { formatPrice } from '@/helpers/helpers.js'
import { swalFire } from '@/helpers/swal.js'

export default {
    name: 'OrderForm',
    data() {
        return {
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
            selectedPaymentMethod: '',
            selectedEWallet: '', 
            eWallets: [
                { name: 'MOMO', image: 'https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png' },
                { name: 'VN-Pay', image: 'https://vinadesign.vn/uploads/thumbnails/800/2023/05/vnpay-logo-vinadesign-25-12-59-16.jpg' },
                { name: 'ShopeePay', image: 'https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ShopeePay-V.png' },
                { name: 'ZaloPay', image: 'https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png' },
                { name: 'Viettel Money', image: 'https://inkythuatso.com/uploads/thumbnails/800/2021/12/logo-viettelpay-inkythuatso-3-14-09-02-46.jpg' }
            ],
            // nếu chưa tạo đơn hàng
            cartDetails: [
                {
                    autoPartId: 'SP001',
                    name: "Macbook 12-inch Retina Display 1.2 GHz Dual-Core Intel Core M Processor",
                    image: "https://images.gr-assets.com/authors/1430676293p8/17212.jpg",
                    priceCurrent: 10000000,
                    priceOld: 12000000,
                    discount: 16.67,
                    rating: 5,
                    quantity: 1,
                    total: 10000000,
                    type: "Laptop",
                    stockQuantity: 5
                },
                {
                    autoPartId: 'SP002',
                    name: "Điện thoại Apple iPhone 13 Pro Max 256GB Chính hãng VN/A",
                    image: "https://images.gr-assets.com/authors/1615483024p5/676.jpg",
                    priceCurrent: 25000000,
                    priceOld: 27000000,
                    discount: 7.41,
                    rating: 4,
                    quantity: 2,
                    total: 50000000,
                    type: "Điện thoại",
                    stockQuantity: 0
                },
                {
                    autoPartId: 'SP003',
                    name: "Laptop Dell XPS 13 9310 Thin and Light Touchscreen Laptop",
                    image: "https://images.gr-assets.com/authors/1615483024p5/676.jpg",
                    priceCurrent: 22000000,
                    priceOld: 25000000,
                    discount: 12.00,
                    rating: 5,
                    quantity: 1,
                    total: 22000000,
                    type: "Laptop",
                    stockQuantity: 3
                },
                {
                    autoPartId: 'SP004',
                    name: "Tai nghe chống ồn Sony WH-1000XM4 Wireless Over-Ear Headphones",
                    image: "https://images.gr-assets.com/authors/1300822269p5/102824.jpg",
                    priceCurrent: 8000000,
                    priceOld: 9500000,
                    discount: 15.79,
                    rating: 4,
                    quantity: 3,
                    total: 24000000,
                    type: "Phụ kiện",
                    stockQuantity: 0
                },
                {
                    autoPartId: 'SP005',
                    name: "Máy tính bảng Samsung Galaxy Tab S6 Wi-Fi, Super AMOLED Display",
                    image: "https://images.gr-assets.com/authors/1430676293p8/17212.jpg",
                    priceCurrent: 12000000,
                    priceOld: 15000000,
                    discount: 20.00,
                    rating: 4,
                    quantity: 1,
                    total: 12000000,
                    type: "Máy tính bảng",
                    stockQuantity: 7
                },
                {
                    autoPartId: 'SP006',
                    name: "Đồng hồ thông minh Apple Watch Series 7 GPS 45mm",
                    image: "https://images.gr-assets.com/authors/1300822269p5/102824.jpg",
                    priceCurrent: 8000000,
                    priceOld: 9500000,
                    discount: 15.79,
                    rating: 5,
                    quantity: 2,
                    total: 16000000,
                    type: "Phụ kiện",
                    stockQuantity: 4
                }
            ]
            .filter(item => item.stockQuantity > 0),
            cart: {
                subTotal: 186000000,
                shippingFee: 5000000,
                totalAmount: 10000
            },
            cities: [], selectedCity: '',
            districts: [], selectedDistrict: '',
            wards: [], selectedWard: '',
            countdown: '15:00',
        }
    },
    created() {
        this.fetchCities();
        this.fetchLastPaymentSuccess();
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
        async fetchCities() {
            try {
                const response = await this.$request.get('https://provinces.open-api.vn/api/p/');
                this.cities = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchDistricts() {
            try {
                const response = await this.$request.get(`https://provinces.open-api.vn/api/p/${this.selectedCity}?depth=2`);
                this.districts = response.data.districts;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchWards() {
            try {
                const response = await this.$request.get(`https://provinces.open-api.vn/api/d/${this.selectedDistrict}?depth=2`);
                this.wards = response.data.wards;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchLastPaymentSuccess() {
            try {
                const response = await new Promise((resolve) => {
                    setTimeout(() => {
                        const data = {
                            id: 2,
                            orderId: 1002,
                            amount: 100.50,
                            currency: "VND",
                            payment_date: "2025-01-22T10:00:00Z",
                            status: "Pending",
                            transaction_id: "TXN0987654321",
                            notes: "Awaiting confirmation from the bank",
                            method: 'creditCard', // qrcode | creditCard | ewallet | cash

                            creditCard: {
                                token: "fake-token-123456",
                                cardNumber: "4111111111111111",
                                cvv: "123",
                                expiryDate: "2025-12",
                                cardholderName: "Lam Le",
                                postalCode: "100000"
                            },
                            // qrcode: {
                            //     image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/QR_code_for_mobile_English_Wikipedia.svg/330px-QR_code_for_mobile_English_Wikipedia.svg.png',
                            // },
                            // ewallet: {
                            //     provider: "ShopeePay",   // MOMO | VN-Pay | ShopeePay | ZaloPay | Viettel Money
                            //     walletId: "",
                            // },
                        };
                        resolve(data);
                    }, 1000);
                });
                this.payment = { ...this.payment, ...response };
                if (this.payment.method) {
                    this.selectedPaymentMethod = this.payment.method;
                    if (this.payment.ewallet) {
                        this.selectedEWallet = this.payment.ewallet.provider;
                    }
                }
            }
            catch (error) {
                console.error(error);
            }
        },
        async fetchPaymentQR() {
            try {
                const account_no = '9998886661';
                const amount = this.cart.totalAmount;
                const description = `Thanh toan don hang Ma ${this.order.id}`;
                const response = await this.$request.post(
                    'https://api.vietqr.io/v2/generate', 
                    {
                        accountNo: account_no,
                        accountName: 'LE LY LAM',
                        acqId: 970407,  // TCB
                        addInfo: description,
                        amount: amount.toString(),
                        template: 'qr_only',
                    },
                    {
                        headers: {
                            'x-client-id': process.env.VUE_APP_CLIENT_ID,
                            'x-api-key': process.env.VUE_APP_API_KEY,
                            'Content-Type': 'application/json',
                        },
                    },
                );
                this.payment.qrcode = {
                    image: response.data.data.qrDataURL,
                };
                this.startCountdown();
                this.checkPaymentStatus();
            } catch (error) {
                console.log(error);
            }
        },
        async save() {
            this.order.id = 999
            if (this.selectedPaymentMethod === 'qrcode') {
                if (this.payment.qrcode) {
                    swalFire('Thông báo', 'Vui lòng quét mã để hoàn tất giao dịch.', 'info')
                    return;
                }
                this.$swal({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng đợi trong giây lát khi chúng tôi đang lấy dữ liệu.',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        this.$swal.showLoading();
                    },
                });
                try {
                    if (!this.order.id) {
                        return;
                    }
                    await this.fetchPaymentQR();
                    swalFire('Thành công', 'Mã QR đã được tạo thành công. Vui lòng quét mã để hoàn tất giao dịch.', 'success')
                } catch (error) {
                    console.error(error);
                    swalFire('Lỗi', 'Đã xảy ra lỗi khi tạo mã QR. Vui lòng thử lại.', 'error')
                }
            }
        },
        async checkPaymentStatus() {
            const maxAttempts = 15 * 60 / 5;
            let attempts = 0;
            this.paymentCheckInterval = setInterval(async () => {
                try {
                    // const response = await this.$request.get();
                    const status = 'pending'; // Giả sử trạng thái ban đầu là 'pending'
                    if (status === 'success') {
                        clearInterval(this.paymentCheckInterval);
                        clearInterval(this.countdownInterval);
                        swalFire('Thanh toán hoàn tất!', 'Cảm ơn bạn đã mua hàng. Chúng tôi đã nhận được thanh toán của bạn.', 'success');
                        // chuyển hướng người dùng đến trang theo dõi đơn
                        return;
                    }
                    attempts++;
                    if (attempts >= maxAttempts) {
                        clearInterval(this.paymentCheckInterval);
                    }
                } catch (error) {
                    console.log(error);
                    clearInterval(this.paymentCheckInterval);
                }
            }, 5000);
        },
        onEWalletSelect(eWalletName) {
            this.selectedEWallet = eWalletName;
        },
        formatPrice, swalFire,
        startCountdown() {
            let time = 15 * 60;
            this.countdownInterval = setInterval(() => {
                if (time <= 0) {
                    clearInterval(this.countdownInterval);
                    swalFire('Hết thời gian', 'Thời gian quét mã QR đã hết. Vui lòng thử lại.', 'error');
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