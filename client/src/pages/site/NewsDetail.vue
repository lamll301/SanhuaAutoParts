<template>
    <div class="news-detail">
        <template v-if="isLoading">
            <div class="spinner-container">
                <div class="spinner"></div>
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
import { getImageUrl } from '@/utils/helpers';
import { articleApi } from '@/api';

export default {
    components: {},
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
                const res = await articleApi.getBySlug(slug)

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

<style scoped>
.spinner-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60vh;
  width: 100%;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 5px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top-color: #0073b1;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>