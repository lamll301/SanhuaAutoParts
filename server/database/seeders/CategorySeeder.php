<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hệ thống phanh',
                'description' => 'Các phụ tùng liên quan đến hệ thống phanh như má phanh, dầu phanh.',
            ],
            [
                'name' => 'Bộ phận động cơ',
                'description' => 'Linh kiện động cơ như lọc dầu, lọc gió, dây đai cam.',
            ],
            [
                'name' => 'Hệ thống đánh lửa',
                'description' => 'Các bộ phận đánh lửa như bugi, cuộn dây đánh lửa.',
            ],
            [
                'name' => 'Làm mát và điều hòa',
                'description' => 'Phụ tùng hệ thống làm mát và điều hòa như két nước, quạt gió.',
            ],
            [
                'name' => 'Hệ thống truyền động',
                'description' => 'Các bộ phận như ly hợp, hộp số, trục truyền động.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
