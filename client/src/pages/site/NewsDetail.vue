<template>
    <div class="news-detail">
        <template v-if="isLoading">
            <div class="news-detail-heading">
                <h2 class="news-detail-title">
                    <SkeletonLoading height="32px" width="80%" />
                </h2>
                <div class="news-detail-single-header">
                    <div style="width: 52px;margin-bottom: 4px;">
                        <SkeletonLoading class="news-detail-single-header-img"
                            width="36px" height="36px" borderRadius="50%"
                        />
                    </div>
                    <div>
                        <SkeletonLoading width="200px" height="13px" />
                        <SkeletonLoading width="150px" height="13px" marginBottom="0" />
                    </div>
                </div>
            </div>
            <div class="news-detail-content">
                <SkeletonLoading height="500px" class="news-detail-content-main-img" />
                <div class="news-detail-content-container">
                    <div class="news-detail-content-container">
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading width="70%" height="14px" marginBottom="28px" />
                        
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading width="60%" height="14px" marginBottom="28px" />
                        
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading height="14px" />
                        <SkeletonLoading width="80%" height="14px" marginBottom="28px" />
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="news-detail-heading">
                <h2 class="news-detail-title">
                    {{ article.title }}
                </h2>
                <div class="news-detail-single-header">
                    <img :src="getImageUrl(article.creator?.avatar.path)" alt="" class="news-detail-single-header-img">
                    <span class="news-detail-single-header-text">
                        Tạo bởi
                        <a href="">
                            {{ article.creator?.name }}
                        </a>
                    </span>
                    <div class="news-detail-single-header-dot"></div>
                    <span class="news-detail-single-header-text">
                        {{ article.publish_date }}
                    </span>
                </div>
            </div>
            <div class="news-detail-content">
                <img :src="getImageUrl(thumbnail.path)" alt="" class="news-detail-content-main-img">
                <div v-html="enhancedContent" class="news-detail-content-container">
                </div>
            </div>
            <div class="news-detail-footer">
                <div class="news-detail-footer-left">
                    <!-- <a
                        v-for="(tag, index) in article.tags"
                        :key="index"
                        :href="tag.link"
                        class="button news-detail-footer-tag"
                    >
                        {{ tag.label }}
                    </a> -->
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
        </template>
    </div>
</template>

<script>
import SkeletonLoading from '@/components/SkeletonLoading.vue';
import apiService from '@/utils/apiService';
import { getImageUrl } from '@/utils/helpers';

export default {
    components: {
        SkeletonLoading
    },
    data() {
        return {
            article: {},
            isLoading: true,
            images: [], thumbnail: {},
        }
    },
    created() {
        this.fetchData();
    },
    computed: {
        enhancedContent() {
            if (!this.article || !this.article.content) return '';
            const parser = new DOMParser()
            const doc = parser.parseFromString(this.article.content, "text/html")
            const images = doc.querySelectorAll('img')
            for (let i = 0; i< images.length - 1; i++) {
                const currImg = images[i]
                const nextImg = images[i + 1]
                this.updateImageSrc(currImg)
                if (currImg.nextElementSibling === nextImg) {
                    this.updateImageSrc(nextImg)
                    const div = doc.createElement('div')
                    div.className = 'news-detail-content-double-img'
                    currImg.parentNode.insertBefore(div, currImg)
                    div.appendChild(currImg)
                    div.appendChild(nextImg)
                    i++
                }
            }
            this.updateImageSrc(images[images.length - 1])

            const bolds = doc.querySelectorAll('b')
            bolds.forEach((b) => {
                const h5 = doc.createElement('h5')
                h5.className = 'news-detail-content-text'
                h5.innerHTML = b.innerHTML
                b.parentNode.replaceChild(h5, b)
            })
            return doc.body.innerHTML
        }
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            try {
                const slug = this.$route.params.slug;
                const res = await apiService.articles.getBySlug(slug)

                this.article = res.data
                this.images = this.article.images
                this.thumbnail = this.images.find(image => image.is_thumbnail === true) || {};
            } catch (error) {
                console.error(error)
            } finally {
                this.isLoading = false;
            }
        },
        updateImageSrc(imgElement) {
            const alt = imgElement.getAttribute('alt');
            const path = this.images.find(img => img.filename === alt)?.path;
            if (path) {
                imgElement.setAttribute('src', this.getImageUrl(path));
                imgElement.className = 'news-detail-content-single-img'
            }
        },
        getImageUrl,
    },
}
</script>