<template>
    <div class="grid">
        <div class="cart">
            <h1 class="cart-heading">Tin tức tiêu biểu</h1>
            <div class="news">
                <ul class="news-list">
                    <li v-for="newsItem in news" :key="newsItem.id" class="news-item">
                        <router-link :to="`/tin-tuc/` + newsItem.id" class="news-item-container">
                            <div class="news-item-img-container">
                                <img :src="newsItem.image + Math.random()" alt="" class="news-item-img">
                            </div>
                            <div class="news-item-card">
                                <div class="news-item-card-category">
                                    {{ newsItem.category }}
                                </div>
                                <div class="news-item-card-time">
                                    {{ newsItem.date }}
                                </div>
                            </div>
                            <p class="news-item-title">
                                {{ newsItem.title }}
                            </p>
                            <span class="news-item-desc">
                                {{ newsItem.description }}
                            </span>
                        </router-link>
                    </li>
                </ul>
                <div v-if="isLoading" class="loading-indicator">
                    Đang tải...
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            news: [
                {
                    id: 1,
                    title: "Bộ lọc dầu động cơ",
                    category: "Hệ thống động cơ",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Bộ lọc dầu giúp loại bỏ cặn bẩn và tạp chất trong dầu động cơ, kéo dài tuổi thọ động cơ.",
                    date: "2025-01-24"
                },
                {
                    id: 2,
                    title: "Bộ phanh đĩa",
                    category: "Hệ thống phanh",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Bộ phanh đĩa chất lượng cao giúp tăng độ an toàn khi phanh, giảm mài mòn má phanh.",
                    date: "2025-02-10"
                },
                {
                    id: 3,
                    title: "Bugi đánh lửa",
                    category: "Hệ thống đánh lửa",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Bugi đánh lửa giúp tối ưu hóa quá trình đốt cháy nhiên liệu, nâng cao hiệu suất động cơ.",
                    date: "2025-03-15"
                },
                {
                    id: 4,
                    title: "Bộ lọc gió",
                    category: "Hệ thống hút gió",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Lọc gió giúp ngăn bụi bẩn và tạp chất xâm nhập vào buồng đốt, cải thiện hiệu suất nhiên liệu.",
                    date: "2025-04-05"
                },
                {
                    id: 5,
                    title: "Ắc quy ô tô",
                    category: "Hệ thống điện",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Ắc quy dung lượng cao, bền bỉ, cung cấp nguồn điện ổn định cho xe.",
                    date: "2025-05-12"
                },
                {
                    id: 6,
                    title: "Lốp xe Michelin",
                    category: "Hệ thống bánh xe",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Lốp xe Michelin với độ bám đường cao, phù hợp với nhiều điều kiện địa hình.",
                    date: "2025-06-01"
                },
                {
                    id: 7,
                    title: "Má phanh cao cấp",
                    category: "Hệ thống phanh",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Má phanh chất lượng cao, giúp tăng hiệu suất phanh và giảm hao mòn đĩa phanh.",
                    date: "2025-06-15"
                },
                {
                    id: 8,
                    title: "Bộ giảm xóc thể thao",
                    category: "Hệ thống treo",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Bộ giảm xóc giúp xe vận hành êm ái, tăng độ ổn định khi lái.",
                    date: "2025-07-01"
                },
                {
                    id: 9,
                    title: "Đèn pha LED",
                    category: "Hệ thống chiếu sáng",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Đèn pha LED siêu sáng, tiết kiệm điện và tuổi thọ cao.",
                    date: "2025-07-20"
                },
                {
                    id: 10,
                    title: "Cảm biến áp suất lốp",
                    category: "Hệ thống an toàn",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Cảm biến theo dõi áp suất lốp theo thời gian thực, giúp đảm bảo an toàn khi lái xe.",
                    date: "2025-08-05"
                },
                {
                    id: 11,
                    title: "Màn hình Android cho ô tô",
                    category: "Hệ thống giải trí",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Màn hình Android 10 inch với nhiều tính năng giải trí và hỗ trợ kết nối Bluetooth.",
                    date: "2025-08-25"
                },
                {
                    id: 12,
                    title: "Camera hành trình",
                    category: "Hệ thống giám sát",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Camera hành trình ghi hình chất lượng cao, hỗ trợ chế độ ban đêm và cảm biến va chạm.",
                    date: "2025-09-10"
                },
                {
                    id: 13,
                    title: "Gạt mưa Bosch",
                    category: "Hệ thống kính lái",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Gạt mưa Bosch với thiết kế tối ưu, đảm bảo tầm nhìn rõ ràng trong mọi điều kiện thời tiết.",
                    date: "2025-09-30"
                },
                {
                    id: 14,
                    title: "Cảm biến lùi",
                    category: "Hệ thống hỗ trợ lái",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Cảm biến lùi giúp dễ dàng đỗ xe và tránh va chạm khi lùi xe.",
                    date: "2025-10-15"
                },
                {
                    id: 15,
                    title: "Bộ đề nổ từ xa",
                    category: "Hệ thống điện",
                    image: "https://picsum.photos/200/300?random=",
                    description: "Bộ đề nổ từ xa giúp bạn khởi động xe dễ dàng mà không cần vào trong xe.",
                    date: "2025-10-30"
                }
            ],
            currentPage: 0,
            isLoading: false,
            hasMore: true
        }
    },
    methods: {
        handleScroll() {
            const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            if (documentHeight - (scrollTop + windowHeight) < 800 && !this.isLoading && this.hasMore) {
                this.loadMore();
            }
        },
        async loadMore() {
            this.isLoading = true;
            try {
                const newNews = await this.fetchNews();
                if (newNews.length === 0) {
                    this.hasMore = false;
                } else {
                    this.news = [...this.news, ...newNews];
                }
            } catch (error) {
                console.error('Lỗi khi tải tin tức:', error);
            } finally {
                this.isLoading = false;
            }
        },
        fetchNews() {
            // Giả lập API trả về 5 item mới sau 1 giây
            return new Promise(resolve => {
                setTimeout(() => {
                    const newItems = [];
                    const startId = this.news.length + 1;
                    for (let i = 0; i < 6; i++) {
                        const id = startId + i;
                        newItems.push({
                            id: id,
                            title: `Tin tức mới ${id}`,
                            category: `Danh mục ${id % 5 + 1}`,
                            image: "https://picsum.photos/200/300?random=",
                            description: `Mô tả cho tin tức mới ${id}`,
                            date: `2025-${String((id % 12) + 1).padStart(2, '0')}-${String((id % 28) + 1).padStart(2, '0')}`
                        });
                    }
                    resolve(newItems);
                }, 1000);
            });
        }
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    }
}
</script>

<style scoped>
.loading-indicator {
    text-align: center;
    padding: 1rem;
    color: #666;
    font-size: 1.2rem;
}
</style>