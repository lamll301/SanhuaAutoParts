<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý danh mục</h3>
                <router-link v-show="this.$route.params.id" to="/admin/category/create" class="admin-content__create">
                    Thêm danh mục
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa danh mục</h4>
                        <h4 v-else>Form thêm danh mục</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã danh mục</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="category.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên danh mục</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên danh mục" v-model="category.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Phân loại</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập loại danh mục" v-model="category.type"
                                v-bind:class="{'is-invalid': errors.type}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.type">{{ errors.type }}</div>

                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập mô tả danh mục" v-model="category.description"
                                v-bind:class="{'is-invalid': errors.description}" @blur="validate()"></textarea>
                                <div class="invalid-feedback" v-if="errors.description">{{ errors.description }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ảnh</h3>
                            <div class="image-upload-container">
                                <div class="upload-actions">
                                    <button type="button" class="upload-button" @click="$refs.imagePreview.$refs.fileInput.click()">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Tải lên ảnh mới</span>
                                    </button>
                    
                                    <div class="upload-hint">
                                        <p>Kéo thả ảnh vào đây hoặc nhấn để chọn</p>
                                        <p class="file-types">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB)</p>
                                    </div>
                                </div>
                            </div>
                            <ImagePreview
                                ref="imagePreview"
                                :images="category.images"
                                @removeImage="handleRemoveImage"
                            />
                        </div>
                        <div class="mb-20 admin-content__form-btn">
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
import { categoryApi } from '@/api';
import ImagePreview from '@/components/ImagePreview.vue';

export default {
    data() {
        return {
            category: {},
            errors: { 
                name: '',
                type: '',
                description: '', 
            },
        }
    },
    components: {
        ImagePreview
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
        validate() {
            let isValid = true;
            this.errors = { 
                name: '',
                type: '',
                description: '',
            }

            if (!this.category.name || this.category.name.trim() === '') {
                this.errors.name = 'Vui lòng nhập tên danh mục';
                isValid = false;
            } else if (this.category.name.length > 255) {
                this.errors.name = 'Tên danh mục không được vượt quá 255 ký tự';
                isValid = false;
            }
            
            if (!this.category.type || this.category.type.trim() === '') {
                this.errors.type = 'Vui lòng nhập phân loại';
                isValid = false;
            } else if (this.category.type.length > 255) {
                this.errors.type = 'Phân loại không được vượt quá 255 ký tự';
                isValid = false;
            }
            
            if (this.category.description && this.category.description.length > 255) {
                this.errors.description = 'Mô tả không được vượt quá 255 ký tự';
                isValid = false;
            }
            
            return isValid;
        },
        async fetchData() {
            try {
                if (this.$route.params.id) {
                    const res = await this.$swal.withLoading(categoryApi.getOne(this.$route.params.id));
                    this.category = res.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.category);

            try {
                if (this.category.id) {
                    await categoryApi.updateWithImages(this.category.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về danh mục đã được cập nhật!", "success")
                }
                else {
                    await categoryApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Danh mục mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.categories' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(category) {
            const formData = new FormData();
            const images = this.$refs.imagePreview.tempImages;
            const selectedThumbnail = this.$refs.imagePreview.selectedThumbnail;
            const deletedImageIds = this.$refs.imagePreview.deletedImageIds;

            Object.entries(category).forEach(([key, value]) => {
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

            if (category.id) {
                formData.append('_method', 'PUT');
                if (deletedImageIds.length > 0) {
                    formData.append('deletedImageIds', JSON.stringify(deletedImageIds));
                }
            }

            return formData;
        },
        handleRemoveImage(imageId) {
            if (this.category.images) {
                this.category.images = this.category.images.filter(image => image.id !== imageId);
            }
        },
        resetForm() {
            this.category = { 
                name: '',
                type: '',
                description: '',
            };
            this.errors = { 
                name: '',
                type: '',
                description: '',
            };
        },
    }
}
</script>