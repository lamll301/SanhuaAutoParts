<template>
    <div class="news-detail">
        <div class="news-detail-heading">
            <h2 class="news-detail-title">
                {{ newsDetail.title }}
            </h2>
            <div class="news-detail-single-header">
                <img :src="newsDetail.authorAvatar" alt="" class="news-detail-single-header-img">
                <span class="news-detail-single-header-text">
                    By
                    <a href="">
                        {{ newsDetail.author }}
                    </a>
                </span>
                <div class="news-detail-single-header-dot"></div>
                <span class="news-detail-single-header-text">
                    {{ newsDetail.timeAgo }}
                </span>
                <div class="news-detail-single-header-dot"></div>
                <span class="news-detail-single-header-text">
                    {{ newsDetail.readTime }}
                </span>
            </div>
        </div>
        <div class="news-detail-content">
            <img :src="newsDetail.mainImage" alt="" class="news-detail-content-main-img">
            <div class="news-detail-content-container">
                <div v-for="(item, index) in newsDetail.content" :key="index">
                    <template v-if="item.type === 'heading'">
                        <h5 class="news-detail-content-text">{{ item.text }}</h5>
                    </template>
                    <template v-else-if="item.type === 'paragraph'">
                        <p class="news-detail-content-sub-text">{{ item.text }}</p>
                    </template>
                    <template v-else-if="item.type === 'image'">
                        <img :src="item.src" alt="Content Image" class="news-detail-content-single-img">
                    </template>
                    <template v-else-if="item.type === 'doubleImage'">
                        <div class="news-detail-content-double-img">
                            <img
                            v-for="(img, idx) in item.images"
                            :key="idx"
                            :src="img"
                            alt="Content Image"
                            class="news-detail-content-single-img"
                            >
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div class="news-detail-footer">
            <div class="news-detail-footer-left">
                <a
                    v-for="(tag, index) in newsDetail.tags"
                    :key="index"
                    :href="tag.link"
                    class="button news-detail-footer-tag"
                >
                    {{ tag.label }}
                </a>
            </div>
            <div class="news-detail-footer-right">
                <a href="" class="news-detail-footer-link">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="" class="news-detail-footer-link">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="" class="news-detail-footer-link">
                    <i class="fa-solid fa-link"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            newsDetail: {
                category: "Phụ Tùng Ô Tô",
                title: "Top phụ tùng ô tô 2024: Lựa chọn hàng đầu cho xe của bạn",
                author: "Nguyễn Văn A",
                authorLink: "#",
                authorAvatar: "https://picsum.photos/200",
                timeAgo: "2 giờ trước",
                readTime: "8 phút đọc",
                mainImage: "https://picsum.photos/500/200",
                content: [
                    {
                        type: "heading",
                        text: "Giới thiệu các phụ tùng ô tô mới"
                    },
                    {
                        type: "paragraph",
                        text: "Phụ tùng ô tô của chúng tôi được sản xuất với chất lượng hàng đầu, đảm bảo an toàn và hiệu suất tối ưu. Với nhiều năm kinh nghiệm trong ngành, chúng tôi cam kết cung cấp những sản phẩm đáng tin cậy cho mọi loại xe."
                    },
                    {
                        type: "paragraph",
                        text: "Sản phẩm của chúng tôi không chỉ mang lại sự an tâm cho khách hàng mà còn giúp nâng cao hiệu suất hoạt động của xe. Chúng tôi luôn cập nhật công nghệ mới để mang đến trải nghiệm tuyệt vời nhất."
                    },
                    {
                        type: "image",
                        src: "https://picsum.photos/300/200"
                    },
                    {
                        type: "heading",
                        text: "Phụ tùng nổi bật của tháng"
                    },
                    {
                        type: "paragraph",
                        text: "Với quy trình kiểm định nghiêm ngặt, mỗi sản phẩm đều được kiểm tra chất lượng trước khi đến tay khách hàng. Đảm bảo an toàn cho người lái và bảo vệ động cơ xe."
                    },
                    {
                        type: "paragraph",
                        text: "Cam kết của chúng tôi là mang đến giá trị vượt trội với giá cả cạnh tranh, giúp khách hàng tối ưu hóa chi phí bảo trì và nâng cao hiệu suất xe."
                    },
                    {
                        type: "doubleImage",
                        images: [
                            "https://picsum.photos/200",
                            "https://picsum.photos/200"
                        ]
                    },
                    {
                        type: "paragraph",
                        text: "Sản phẩm của chúng tôi được thiết kế với công nghệ hiện đại, phù hợp với mọi dòng xe từ phổ thông đến cao cấp. Đảm bảo sự bền bỉ và hiệu suất ổn định theo thời gian."
                    },
                    {
                        type: "paragraph",
                        text: "Hãy lựa chọn phụ tùng ô tô của chúng tôi để trải nghiệm sự khác biệt về chất lượng và dịch vụ. Chúng tôi luôn đồng hành cùng bạn trên mọi chặng đường."
                    }
                ],
                tags: [
                    { label: "Phụ tùng", link: "#" },
                    { label: "Ô tô", link: "#" }
                ]
            }
        }
    }
}
</script>