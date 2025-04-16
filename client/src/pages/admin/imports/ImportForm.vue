<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phiếu nhập</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phiếu nhập</h4>
                        <h4 v-else>Form thêm phiếu nhập</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã phiếu nhập</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="importData.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Nhà cung cấp</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="importData.supplier_id">
                                    <option disabled value="">Chọn nhà cung cấp</option>
                                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                        {{ supplier.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Người giao</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên phiếu nhập" v-model="importData.deliverer"
                                v-bind:class="{'is-invalid': errors.deliverer}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.deliverer">{{ errors.deliverer }}</div>
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Địa chỉ</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập địa chỉ" v-model="importData.address"
                                v-bind:class="{'is-invalid': errors.address}" @blur="validate()"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.address">{{ errors.address }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ngày nhập</h3>
                            <div class="valid-elm input-group">
                                <input type="date" class="fs-16 form-control" v-model="importData.import_date"
                                v-bind:class="{'is-invalid': errors.import_date}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.import_date">{{ errors.import_date }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Chi tiết phiếu nhập</h3>
                            <TableDetails
                                :headers="[
                                    { text: 'Sản phẩm', key: 'product_id', type: 'select', options: products },
                                    { text: 'Số lượng', key: 'quantity', type: 'number', min: 1, default: 1 },
                                    { text: 'Đơn giá', key: 'price', type: 'number' }
                                ]"
                                :items="importData.import_details"
                                @add="addImportDetail"
                                @remove="removeImportDetail"
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
/* eslint-disable */
import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';
import TableDetails from '@/components/TableDetails.vue';

export default {
    components: {
        TableDetails
    },
    data() {
        return {
            importData: {
                supplier_id: '',
                import_details: []
            },
            suppliers: [], products: [],
            errors: {
                deliverer: '',
                address: '',
                import_date: ''
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
                deliverer: '',
                address: '',
                import_date: ''
            }
            if (this.importData.deliverer?.length > 64) {
                this.errors.deliverer = 'Tên người giao không được vượt quá 64 ký tự.';
                isValid = false;
            }
            if (this.importData.address?.length > 255) {
                this.errors.address = 'Địa chỉ không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.importData.import_date) {
                this.errors.import_date = 'Ngày nhập không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                const res = await handleApiCall(() => this.$request.get(apiService.imports.view(this.$route.params.id)));
                this.importData = res;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchInitialData() {
            try {
                const [suppliers, products] = await Promise.all([
                    handleApiCall(() => this.$request.get(apiService.suppliers.get({}, false, true))),
                    handleApiCall(() => this.$request.get(apiService.products.get({}, false, true))),
                ]);
                this.suppliers = suppliers.data;
                this.products = products.data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;

            if (this.importData.id) {
                await handleApiCall(() => this.$request.put(apiService.imports.update(this.importData.id), this.importData));
                await swalFire("Cập nhật thành công!", "Thông tin về phiếu nhập đã được cập nhật!", "success");
            }
            else {
                await handleApiCall(() => this.$request.post(apiService.imports.create(), this.importData));
                await swalFire("Thêm thành công!", "Phiếu nhập mới đã được thêm vào hệ thống!", "success");
            }
            this.$router.push({ name: 'admin.imports' });
        },
        addImportDetail() {
            const newDetail = {
                product_id: '',
                quantity: 1,
                price: 0
            };
            
            if (!this.importData.import_details) {
                this.importData.import_details = [];
            }
            
            this.importData.import_details.push(newDetail);
        },
        removeImportDetail(index) {
            this.importData.import_details.splice(index, 1);
        
            if (this.importData.import_details.length === 0) {
                this.addImportDetail();
            }
        }
    }
}
</script>