<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'cái'],
            ['name' => 'can', 'description' => 'đo chất lỏng'],
            ['name' => 'thùng', 'description' => '1 thùng gồm 100 sản phẩm'],
            ['name' => 'kg'],
            ['name' => 'lít'],
            ['name' => 'hộp', 'description' => '1 hộp gồm 4 sản phẩm (chỉ áp dụng mặt hàng nhỏ gọn)'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
