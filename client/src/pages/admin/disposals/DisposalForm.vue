<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phiếu thanh lý</h3>
                <router-link v-show="this.$route.params.id" to="/admin/disposal/create" class="admin-content__create">
                    Thêm phiếu thanh lý
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phiếu thanh lý</h4>
                        <h4 v-else>Form thêm phiếu thanh lý</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã phiếu thanh lý</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="disposalData.id">
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Lý do thanh lý</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập lý do thanh lý" v-model="disposalData.reason"
                                v-bind:class="{'is-invalid': errors.reason}" @blur="validate()"
                                ></textarea>
                                <div class="invalid-feedback" v-if="errors.reason">{{ errors.reason }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ngày thanh lý</h3>
                            <div class="valid-elm input-group">
                                <input type="date" class="fs-16 form-control" v-model="disposalData.date"
                                v-bind:class="{'is-invalid': errors.date}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.date">{{ errors.date }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Chi tiết phiếu thanh lý</h3>
                            <TableDetails
                                :headers="[
                                    { text: 'Sản phẩm', key: 'inventory_id', type: 'select', options: inventories,
                                        optionDisplayText: (inventory) => `${inventory.product?.name} - Lô ${inventory.batch_number}`
                                    },
                                    { text: 'Số lượng', key: 'quantity', type: 'number', min: 1, default: 1 },
                                    { text: 'Phương thức thanh lý', key: 'method', type: 'text' },
                                    { text: 'Đơn giá', key: 'price', type: 'number' }
                                ]"
                                :items="disposalData.details"
                                @add="addDisposalDetail"
                                @remove="removeDisposalDetail"
                            />
                        </div>
                        <div class="mb-20 admin-content__form-btn">
                            <button v-if="!disposalData.approved_by && disposalData.id" type="button" class="fs-16 btn btn-secondary" @click="approve()">Duyệt</button>
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
import { disposalApi, inventoryApi } from '@/api';

export default {
    components: {
        TableDetails
    },
    data() {
        return {
            disposalData: {
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
                await disposalApi.approve(this.disposalData.id);
                await this.$swal.fire("Duyệt thành công!", "Phiếu thanh lý đã được duyệt!", "success")
                this.$router.push({ name: 'admin.disposals' });
            }
            catch (e) {
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
            if (this.disposalData.reason?.length > 255) {
                this.errors.reason = 'Lý do thanh lý không được vượt quá 255 ký tự.';
                isValid = false;
            }
            if (!this.disposalData.date) {
                this.errors.date = 'Ngày thanh lý không được để trống.';
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
                        disposalApi.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.inventories = res[0].data.data;
                if (this.$route.params.id) this.disposalData = res[1].data;
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.collectData(this.disposalData);
            console.log(data);

            try {
                if (this.disposalData.id) {
                    await disposalApi.update(this.disposalData.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về phiếu thanh lý đã được cập nhật!", "success")
                }
                else {
                    await disposalApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Phiếu thanh lý mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.disposals' });
            } catch (error) {
                console.error(error);
            }
        },
        collectData(disposalData) {
            const data = { ...disposalData };
            if (data.details) {
                data.details = data.details.filter(detail => 
                    detail.inventory_id && 
                    Number(detail.quantity) > 0 && 
                    Number(detail.price) > 0
                );
            }
            return data;
        },
        addDisposalDetail() {
            const newDetail = {
                inventory_id: '',
                quantity: 1,
                price: 0,
                method: ''
            };
            if (!this.disposalData.details) {
                this.disposalData.details = [];
            }
            
            this.disposalData.details.push(newDetail);
        },
        removeDisposalDetail(index) {
            this.disposalData.details.splice(index, 1);
        
            if (this.disposalData.details.length === 0) {
                this.addDisposalDetail();
            }
        },
        resetForm() {
            this.disposalData = {
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