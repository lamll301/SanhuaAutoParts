<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Trang chủ</h3>
            </div>
            <!-- admin card -->
            <div class="admin-content__card-mold">
                <div class="bg-blue admin-content__card">
                    <div class="admin-content__card-body">
                        <div class="admin-content__card-text">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <i class="dark-blue admin-content__card-icon fa-solid fa-bag-shopping"></i>
                    </div>
                    <a href="#" class="admin-content__card-link white bg-dark-blue">
                        <p>Xem thêm</p>
                        <i class="fa-solid fa-circle-arrow-right"></i>
                    </a>
                </div>
                <div class="bg-green admin-content__card">
                    <div class="admin-content__card-body">
                        <div class="admin-content__card-text">
                            <h3>50%</h3>
                            <p>Bounce Rate</p>
                        </div>
                        <i class="dark-green admin-content__card-icon fa-solid fa-bag-shopping"></i>
                    </div>
                    <a href="#" class="admin-content__card-link white bg-dark-green">
                        <p>Xem thêm</p>
                        <i class="fa-solid fa-circle-arrow-right"></i>
                    </a>
                </div>
                <div class="bg-yellow admin-content__card">
                    <div class="admin-content__card-body">
                        <div class="black admin-content__card-text">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <i class="dark-yellow admin-content__card-icon fa-solid fa-bag-shopping"></i>
                    </div>
                    <a href="#" class="admin-content__card-link black bg-dark-yellow">
                        <p>Xem thêm</p>
                        <i class="fa-solid fa-circle-arrow-right"></i>
                    </a>
                </div>
                <div class="bg-red admin-content__card">
                    <div class="admin-content__card-body">
                        <div class="admin-content__card-text">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <i class="dark-red admin-content__card-icon fa-solid fa-bag-shopping"></i>
                    </div>
                    <a href="#" class="admin-content__card-link white bg-dark-red">
                        <p>Xem thêm</p>
                        <i class="fa-solid fa-circle-arrow-right"></i>
                    </a>
                </div>
            </div>
            <!-- chart -->
            <div class="admin-content__chart-mold">
                <div class="admin-content__chart">
                    <LineChart :chartData="lineData" :chartOptions="chartOptions"/>
                </div>
                <div class="admin-content__form-divided">
                    <div class="admin-content__chart">
                        <BarChart :chartData="barData" :chartOptions="chartOptions"/>
                    </div>
                    <div class="admin-content__chart">
                        <DoughnutChart :chartData="doughnutData" :chartOptions="chartOptions"/>
                    </div>
                </div>
            </div>
            <!-- table -->
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4>Báo cáo thống kê</h4>
                    <select id="selectReport" class="form-select admin-content__checkbox-select-all-opts" @change="renderSelectChange">
                        <option value="" selected>-- Chọn loại --</option>
                        <option value="">Sản phẩm</option>
                        <option value="">Đơn hàng</option>
                        <option value="">Khách hàng</option>
                        <option value="">Khuyến mãi</option>
                    </select>
                    <select id="selectFilter" class="form-select admin-content__checkbox-select-all-opts" @change="renderSelectChange">
                        <option value="" selected>-- Chọn bộ lọc --</option>
                        <option value="">Thời gian</option>
                    </select>
                    <button class="fs-16 btn btn-primary disabled" id="btnCheckboxSubmit" @click="btnCheckboxSubmitClicked()">Thực hiện</button>
                </div>
                <table class="table admin-content__table-main">
                    <thead class="admin-content__table-main-head">
                        <tr class="admin-content__table-first-row">
                            <th scope="col">
                                <input class="form-check-input admin-content__checkbox" type="checkbox" ref="checkboxAll" @change="onCheckboxAllChange($event)">
                            </th>
                            <th scope="col">ID
                                <SortComponent field="id" :sort="sort"/>
                            </th>
                            <th scope="col">Ngày tạo
                                <SortComponent field="createdAt" :sort="sort"/>
                            </th>
                            <th scope="col">Ngày cập nhật
                                <SortComponent field="updatedAt" :sort="sort"/>
                            </th>
                            <th scope="col" class="col-2"></th>
                        </tr>
                    </thead>
                    <tbody class="admin-content__table-main-body">
                        <template v-if="items">
                            <tr class="admin-content__table-row" v-for="item in items" :key="item._id">
                                <th>
                                    <input class="form-check-input" type="checkbox" ref="checkboxes" :value="item._id" @change="onCheckboxChange()">
                                </th>
                                <th>{{ item.id }}</th>
                                <th>{{ item.createdAt }}</th>
                                <th>{{ item.updatedAt }}</th>
                                <td>
                                    <router-link :to="'/admin/item/edit/' + item._id" class="fs-16 btn btn-primary">Sửa</router-link>&nbsp;
                                    <button class="fs-16 btn btn-danger" @click="onDelete(item._id)">Xóa</button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="13" class="text-center">
                                    Bạn chưa có dữ liệu nào.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="admin-content__table-footer"></div>
            </div>
            <AdminPagination :totalPages="totalPages" :currentPage="currentPage"/>
        </div>
    </div>
</template>

<script>
import LineChart from '@/components/LineChart.vue';
import BarChart from '@/components/BarChart.vue';
import DoughnutChart from '@/components/DoughnutChart.vue';
import AdminPagination from '@/components/AdminPagination.vue';
import SortComponent from '@/components/SortComponent.vue';

export default {
    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            lineData: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [
                    {
                        label: 'Doanh thu',
                        data: [65, 59, 80, 81, 56],
                        borderColor: '#4CAF50',
                        tension: 0.1
                    }
                ]
            },
            barData: {
                labels: ['Sản phẩm A', 'Sản phẩm B', 'Sản phẩm C'],
                datasets: [
                    {
                        label: 'Bán hàng',
                        data: [12, 19, 3],
                        backgroundColor: ['#2196F3', '#4CAF50', '#FFC107']
                    }
                ]
            },
            doughnutData: {
                labels: ['Đỏ', 'Xanh', 'Vàng'],
                datasets: [
                    {
                        data: [300, 50, 100],
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                    }
                ]
            },
            items: [],
            sort: {}, totalPages: 0, currentPage: 1,
        }
    },
    components: {
        LineChart, BarChart, DoughnutChart, 
        AdminPagination, SortComponent
    },
    methods: {

    },
}
</script>