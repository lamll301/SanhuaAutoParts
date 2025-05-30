<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý tin tức</h3>
                <router-link v-show="$route.params.id" to="/admin/article/create" class="admin-content__create">
                    Thêm tin tức
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="$route.params.id">Form sửa tin tức</h4>
                        <h4 v-else>Form thêm tin tức</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã tin tức</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="article.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tiêu đề</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tiêu đề" v-model="article.title"
                                v-bind:class="{'is-invalid': errors.title}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.title">{{ errors.title }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Nổi bật</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập nổi bật tin tức" v-model="article.highlight"
                                v-bind:class="{'is-invalid': errors.highlight}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.highlight">{{ errors.highlight }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Nội dung</h3>
                            <RichTextEditor 
                                ref="richTextEditor"
                                :content="article.content"
                                :images="article.images"
                                @removeImage="handleRemoveImage"
                            />
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Danh mục</h3>
                            <div class="valid-elm input-group">
                                <select class="fs-16 form-select" v-model="article.category_id">
                                    <option value="" selected>-- Chọn danh mục --</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!article.approved_by && article.id" type="button" class="fs-16 btn btn-secondary" @click="approve()">Duyệt</button>
                            <button type="submit" class="fs-16 btn btn-primary">Xác nhận</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RichTextEditor from '@/components/RichTextEditor.vue';
import { articleApi, categoryApi } from '@/api';

export default {
    components: {
        RichTextEditor
    },
    data() {
        return {
            article: { category_id: '' },
            errors: {
                title: '', highlight: ''
            },
        }
    },
    watch: {
        '$route'(to, from) {
            if (from.params.id && !to.params.id) {
                this.resetForm();
            } else {
                this.fetchData();
            }
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async approve() {
            try {
                await articleApi.approve(this.$route.params.id);
                await this.$swal.fire("Duyệt thành công!", "Tin tức đã được duyệt!", "success")
                this.$router.push({ name: 'admin.articles' });
            } catch (e) {
                console.error(e);
                this.$swal.fire("Lỗi!", e.message, "error")
            }
        },
        async fetchData() {
            try {
                const req = [
                    categoryApi.getAll({ key: 'article'}),
                ];

                if (this.$route.params.id) {
                    req.push(
                        articleApi.getOne(this.$route.params.id)
                    );
                }
                const res = await this.$swal.withLoading(Promise.all(req));
                this.categories = res[0].data?.data || [];
                if (this.$route.params.id) {
                    this.article = res[1].data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.article);
            try {
                if (this.article.id) {
                    await articleApi.updateWithImages(this.article.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về tin tức đã được cập nhật!", "success")
                }
                else {
                    await articleApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Tin tức mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.articles' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(article) {
            const formData = new FormData();
            const content = this.$refs.richTextEditor.getContent();
            const images = this.$refs.richTextEditor.getTempImages();
            const selectedThumbnail = this.$refs.richTextEditor.getThumbnail();
            const deletedImageIds = this.$refs.richTextEditor.getDeletedImages();

            formData.append('content', content);
            Object.entries(article).forEach(([key, value]) => {
                if (key === 'content') return
                if (value !== null && value !== undefined) {
                    formData.append(key, value);
                }
            });

            Object.entries(images).forEach(([imageId, file]) => {
                formData.append(`images[]`, file, imageId);
            });

            if (selectedThumbnail) {
                formData.append('selectedThumbnail', selectedThumbnail);
            }

            if (article.id) {
                formData.append('_method', 'PUT');
                if (deletedImageIds.length > 0) {
                    formData.append('deletedImageIds', JSON.stringify(deletedImageIds));
                }
            }

            return formData;
        },
        handleRemoveImage(imageId) {
            this.article.images = this.article.images.filter(image => image.id !== imageId);
        },
        validate() {
            let isValid = true;
            this.errors = {
                title: '', highlight: ''
            }
            if (!this.article.title) {
                this.errors.title = 'Tiêu đề không được để trống';
                isValid = false;
            } else if (this.article.title.length > 255) {
                this.errors.title = 'Tiêu đề không được vượt quá 255 ký tự';
                isValid = false;
            }

            if (this.article.highlight && this.article.highlight.length > 255) {
                this.errors.highlight = 'Nổi bật không được vượt quá 255 ký tự';
                isValid = false;
            }

            return isValid;
        },
        resetForm() {
            this.article = { title: '', highlight: '', publish_date: '', content: '', category_id: '' };
            this.errors = { title: '', highlight: '' };
        },
    }
}
</script>