<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý vai trò</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa vai trò</h4>
                        <h4 v-else>Form thêm vai trò</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-16">
                            <h3 class="admin-content__form-text">Mã vai trò</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="role.id">
                            </div>
                        </div>
                        <div class="mb-16">
                            <h3 class="admin-content__form-text">Tên vai trò</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên vai trò" v-model="role.name"
                                v-bind:class="{'is-invalid': errors.name}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
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
            role: {},
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
                const res = await this.$request.get(`${process.env.VUE_APP_API_BASE_URL}/api/roles/${this.$route.params.id}`);
                this.role = res.data;
            }
            catch (error) {
                swalFire("Lỗi!", error, "error");
            }
        },
        save() {
            if (!this.validate()) {
                return;
            }

            if (this.role.id) {
                this.$request.put(`${process.env.VUE_APP_API_BASE_URL}/api/roles/${this.role.id}`, this.role).then(() => {
                    this.swalFire("Cập nhật thành công!", "Thông tin về vai trò đã được cập nhật!", "success")
                    .then(() => {
                        this.$router.push({name: 'admin.roles'})
                    })
                })
                .catch(error => {
                    swalFire("Lỗi!", error, "error");
                })
            }
            else {
                this.$request.post(`${process.env.VUE_APP_API_BASE_URL}/api/roles`, this.role).then(() => {
                    this.swalFire("Thêm thành công!", "Vai trò mới đã được thêm vào hệ thống!", "success")
                    .then(() => {
                        this.$router.push({name: 'admin.roles'})
                    })
                })
                .catch(error => {
                    swalFire("Lỗi!", error, "error");
                })
            }
        },
        validate() {
            let isValid = true;
            this.errors = {
                name: '',
            }
            if (!this.role.name) {
                this.errors.name = 'Tên vai trò không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        swalFire, swalMixin
    }
}
</script>