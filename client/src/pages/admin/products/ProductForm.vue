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
                        <div v-if="this.$route.params.id" class="mb-16">
                            <h3 class="admin-content__form-text">Mã sản phẩm</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="autoPart._id">
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Tên sản phẩm</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên sản phẩm" v-model="autoPart.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-16 height-205">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" placeholder="Nhập mô tả sản phẩm" rows="6" v-model="autoPart.description"></textarea>
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Ảnh</h3>
                            <div class="valid-elm input-group">
                                <input type="file" class="fs-16 lh-30 form-control" name="image" accept="image/*" ref="imageInput">
                                <button v-if="this.$route.params.id" type="button" class="btn btn-light fs-16 btn" @click="addImage()">Thêm ảnh</button>
                            </div>
                        </div>
                        <div>
                            <div v-for="image in autoPart.images" :key="image.name" class="admin-content__image-container mr-16 mb-16">
                                <button type="button" class="btn btn-danger" @click="removeImage(image.name)">X</button>
                                <img v-bind:src="image.name" class="img-thumbnail admin-content__image-report" alt="">
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Nhà cung cấp</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="autoPart.supplierId">
                                    <option disabled value="">Chọn nhà cung cấp sản phẩm</option>
                                    <option v-for="supplier in suppliers" :key="supplier._id" :value="supplier._id">
                                        {{ supplier.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Giá niêm yết</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập giá niêm yết" v-model="autoPart.listPrice"
                                v-bind:class="{'is-invalid': errors.listPrice}" @blur="validate()">
                                <span class="fs-16 input-group-text">đồng</span>
                                <div class="invalid-feedback" v-if="errors.listPrice">{{ errors.listPrice }}</div>
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Khuyến mãi</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="autoPart.promotionId">
                                    <option disabled value="">Chọn khuyến mãi sản phẩm</option>
                                    <option v-for="promotion in promotions" :key="promotion._id" :value="promotion._id">
                                        {{ promotion.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-16">
                                <h3 class="admin-content__form-text">Số lượng</h3>
                                <div class="valid-elm input-group">
                                    <input type="number" class="fs-16 form-control" placeholder="Nhập số lượng sản phẩm" v-model="autoPart.quantity"
                                    v-bind:class="{'is-invalid': errors.quantity}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.quantity">{{ errors.quantity }}</div>
                                </div>
                            </div>
                            <div class="mb-16">
                                <h3 class="admin-content__form-text">Đơn vị tính</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="autoPart.unitId">
                                        <option disabled value="">Chọn đơn vị tính sản phẩm</option>
                                        <option v-for="unit in units" :key="unit._id" :value="unit._id">
                                            {{ unit.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided" v-if="this.$route.params.id">
                            <div class="mb-16">
                                <h3 class="admin-content__form-text">Danh mục sản phẩm</h3>
                                <div class="valid-elm input-group mb-4">   
                                    <input type="text" class="fs-16 form-control" readonly :value="getautoPartCategoryName">
                                </div>
                            </div>
                            <div class="mb-16">
                                <h3 class="admin-content__form-text white">.</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" ref="selectedCategory">
                                        <option disabled value="">Chọn danh mục sản phẩm</option>
                                        <option v-for="category in categories" :key="category._id" :value="category._id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <button class="valid-elm btn btn-outline-secondary admin-content__form-btn-with-icon" type="button" @click="addCategory(parseInt($refs.selectedCategory.value))">
                                        Thêm
                                        <img src="@/assets/images/add.png">
                                    </button>
                                    <button class="valid-elm btn btn-outline-secondary admin-content__form-btn-with-icon" type="button" @click="removeCategory(parseInt($refs.selectedCategory.value))">
                                        Xóa
                                        <img src="@/assets/images/trash.png">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-16" v-else>
                            <h3 class="admin-content__form-text">Danh mục sản phẩm</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="autoPart.categoryId">
                                    <option disabled value="">Chọn danh mục sản phẩm</option>
                                    <option v-for="category in categories" :key="category._id" :value="category._id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-16" v-if="this.$route.params.id">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <select class="valid-elm form-select" v-model="autoPart.status">
                                <option disabled value="">Chọn trạng thái sản phẩm</option>
                                <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                                    {{ label }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-16 admin-content__form-btn">
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
export default {
    data() {
        return {
            autoPart: {
                name: "Lọc dầu động cơ",
                description: "Lọc dầu động cơ giúp loại bỏ tạp chất, bảo vệ động cơ khỏi cặn bẩn và gia tăng tuổi thọ.",
                images: [
                    { name: "https://picsum.photos/500/300" },
                    { name: "https://picsum.photos/600" }
                ],
                supplier: "Nhà cung cấp A",
                listPrice: 450000,
                promotion: "15%",
                quantity: 100,
                unit: "Cái",
                category: "Lọc dầu",
                status: "active",
                categories: ["Lọc dầu", "Bảo dưỡng động cơ"]
            },
            errors: {
                name: '',
                listPrice: '',
                quantity: '',
            },
        }
    },
    created() {

    },
    methods: {
        validate() {

        }
    }
}
</script>