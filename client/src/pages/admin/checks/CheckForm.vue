<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phiếu kiểm kê</h3>
                <router-link v-show="this.$route.params.id" to="/admin/check/create" class="admin-content__create">
                    Thêm phiếu kiểm kê
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phiếu kiểm kê</h4>
                        <h4 v-else>Form thêm phiếu kiểm kê</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã phiếu kiểm kê</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="check.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ngày kiểm kê</h3>
                            <div class="valid-elm input-group">
                                <input type="date" class="fs-16 form-control" v-model="check.date"
                                v-bind:class="{'is-invalid': errors.date}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.date">{{ errors.date }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Chi tiết phiếu kiểm kê</h3>
                            <TableDetails
                                :headers="[
                                    { text: 'Sản phẩm', key: 'inventory_id', type: 'select', options: inventories,
                                        optionDisplayText: (inventory) => `${inventory.product?.name} - Lô ${inventory.batch_number}`
                                    },
                                    { text: 'Chất lượng', key: 'quality', type: 'select', options: qualityOptions },
                                    { text: 'Số lượng thực tế', key: 'quantity', type: 'number', min: 0, default: 0 },
                                    { text: 'Đơn giá', key: 'price', type: 'number' }
                                ]"
                                :items="check.details"
                                @add="addCheckDetail"
                                @remove="removeCheckDetail"
                            />
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!check.approved_by && check.id" type="button" class="fs-16 btn btn-secondary" @click="approve()">Duyệt</button>
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
import TableDetails from '@/components/TableDetails.vue';
import { checkApi, inventoryApi } from '@/api';

export default {
    components: {
        TableDetails
    },
    data() {
        return {
            check: {
                date: new Date().toISOString().split('T')[0],
                details: []
            },
            inventories: [],
            qualityOptions: [
                { id: 'Còn tốt 100%', name: 'Còn tốt 100%' },
                { id: 'Kém phẩm chất', name: 'Kém phẩm chất' },
                { id: 'Mất phẩm chất', name: 'Mất phẩm chất' }
            ],
            errors: {
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
                await checkApi.approve(this.check.id);
                await this.$swal.fire("Duyệt thành công!", "Phiếu kiểm kê đã được duyệt!", "success")
                this.$router.push({ name: 'admin.checks' });
            } catch (e) {
                console.error(e);
                this.$swal.fire("Lỗi!", e.message, "error")
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                date: ''
            }

            if (!this.check.date) {
                this.errors.date = 'Ngày kiểm kê không được để trống.';
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
                        checkApi.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.inventories = res[0].data.data;
                if (this.$route.params.id) this.check = res[1].data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.check);

            try {
                if (this.check.id) {
                    await checkApi.update(this.check.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về phiếu kiểm kê đã được cập nhật!", "success")
                }
                else {
                    await checkApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Phiếu kiểm kê mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.checks' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(check) {
            const data = { ...check };
            if (data.details) {
                data.details = data.details.filter(detail => 
                    detail.inventory_id && 
                    Number(detail.price) > 0
                );
            }
            
            return data;
        },
        addCheckDetail() {
            const newDetail = {
                inventory_id: '',
                quality: 'Còn tốt 100%',
                quantity: 0,
                price: 0
            };
            
            if (!this.check.details) {
                this.check.details = [];
            }
            
            this.check.details.push(newDetail);
        },
        removeCheckDetail(index) {
            this.check.details.splice(index, 1);
        
            if (this.check.details.length === 0) {
                this.addCheckDetail();
            }
        },
        resetForm() {
            this.check = {
                date: new Date().toISOString().split('T')[0],
                details: []
            };
            this.errors = {
                date: ''
            };
        },
    }
}
</script>