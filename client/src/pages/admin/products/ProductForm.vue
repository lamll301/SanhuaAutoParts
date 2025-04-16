<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý sản phẩm</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa sản phẩm</h4>
                        <h4 v-else>Form thêm sản phẩm</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã sản phẩm</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="product.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên sản phẩm</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên sản phẩm" v-model="product.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập mô tả sản phẩm" v-model="product.description"></textarea>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Giá gốc</h3>
                                <div class="valid-elm input-group">
                                    <input type="number" class="fs-16 form-control" placeholder="Nhập giá sản phẩm" v-model="product.original_price"
                                    v-bind:class="{'is-invalid': errors.original_price}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.original_price">{{ errors.original_price }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Khuyến mãi</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="product.promotion_id">
                                        <option disabled value="null">Chọn khuyến mãi</option>
                                        <option v-for="promotion in promotions" :key="promotion.id" :value="promotion.id">
                                            {{ promotion.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Nhà cung cấp</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="product.supplier_id">
                                        <option disabled value="null">Chọn nhà cung cấp</option>
                                        <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                            {{ supplier.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Đơn vị tính</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="product.unit_id">
                                        <option disabled value="null">Chọn đơn vị tính</option>
                                        <option v-for="unit in units" :key="unit.id" :value="unit.id">
                                            {{ unit.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Danh mục</h3>
                            <ItemDashboard
                                ref="itemDashboard"
                                :items="product.categories"
                                :options="categories"
                                @remove="removeCategory"
                            />
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
                                :images="product.images"
                                @removeImage="handleRemoveImage"
                            />
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Kích thước</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" 
                                        class="fs-16 form-control" 
                                        placeholder="Nhập mô tả kích thước sản phẩm" 
                                        v-model="product.dimensions"
                                        v-bind:class="{'is-invalid': errors.dimensions}" @blur="validate()"
                                    >
                                    <div class="invalid-feedback" v-if="errors.dimensions">{{ errors.dimensions }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Trọng lượng</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" 
                                        class="fs-16 form-control" 
                                        placeholder="Nhập mô tả trọng lượng sản phẩm" 
                                        v-model="product.weight"
                                        v-bind:class="{'is-invalid': errors.weight}" @blur="validate()"
                                    >
                                    <div class="invalid-feedback" v-if="errors.weight">{{ errors.weight }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Màu sắc</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" 
                                        class="fs-16 form-control" 
                                        placeholder="Nhập mô tả màu sắc sản phẩm" 
                                        v-model="product.color"
                                        v-bind:class="{'is-invalid': errors.color}" @blur="validate()"
                                    >
                                    <div class="invalid-feedback" v-if="errors.color">{{ errors.color }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Chất liệu</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" 
                                        class="fs-16 form-control" 
                                        placeholder="Nhập mô tả chất liệu sản phẩm" 
                                        v-model="product.material"
                                        v-bind:class="{'is-invalid': errors.material}" @blur="validate()"
                                    >
                                    <div class="invalid-feedback" v-if="errors.material">{{ errors.material }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tương thích</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" 
                                        class="fs-16 form-control" 
                                        placeholder="Nhập mô tả tương thích sản phẩm" 
                                        v-model="product.compatibility"
                                        v-bind:class="{'is-invalid': errors.compatibility}" @blur="validate()"
                                    >
                                    <div class="invalid-feedback" v-if="errors.compatibility">{{ errors.compatibility }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="product.status">
                                    <option value="" disabled selected>Chọn trạng thái</option>
                                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                                        {{ status.label }}
                                    </option>
                                </select>
                            </div>
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
/* eslint-disable */
import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';
import ImagePreview from '@/components/ImagePreview.vue';
import { statusService } from '@/utils/statusService';
import ItemDashboard from '@/components/ItemDashboard.vue';

export default {
    data() {
        return {
            product: {
                status: '',
                supplier_id: null,
                unit_id: null,
                promotion_id: null,
            },
            promotions: [], suppliers: [], units: [], categories: [],
            statusOptions: statusService.getOptions('product'),
            errors: {
                name: '', original_price: '', dimensions: '', weight: '', color: '', material: '', compatibility: ''
            },
        }
    },
    components: {
        ImagePreview, ItemDashboard
    },
    async created() {
        await this.fetchInitialData();
        if (this.$route.params.id) {
            await this.fetchData();
        }
    },
    methods: {
        validate() {
            let isValid = true;
            this.errors = {
                name: '', original_price: '', dimensions: '', weight: '', color: '', material: '', compatibility: ''
            }
            if (!this.product.name) {
                this.errors.name = 'Tên sản phẩm không được để trống.';
                isValid = false;
            } else if (this.product.name.length > 128) {
                this.errors.name = 'Tên sản phẩm không được vượt quá 128 ký tự.';
                isValid = false;
            }
            if (!this.product.original_price) {
                this.errors.original_price = 'Giá gốc sản phẩm không được để trống.';
                isValid = false;
            } else if (this.product.original_price <= 0) {
                this.errors.original_price = 'Giá gốc sản phẩm phải lớn hơn 0.';
                isValid = false;
            }
            if (this.product.dimensions?.length > 64) {
                this.errors.dimensions = 'Kích thước không được vượt quá 64 ký tự.';
                isValid = false;
            }
            if (this.product.material?.length > 64) {
                this.errors.material = 'Chất liệu không được vượt quá 64 ký tự.';
                isValid = false;
            }
            if (this.product.weight?.length > 32) {
                this.errors.weight = 'Trọng lượng không được vượt quá 32 ký tự.';
                isValid = false;
            }
            if (this.product.color?.length > 32) {
                this.errors.color = 'Màu sắc không được vượt quá 32 ký tự.';
                isValid = false;
            }
            if (this.product.compatibility?.length > 128) {
                this.errors.compatibility = 'Tương thích không được vượt quá 128 ký tự.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                const res = await handleApiCall(() => this.$request.get(apiService.products.view(this.$route.params.id)));
                this.product = res;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchInitialData() {
            try {
                const [promotions, suppliers, units, categories] = await Promise.all([
                    handleApiCall(() => this.$request.get(apiService.promotions.get({}, false, true))),
                    handleApiCall(() => this.$request.get(apiService.suppliers.get({}, false, true))),
                    handleApiCall(() => this.$request.get(apiService.units.get({}, false, true))),
                    handleApiCall(() => this.$request.get(apiService.categories.get({}, false, true))),
                ]);
                this.promotions = promotions.data;
                this.suppliers = suppliers.data;
                this.units = units.data;
                this.categories = categories.data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;

            const formData = this.prepareFormData();

            if (this.product.id) {
                await handleApiCall(() => this.$request.post(apiService.products.update(this.product.id), formData));
                await swalFire("Cập nhật thành công!", "Thông tin về sản phẩm đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.products.create(), formData));
                await swalFire("Thêm thành công!", "Sản phẩm mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.products' });
        },
        prepareFormData() {
            const formData = new FormData();
            const images = this.$refs.imagePreview.tempImages;
            const selectedThumbnail = this.$refs.imagePreview.selectedThumbnail;
            const deletedImageIds = this.$refs.imagePreview.deletedImageIds;
            const { addedIds, deletedIds } = this.$refs.itemDashboard.getIds();

            Object.entries(this.product).forEach(([key, value]) => {
                if (value !== null && value !== undefined && value !== '') {
                    formData.append(key, value);
                }
            });

            Object.entries(images).forEach(([imageId, file]) => {
                formData.append(`images[]`, file, imageId);
            });

            if (selectedThumbnail) {
                formData.append('selectedThumbnail', selectedThumbnail);
            }

            if (addedIds.length > 0) {
                formData.append('addedIds', JSON.stringify(addedIds));
            }

            if (this.product.id) {
                formData.append('_method', 'PUT');
                if (deletedImageIds.length > 0) {
                    formData.append('deletedImageIds', JSON.stringify(deletedImageIds));
                }
                if (deletedIds.length > 0) {
                    formData.append('deletedIds', JSON.stringify(deletedIds));
                }
            }

            return formData;
        },
        handleRemoveImage(imageId) {
            if (this.product.images) {
                this.product.images = this.product.images.filter(image => image.id !== imageId);
            }
        },
        removeCategory(id) {
            this.product.categories = this.product.categories.filter(category => category.id !== id);
        },
    }
}
</script>