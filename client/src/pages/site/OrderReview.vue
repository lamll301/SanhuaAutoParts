<template>
    <div class="grid">
        <div class="settings">
            <h1 class="settings-heading">
                Đánh giá sản phẩm
            </h1>
            <div class="settings-my-profile">
                <div class="order-form-container">
                    <p class="order-form-title">
                        Chọn sản phẩm cần đánh giá
                    </p>
                    <select class="order-form-select order-form-input" v-model="selectedProduct" v-bind:class="{'is-invalid': errors.selectedProduct}" @blur="validate()">
                        <option value="" selected disabled>Chọn sản phẩm</option>
                        <option v-for="detail in details" :key="detail.id" :value="detail.product_id">
                            {{ detail.product.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.selectedProduct">{{ errors.selectedProduct }}</div>
                </div>
                <div class="order-form-container">
                    <p class="order-form-title">
                        Đánh giá của bạn
                    </p>
                    <div class="rating-stars">
                        <i v-for="star in 5" :key="star" class="fas fa-star star" 
                            :class="{ 'active': star <= (hoverRating || rating) }"
                            @click="setRating(star)"
                            @mouseover="hoverRating = star"
                            @mouseleave="hoverRating = null"
                        ></i>
                    </div>
                    <input type="hidden" v-model="rating" required v-bind:class="{'is-invalid': errors.rating}" @blur="validate()">
                    <div class="invalid-feedback" v-if="errors.rating">{{ errors.rating }}</div>
                </div>
                <div class="order-form-container">
                    <p class="order-form-title">
                        Nội dung đánh giá
                    </p>
                    <textarea class="order-form-area" placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm" rows="3" v-model="reviewContent"
                    v-bind:class="{'is-invalid': errors.reviewContent}" @blur="validate()">
                    </textarea>
                    <div class="invalid-feedback" v-if="errors.reviewContent">{{ errors.reviewContent }}</div>
                </div>
                <div class="order-form-container">
                    <p class="order-form-title">
                        Hình ảnh đánh giá
                    </p>
                    <div class="image-upload">
                        <div class="upload-btn" @click="triggerFileInput">
                            <i class="fas fa-camera"></i>
                            <span>Thêm ảnh</span>
                            <input type="file" accept="image/*" multiple style="display: none;" ref="fileInput" @change="handleImageUpload">
                        </div>
                        <div v-for="(image, index) in previewImages" :key="index" class="preview-image">
                            <img :src="image.url" alt="Preview">
                            <div class="remove-image" @click="removeImage(index)">&times;</div>
                        </div>
                    </div>
                </div>
                <div class="settings-my-profile-btn">
                    <button class="settings-btn" @click="save">
                        Gửi
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reviewApi } from '@/api';
import { useReviewStore } from '@/stores/review';

export default {
    setup() {
        const reviewStore = useReviewStore();
        return {
            reviewStore,
        }
    },
    mounted() {
        this.details = this.reviewStore.review?.details?.filter(detail => !detail.isReviewed) || [];
    },
    beforeRouteEnter(to, from, next) {
        const token = localStorage.getItem('token');
        if (!token) {
            next({ name: 'NotFound' });
            return;
        }
        next();
    },
    beforeUnmount() {
        this.previewImages.forEach(image => {
            URL.revokeObjectURL(image.url);
        });
    },
    async created() {
        const review = this.reviewStore.review;
        if (Object.keys(review).length === 0) {
            await this.$swal.fire(
                'Chưa chọn đơn hàng!',
                'Vui lòng chọn đơn hàng để có thể gửi đánh giá.',
                'warning'
            );
            this.$router.push({ name: 'orderTracking', query: { action: 'filterCompleted' } });
        }
    },
    data() {
        return {
            rating: 0,
            hoverRating: null,
            reviewContent: '',
            previewImages: [],
            details: [],
            errors: {
                selectedProduct: '',
                rating: '',
                reviewContent: '',
            },
            selectedProduct: '',
        }
    },
    methods: {
        setRating(star) {
            this.rating = star;
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        handleImageUpload(event) {
            const files = event.target.files;
            const newImages = Array.from(files).map(file => ({
                file,
                url: URL.createObjectURL(file)
            }));
            this.previewImages = [...this.previewImages, ...newImages];
        },
        removeImage(index) {
            URL.revokeObjectURL(this.previewImages[index].url);
            this.previewImages.splice(index, 1);
        },
        collectData() {
            const formData = new FormData();
            this.previewImages.forEach(({ file }) => {
                formData.append('images[]', file);
            });
            formData.append('product_id', this.selectedProduct);
            formData.append('order_id', this.reviewStore.review.id);
            formData.append('rating', this.rating);
            formData.append('comment', this.reviewContent);
            return formData;
        },
        validate() {
            let isValid = true;
            this.errors = {
                selectedProduct: '',
                rating: '',
                reviewContent: '',
            };
            if (!this.selectedProduct) {
                this.errors.selectedProduct = 'Vui lòng chọn sản phẩm để đánh giá';
                isValid = false;
            }
            if (!this.rating) {
                this.errors.rating = 'Vui lòng chọn số sao đánh giá';
                isValid = false;
            }
            if (!this.reviewContent || this.reviewContent.trim() === '') {
                this.errors.reviewContent = 'Vui lòng nhập nội dung đánh giá';
                isValid = false;
            }
            return isValid;
        },
        async save() {
            if (!this.validate()) {
                return;
            }
            const formData = this.collectData();
            try {
                await reviewApi.create(formData);
                await this.$swal.fire('Đánh giá đã được gửi!', 'Chúng tôi sẽ cải thiện sản phẩm dựa trên phản hồi của bạn.', 'success');
                this.$router.push({ name: 'orderTrackingDetail', params: { id: this.reviewStore.review.id } });
                this.reviewStore.clearReview();
            } catch (err) {
                console.error(err);
            }
        }
    }
}
</script>

<style scoped>
.rating-stars {
    display: flex;
    gap: 10px;
    margin: 10px 0;
}

.star {
    font-size: 32px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.star:hover, .star.active {
    color: #ffc107;
}

.image-upload {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 10px;
}

.upload-btn {
    width: 100px;
    height: 100px;
    border: 2px dashed #e1e5eb;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #f5f7fa;
}

.upload-btn:hover {
    border-color: var(--primary-color);
    background-color: rgba(74, 107, 255, 0.05);
}

.upload-btn i {
    font-size: 24px;
    color: var(--primary-color);
    margin-bottom: 8px;
}

.upload-btn span {
    font-size: 12px;
    color: #666;
}

.preview-image {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
}

.invalid-feedback {
    font-size: 12px;
}
</style>
