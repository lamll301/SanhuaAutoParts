<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý đơn vị tính</h3>
                <router-link v-show="this.$route.params.id" to="/admin/unit/create" class="admin-content__create">
                    Thêm đơn vị tính
                </router-link>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa đơn vị tính</h4>
                        <h4 v-else>Form thêm đơn vị tính</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã đơn vị tính</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="unit.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tên đơn vị tính</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên đơn vị tính" v-model="unit.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-20" style="height: 114px;">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập mô tả đơn vị tính" v-model="unit.description"
                                v-bind:class="{'is-invalid': errors.description}" @blur="validate()"></textarea>
                                <div class="invalid-feedback" v-if="errors.description">{{ errors.description }}</div>
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
import { unitApi } from '@/api';

export default {
    data() {
        return {
            unit: {},
            errors: {
                name: '', description: ''
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
                name: '',
                description: '',
            };
            
            if (!this.unit.name || this.unit.name.trim() === '') {
                this.errors.name = 'Tên đơn vị là bắt buộc';
                isValid = false;
            } else if (this.unit.name.length > 255) {
                this.errors.name = 'Tên đơn vị không được vượt quá 255 ký tự';
                isValid = false;
            }
            
            if (this.unit.description && this.unit.description.length > 255) {
                this.errors.description = 'Mô tả không được vượt quá 255 ký tự';
                isValid = false;
            }
            
            return isValid;
        },
        async fetchData() {
            try {
                if (this.$route.params.id) {
                    const res = await this.$swal.withLoading(unitApi.getOne(this.$route.params.id));
                    this.unit = res.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async save() {
            if (!this.validate()) return;
            try {
                if (this.unit.id) {
                    await unitApi.update(this.unit.id, this.unit);
                    await this.$swal.fire("Cập nhật thành công!", "Thông tin về đơn vị tính đã được cập nhật!", "success");
                }
                else {
                    await unitApi.create(this.unit);
                    await this.$swal.fire("Thêm thành công!", "Đơn vị tính mới đã được thêm vào hệ thống!", "success");
                }
                this.$router.push({ name: 'admin.units' });
            } catch (error) {
                console.error(error)
            }
        },
        resetForm() {
            this.unit = {
                name: '', description: ''
            };
            this.errors = {
                name: '', description: ''
            };
        },
    }
}
</script>