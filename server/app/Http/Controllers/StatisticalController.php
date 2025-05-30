<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;

class StatisticalController extends Controller
{
    const SEARCH_FIELDS = ['name', 'phone'];
    public function getCompletedOrdersByPeriod(Request $request) {
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $query = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->with([
                'voucher:id,code,value',
            ]);
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, []);
    }
    public function revenueByPeriod(Request $request) {
        $period = $request->get('period', 'day');
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $query = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->select([
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders_count'),
            ]);
        switch ($period) {
            case 'day':
                $query->addSelect(
                    DB::raw('DATE(completed_at) as period'),
                )->groupBy(DB::raw('DATE(completed_at)'))
                ->orderBy('period');
                break;
            case 'week':
                $query->addSelect(
                    DB::raw('TO_CHAR(completed_at, \'IYYY-IW\') as period'),
                )->groupBy(DB::raw('TO_CHAR(completed_at, \'IYYY-IW\')'))
                ->orderBy('period');
                break;
            case 'month':
                $query->addSelect(
                    DB::raw('EXTRACT(YEAR FROM completed_at) as year'),
                    DB::raw('EXTRACT(MONTH FROM completed_at) as month'),
                    DB::raw('CONCAT(EXTRACT(YEAR FROM completed_at), \'-\', LPAD(EXTRACT(MONTH FROM completed_at)::text, 2, \'0\')) as period')
                )->groupBy(
                    DB::raw('EXTRACT(YEAR FROM completed_at)'),
                    DB::raw('EXTRACT(MONTH FROM completed_at)')
                )->orderBy('year')
                ->orderBy('month');
                break;
            case 'quarter':
                $query->addSelect(
                    DB::raw('EXTRACT(YEAR FROM completed_at) as year'),
                    DB::raw('EXTRACT(QUARTER FROM completed_at) as quarter'),
                    DB::raw('CONCAT(EXTRACT(YEAR FROM completed_at), \'-Q\', EXTRACT(QUARTER FROM completed_at)) as period')
                )->groupBy(
                    DB::raw('EXTRACT(YEAR FROM completed_at)'),
                    DB::raw('EXTRACT(QUARTER FROM completed_at)')
                )->orderBy('year')
                ->orderBy('quarter');
                break;
            case 'year':
                $query->addSelect(
                    DB::raw('EXTRACT(YEAR FROM completed_at) as year'),
                    DB::raw('CONCAT(EXTRACT(YEAR FROM completed_at)) as period')
                )->groupBy(DB::raw('EXTRACT(YEAR FROM completed_at)'))
                ->orderBy('year');
                break;
        }
        $data = $query->get();
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'period' => $period,
            'data' => $data
        ]);
    }
    
    public function profitByPeriod(Request $request) {
        $period = $request->get('period', 'day');
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $query = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('order_detail_inventory', 'order_details.id', '=', 'order_detail_inventory.order_detail_id')
            ->join('inventories', 'order_detail_inventory.inventory_id', '=', 'inventories.id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereBetween('orders.completed_at', [$startDate, $endDate])
            ->select([
                DB::raw('SUM(order_detail_inventory.quantity) as total_quantity'),      // sản lượng bán ra
                DB::raw('SUM(order_details.price * order_detail_inventory.quantity) as gross_sales'),   // tổng tiền thu từ bán hàng (chưa tính phí khác) = tổng giá bán * số lượng
                DB::raw('SUM(inventories.price * order_detail_inventory.quantity) as cost'), // chi phí = giá nhập × số lượng
                DB::raw('SUM(order_details.price * order_detail_inventory.quantity) - SUM(inventories.price * order_detail_inventory.quantity) as profit'), // lợi nhuận = tổng tiền thu từ bán hàng - chi phí
            ]);
        switch ($period) {
            case 'day':
                $query->addSelect(DB::raw('DATE(orders.completed_at) as period'))
                    ->groupBy(DB::raw('DATE(orders.completed_at)'))
                    ->orderBy('period');
                break;                
            case 'week':
                $query->addSelect(DB::raw('TO_CHAR(orders.completed_at, \'IYYY-IW\') as period'))
                    ->groupBy(DB::raw('TO_CHAR(orders.completed_at, \'IYYY-IW\')'))
                    ->orderBy('period');
                break;                
            case 'month':
                $query->addSelect(
                    DB::raw('EXTRACT(YEAR FROM orders.completed_at) as year'),
                    DB::raw('EXTRACT(MONTH FROM orders.completed_at) as month'),
                    DB::raw('CONCAT(EXTRACT(YEAR FROM orders.completed_at), \'-\', LPAD(EXTRACT(MONTH FROM orders.completed_at)::text, 2, \'0\')) as period')
                )
                ->groupBy(
                    DB::raw('EXTRACT(YEAR FROM orders.completed_at)'),
                    DB::raw('EXTRACT(MONTH FROM orders.completed_at)')
                )
                ->orderBy('year')
                ->orderBy('month');
                break;
            case 'quarter':
                $query->addSelect(
                    DB::raw('EXTRACT(YEAR FROM orders.completed_at) as year'),
                    DB::raw('EXTRACT(QUARTER FROM orders.completed_at) as quarter'),
                    DB::raw('CONCAT(EXTRACT(YEAR FROM orders.completed_at), \'-Q\', EXTRACT(QUARTER FROM orders.completed_at)) as period')
                )
                ->groupBy(
                    DB::raw('EXTRACT(YEAR FROM orders.completed_at)'),
                    DB::raw('EXTRACT(QUARTER FROM orders.completed_at)')
                )
                ->orderBy('year')
                ->orderBy('quarter');
                break;
            case 'year':
                $query->addSelect(DB::raw('EXTRACT(YEAR FROM orders.completed_at) as period'))
                    ->groupBy(DB::raw('EXTRACT(YEAR FROM orders.completed_at)'))
                    ->orderBy('period');
                break;
        }
        $data = $query->get();
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'period' => $period,
            'data' => $data
        ]);
    }

    public function bestSellingProducts(Request $request) {
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $sortBy = $request->get('sort_by', 'revenue');  // quantity, revenue
        $limit = $request->get('limit', 10);
        $query = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('images', function($join) {
                $join->on('images.product_id', '=', 'products.id')
                    ->where('images.is_thumbnail', '=', true);
            })
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereBetween('orders.completed_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                'suppliers.name as supplier_name',
                DB::raw('MIN(images.path) as thumbnail_path'),
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue'),
                DB::raw('SUM(order_details.price * order_details.quantity) / SUM(order_details.quantity) as average_selling_price')
            )
            ->groupBy('products.id', 'suppliers.name');
        if ($sortBy === 'revenue') {
            $query->orderBy('total_revenue', 'desc');
        } else {
            $query->orderBy('total_quantity', 'desc');
        }
        $data = $query->limit($limit)->get();
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'data' => $data
        ]);
    }

    public function customerStatistics(Request $request) {
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $sortBy = $request->get('sort_by', 'revenue'); // revenue, orders_count, total_quantity
        $limit = $request->get('limit', 10);
        $query = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereNull('users.role_id')
            ->whereBetween('orders.completed_at', [$startDate, $endDate])
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(orders.total_amount) as total_revenue'),
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('AVG(orders.total_amount) as average_order_value'),
                DB::raw('MAX(orders.completed_at) as last_order_date')
            )
            ->groupBy('users.id');
        switch ($sortBy) {
            case 'revenue':
                $query->orderBy('total_revenue', 'desc');
                break;
            case 'orders_count':
                $query->orderBy('total_orders', 'desc');
                break;
            case 'total_quantity':
                $query->orderBy('total_quantity', 'desc');
                break;
        }
        $data = $query->limit($limit)->get();
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'data' => $data
        ]);
    }

    public function orderStatistics(Request $request) {
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $type = $request->get('filter_type', 'status');
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);
        switch ($type) {
            case 'status':
                $query = $query->select(
                    DB::raw('COUNT(CASE WHEN status = ' . Order::STATUS_PENDING . ' THEN 1 END) as pending'),
                    DB::raw('COUNT(CASE WHEN status = ' . Order::STATUS_PACKED . ' THEN 1 END) as packed'),
                    DB::raw('COUNT(CASE WHEN status = ' . Order::STATUS_SHIPPED . ' THEN 1 END) as shipped'),
                    DB::raw('COUNT(CASE WHEN status = ' . Order::STATUS_COMPLETED . ' THEN 1 END) as completed'),
                    DB::raw('COUNT(CASE WHEN status = ' . Order::STATUS_CANCELLED . ' THEN 1 END) as cancelled'),
                    DB::raw('COUNT(*) as total')
                )->first();
                break;
            case 'payment_method':
                $query = $query->select(
                    DB::raw("COUNT(CASE WHEN payment_method = '" . Order::PAYMENT_METHOD_COD . "' THEN 1 END) as cod"),
                    DB::raw("COUNT(CASE WHEN payment_method = '" . Order::PAYMENT_METHOD_CARD . "' THEN 1 END) as card"),
                    DB::raw("COUNT(CASE WHEN payment_method = '" . Order::PAYMENT_METHOD_EWALLET . "' THEN 1 END) as ewallet"),
                    DB::raw("COUNT(CASE WHEN payment_method = '" . Order::PAYMENT_METHOD_QR . "' THEN 1 END) as qr"),
                    DB::raw('COUNT(*) as total')
                )->first();
                break;
            case 'payment_status':
                $query = $query->select(
                    DB::raw('COUNT(CASE WHEN payment_status = ' . Order::PAYMENT_STATUS_PENDING . ' THEN 1 END) as pending'),
                    DB::raw('COUNT(CASE WHEN payment_status = ' . Order::PAYMENT_STATUS_PAID . ' THEN 1 END) as paid'),
                    DB::raw('COUNT(*) as total')
                )->first();
                break;
        }
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'type' => $type,
            'data' => $query,
        ]);
    }

    public function productExpiredStatistics(Request $request) {
        $sortBy = $request->get('sort_by', 'expiry_date');  // expiry_date, quantity
        $thirtyDaysFromNow = Carbon::now()->addDays(30);
        $query = Inventory::join('products', 'inventories.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('images', function($join) {
                $join->on('images.product_id', '=', 'products.id')
                    ->where('images.is_thumbnail', '=', true);
            })
            ->where('inventories.quantity', '>', 0)
            ->whereNotNull('inventories.expiry_date')
            ->where('inventories.expiry_date', '<=', $thirtyDaysFromNow)
            ->select(
                'products.id as product_id',
                'products.name',
                'suppliers.name as supplier_name',
                'inventories.quantity',
                'inventories.expiry_date',
                'inventories.batch_number',
                'inventories.id as inventory_id',
                DB::raw('MIN(images.path) as thumbnail_path'),
                DB::raw('EXTRACT(DAY FROM (inventories.expiry_date::timestamp - CURRENT_DATE::timestamp)) as days_until_expiry')
            )
            ->groupBy(
                'products.id',
                'products.name',
                'suppliers.name',
                'inventories.quantity',
                'inventories.expiry_date',
                'inventories.batch_number',
                'inventories.id'
            );
        if ($sortBy === 'quantity') {
            $query->orderBy('inventories.quantity', 'desc');
        } else {
            $query->orderBy('inventories.expiry_date');
        }
        $fullQuery = clone $query;
        $listQuery = clone $query;

        $paginated = $listQuery->paginate(config('app.per_page'));
        $fullData = $fullQuery->get();

        return response()->json([
            'list' => $paginated->items(),
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
            'data' => $fullData
        ]);
    }


    public function summary(Request $request) {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        $revenue = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->sum('total_amount');
        $ordersCount = Order::whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $completionRate = $ordersCount > 0 ? ($completedOrders / $ordersCount) * 100 : 0;
        $totalReviews = Review::join('orders', 'reviews.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->count();
        $positiveReviews = Review::join('orders', 'reviews.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('reviews.rating', '>=', 4)
            ->count();
        $positiveRate = $totalReviews > 0 ? ($positiveReviews / $totalReviews) * 100 : 0;
        return response()->json([
            'revenue' => $revenue,
            'orders_count' => $ordersCount,
            'completion_rate' => $completionRate,
            'positive_rate' => $positiveRate,
        ]);
    }

}
