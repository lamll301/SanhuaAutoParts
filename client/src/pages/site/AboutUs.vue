<template>
    <div class="about-us">
        <div class="about-us-heading">
            <div class="about-us-theme">
                <h1>Về Chúng Tôi</h1>
                <div class="about-us-theme-1"></div>
                <div class="about-us-theme-1">
                    <div class="about-us-theme-2"></div>
                    <div class="about-us-theme-3"></div>
                </div>
            </div>
            <div class="about-us-heading-video" data-video="https://player.vimeo.com/external/389123523.hd.mp4?s=59807ffb60cce6ce7e81a36e376a0276ab1bcfa0&amp;profile_id=175">
                <video autoplay loop muted playsinline>
                    <source src="https://player.vimeo.com/external/389123523.hd.mp4?s=59807ffb60cce6ce7e81a36e376a0276ab1bcfa0&amp;profile_id=175" type="video/mp4">
                </video>
            </div>
        </div>
        <div class="about-us-body">
            <h2 class="about-us-title">Kết nối cộng đồng yêu xe - Nâng tầm dịch vụ sửa chữa</h2>
            <p class="about-us-desc-news about-us-desc">
                Tại Sanhua, chúng tôi mang đến giải pháp toàn diện về phụ tùng chính hãng cho mọi dòng xe. Với mạng lưới cung ứng rộng khắp và công nghệ quản lý hiện đại, chúng tôi cam kết mang lại trải nghiệm mua sắm tốt nhất cho khách hàng và cơ hội phát triển cho các đối tác kinh doanh.
            </p>
            <div class="about-us-media">
                <div class="about-us-news about-us-media-content">
                    <img src="../../assets/images/520-001-sanhua-vietnam.jpg" alt="" class="about-us-news-img">
                    <div class="about-us-news-body">
                        <p class="about-us-news-tag">
                            Dành cho đại lý
                        </p>
                        <h3 class="about-us-news-title">
                            Hệ thống phân phối chuyên nghiệp
                        </h3>
                        <p class="about-us-news-desc">
                            Cung cấp giải pháp kinh doanh phụ tùng trọn gói với chính sách chiết khấu ưu đãi, hỗ trợ logistics và quản lý kho thông minh. Chúng tôi đồng hành cùng sự phát triển của đối tác.
                        </p>
                    </div>
                    <a href="#" class="about-us-news-link">
                        <span>Xem thêm</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
                <div class="about-us-news about-us-media-content">
                    <img src="../../assets/images/tqc-tien-_637902157324461079.jpg" alt="" class="about-us-news-img">
                    <div class="about-us-news-body">
                        <p class="about-us-news-tag">
                            Dành cho gara
                        </p>
                        <h3 class="about-us-news-title">
                            Nguồn cung ứng đáng tin cậy
                        </h3>
                        <p class="about-us-news-desc">
                            Hệ thống đặt hàng 24/7 với hơn 50,000 SKU luôn sẵn kho. Bảo hành chính hãng và hỗ trợ kỹ thuật chuyên sâu cho mọi loại xe.
                        </p>
                    </div>
                    <a href="#" class="about-us-news-link">
                        <span>Xem thêm</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <!-- video -->
            <h2 class="about-us-title">Hoạt Động của Chúng Tôi</h2>
            <p class="about-us-desc">Xưởng sản xuất và hệ thống kiểm định chất lượng tiêu chuẩn quốc tế</p>
            <div class="about-us-video-wrapper">
                <iframe src="https://www.youtube.com/embed/MBZfHFRD9GQ" 
                    title="Video Giới Thiệu" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            <!--  -->
            <div class="about-us-circle" ref="aboutUsCircle">
                <h2 class="about-us-circle-title">Quy mô và phạm vi</h2>
                <div class="about-us-circle-body">
                    <div v-for="(circle, index) in circles" :key="index" class="about-us-circle-item">
                        <div class="about-us-circle-content"
                            :style="{
                                background: `conic-gradient(#01718f 0deg, #01718f ${progressAngles[index]}deg, #ccc ${progressAngles[index]}deg 360deg)`
                            }"
                        >
                        <div class="about-us-circle-inner">
                            <span class="about-us-circle-val" :ref="'circleValue' + index">0</span>
                            <span class="about-us-circle-sign">{{ circle.sign }}</span>
                        </div>
                        </div>
                        <p class="about-us-circle-type">
                            {{ circle.text }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="about-us-media about-us-leader">
                <img src="../../assets/images/about-us-leader.jpeg" alt="" class="about-us-leader-img">
                <div class="about-us-leader-body">
                    <h2 class="about-us-leader-title">
                        Đội ngũ của chúng tôi
                    </h2>
                    <p class="about-us-leader-text">
                        Gặp gỡ các nhà lãnh đạo tại Sanhua, những người đang định hình tầm nhìn cho công ty của chúng tôi và thúc đẩy tương lai của ngành công nghiệp ô tô.
                    </p>
                    <a href="#" class="about-us-leader-link about-us-news-link">
                        <span>Xem thêm</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <div class="about-us-media">
                <div class="about-us-tech about-us-media-theme about-us-media-content">
                    <h2 class="about-us-media-theme-title">Công Nghệ</h2>
                    <p class="about-us-media-theme-text">
                        Chúng tôi ứng dụng công nghệ hiện đại để tối ưu hóa quy trình từ tìm kiếm, đặt hàng đến giao nhận. Hệ thống thông minh, tự động và đồng bộ giúp khách hàng dễ dàng tiếp cận sản phẩm chính xác, nhanh chóng và trải nghiệm mua sắm hiệu quả, chuyên nghiệp.
                    </p>
                    <router-link to="/tin-tuc" class="about-us-theme-link about-us-news-link">
                        <span>Xem chi tiết</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </router-link>
                </div>
                <div class="about-us-impact about-us-media-theme about-us-media-content">
                    <h2 class="about-us-media-theme-title">Tác động</h2>
                    <p class="about-us-media-theme-text">
                        Sanhua góp phần nâng cao hiệu quả vận hành cho các gara và đại lý bằng việc cung cấp phụ tùng chính xác, nhanh chóng, giá hợp lý. Hệ thống giúp giảm thời gian chờ, tối ưu chi phí và cải thiện chất lượng dịch vụ sửa chữa ô tô trên toàn quốc.
                    </p>
                    <router-link to="/tin-tuc" class="about-us-theme-link about-us-news-link">
                        <span>Xem chi tiết</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </router-link>
                </div>
            </div>
            <h2 class="about-us-title">Tin Tức Mới Nhất</h2>
            <div class="about-us-media">
                <router-link v-for="article in articles.slice(0, 3)" :key="article.id" :to="`/tin-tuc/${article.slug}`" class="about-us-latest-news-link">
                    <div class="about-us-news about-us-latest-news">
                        <img :src="getImageUrl(article.images[0].path)" alt="" class="about-us-news-img about-us-latest-news-img">
                        <div class="about-us-news-body about-us-latest-news-body">
                            <div class="news-item-card about-us-latest-news-tag-date">
                                <div class="news-item-card-category">
                                    {{ article.creator?.name || 'Sanhua' }}
                                </div>
                                <div class="news-item-card-time">
                                    {{ article.publish_date }}
                                </div>
                            </div>
                            <h3 class="about-us-news-title">
                                {{ article.title }}
                            </h3>
                            <p class="about-us-news-desc">
                                {{ article.highlight }}
                            </p>
                        </div>
                        <a href="#" class="about-us-news-link">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </router-link>
            </div>
            <router-link to="/tin-tuc" class="about-us-news-link about-us-latest-news-more">
                <span>Xem thêm</span>
                <i class="fa-solid fa-chevron-right"></i>
            </router-link>
        </div>
    </div>
    <div class="about-us-footer">
        <h2 class="about-us-footer-title about-us-title">Tài chính và Chỉ số kinh doanh</h2>
        <a href="" class="button about-us-footer-link">
            Xem chi tiết
            <i class="fa-solid fa-right-long"></i>
        </a>
    </div>
</template>

<script>
import { gsap } from "gsap";
import { getImageUrl } from "@/utils/helpers";
import { articleApi } from "@/api";

export default {
    data() {
        return {
            progressAngles: [0, 0, 0],
            circles: [
                {
                    value: 20,
                    sign: "K",
                    text: "Số lượng sản phẩm đa dạng, phong phú",
                },
                {
                    value: 63,
                    sign: "",
                    text: "Tỉnh thành phủ sóng giao hàng toàn quốc",
                },
                {
                    value: 5000,
                    sign: "+",
                    text: "Gara và đại lý tin dùng trên toàn quốc",
                }
            ],
            articles: [],
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        getImageUrl,
        async fetchData() {
            try {
                const res = await articleApi.getByCategory('tin-cong-ty');
                this.articles = res.data;
            } catch (e) {
                console.error(e);
            }
        },
        startAnimationWhenVisible(element, callback) {
            if (!element) return;
            const observer = new IntersectionObserver((entries, observer) => {
                if (entries[0].isIntersecting) {
                    callback();
                    observer.disconnect();
                }
            }, { threshold: 0.5 });
            observer.observe(element);
        },
        animateCircleValues() {
            this.circles.forEach((item, index) => {
                const valueRef = this.$refs["circleValue" + index][0];
                let progressObj = { val: 0 };
                gsap.to(progressObj, {
                    val: item.value,
                    duration: 3,
                    ease: "power1.out",
                    onUpdate: () => {
                        const progress = progressObj.val / item.value;
                        valueRef.innerText = Math.round(progressObj.val);
                        this.progressAngles[index] = progress * 360;
                        this.$forceUpdate();
                    },
                });
            });
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.startAnimationWhenVisible(this.$refs.aboutUsCircle, this.animateCircleValues);
        });
    }
}
</script>

<style scoped>
.about-us-circle-inner {
    width: 144px;
    height: 144px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
}
</style>