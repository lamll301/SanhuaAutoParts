
import Swiper from 'swiper/bundle';
import { onMounted } from 'vue';

export default function useSwiper() {

    onMounted(() => {
        // trang chủ
        initSwiper(1, '.swiper__slider', '.swiper__slider-btn-next', '.swiper__slider-btn-prev', {
            delay: 5000,
            disableOnInteraction: false,
        });
        initSwiper(6, '.swiper__brew-product', '.swiper__brew-product-btn-next', '.swiper__brew-product-btn-prev');
        initSwiper(9, '.swiper__online-store-best-sellers', '.swiper__online-store-best-sellers-btn-next', '.swiper__online-store-best-sellers-btn-prev');
        initSwiper(9, '.swiper__online-store-newest', '.swiper__online-store-newest-btn-next', '.swiper__online-store-newest-btn-prev');
        initSwiper(9, '.swiper__online-store-on-sale', '.swiper__online-store-on-sale-btn-next', '.swiper__online-store-on-sale-btn-prev');
        initSwiper(9, '.swiper__online-store-seasonal', '.swiper__online-store-seasonal-btn-next', '.swiper__online-store-seasonal-btn-prev');
        initSwiper(6, '.swiper__stories', '.swiper__stories-btn-next', '.swiper__stories-btn-prev');
    });

    const initSwiper = (slidesPerView, containerClass, nextBtnClass, prevBtnClass, autoplayConfig = null) => {
        const swiperConfig = {
            slidesPerView: slidesPerView,
            navigation: {
                nextEl: nextBtnClass,
                prevEl: prevBtnClass,
            },
        };
        if (autoplayConfig) {
            swiperConfig.autoplay = autoplayConfig;
        }
        const swiper = new Swiper(containerClass, swiperConfig);
        swiper.on('slideChange', function () {
            const curr = swiper.activeIndex;
            const prev = swiper.navigation.prevEl;
            const next = swiper.navigation.nextEl;
            if (curr === 0) {
                prev.classList.add('hidden');
            } else {
                prev.classList.remove('hidden');
            }
            if (curr + swiper.params.slidesPerView >= swiper.slides.length) {
                next.classList.add('hidden');
            } else {
                next.classList.remove('hidden');
            }
        })
    };

    return {
        initSwiper
    };
}
