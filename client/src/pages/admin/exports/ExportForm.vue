<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phiếu xuất</h3>
                <router-link v-show="this.$route.params.id" to="/admin/export/create" class="admin-content__create">
                    Thêm phiếu xuất
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phiếu xuất</h4>
                        <h4 v-else>Form thêm phiếu xuất</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã phiếu xuất</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="exportData.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Người nhận</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên người nhận" v-model="exportData.receiver"
                                v-bind:class="{'is-invalid': errors.receiver}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.receiver">{{ errors.receiver }}</div>
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Địa chỉ</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập địa chỉ" v-model="exportData.address"
                                v-bind:class="{'is-invalid': errors.address}" @blur="validate()"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.address">{{ errors.address }}</div>
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Lý do xuất</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập lý do xuất" v-model="exportData.reason"
                                v-bind:class="{'is-invalid': errors.reason}" @blur="validate()"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.reason">{{ errors.reason }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ngày xuất</h3>
                            <div class="valid-elm input-group">
                                <input type="date" class="fs-16 form-control" v-model="exportData.date"
                                v-bind:class="{'is-invalid': errors.date}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.date">{{ errors.date }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Chi tiết phiếu xuất</h3>
                            <TableDetails
                                :headers="[
                                    { text: 'Sản phẩm', key: 'inventory_id', type: 'select', options: inventories,
                                        optionDisplayText: (inventory) => `${inventory.product?.name} - Lô ${inventory.batch_number}`
                                    },
                                    { text: 'Số lượng', key: 'quantity', type: 'number', min: 1, default: 1 },
                                    { text: 'Thực xuất', key: 'actual_quantity', type: 'number', min: 0, default: 0 },
                                    { text: 'Đơn giá', key: 'price', type: 'number' }
                                ]"
                                :items="exportData.details"
                                @add="addExportDetail"
                                @remove="removeExportDetail"
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
import { exportApi, inventoryApi } from '@/api';
import TableDetails from '@/components/TableDetails.vue';

export default {
    components: {
        TableDetails
    },
    data() {
        return {
            exportData: {
                receiver: '',
                address: '',
                reason: '',
                date: new Date().toISOString().split('T')[0],
                details: []
            },
            inventories: [],
            errors: {
                receiver: '',
                address: '',
                reason: '',
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
        validate() {
            let isValid = true;
            this.errors = {
                receiver: '',
                address: '',
                reason: '',
                date: ''
            }
            if (this.exportData.receiver?.length > 255) {
                this.errors.receiver = 'Tên người nhận không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (this.exportData.address?.length > 255) {
                this.errors.address = 'Địa chỉ không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (this.exportData.reason?.length > 255) {
                this.errors.reason = 'Lý do xuất không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.exportData.date) {
                this.errors.date = 'Ngày xuất không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            try {
                const req = [
                    inventoryApi.getAll(),
                ];

                if (this.$route.params.id) {
                    req.push(
                        exportApi.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.inventories = res[0].data.data;
                if (this.$route.params.id) this.exportData = res[1].data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.exportData);

            try {
                if (this.exportData.id) {
                    await exportApi.update(this.exportData.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về phiếu xuất đã được cập nhật!", "success")
                }
                else {
                    await exportApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Phiếu xuất mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.exports' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(exportData) {
            const data = { ...exportData };
            if (data.details) {
                data.details = data.details.filter(detail => 
                    detail.inventory_id && 
                    Number(detail.quantity) > 0 && 
                    Number(detail.price) > 0
                );
            }
            
            return data;
        },
        addExportDetail() {
            const newDetail = {
                inventory_id: '',
                quantity: 1,
                actual_quantity: 0,
                price: 0
            };
            
            if (!this.exportData.details) {
                this.exportData.details = [];
            }
            
            this.exportData.details.push(newDetail);
        },
        removeExportDetail(index) {
            this.exportData.details.splice(index, 1);
        
            if (this.exportData.details.length === 0) {
                this.addExportDetail();
            }
        },
        resetForm() {
            this.exportData = {
                receiver: '',
                address: '',
                reason: '',
                date: new Date().toISOString().split('T')[0],
                details: []
            };
            this.errors = {
                receiver: '',
                address: '',
                reason: '',
                date: ''
            };
        },
    }
}
</script>