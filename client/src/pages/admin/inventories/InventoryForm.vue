<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý hàng tồn kho</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa hàng tồn kho</h4>
                        <h4 v-else>Form thêm hàng tồn kho</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã hàng tồn kho</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="inventory.id">
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Sản phẩm</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="inventory.product_id">
                                        <option disabled value="">Chọn sản phẩm</option>
                                        <option v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phiếu nhập</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="inventory.import_id">
                                        <option disabled value="">Chọn phiếu nhập</option>
                                        <option v-for="importReceipt in imports" :key="importReceipt.id" :value="importReceipt.id">
                                            {{ importReceipt.id }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Số lô</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập số lô" v-model="inventory.batch_number"
                                v-bind:class="{'is-invalid': errors.batch_number}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.batch_number">{{ errors.batch_number }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Vị trí</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập vị trí" v-model="inventory.location"
                                v-bind:class="{'is-invalid': errors.location}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.location">{{ errors.location }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Số lượng</h3>
                            <div class="valid-elm input-group">
                                <input type="number" class="fs-16 form-control" placeholder="Nhập số lượng" v-model="inventory.quantity"
                                v-bind:class="{'is-invalid': errors.quantity}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.quantity">{{ errors.quantity }}</div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày sản xuất</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="inventory.manufacture_date">
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày hết hạn</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="inventory.expiry_date">
                                </div>
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
import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';

export default {
    data() {
        return {
            products: [], imports: [],
            inventory: {
                product_id: '', import_id: ''
            },
            errors: {
                quantity: '', batch_number: '', location: ''
            },
        }
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
                quantity: '', batch_number: '', location: ''
            }
            if (this.inventory.quantity < 0) {
                this.errors.quantity = 'Số lượng hàng tồn phải lớn hơn hoặc bằng 0.';
                isValid = false;
            }
            if (!this.inventory.batch_number) {
                this.errors.batch_number = 'Số lô không được để trống.';
                isValid = false;
            }
            if (!this.inventory.location) {
                this.errors.location = 'Vị trí không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchInitialData() {
            try {
                const [products, imports] = await Promise.all([
                    handleApiCall(() => this.$request.get(apiService.products.get({}, false, true))),
                    handleApiCall(() => this.$request.get(apiService.imports.get({}, false, true))),
                ]);
                this.products = products.data;
                this.imports = imports.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchData() {
            try {
                const res = await handleApiCall(() => this.$request.get(apiService.inventories.view(this.$route.params.id)));
                this.inventory = res;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;

            const payload = Object.fromEntries(
                Object.entries(this.promotion).filter(([, value]) => 
                    value !== null && value !== undefined && value !== ''
                )
            );

            if (this.inventory.id) {
                await handleApiCall(() => this.$request.put(apiService.inventories.update(this.inventory.id), payload));
                await swalFire("Cập nhật thành công!", "Thông tin về hàng tồn kho đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.inventories.create(), payload));
                await swalFire("Thêm thành công!", "Hàng tồn kho mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.inventories' });
        },
    }
}
</script>