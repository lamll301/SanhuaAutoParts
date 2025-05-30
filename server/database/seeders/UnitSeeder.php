<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Cái',   'description' => 'Đơn vị cơ bản cho từng sản phẩm'],
            ['name' => 'Thùng', 'description' => 'Đóng gói nhiều cái trong một đơn vị lớn'],
            ['name' => 'Kg',    'description' => 'Tính theo khối lượng'],
            ['name' => 'Lít',   'description' => 'Tính theo thể tích, thường dùng cho chất lỏng'],
        ];
        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
