<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::create([
            'code' => 'thanhcong',
            'value' => 1000000,
            'usage_limit' => 100,
            'start_date' => Carbon::now()->addDays(-30),
            'end_date' => Carbon::now()->addDays(30),
        ]);

        Voucher::create([
            'code' => 'thanhcong1',
            'value' => 1000000,
            'usage_limit' => 100,
            'used_count' => 99,
            'start_date' => Carbon::now()->addDays(-30),
            'end_date' => Carbon::now()->addDays(30),
        ]);

        Voucher::create([
            'code' => 'thatbai1',
            'value' => 500000,
            'usage_limit' => 100,
            'start_date' => Carbon::now()->addDays(30),
            'end_date' => Carbon::now()->addDays(60),
        ]);

        Voucher::create([
            'code' => 'thatbai2',
            'value' => 500000,
            'usage_limit' => 100,
            'used_count' => 100,
            'start_date' => Carbon::now()->addDays(-30),
            'end_date' => Carbon::now()->addDays(30),
        ]);
    }
}
