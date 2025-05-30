<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Trang chủ</h3>
            </div>
            <!-- admin card -->
            <div class="admin-content__card-mold">
                <div v-for="card in dashboardCards" :key="card.label" :class="`bg-${card.color} admin-content__card`">
                    <div class="admin-content__card-body">
                        <div :class="`admin-content__card-text ${card.color === 'yellow' ? 'black' : ''}`">
                            <h3>{{ card.value }}</h3>
                            <p>{{ card.label }}</p>
                        </div>
                        <i :class="`dark-${card.color} admin-content__card-icon fa-solid ${card.icon}`"></i>
                    </div>
                    <a href="#" :class="`admin-content__card-link ${card.color === 'yellow' ? 'black' : 'white'} bg-dark-${card.color}`">
                        <p>Xem thêm</p>
                        <i class="fa-solid fa-circle-arrow-right"></i>
                    </a>
                </div>
            </div>
            <!-- chart -->
            <div class="admin-content__chart-mold">
                <div class="admin-content__chart" v-if="isShowLineChart">
                    <LineChart :chartData="lineData" :key="chartKey" :chartOptions="chartOptions"/>
                </div>
                <div class="admin-content__chart" v-if="isShowBarChart">
                    <BarChart :chartData="barData" :key="chartKey" :chartOptions="chartOptions"/>
                </div>
                <div class="admin-content__chart" v-if="isShowDoughnutChart">
                    <DoughnutChart :chartData="doughnutData" :key="chartKey" :chartOptions="chartOptions"/>
                </div>
            </div>
            <!-- table -->
            <div class="admin-content__table">
                <div class="admin-content__header d-flex align-items-center">
                    <h4>Báo cáo thống kê</h4>
                    <select ref="selectReport" class="form-select admin-content__checkbox-select-all-opts" 
                    @change="renderSelectReportChange">
                        <option value="" selected>-- Chọn loại --</option>
                        <option value="revenueByPeriod">Doanh thu</option>
                        <option value="profitByPeriod">Lợi nhuận</option>
                        <option value="bestSellingProducts">Sản phẩm bán chạy</option>
                        <option value="expiredProducts">Sản phẩm tồn kho</option>
                        <option value="customer">Khách hàng</option>
                        <option value="order">Đơn hàng</option>
                    </select>
                    <select ref="selectFilter" class="form-select admin-content__checkbox-select-all-opts" style="width: 148px;" v-model="selectedFilter"
                    @change="renderSelectFilterChange">
                        <option value="" selected>-- Chọn bộ lọc --</option>
                        <option value="filterByDay" v-show="isShowFilterByPeriod">Lọc theo ngày</option>
                        <option value="filterByWeek" v-show="isShowFilterByPeriod">Lọc theo tuần</option>
                        <option value="filterByMonth" v-show="isShowFilterByPeriod">Lọc theo tháng</option>
                        <option value="filterByQuarter" v-show="isShowFilterByPeriod">Lọc theo quý</option>
                        <option value="filterByYear" v-show="isShowFilterByPeriod">Lọc theo năm</option>
                        <option value="filterByRevenue" v-show="this.selectedReport === 'bestSellingProducts' || this.selectedReport === 'customer'">Lọc theo {{ this.selectedReport === 'customer' ? 'chi tiêu' : 'doanh thu' }}</option>
                        <option value="filterBySold" v-show="this.selectedReport === 'bestSellingProducts'">Lọc theo số lượng bán</option>
                        <option value="filterByOrdersCount" v-show="this.selectedReport === 'customer'">Lọc theo số lượng đơn</option>
                        <option value="filterByTotalQuantity" v-show="this.selectedReport === 'customer'">Lọc theo số lượng sản phẩm mua</option>
                        <option value="filterByStatus" v-show="isShowFilterOrder">Lọc theo trạng thái</option>
                        <option value="filterByPaymentMethod" v-show="isShowFilterOrder">Lọc theo phương thức thanh toán</option>
                        <option value="filterByPaymentStatus" v-show="isShowFilterOrder">Lọc theo trạng thái thanh toán</option>
                        <option value="filterByExpired" v-show="isShowFilterExpired">Lọc theo ngày hết hạn</option>
                        <option value="filterByExpiredQuantity" v-show="isShowFilterExpired">Lọc theo số lượng</option>
                    </select>
                    <div class="admin-content__input-date-group" v-show="isShowInputDate">
                        <label for="">Từ ngày:</label>
                        <input type="date" class="form-control admin-content__input-date" v-model="startDate">
                    </div>
                    <div class="admin-content__input-date-group" v-show="isShowInputDate">
                        <label for="">Đến ngày:</label>
                        <input type="date" class="form-control admin-content__input-date" v-model="endDate">
                    </div>
                    <div class="admin-content__input-date-group" v-show="isShowLimit">
                        <input type="number" class="form-control" style="width: 56px; font-size: 1.4rem; margin-right: 20px;" v-model="limit">
                    </div>
                    <button class="fs-16 btn btn-primary" id="btnCheckboxSubmit" @click="filterData()">Thực hiện</button>
                </div>
                <table class="table admin-content__table-main" v-if="isShowOrderTable">
                    <thead class="admin-content__table-main-head">
                        <tr class="admin-content__table-first-row">
                            <th scope="col">ID
                                <SortComponent field="id" :sort="sort"/>
                            </th>
                            <th scope="col">Tên
                                <SortComponent field="name" :sort="sort"/>
                            </th>
                            <th scope="col">Số điện thoại
                                <SortComponent field="phone" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'revenueByPeriod'">Tiền hàng
                                <SortComponent field="product_total" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'revenueByPeriod'">Phí ship
                                <SortComponent field="shipping_fee" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'revenueByPeriod'">Giảm giá</th>
                            <th scope="col">Tổng tiền
                                <SortComponent field="total_amount" :sort="sort"/>
                            </th>
                            <th scope="col">Phương thức TT
                                <SortComponent field="payment_method" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'order'">Trạng thái TT
                                <SortComponent field="payment_status" :sort="sort"/>
                            </th>
                            <th scope="col">Thông tin thanh toán
                                <SortComponent field="payment_info" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'order'">Trạng thái đơn hàng
                                <SortComponent field="status" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'order'">Ngày hủy
                                <SortComponent field="cancelled_at" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'order'">Ngày đóng gói
                                <SortComponent field="packed_at" :sort="sort"/>
                            </th>
                            <th scope="col" v-show="this.currentReport === 'order'">Ngày giao hàng
                                <SortComponent field="shipped_at" :sort="sort"/>
                            </th>
                            <th scope="col">Ngày nhận hàng
                                <SortComponent field="completed_at" :sort="sort"/>
                            </th>
                            <th scope="col">Ngày tạo
                                <SortComponent field="created_at" :sort="sort"/>
                            </th>
                            <th scope="col">Ngày cập nhật
                                <SortComponent field="updated_at" :sort="sort"/>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="admin-content__table-main-body">
                        <template v-if="orders.length > 0">
                            <tr class="admin-content__table-row" v-for="order in orders" :key="order.id">
                                <th>{{ order.id }}</th>
                                <td>{{ order.name }}</td>
                                <td>{{ order.phone }}</td>
                                <td v-show="this.currentReport === 'revenueByPeriod'">
                                    {{ formatPrice(order.product_total) }}
                                </td>
                                <td v-show="this.currentReport === 'revenueByPeriod'">
                                    {{ formatPrice(order.shipping_fee) }}
                                </td>
                                <td v-show="this.currentReport === 'revenueByPeriod'">
                                    {{ order.voucher ? formatPrice(order.voucher.value) + ' (' + order.voucher.code + ')' : 'Không có' }}
                                </td>
                                <td>{{ formatPrice(order.total_amount) }}</td>
                                <td>{{ order.payment_method }}</td>
                                <td v-show="this.currentReport === 'order'">{{ order.payment_method === 'Thanh toán khi nhận hàng' ? 'TT khi nhận' : getStatusText('payment', order.payment_status) }}</td>
                                <td>{{ order.payment_info }}</td>
                                <td v-show="this.currentReport === 'order'">{{ getStatusText('order', order.status) }}</td>
                                <td v-show="this.currentReport === 'order'">{{ order.cancelled_at ? formatDate(order.cancelled_at) : 'Chưa hủy' }}</td>
                                <td v-show="this.currentReport === 'order'">{{ order.packed_at ? formatDate(order.packed_at) : 'Chưa đóng gói' }}</td>
                                <td v-show="this.currentReport === 'order'">{{ order.shipped_at ? formatDate(order.shipped_at) : 'Chưa giao hàng' }}</td>
                                <td>{{ order.completed_at ? formatDate(order.completed_at) : 'Chưa nhận hàng' }}</td>
                                <td>{{ formatDate(order.created_at) }}</td>
                                <td>{{ formatDate(order.updated_at) }}</td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="20" class="text-center">
                                    Bạn chưa có dữ liệu nào.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <table class="table admin-content__table-main" v-if="isShowBestSellingProductsTable">
                    <thead class="admin-content__table-main-head">
                        <tr class="admin-content__table-first-row">
                            <th scope="col">Hạng</th>
                            <th scope="col">ID</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Nhà cung cấp</th>
                            <th scope="col">Số lượng bán</th>
                            <th scope="col">Doanh thu</th>
                            <th scope="col">Giá bán trung bình</th>
                        </tr>
                    </thead>
                    <tbody class="admin-content__table-main-body">
                        <template v-if="bestSellingProducts.length > 0">
                            <tr class="admin-content__table-row" v-for="(product, index) in bestSellingProducts" :key="product.id">
                                <th>{{ index + 1 }}</th>
                                <td>{{ product.id }}</td>
                                <td>{{ product.name }}</td>
                                <td><img :src="getImageUrl(product.thumbnail_path)" alt="Hình ảnh sản phẩm" class="admin-content__table-img-cell"></td>
                                <td>{{ product.supplier_name }}</td>
                                <td>{{ product.total_quantity }}</td>
                                <td>{{ formatPrice(product.total_revenue) }}</td>
                                <td>{{ formatPrice(product.average_selling_price) }}</td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="20" class="text-center">
                                    Bạn chưa có dữ liệu nào.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <table class="table admin-content__table-main" v-if="isShowCustomerTable">
                    <thead class="admin-content__table-main-head">
                        <tr class="admin-content__table-first-row">
                            <th scope="col">Hạng</th>
                            <th scope="col">ID</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Số đơn hàng</th>
                            <th scope="col">Chi tiêu</th>
                            <th scope="col">Số sản phẩm đã mua</th>
                            <th scope="col">Ngày mua gần nhất</th>
                        </tr>
                    </thead>
                    <tbody class="admin-content__table-main-body">
                        <template v-if="customers.length > 0">
                            <tr class="admin-content__table-row" v-for="(customer, index) in customers" :key="customer.id">
                                <th>{{ index + 1 }}</th>
                                <td>{{ customer.id }}</td>
                                <td>{{ customer.name }}</td>
                                <td>{{ customer.email }}</td>
                                <td>{{ customer.phone }}</td>
                                <td>{{ customer.total_orders }}</td>
                                <td>{{ formatPrice(customer.total_revenue) }}</td>
                                <td>{{ customer.total_quantity }}</td>
                                <td>{{ customer.last_order_date ? formatDate(customer.last_order_date) : 'Chưa mua hàng' }}</td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="20" class="text-center">
                                    Bạn chưa có dữ liệu nào.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <table class="table admin-content__table-main" v-if="isShowExpiredProductsTable">
                    <thead class="admin-content__table-main-head">
                        <tr class="admin-content__table-first-row">
                            <th scope="col">ID hàng tồn</th>
                            <th scope="col">Mã lô</th>
                            <th scope="col">ID sản phẩm</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Nhà cung cấp</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Ngày hết hạn</th>
                            <th scope="col">Ngày hết hạn còn lại</th>
                        </tr>
                    </thead>
                    <tbody class="admin-content__table-main-body">
                        <template v-if="expiredProducts.length > 0">
                            <tr class="admin-content__table-row" v-for="product in expiredProducts" :key="product.id">
                                <th>{{ product.inventory_id }}</th>
                                <td>{{ product.batch_number }}</td>
                                <td>{{ product.product_id }}</td>
                                <td>{{ product.name }}</td>
                                <td><img :src="getImageUrl(product.thumbnail_path)" alt="Hình ảnh sản phẩm" class="admin-content__table-img-cell"></td>
                                <td>{{ product.supplier_name }}</td>
                                <td>{{ product.quantity }}</td>
                                <td>{{ product.expiry_date }}</td>
                                <td>{{ product.days_until_expiry }} ngày</td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="20" class="text-center">
                                    Bạn chưa có dữ liệu nào.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="admin-content__table-footer"></div>
            </div>
            <AdminPagination :totalPages="totalPages" :currentPage="currentPage" v-if="isShowPagination"/>
        </div>
    </div>
</template>

<script>
import LineChart from '@/components/LineChart.vue';
import BarChart from '@/components/BarChart.vue';
import DoughnutChart from '@/components/DoughnutChart.vue';
import AdminPagination from '@/components/AdminPagination.vue';
import SortComponent from '@/components/SortComponent.vue';
import { statisticalApi } from '@/api';
import { formatPrice, formatDate, getImageUrl } from '@/utils/helpers';
import { getAllStatusOptions, getStatusText } from '@/utils/statusMap';

export default {
    data() {
        return {
            chartKey: 0,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const datasetIndex = context[0].datasetIndex;
                                const dataIndex = context[0].dataIndex;
                                const dataset = context[0].chart.data.datasets[datasetIndex];
                                return dataset.fullNames ? dataset.fullNames[dataIndex] : context[0].label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            },
            lineData: {},
            barData: {},
            doughnutData: {},
            orders: [], bestSellingProducts: [], customers: [], expiredProducts: [],
            sort: {}, totalPages: 0, currentPage: 1,
            isShowLineChart: false, isShowBarChart: false, isShowDoughnutChart: false,
            isShowOrderTable: false, isShowBestSellingProductsTable: false, isShowCustomerTable: false,
            isShowExpiredProductsTable: false,
            selectedReport: 'revenueByPeriod',  selectedFilter: '', startDate: '', endDate: '', limit: 10, currentReport: 'revenueByPeriod',
            dashboardCards: [
                {
                    value: 'N/A',
                    label: 'Doanh thu tháng',
                    color: 'blue',
                    icon: 'fa-sack-dollar',
                },
                {
                    value: 'N/A',
                    label: 'Tỷ lệ hoàn thành',
                    color: 'green',
                    icon: 'fa-check',
                },
                {
                    value: 'N/A',
                    label: 'Đơn mới tạo',
                    color: 'yellow',
                    icon: 'fa-bag-shopping',
                },
                {
                    value: 'N/A',
                    label: 'Tỷ lệ đánh giá tích cực',
                    color: 'red',
                    icon: 'fa-star',
                }
            ],
        }
    },
    components: {
        LineChart, BarChart, DoughnutChart, 
        AdminPagination, SortComponent
    },
    created() {
        this.fetchData();
    },
    watch: {
        '$route': {
            handler(to, from) {
                if (!to || !to.query) return;
                const newPage = to.query.page;
                const oldPage = from?.query?.page;
                const newSort = to.query._sort;
                const oldSort = from?.query?._sort;
                const newColumn = to.query.column;
                const oldColumn = from?.query?.column;
                const newType = to.query.type;
                const oldType = from?.query?.type;

                if (newPage !== oldPage || newSort !== oldSort || newColumn !== oldColumn || newType !== oldType) {
                    switch (this.selectedReport) {
                        case 'order':
                        case 'profitByPeriod':
                        case 'revenueByPeriod': {
                            this.fetchCompletedOrdersByPeriodData();
                            break;
                        }
                        case 'expiredProducts': {
                            const query = { ...to.query };
                            this.fetchExpiredProductsData(query);
                            break;
                        }
                    }
                }
            },
            immediate: true
        },
    },
    computed: {
        isShowLimit() {
            return this.selectedReport === 'bestSellingProducts' || this.selectedReport === 'customer';
        },
        isShowInputDate() {
            return this.selectedReport !== 'expiredProducts';
        },
        isShowFilterByPeriod() {
            return this.selectedReport === 'revenueByPeriod' || this.selectedReport === 'profitByPeriod';
        }, 
        isShowFilterOrder() {
            return this.selectedReport === 'order';
        },
        isShowFilterExpired() {
            return this.selectedReport === 'expiredProducts';
        },
        isShowPagination() {
            return this.selectedReport === 'revenueByPeriod' || this.selectedReport === 'order' || this.selectedReport === 'expiredProducts' || this.selectedReport === 'profitByPeriod';
        }
    },
    methods: {
        formatPrice, formatDate, getStatusText, getAllStatusOptions, getImageUrl,
        async fetchData() {
            try {
                const res = await this.$swal.withLoading(statisticalApi.summary());
                this.dashboardCards[0].value = (res.data.revenue / 1_000_000).toFixed(1) + ' triệu';
                this.dashboardCards[1].value = res.data.completion_rate.toFixed(1) + '%';
                this.dashboardCards[2].value = res.data.orders_count;
                this.dashboardCards[3].value = res.data.positive_rate.toFixed(1) + '%';
                await this.fetchRevenueByPeriodData();
            } catch (e) {
                console.error(e);
            }
        },
        async fetchCompletedOrdersByPeriodData() {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getCompletedOrdersByPeriod(this.$route.query));
                this.orders = res.data.data || [];
                this.totalPages = Math.ceil(res.data.pagination.total / res.data.pagination.per_page);
                this.currentPage = res.data.pagination.current_page;
                this.sort = res.data._sort;
            } catch (e) {
                console.error(e);
            }
        },
        async fetchRevenueByPeriodData(query = {}) {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getRevenueByPeriod(query));
                const revenue = res.data.data;
                const period = res.data.period;
                const dateRange = res.data.date_range;
                this.lineData = {
                    labels: revenue.map(item => item.period),
                    datasets: [{
                        label: 'Thống kê doanh thu theo ' + (period === 'day' ? 'ngày' : period === 'week' ? 'tuần' : period === 'month' ? 'tháng' : period === 'quarter' ? 'quý' : 'năm' ) + ' (' + dateRange + ')',
                        data: revenue.map(item => item.revenue),
                        borderColor: '#4CAF50',
                        tension: 0.1
                    }]
                };
                this.chartKey++;
                this.resetChart();
                this.resetTable();
                this.isShowLineChart = true;
                this.isShowOrderTable = true;
                this.currentReport = 'revenueByPeriod';
                await this.fetchCompletedOrdersByPeriodData();
            } catch (e) {
                console.error(e);
            }
        },
        async fetchProfitByPeriodData(query = {}) {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getProfitByPeriod(query));
                const profit = res.data.data;
                const period = res.data.period;
                const dateRange = res.data.date_range;
                this.lineData = {
                    labels: profit.map(item => item.period),
                    datasets: [
                        {
                            label: 'Thống kê lợi nhuận theo ' + (period === 'day' ? 'ngày' : period === 'week' ? 'tuần' : period === 'month' ? 'tháng' : period === 'quarter' ? 'quý' : 'năm' ) + ' (' + dateRange + ')',
                            data: profit.map(item => item.profit),
                            borderColor: '#4CAF50',
                            tension: 0.1
                        },
                        {
                            label: 'Tiền sản phẩm bán được theo ' + (period === 'day' ? 'ngày' : period === 'week' ? 'tuần' : period === 'month' ? 'tháng' : period === 'quarter' ? 'quý' : 'năm' ) + ' (' + dateRange + ')',
                            data: profit.map(item => item.gross_sales),
                            borderColor: '#FF9800',
                            tension: 0.1
                        }
                    ]
                };
                this.chartKey++;
                this.resetChart();
                this.resetTable();
                this.isShowLineChart = true;
                this.isShowOrderTable = true;
                this.currentReport = 'revenueByPeriod';
                await this.fetchCompletedOrdersByPeriodData();
            } catch (e) {
                console.error(e);
            }
        },
        async fetchBestSellingProductsData(query = {}) {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getBestSellingProducts(query));
                this.bestSellingProducts = res.data.data;
                let data = [];
                if (this.selectedFilter === 'filterBySold') {
                    data = this.bestSellingProducts.map(item => item.total_quantity);
                } else {
                    data = this.bestSellingProducts.map(item => item.total_revenue);
                }
                const truncatedLabels = this.bestSellingProducts.map(item => this.truncateText(item.name));
                const fullNames = this.bestSellingProducts.map(item => item.name);
                this.barData = {
                    labels: truncatedLabels,
                    datasets: [{
                        label: 'Sản phẩm bán chạy theo ' + (this.selectedFilter === 'filterBySold' ? 'số lượng' : 'doanh thu') + ' (' + res.data.date_range + ')',
                        data: data,
                        backgroundColor: ['#2196F3', '#4CAF50', '#FFC107'],
                        fullNames: fullNames
                    }]
                };
                this.chartKey++;
                this.resetChart();
                this.resetTable();
                this.isShowBarChart = true;
                this.isShowBestSellingProductsTable = true;
            } catch (e) {
                console.error(e);
            }
        },
        async fetchCustomerStatisticsData(query = {}) {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getCustomerStatistics(query));
                this.customers = res.data.data;
                let data = [];
                if (this.selectedFilter === 'filterByOrdersCount') {
                    data = this.customers.map(item => item.total_orders);
                } else if (this.selectedFilter === 'filterByTotalQuantity') {
                    data = this.customers.map(item => item.total_quantity);
                } else {
                    data = this.customers.map(item => item.total_revenue);
                }
                const truncatedLabels = this.customers.map(item => this.truncateText(item.name));
                const fullNames = this.customers.map(item => item.name);
                this.barData = {
                    labels: truncatedLabels,
                    datasets: [{
                        label: 'Khách hàng theo ' + (this.selectedFilter === 'filterByOrdersCount' ? 'số lượng đơn' : this.selectedFilter === 'filterByTotalQuantity' ? 'số lượng sản phẩm mua' : 'chi tiêu') + ' (' + res.data.date_range + ')',
                        data: data,
                        backgroundColor: ['#2196F3', '#4CAF50', '#FFC107'],
                        fullNames: fullNames
                    }]
                };
                this.chartKey++;
                this.resetChart();
                this.resetTable();
                this.isShowCustomerTable = true;
                this.isShowBarChart = true;
            } catch (e) {
                console.error(e);
            }
        },
        async fetchOrderStatisticsData(query = {}) {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getOrderStatistics(query));
                const dt = res.data.data;
                let labels = [], data = [], colors = [];
                if (res.data.type === 'status') {
                    labels = ['Đang xử lý', 'Đang đóng gói', 'Đang giao hàng', 'Đã giao hàng', 'Đã hủy'];
                    data = [dt.pending, dt.packed, dt.shipped, dt.completed, dt.cancelled];
                    colors = ['#FFEB3B', '#FFC107', '#2196F3', '#4CAF50', '#FF5722'];
                } else if (res.data.type === 'payment_method') {
                    labels = ['COD', 'Thẻ tín dụng / ghi nợ', 'Ví điện tử', 'Mã QR'];
                    data = [dt.cod, dt.card, dt.ewallet, dt.qr];
                    colors = ['#FFEB3B', '#FFC107', '#2196F3', '#4CAF50'];
                } else if (res.data.type === 'payment_status') {
                    labels = ['Chưa thanh toán', 'Đã thanh toán'];
                    data = [dt.pending, dt.paid];
                    colors = ['#FFEB3B', '#4CAF50'];
                }
                this.doughnutData = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors
                    }]
                };
                this.chartKey++;
                this.resetChart();
                this.resetTable();
                this.isShowDoughnutChart = true;
                this.isShowOrderTable = true;
                this.currentReport = 'order';
                await this.fetchCompletedOrdersByPeriodData();
            } catch (e) {
                console.error(e);
            }
        },
        async fetchExpiredProductsData(query = {}) {
            try {
                const res = await this.$swal.withLoading(statisticalApi.getProductExpiredStatistics(query));
                this.expiredProducts = res.data.list;
                this.totalPages = Math.ceil(res.data.pagination.total / res.data.pagination.per_page);
                this.currentPage = res.data.pagination.current_page;
                const chartData = res.data.data;
                const truncatedLabels = chartData.map(item => this.truncateText(item.name));
                const fullNames = chartData.map(item => item.name);
                this.barData = {
                    labels: truncatedLabels,
                    datasets: [{
                        label: 'Sản phẩm hết hạn (30 ngày tới)',
                        data: chartData.map(item => item.quantity),
                        backgroundColor: ['#2196F3', '#4CAF50', '#FFC107'],
                        fullNames: fullNames
                    }]
                };
                this.chartKey++;
                this.resetChart();
                this.resetTable();
                this.isShowExpiredProductsTable = true;
                this.isShowBarChart = true;
            } catch (e) {
                console.error(e);
            }
        },
        filterData() {
            if (!this.selectedReport) {
                this.$swal.fire('Lỗi!', 'Vui lòng chọn loại báo cáo để thực hiện hành động.', 'error');
                return;
            }
            if (this.startDate && this.endDate && this.startDate > this.endDate) {
                this.$swal.fire('Lỗi!', 'Ngày bắt đầu không được lớn hơn ngày kết thúc.', 'error');
                return;
            }
            const periodMap = {
                filterByDay: 'day',
                filterByWeek: 'week',
                filterByMonth: 'month', 
                filterByQuarter: 'quarter',
                filterByYear: 'year'
            }
            const sortMap = {
                filterBySold: 'quantity',
                filterByOrdersCount: 'orders_count',
                filterByTotalQuantity: 'total_quantity',
                filterByExpiredQuantity: 'quantity'
            }
            const filterTypeMap = {
                filterByPaymentMethod: 'payment_method',
                filterByPaymentStatus: 'payment_status',
            }
            const baseQuery = {}
            if (this.startDate && this.endDate) {
                baseQuery.start_date = this.startDate;
                baseQuery.end_date = this.endDate;
            }
            switch (this.selectedReport) {
                case 'revenueByPeriod': {
                    const query = {
                        ...baseQuery,
                        period: periodMap[this.selectedFilter]
                    }
                    this.fetchRevenueByPeriodData(query);
                    this.$router.push({ query });
                    break;
                }
                case 'bestSellingProducts': {
                    const query = {
                        ...baseQuery,
                        limit: this.limit,
                        sort_by: sortMap[this.selectedFilter]
                    }
                    this.fetchBestSellingProductsData(query);
                    this.$router.push({ query });
                    break;
                }
                case 'customer': {
                    const query = {
                        ...baseQuery,
                        limit: this.limit,
                        sort_by: sortMap[this.selectedFilter]
                    }
                    this.fetchCustomerStatisticsData(query);
                    this.$router.push({ query });
                    break;
                }
                case 'order': {
                    const query = {
                        ...baseQuery,
                        filter_type: filterTypeMap[this.selectedFilter]
                    }
                    this.fetchOrderStatisticsData(query);
                    this.$router.push({ query });
                    break;
                }
                case 'expiredProducts': {
                    const query = {
                        ...baseQuery,
                        sort_by: sortMap[this.selectedFilter]
                    }
                    this.fetchExpiredProductsData(query);
                    this.$router.push({ query });
                    break;
                }
                case 'profitByPeriod': {
                    const query = {
                        ...baseQuery,
                        period: periodMap[this.selectedFilter]
                    }
                    this.fetchProfitByPeriodData(query);
                    this.$router.push({ query });
                    break;
                }
            }
        },
        renderSelectReportChange(event) {
            this.selectedReport = event.target.value;
            this.selectedFilter = '';
        },
        renderSelectFilterChange(event) {
            this.selectedFilter = event.target.value;
        },
        truncateText(text, maxLength = 15) {
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        },
        resetTable() {
            this.isShowOrderTable = false;
            this.isShowBestSellingProductsTable = false;
            this.isShowCustomerTable = false;
            this.isShowExpiredProductsTable = false;
        },
        resetChart() {
            this.isShowLineChart = false;
            this.isShowBarChart = false;
            this.isShowDoughnutChart = false;
        },
    },
}
</script>

<style scoped>
.admin-content__input-date {
    font-size: 1.4rem;
    width: 124px;
    margin-right: 20px;
}
.admin-content__input-date-group {
    display: flex;
    align-items: center;
    font-size: 1.4rem;
}
.admin-content__input-date-group label {
    margin-right: 6px;
}
</style>
