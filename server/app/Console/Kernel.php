<?php   

namespace App\Console;

use App\Console\Commands\UpdatePromotionStatus;
use App\Console\Commands\AutoCancelExpiredOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel  // php artisan schedule:run
{
    protected $commands = [
        UpdatePromotionStatus::class,
        AutoCancelExpiredOrders::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:update-promotion-status')   // php artisan app:update-promotion-status --dry-run
            // ->everyMinute()
            ->dailyAt('00:01')
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command('app:auto-cancel-expired-orders') // php artisan app:auto-cancel-expired-orders --dry-run
            ->dailyAt('00:02')
            ->withoutOverlapping()
            ->runInBackground();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
