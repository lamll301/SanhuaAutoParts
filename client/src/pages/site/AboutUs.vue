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
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" alt="" class="about-us-news-img">
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
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" alt="" class="about-us-news-img">
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
                        Sanhua ứng dụng công nghệ tiên tiến như IoT, AI và hệ thống quản lý kho thông minh để tối ưu hóa quy trình và nâng cao chất lượng sản phẩm. Chúng tôi sử dụng máy móc hiện đại để kiểm định chất lượng và hỗ trợ khách hàng 24/7, mang đến trải nghiệm mua sắm thuận tiện và đáng tin cậy.
                    </p>
                    <a href="#" class="about-us-theme-link about-us-news-link">
                        <span>Xem chi tiết</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
                <div class="about-us-impact about-us-media-theme about-us-media-content">
                    <h2 class="about-us-media-theme-title">Tác động</h2>
                    <p class="about-us-media-theme-text">Sanhua cam kết phát triển bền vững bằng cách sử dụng bao bì thân thiện môi trường và tái chế phụ tùng cũ. Chúng tôi cũng hỗ trợ cộng đồng thông qua các chương trình đào tạo kỹ thuật viên và tài trợ phụ tùng miễn phí cho hộ gia đình khó khăn.</p>
                    <a href="#" class="about-us-theme-link about-us-news-link">
                        <span>Xem chi tiết</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <h2 class="about-us-title">Tin Tức Mới Nhất</h2>
            <div class="about-us-media">
                <a v-for="newsDetail in news" :key="newsDetail" href="" class="about-us-latest-news-link">
                    <div class="about-us-news about-us-latest-news">
                        <img :src="newsDetail.image" alt="" class="about-us-news-img about-us-latest-news-img">
                        <div class="about-us-news-body about-us-latest-news-body">
                            <div class="news-item-card about-us-latest-news-tag-date">
                                <div class="news-item-card-category">
                                    {{ newsDetail.category }}
                                </div>
                                <div class="news-item-card-time">
                                    {{ newsDetail.date }}
                                </div>
                            </div>
                            <h3 class="about-us-news-title">
                                {{ newsDetail.title }}
                            </h3>
                            <p class="about-us-news-desc">
                                {{ newsDetail.description }}
                            </p>
                        </div>
                        <a href="#" class="about-us-news-link">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </a>
            </div>
            <a href="#" class="about-us-news-link about-us-latest-news-more">
                <span>Xem thêm</span>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="about-us-footer">
        <h2 class="about-us-footer-title about-us-title">Financials & Business Metrics</h2>
        <a href="" class="button about-us-footer-link">
            Xem chi tiết
            <i class="fa-solid fa-right-long"></i>
        </a>
    </div>
</template>

<script>
import { gsap } from "gsap";

export default {
    data() {
        return {
            progressAngles: [0, 0, 0],
            circles: [
                {
                    value: 2.1,
                    sign: "B",
                    text: "Approximate number of live listings",
                },
                {
                    value: 133,
                    sign: "M",
                    text: "Active buyers worldwide",
                },
                {
                    value: 190,
                    sign: "+",
                    text: "Markets around the world",
                }
            ],
            news: [
                {
                    category: "Công nghệ",
                    date: "15/7/2024",
                    title: "Ra mắt hệ thống AI dự đoán hỏng hóc",
                    description: "Hệ thống AI tiên tiến của chúng tôi được thiết kế để phát hiện và dự đoán các lỗi có thể xảy ra trong quá trình vận hành của thiết bị. Công nghệ này không chỉ giúp giảm thiểu thời gian chết mà còn nâng cao hiệu suất vận hành bằng cách cung cấp các cảnh báo kịp thời và gợi ý các biện pháp khắc phục, bao gồm đề xuất phụ tùng thay thế chính xác, giúp giảm thiểu chi phí bảo trì và tối ưu hóa hiệu quả công việc.",
                    image: "https://picsum.photos/200/300",
                    link: "#"
                },
                {
                    category: "Kinh doanh",
                    date: "12/2/2025",
                    title: "Kế hoạch mở rộng thị trường tại châu Á",
                    description: "Với mục tiêu tăng trưởng mạnh mẽ trong năm nay, chúng tôi đã lên kế hoạch mở rộng thị trường tại các quốc gia châu Á, đặc biệt là ở các thị trường mới nổi như Ấn Độ, Trung Quốc và Việt Nam. Việc mở rộng này không chỉ giúp nâng cao sự hiện diện toàn cầu của công ty mà còn mang lại những cơ hội hợp tác chiến lược mới, tạo điều kiện phát triển mạnh mẽ hơn trong ngành công nghiệp công nghệ. Những bước đi này sẽ giúp công ty tiếp cận được khách hàng tiềm năng và tối ưu hóa nguồn lực.",
                    image: "https://picsum.photos/200/300",
                    link: "#"
                },
                {
                    category: "Sản phẩm",
                    date: "10/1/2025",
                    title: "Ra mắt sản phẩm điện thoại thông minh mới",
                    description: "Chúng tôi tự hào giới thiệu sản phẩm điện thoại thông minh mới nhất của mình, được trang bị những tính năng công nghệ tiên tiến, thiết kế tinh tế và màn hình siêu nét. Sản phẩm này không chỉ có hiệu suất mạnh mẽ mà còn mang lại những trải nghiệm tuyệt vời cho người dùng nhờ vào khả năng xử lý đa tác vụ nhanh chóng, thời lượng pin dài lâu và tính năng camera đột phá. Chắc chắn rằng sản phẩm mới này sẽ làm hài lòng ngay cả những khách hàng khó tính nhất và là lựa chọn lý tưởng cho người dùng yêu thích công nghệ.",
                    image: "https://picsum.photos/200/300",
                    link: "#"
                }
            ],
            
        }
    },
    methods: {
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