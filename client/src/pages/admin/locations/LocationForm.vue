<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý vị trí kho</h3>
                <router-link v-show="this.$route.params.id" to="/admin/location/create" class="admin-content__create">
                    Thêm vị trí kho
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa vị trí kho</h4>
                        <h4 v-else>Form thêm vị trí kho</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã vị trí kho</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="location.id">
                            </div>
                        </div>
                        <div class="admin-content__form-divided">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Khu vực</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" placeholder="Nhập khu vực" class="fs-16 form-control" v-model="location.zone"
                                    v-bind:class="{'is-invalid': errors.zone}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.zone">{{ errors.zone }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Lối đi</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" placeholder="Nhập lối đi" class="fs-16 form-control" v-model="location.aisle"
                                    v-bind:class="{'is-invalid': errors.aisle}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.aisle">{{ errors.aisle }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-content__form-divided-3">
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Kệ</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" placeholder="Nhập số kệ" class="fs-16 form-control" v-model="location.rack"
                                    v-bind:class="{'is-invalid': errors.rack}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.rack">{{ errors.rack }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Tầng</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" placeholder="Nhập số tầng" class="fs-16 form-control" v-model="location.shelf"
                                    v-bind:class="{'is-invalid': errors.shelf}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.shelf">{{ errors.shelf }}</div>
                                </div>
                            </div>
                            <div class="mb-20">
                                <h3 class="admin-content__form-text">Ngăn</h3>
                                <div class="valid-elm input-group">
                                    <input type="text" placeholder="Nhập số ngăn" class="fs-16 form-control" v-model="location.bin"
                                    v-bind:class="{'is-invalid': errors.bin}" @blur="validate()">
                                    <div class="invalid-feedback" v-if="errors.bin">{{ errors.bin }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Danh mục</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="location.category_id">
                                    <option value="" disabled selected>Chọn danh mục</option>
                                    <option :value="null">Không có danh mục</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Hàng tồn</h3>
                            <ItemDashboard
                                ref="itemDashboard"
                                :items="location.inventories"
                                :options="inventories"
                                :display="(inventory) => `${inventory.product?.name} - Lô ${inventory.batch_number}`"
                                :inputFields="[
                                    { text: 'Số lượng', key: 'quantity', type: 'number' },
                                ]"
                                @remove="removeInventory"
                            />
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="location.status">
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
import ItemDashboard from '@/components/ItemDashboard.vue';
import apiService from '@/utils/apiService';
import { statusService } from '@/utils/statusMap';

export default {
    components: {
        ItemDashboard
    },
    data() {
        return {
            statusOptions: statusService.getOptions('location'),
            categories: [], inventories: [],
            location: {
                category_id: '', status: ''
            },
            errors: {
                zone: '', aisle: '', rack: '', shelf: '', bin: '',
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
        async fetchData() {
            try {
                const req = [
                    apiService.categories.getAll(),
                    apiService.inventories.getAll(),
                ];

                if (this.$route.params.id) {
                    req.push(
                        apiService.locations.getOne(this.$route.params.id)
                    );
                }

                const res = await this.$swal.withLoading(Promise.all(req));

                this.categories = res[0].data.data;
                this.inventories = res[1].data.data;
                if (this.$route.params.id) this.location = res[2].data;
            } catch (error) {
                console.error(error);
            }
        },
        resetForm() {
            this.location = {
                zone: '', aisle: '', rack: '', shelf: '', bin: '', category_id: '', status: ''
            };
            this.errors = {
                zone: '', aisle: '', rack: '', shelf: '', bin: ''
            };
        },
        validate() {
            let isValid = true;
            this.errors = {
                zone: '', aisle: '', rack: '', shelf: '', bin: '',
            }
            if (this.location.zone && this.location.zone.length > 10) {
                this.errors.zone = 'Khu vực không được vượt quá 10 ký tự';
                isValid = false;
            }
            if (this.location.aisle && this.location.aisle.length > 5) {
                this.errors.aisle = 'Lối đi không được vượt quá 5 ký tự';
                isValid = false;
            }
            if (this.location.rack && this.location.rack.length > 5) {
                this.errors.rack = 'Số kệ không được vượt quá 5 ký tự';
                isValid = false;
            }
            if (this.location.shelf && this.location.shelf.length > 5) {
                this.errors.shelf = 'Số tầng không được vượt quá 5 ký tự';
                isValid = false;
            }
            if (this.location.bin && this.location.bin.length > 5) {
                this.errors.bin = 'Số ngăn không được vượt quá 5 ký tự';
                isValid = false;
            }
            return isValid;
        },
        cleanData(location) {
            const data = { ...location }
            const { addedIdsWithAttributes, updatedIdsWithAttributes } = this.$refs.itemDashboard.getFieldValue()
            const { deletedIds } = this.$refs.itemDashboard.getIds()

            if (!data.status) {
                delete data.status
            }

            return {
                ...data,
                addedIdsWithAttributes,
                updatedIdsWithAttributes,
                deletedIds
            }
        },
        async save() {
            if (!this.validate()) return;
            const data = this.cleanData(this.location);

            try {
                if (this.location.id) {
                    await apiService.locations.update(this.location.id, data);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về vị trí kho đã được cập nhật!", "success")
                }
                else {
                    await apiService.locations.create(data);
                    await this.$swal.fire("Thêm thành công!", "Vị trí kho mới đã được thêm vào hệ thống!", "success")
                }
                this.$router.push({ name: 'admin.locations' });
            } catch (error) {
                console.error(error);
            }
        },
        removeInventory(id) {
            this.location.inventories = this.location.inventories.filter(inventory => inventory.id !== id);
        },
    }
}
</script>