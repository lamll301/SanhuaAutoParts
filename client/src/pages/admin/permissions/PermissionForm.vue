<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý phân quyền</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa phân quyền</h4>
                        <h4 v-else>Form thêm phân quyền</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-16">
                            <h3 class="admin-content__form-text">Mã</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="permission.id">
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Tên</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên phân quyền" v-model="permission.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="mb-16 height-105">
                            <h3 class="admin-content__form-text">Mô tả</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập mô tả phân quyền" v-model="permission.description"></textarea>
                            </div>
                        </div>
                        <div class="mb-16 admin-content__form-btn">
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
import { swalFire, swalMixin } from '@/helpers/swal.js'

export default {
    data() {
        return {
            permission: {},
            errors: {
                name: '',
            },
        }
    },
    created() {
        if (this.$route.params.id) {
            this.fetchData();
        }
    },
    methods: {
        async fetchData() {
            try {
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/${this.$route.params.id}`);
                this.permission = res.data;
            }
            catch (error) {
                console.log(error);
            }
        },
        save() {
            if (!this.validate()) {
                return;
            }

            if (this.permission.id) {
                this.$request.put(`${process.env.VUE_APP_API_BASE_URL}/api/permissions/${this.permission.id}`, this.permission).then(() => {
                    this.swalFire("Cập nhật thành công!", "Thông tin về phân quyền đã được cập nhật!", "success")
                    .then(() => {
                        this.$router.push({name: 'admin.permissions'})
                    })
                })
                .catch(error => {
                    console.log(error)
                })
            }
            else {
                this.$request.post(`${process.env.VUE_APP_API_BASE_URL}/api/permissions`, this.permission).then(() => {
                    this.swalFire("Thêm thành công!", "Phân quyền mới đã được thêm vào hệ thống!", "success")
                    .then(() => {
                        this.$router.push({name: 'admin.permissions'})
                    })
                })
                .catch(error => {
                    console.log(error)
                })
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                name: '',
            }
            if (!this.permission.name) {
                this.errors.name = 'Tên phân quyền không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        swalFire, swalMixin
    }
}
</script>