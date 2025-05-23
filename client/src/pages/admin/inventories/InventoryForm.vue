<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý hàng tồn kho</h3>
                <router-link v-show="this.$route.params.id" to="/admin/inventory/create" class="admin-content__create">
                    Thêm hàng tồn kho
                </router-link>
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
                                <div class="input-group valid-elm">
                                    <select class="valid-elm form-select" v-model="inventory.product_id"
                                    v-bind:class="{'is-invalid': errors.product_id}" @change="validate()">
                                        <option disabled value="" selected>Chọn sản phẩm</option>
                                        <option v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback" v-if="errors.product_id">{{ errors.product_id }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Phiếu nhập</h3>
                                <div class="input-group">
                                    <select class="valid-elm form-select" v-model="inventory.import_id">
                                        <option disabled value="" selected>Chọn phiếu nhập</option>
                                        <option :value="null">Không có phiếu nhập</option>
                                        <option v-for="receipt in imports" :key="receipt.id" :value="receipt.id">
                                            {{ receipt?.supplier?.name }} - {{ receipt.date }}
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
                            <ItemDashboard
                                ref="itemDashboard"
                                :items="inventory.locations"
                                :options="locations"
                                :display="(location) => 
                                    `Khu ${location.zone}, Dãy ${location.aisle}, Kệ ${location.rack}, Tầng ${location.shelf}, Ngăn ${location.bin}` 
                                    + (location.category?.name ? ` - ${location.category.name}` : '')"
                                :inputFields="[
                                    { text: 'Số lượng', key: 'quantity', type: 'number' },
                                ]"
                                @remove="removeLocation"
                            />
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
                                    <input type="date" class="fs-16 form-control" v-model="inventory.manufacture_date"
                                    v-bind:class="{'is-invalid': errors.manufacture_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.manufacture_date">{{ errors.manufacture_date }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngày hết hạn</h3>
                                <div class="valid-elm input-group">
                                    <input type="date" class="fs-16 form-control" v-model="inventory.expiry_date"
                                    v-bind:class="{'is-invalid': errors.expiry_date}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.expiry_date">{{ errors.expiry_date }}</div>
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
import ItemDashboard from '@/components/ItemDashboard.vue';
import { inventoryApi, productApi, importApi, locationApi } from '@/api';

export default {
    components: {
        ItemDashboard
    },
    data() {
        return {
            products: [], imports: [], locations: [],
            inventory: {
                product_id: '', import_id: ''
            },
            errors: {
                quantity: '', batch_number: '', manufacture_date: '', expiry_date: '', product_id: ''
            },
        }
    },
    watch: {
        '$route'(to, from) {
            if (from.params.id && !to.params.id) {
                this.resetForm();
            }
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                const req = [
                    productApi.getAll(),
                    importApi.getAll(),
                    locationApi.getAll(),
                ];

                if (this.$route.params.id) {
                    req.push(
                        inventoryApi.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.products = res[0].data.data;
                this.imports = res[1].data.data;
                this.locations = res[2].data.data;
                if (this.$route.params.id) this.inventory = res[3].data;
            } catch (error) {
                console.error(error);
            }
        },
        cleanData(inventory) {
            const data = { ...inventory }
            const { addedIdsWithAttributes, updatedIdsWithAttributes } = this.$refs.itemDashboard.getFieldValue()
            const { deletedIds } = this.$refs.itemDashboard.getIds()
            
            return {
                ...data,
                addedIdsWithAttributes,
                updatedIdsWithAttributes,
                deletedIds
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.cleanData(this.inventory);
            
            try {
                if (this.inventory.id) {
                    await inventoryApi.update(this.inventory.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về hàng tồn kho đã được cập nhật!", "success");
                }
                else {
                    await inventoryApi.create(data);
                    await this.$swal.fire("Thêm thành công!", "Hàng tồn kho mới đã được thêm vào hệ thống!", "success");
                }
                this.$router.push({ name: 'admin.inventories' });
            } catch (error) {
                console.error(error);
            }
        },
        removeLocation(id) {
            this.inventory.locations = this.inventory.locations.filter(location => location.id !== id);
        },
        resetForm() {
            this.inventory = {
                batch_number: '', quantity: '', manufacture_date: '', expiry_date: '', product_id: '', import_id: ''
            };
            this.errors = {
                quantity: '', batch_number: '', manufacture_date: '', expiry_date: '', product_id: ''
            };
        },
        validate() {
            let isValid = true;
            this.errors = {
                quantity: '', batch_number: '', manufacture_date: '', expiry_date: '', product_id: ''
            };

            if (this.inventory.quantity === null || this.inventory.quantity === undefined || this.inventory.quantity === '') {
                this.errors.quantity = 'Số lượng không được để trống.';
                isValid = false;
            } else if (this.inventory.quantity < 0) {
                this.errors.quantity = 'Số lượng hàng tồn phải lớn hơn hoặc bằng 0.';
                isValid = false;
            } else if (!Number.isInteger(Number(this.inventory.quantity))) {
                this.errors.quantity = 'Số lượng phải là số nguyên.';
                isValid = false;
            }

            if (!this.inventory.batch_number || this.inventory.batch_number.trim() === '') {
                this.errors.batch_number = 'Số lô không được để trống.';
                isValid = false;
            }

            if (!this.inventory.manufacture_date) {
                this.errors.manufacture_date = 'Ngày sản xuất không được để trống.';
                isValid = false;
            }

            if (!this.inventory.expiry_date) {
                this.errors.expiry_date = 'Ngày hết hạn không được để trống.';
                isValid = false;
            } else if (this.inventory.manufacture_date && new Date(this.inventory.expiry_date) <= new Date(this.inventory.manufacture_date)) {
                this.errors.expiry_date = 'Ngày hết hạn phải sau ngày sản xuất.';
                isValid = false;
            }

            if (!this.inventory.product_id) {
                this.errors.product_id = 'Sản phẩm không được để trống.';
                isValid = false;
            }

            return isValid;
        },
    }
}
</script>