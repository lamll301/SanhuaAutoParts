<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Voucher;

class StatisticalController extends Controller
{
    public function revenueByPeriod(Request $request) {
        $period = $request->get('period', 'day');
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $query = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$startDate, $endDate]);
        $listQuery = clone $query;
        switch ($period) {
            case 'day':
                $data = $query->selectRaw('DATE(completed_at) as period, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                    ->groupBy(DB::raw('DATE(completed_at)'))
                    ->orderBy('period')
                    ->get();
                break;
            case 'week':
                $data = $query->selectRaw('TO_CHAR(completed_at, \'IYYY-IW\') as period, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                    ->groupBy(DB::raw('TO_CHAR(completed_at, \'IYYY-IW\')'))
                    ->orderBy('period')
                    ->get();
                break;
            case 'month':
                $data = $query->selectRaw('EXTRACT(YEAR FROM completed_at) as year, EXTRACT(MONTH FROM completed_at) as month, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                    ->groupBy(DB::raw('EXTRACT(YEAR FROM completed_at)'), DB::raw('EXTRACT(MONTH FROM completed_at)'))
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
                break;
            case 'quarter':
                $data = $query->selectRaw('EXTRACT(YEAR FROM completed_at) as year, EXTRACT(QUARTER FROM completed_at) as quarter, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                    ->groupBy(DB::raw('EXTRACT(YEAR FROM completed_at)'), DB::raw('EXTRACT(QUARTER FROM completed_at)'))
                    ->orderBy('year')
                    ->orderBy('quarter')
                    ->get();
                break;
            case 'year':
                $data = $query->selectRaw('EXTRACT(YEAR FROM completed_at) as year, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                    ->groupBy(DB::raw('EXTRACT(YEAR FROM completed_at)'))
                    ->orderBy('year')
                    ->get();
                break;
        }
        $list = $this->getListResponse($listQuery, $request, [], [])->getData(true);
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'period' => $period,
            'data' => $data,
            'list' => $list
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
                DB::raw('MAX(orders.completed_at) as last_order_date'),
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
        $listQuery = clone $query;
        
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
        $list = $this->getListResponse($listQuery, $request, [], [])->getData(true);
        return response()->json([
            'date_range' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'type' => $type,
            'data' => $query,
            'list' => $list
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

    public function profitByPeriod(Request $request) {
        $period = $request->get('period', 'day');
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));
        $query = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('order_detail_inventory', 'order_details.id', '=', 'order_detail_inventory.order_detail_id')
            ->join('inventories', 'order_detail_inventory.inventory_id', '=', 'inventories.id')
            ->join('import_details', function($join) {
                $join->on('inventories.import_id', '=', 'import_details.import_id')
                    ->on('inventories.product_id', '=', 'import_details.product_id');
            })
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereBetween('orders.completed_at', [$startDate, $endDate])
            ->select([
                'products.id as product_id',
                'products.name as product_name',
                'suppliers.name as supplier_name',
                DB::raw('SUM(order_detail_inventory.quantity) as total_quantity'),
                DB::raw('SUM(order_details.price * order_detail_inventory.quantity) as revenue'),
                DB::raw('SUM(import_details.price * order_detail_inventory.quantity) as cost'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('COUNT(DISTINCT products.id) as products_count'),
                DB::raw('COUNT(DISTINCT inventories.id) as inventory_batches_count'),
                DB::raw('AVG(order_details.price) as average_selling_price'),
                DB::raw('AVG(import_details.price) as average_import_price')
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
