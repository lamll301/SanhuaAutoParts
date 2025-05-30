<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Promotion;

class UpdatePromotionStatus extends Command
{
    protected $signature = 'app:update-promotion-status
                            {--dry-run : Chạy giả lập không thực hiện thay đổi}
                            {--force : Bắt buộc chạy ngay cả khi không phải môi trường production}';
    protected $description = 'Cập nhật trạng thái khuyến mãi dựa trên thời gian hiện tại';

    public function handle()
    {
        /* dry run (chạy giả lập) là cách chạy thử 1 lệnh mà không thực hiện thay đổi thật nào đến dữ liệu,
        giúp mô phỏng quá trình chạy in ra log, thông báo hoặc hành động */
        $isDryRun = $this->option('dry-run');       
        $now = Carbon::now();
        $this->info('🚀 Bắt đầu cập nhật trạng thái khuyến mãi...');
        $upcomingToActive = Promotion::where('status', Promotion::STATUS_UPCOMING)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
        $upcomingToActiveCount = $upcomingToActive->count();
        if ($upcomingToActiveCount > 0) {
            if ($isDryRun) {
                $this->warn("[DRY RUN] Sẽ cập nhật {$upcomingToActiveCount} khuyến mãi từ 'upcoming' sang 'active'");
                $this->table(['ID', 'Tên', 'Ngày bắt đầu', 'Ngày kết thúc'], 
                    $upcomingToActive->get(['id', 'name', 'start_date', 'end_date'])->toArray());
            } else {
                $updated = $upcomingToActive->update(['status' => Promotion::STATUS_ACTIVE]);
                $this->info("Cập nhật {$updated} khuyến mãi từ 'upcoming' sang 'active'");
            }
        }

        $activeToExpired = Promotion::where('status', Promotion::STATUS_ACTIVE)
            ->where('end_date', '<', $now);
        $activeToExpiredCount = $activeToExpired->count();
        if ($activeToExpiredCount > 0) {
            if ($isDryRun) {
                $this->warn("[DRY RUN] Sẽ cập nhật {$activeToExpiredCount} khuyến mãi từ 'active' sang 'expired'");
                $this->table(['ID', 'Tên', 'Ngày kết thúc'], 
                    $activeToExpired->get(['id', 'name', 'end_date'])->toArray());
            } else {
                $updated = $activeToExpired->update(['status' => Promotion::STATUS_EXPIRED]);
                $this->info("Cập nhật {$updated} khuyến mãi từ 'active' sang 'expired'");
            }
        }

        $summary = Promotion::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $this->info("📊 Thống kê sau khi cập nhật:");
        $this->table(['Trạng thái', 'Số lượng'], [
            ['Chưa đến lịch (upcoming)', $summary[Promotion::STATUS_UPCOMING] ?? 0],
            ['Đang hoạt động (active)', $summary[Promotion::STATUS_ACTIVE] ?? 0],
            ['Hết hạn (expired)', $summary[Promotion::STATUS_EXPIRED] ?? 0],
        ]);
        
        if (!$isDryRun) {
            $logMessage = "Cập nhật trạng thái khuyến mãi: {$upcomingToActiveCount} upcoming->active, {$activeToExpiredCount} active->expired";
            Log::info($logMessage, [
                'timestamp' => $now,
                'upcoming_to_active' => $upcomingToActiveCount,
                'active_to_expired' => $activeToExpiredCount
            ]);
        }

        $this->info('🎉 Hoàn thành cập nhật trạng thái khuyến mãi!');
        return Command::SUCCESS;
    }
}
