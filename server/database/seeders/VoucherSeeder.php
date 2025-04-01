<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'DISCOUNT10',
                'value' => 10,
                'usage_limit' => 100,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-05-01 00:00:00',
                'status' => 1,
            ],
            [
                'code' => 'SALE20',
                'value' => 20,
                'usage_limit' => 50,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-04-16 00:00:00',
                'status' => 1,
            ],
            [
                'code' => 'NEWYEAR50',
                'value' => 50,
                'usage_limit' => 20,
                'used_count' => 5,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-05-31 00:00:00',
            ],
            [
                'code' => 'SUMMER15',
                'value' => 15,
                'usage_limit' => 200,
                'used_count' => 10,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-06-30 00:00:00',
            ],
            [
                'code' => 'WINTER25',
                'value' => 25,
                'usage_limit' => 75,
                'used_count' => 0,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-07-31 00:00:00',
            ],
            [
                'code' => 'FALL30',
                'value' => 30,
                'usage_limit' => 150,
                'used_count' => 20,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-09-30 00:00:00',
                'status' => 1,
            ],
            [
                'code' => 'SPRING40',
                'value' => 40,
                'usage_limit' => 80,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-08-31 00:00:00',
                'status' => 1,
            ],
            [
                'code' => 'BLACKFRIDAY50',
                'value' => 50,
                'usage_limit' => 500,
                'used_count' => 50,
                'start_date' => '2025-11-01 00:00:00',
                'end_date' => '2025-11-30 00:00:00',
                'status' => 1,
            ],
            [
                'code' => 'CYBERMONDAY35',
                'value' => 35,
                'usage_limit' => 300,
                'used_count' => 30,
                'start_date' => '2025-12-01 00:00:00',
                'end_date' => '2025-12-02 00:00:00',
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
