<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý tin tức</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-content__form">
                    <div class="admin-content__header">
                        <h4 v-if="this.$route.params.id">Form sửa tin tức</h4>
                        <h4 v-else>Form thêm tin tức</h4>
                    </div>
                    <form @submit.prevent="save()">
                    <div class="admin-content__form-body">
                        <div v-if="this.$route.params.id" class="mb-20">
                            <h3 class="admin-content__form-text">Mã tin tức</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" disabled v-model="article.id">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tiêu đề</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tiêu đề" v-model="article.title"
                                v-bind:class="{'is-invalid': errors.title}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.title">{{ errors.title }}</div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Tác giả</h3>
                            <div class="valid-elm input-group">
                                <input type="text" class="fs-16 form-control" placeholder="Nhập tên tác giả" v-model="article.author"
                                v-bind:class="{'is-invalid': errors.author}" @blur="validate()">
                                <div class="invalid-feedback" v-if="errors.author">{{ errors.author }}</div>
                            </div>
                        </div>
                        <div class="mb-20 height-105">
                            <h3 class="admin-content__form-text">Highlight</h3>
                            <div class="valid-elm input-group">
                                <textarea class="fs-16 form-control" rows="3" placeholder="Nhập highlight tin tức" v-model="article.highlight"></textarea>
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Nội dung</h3>
                            <RichTextEditor v-model="article.content" @input="handleEditorInput"/>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Ngày xuất bản</h3>
                            <div class="valid-elm input-group">
                                <input type="datetime-local" class="fs-16 form-control" v-model="article.publish_date">
                            </div>
                        </div>
                        <div class="mb-20">
                            <h3 class="admin-content__form-text">Trạng thái</h3>
                            <div class="input-group">
                                <select class="valid-elm form-select" v-model="article.status">
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
// import { swalFire } from '@/utils/swal.js';
import apiService from '@/utils/apiService';
import { handleApiCall } from '@/utils/errorHandler';
import { statusService } from '@/utils/statusService';
import RichTextEditor from '@/components/RichTextEditor.vue';

export default {
    components: {
        RichTextEditor
    },
    data() {
        return {
            article: {
                status: '',
            },
            statusOptions: statusService.getOptions('article'),
            errors: {
                title: '',
                author: '',
            },
        }
    },
    async created() {
        if (this.$route.params.id) {
            await this.fetchData();
        }
    },
    methods: {
        validate() {
            let isValid = true;
            this.errors = {
                title: '',
                author: '',
            }
            if (!this.article.title) {
                this.errors.title = 'Tiêu đề không được để trống.';
                isValid = false;
            }
            if (!this.article.author) {
                this.errors.author = 'Tên tác giả không được để trống.';
                isValid = false;
            }
            return isValid;
        },
        async fetchData() {
            const res = await handleApiCall(() => this.$request.get(apiService.articles.view(this.$route.params.id)));
            this.article = res;
        },
        async save() {
            
            console.log(this.article); 

            // if (!this.validate()) return;
            // const payload = Object.fromEntries(
            //     Object.entries(this.article).filter(([, value]) => 
            //         value !== null && value !== undefined && value !== ''
            //     )
            // );

            // if (this.article.id) {
            //     await handleApiCall(() => this.$request.put(apiService.articles.update(this.article.id), payload));
            //     await swalFire("Cập nhật thành công!", "Thông tin về tin tức đã được cập nhật!", "success");
            // }
            // else {
            //     await handleApiCall(() => this.$request.post(apiService.articles.create(), payload));
            //     await swalFire("Thêm thành công!", "Tin tức mới đã được thêm vào hệ thống!", "success");
            // }
            // this.$router.push({ name: 'admin.articles' });
        },
        handleEditorInput(content) {
            this.article.content = content;
        },
    }
}
</script>