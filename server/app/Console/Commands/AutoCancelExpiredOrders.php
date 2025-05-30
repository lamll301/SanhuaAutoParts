<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AutoCancelExpiredOrders extends Command
{
    protected $signature = 'app:auto-cancel-expired-orders
                        {--dry-run : Chỉ hiển thị những đơn sẽ bị hủy mà không thực sự hủy}
                        {--force : Bắt buộc chạy ngay cả khi không phải môi trường production}';
    protected $description = 'Tự động hủy đơn hàng chưa hoàn thành sau số ngày nhất định (mặc định 30 ngày)';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $day = 30;
        $now = Carbon::now();
        $expiredDate = $now->copy()->subDays($day);
        $this->info("🚀 Bắt đầu kiểm tra đơn hàng cần tự động hủy, hủy đơn tạo trước ngày " . $expiredDate->format('Y-m-d H:i:s') . "...");
        $orders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_PACKED,
            Order::STATUS_SHIPPED,
        ])->where('created_at', '<=', $expiredDate);
        $totalOrders = $orders->count();
        if ($totalOrders === 0) {
            $this->info("✅ Không có đơn hàng nào cần tự động hủy");
            return Command::SUCCESS;
        }
        $this->warn("Tìm thấy {$totalOrders} đơn hàng cần tự động hủy");
        if ($isDryRun) {
            $this->warn("[DRY RUN] Sẽ hủy {$totalOrders} đơn hàng");
            $data = $orders->limit(20)->get(['id', 'name', 'total_amount', 'payment_status', 'status', 'created_at'])
                ->map(function ($order) {
                    return [
                        $order->id,
                        $order->name,
                        number_format($order->total_amount) . ' VNĐ',
                        $order->payment_status == Order::PAYMENT_STATUS_PENDING ? 'Chưa TT' : 'Đã TT',
                        $order->status == Order::STATUS_PENDING ? 'Đang xử lý' : ($order->status == Order::STATUS_PACKED ? 'Đang đóng gói' : 'Đang giao hàng'),
                        $order->created_at->format('d/m/Y H:i')
                    ];
                })->toArray();
            $this->table(['ID', 'Tên khách hàng', 'Tổng tiền', 'Trạng thái TT', 'Trạng thái', 'Ngày tạo'], $data);
            return Command::SUCCESS;
        }

        $cancelReason = "Đơn hàng tự động bị hủy sau {$day} ngày chưa hoàn thành";
        DB::transaction(function () use ($orders, $cancelReason) {
            $orders->chunk(100, function ($orderChunk) use ($cancelReason) {
                $chunkIds = $orderChunk->pluck('id')->toArray();
                Order::whereIn('id', $chunkIds)->update([
                    'status' => Order::STATUS_CANCELLED,
                    'cancel_reason' => $cancelReason,
                    'cancelled_at' => now()
                ]);
            });
        });

        $this->info('🎉 Hoàn thành việc tự động hủy đơn hàng!');
        return Command::SUCCESS;
    }
}
