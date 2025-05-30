<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phiếu hủy hàng</h3>
                <router-link v-show="this.$route.params.id" to="/admin/cancel/create" class="admin-content__create">
                    Thêm phiếu hủy hàng
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phiếu hủy hàng</h4>
                        <h4 v-else>Form thêm phiếu hủy hàng</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã phiếu hủy hàng</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="cancel.id">
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Lý do hủy</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập lý do hủy hàng" v-model="cancel.reason"
                                v-bind:class="{'is-invalid': errors.reason}" @blur="validate()"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.reason">{{ errors.reason }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ngày hủy</h3>
                            <div class="valid-elm input-group">
                                <input type="date" class="fs-16 form-control" v-model="cancel.date"
                                v-bind:class="{'is-invalid': errors.date}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.date">{{ errors.date }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Chi tiết phiếu hủy hàng</h3>
                            <TableDetails
                                :headers="[
                                    { text: 'Sản phẩm', key: 'inventory_id', type: 'select', options: inventories,
                                        optionDisplayText: (inventory) => `${inventory.product?.name} - Lô ${inventory.batch_number}`
                                    },
                                    { text: 'Số lượng', key: 'quantity', type: 'number', min: 1, default: 1 },
                                    { text: 'Phương thức hủy', key: 'method', type: 'text' },
                                    { text: 'Đơn giá', key: 'price', type: 'number' }
                                ]"
                                :items="cancel.details"
                                @add="addCancelDetail"
                                @remove="removeCancelDetail"
                            />
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!cancel.approved_by && cancel.id" type="button" class="fs-16 btn btn-secondary" @click="approve()">Duyệt</button>
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
import { cancelApi, inventoryApi } from '@/api';
import TableDetails from '@/components/TableDetails.vue';

export default {
    components: {
        TableDetails
    },
    data() {
        return {
            cancel: {
                reason: '',
                date: new Date().toISOString().split('T')[0],
                details: []
            },
            inventories: [],
            errors: {
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
        async approve() {
            try {
                await cancelApi.approve(this.cancel.id);
                await this.$swal.fire("Duyệt thành công!", "Phiếu hủy hàng đã được duyệt!", "success")
                this.$router.push({ name: 'admin.cancels' });
            } catch (e) {
                console.error(e);
                this.$swal.fire("Lỗi!", e.message, "error")
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                reason: '',
                date: ''
            }
            if (this.cancel.reason?.length > 255) {
                this.errors.reason = 'Lý do hủy không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.cancel.date) {
                this.errors.date = 'Ngày hủy không được để trống.';
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
                        cancelApi.getOne(this.$route.params.id)
                    );
                }
                const res = await this.$swal.withLoading(Promise.all(req));
                this.inventories = res[0].data.data;
                if (this.$route.params.id) this.cancel = res[1].data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.cancel);

            try {
                if (this.cancel.id) {
                    await cancelApi.update(this.cancel.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về phiếu hủy hàng đã được cập nhật!", "success")
                }
                else {
                    await cancelApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Phiếu hủy hàng mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.cancels' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(cancel) {
            const data = { ...cancel };
            if (data.details) {
                data.details = data.details.filter(detail => 
                    detail.inventory_id && 
                    Number(detail.quantity) > 0 && 
                    Number(detail.price) > 0
                );
            }
            return data;
        },
        addCancelDetail() {
            const newDetail = {
                inventory_id: '',
                quantity: 1,
                price: 0,
                method: ''
            };
            
            if (!this.cancel.details) {
                this.cancel.details = [];
            }
            
            this.cancel.details.push(newDetail);
        },
        removeCancelDetail(index) {
            this.cancel.details.splice(index, 1);
        
            if (this.cancel.details.length === 0) {
                this.addCancelDetail();
            }
        },
        resetForm() {
            this.cancel = {
                reason: '',
                date: new Date().toISOString().split('T')[0],
                details: []
            };
            this.errors = {
                reason: '',
                date: ''
            };
        },
    }
}
</script>