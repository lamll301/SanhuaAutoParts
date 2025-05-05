<template>
    <div class="grid">
        <div class="cart">
            <h1 class="cart-heading">Tin tức tiêu biểu</h1>
            <template v-if="isLoading">
                <div class="news">
                    <ul class="news-list">
                        <li v-for="i in 9" :key="i" class="news-item">
                            <SkeletonLoading height="256px" />
                            <SkeletonLoading 
                                :width="'160px'"
                                :height="'16px'"
                                :borderRadius="'4px'"
                            />
                            <SkeletonLoading 
                                :width="'100%'"
                                :height="'20px'"
                                :borderRadius="'4px'"
                            />
                            <SkeletonLoading 
                                :width="'80%'"
                                :height="'20px'"
                                :borderRadius="'4px'"
                            />
                            <SkeletonLoading 
                                :width="'70%'"
                                :height="'14px'"
                                :borderRadius="'4px'"
                            />
                        </li>
                    </ul>
                </div>

                <ul class="pagination category-pagination">
                    <li v-for="i in 8" :key="i" class="page-item category-page-item">
                        <SkeletonLoading height="40px" width="40px" borderRadius="50%" />
                    </li>
                </ul>
            </template>
            <template v-else>
                <div class="news">
                    <ul class="news-list">
                        <li v-for="article in articles" :key="article.id" class="news-item">
                            <router-link :to="`/tin-tuc/` + article.slug" class="news-item-container">
                                <div class="news-item-img-container">
                                    <img :src="getImageUrl(article.images[0].path)" alt="" class="news-item-img">
                                </div>
                                <div class="news-item-card">
                                    <div class="news-item-card-category">
                                        {{ article.creator.name }}
                                    </div>
                                    <div class="news-item-card-time">
                                        {{ article.publish_date }}
                                    </div>
                                </div>
                                <p class="news-item-title">
                                    {{ article.title }}
                                </p>
                                <span class="news-item-desc">
                                    {{ article.highlight }}
                                </span>
                            </router-link>
                        </li>
                    </ul>
                </div>
                <SitePagination :totalPages="totalPages" :currentPage="currentPage"/>
            </template>
        </div>
    </div>
</template>

<script>
import { getImageUrl } from '@/utils/helpers';
import apiService from '@/utils/apiService';
import SitePagination from '@/components/SitePagination.vue';
import SkeletonLoading from '@/components/SkeletonLoading.vue';

export default {
    components: {
        SitePagination, SkeletonLoading
    },
    watch: {
        '$route': {
            handler: 'fetchData',
            immediate: true,
            deep: true,
        },
    },
    data() {
        return {
            isLoading: true, totalPages: 0, currentPage: 1,
            articles: [],
        }
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            try {
                const req = [
                    apiService.articles.getPublished({ page: this.$route.query.page }),
                ];

                const res = await Promise.all(req)
                this.articles = res[0].data.data;
                this.totalPages = Math.ceil(res[0].data.pagination.total / res[0].data.pagination.per_page);
                this.currentPage = res[0].data.pagination.current_page;
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        getImageUrl,
    },
}
</script>