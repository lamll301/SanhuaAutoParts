<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'name' => 'Giảm giá má phanh 20%',
                'discount_percent' => 20,
                'max_discount_amount' => 500000,
                'start_date' => '2025-04-01 00:00:00',
                'end_date' => '2025-04-30 23:59:59',
                'status' => 1,
            ],
            [
                'name' => 'Ưu đãi lọc dầu tháng 5',
                'discount_percent' => 15,
                'max_discount_amount' => 300000,
                'start_date' => '2025-05-01 00:00:00',
                'end_date' => '2025-05-31 23:59:59',
                'status' => 1,
            ],
            [
                'name' => 'Khuyến mãi bugi đánh lửa',
                'discount_percent' => 10,
                'max_discount_amount' => 200000,
                'start_date' => '2025-06-10 00:00:00',
                'end_date' => '2025-06-20 23:59:59',
                'status' => 1,
            ],
            [
                'name' => 'Giảm 25% cho lọc gió động cơ',
                'discount_percent' => 25,
                'max_discount_amount' => 400000,
                'start_date' => '2025-07-01 00:00:00',
                'end_date' => '2025-07-15 23:59:59',
                'status' => 1,
            ],
            [
                'name' => 'Chương trình ưu đãi dây đai cam',
                'discount_percent' => 30,
                'max_discount_amount' => 600000,
                'start_date' => '2025-08-01 00:00:00',
                'end_date' => '2025-08-31 23:59:59',
                'status' => 1,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}
