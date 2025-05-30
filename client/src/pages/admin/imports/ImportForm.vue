<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phiếu nhập</h3>
                <router-link v-show="this.$route.params.id" to="/admin/import/create" class="admin-content__create">
                    Thêm phiếu nhập
                </router-link>
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
                                    <option disabled selected value="null">Chọn nhà cung cấp</option>
                                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                        {{ supplier.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Người giao</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên người giao" v-model="importData.deliverer"
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
                                <input type="date" class="fs-16 form-control" v-model="importData.date"
                                v-bind:class="{'is-invalid': errors.date}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.date">{{ errors.date }}</div>
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
                                :items="importData.details"
                                @add="addImportDetail"
                                @remove="removeImportDetail"
                            />
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!importData.approved_by && importData.id" type="button" class="fs-16 btn btn-secondary" @click="approve()">Duyệt</button>
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
import { importApi, supplierApi, productApi } from '@/api';
import TableDetails from '@/components/TableDetails.vue';

export default {
    components: {
        TableDetails
    },
    data() {
        return {
            importData: {
                supplier_id: null,
                details: []
            },
            suppliers: [], products: [],
            errors: {
                deliverer: '',
                address: '',
                date: ''
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
                await importApi.approve(this.$route.params.id);
                await this.$swal.fire("Duyệt thành công!", "Phiếu nhập đã được duyệt!", "success")
                this.$router.push({ name: 'admin.imports' });
            } catch (e) {
                console.error(e);
                this.$swal.fire("Lỗi!", e.message, "error")
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                deliverer: '',
                address: '',
                date: ''
            }
            if (this.importData.deliverer?.length > 255) {
                this.errors.deliverer = 'Tên người giao không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (this.importData.address?.length > 255) {
                this.errors.address = 'Địa chỉ không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.importData.date) {
                this.errors.date = 'Ngày nhập không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                const req = [
                    supplierApi.getAll(),
                    productApi.getAll(),
                ];

                if (this.$route.params.id) {
                    req.push(
                        importApi.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.suppliers = res[0].data.data;
                this.products = res[1].data.data;
                if (this.$route.params.id) this.importData = res[2].data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.importData);

            try {
                if (this.importData.id) {
                    await importApi.update(this.importData.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về phiếu nhập đã được cập nhật!", "success")
                }
                else {
                    await importApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Phiếu nhập mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.imports' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(importData) {
            const data = { ...importData };
            if (data.details) {
                data.details = data.details.filter(detail => 
                    detail.product_id && 
                    Number(detail.quantity) > 0 && 
                    Number(detail.price) > 0
                );
            }
            
            return data;
        },
        addImportDetail() {
            const newDetail = {
                product_id: '',
                quantity: 1,
                price: 0
            };
            
            if (!this.importData.details) {
                this.importData.details = [];
            }
            
            this.importData.details.push(newDetail);
        },
        removeImportDetail(index) {
            this.importData.details.splice(index, 1);
        
            if (this.importData.details.length === 0) {
                this.addImportDetail();
            }
        },
        resetForm() {
            this.importData = {
                deliverer: '',
                address: '',
            };
            this.errors = {
                deliverer: '',
                address: '',
                date: ''
            };
        },
    }
}
</script>