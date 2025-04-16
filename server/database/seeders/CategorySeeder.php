<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan',
            'BMW', 'Mercedes-Benz', 'Volkswagen', 'Audi', 'Lexus',
            'Hyundai', 'Kia', 'Mazda', 'Mitsubishi', 'Subaru',
            'Suzuki', 'Porsche', 'Jaguar', 'Land Rover', 'Volvo',
        ];

        foreach ($brands as $brand) {
            Category::create([
                'name' => $brand,
                'type' => 'brand',
            ]);
        }

        $parts = [
            "Phụ tùng động cơ",
            "Phụ tùng gầm xe",
            "Phụ tùng thân & vỏ",
            "Phụ tùng điện",
            "Phụ tùng điều hòa",
            "Hệ thống phanh",
            "Hệ thống treo",
            "Hệ thống làm mát",
            "Hệ thống nhiên liệu",
            "Hệ thống chiếu sáng",
            "Phụ tùng lốp xe",
            "Gương và kính xe",
            "Bộ ly hợp và hộp số",
            "Hệ thống lái",
            "Hệ thống xả",
            "Phụ kiện trang trí nội thất",
            "Phụ kiện trang trí ngoại thất",
            "Phụ kiện công nghệ và tiện ích"
        ];

        foreach ($parts as $part) {
            Category::create([
                'name' => $part,
                'type' => 'part',
            ]);
        }

        $specials = [
            "Được mua nhiều gần đây",
            "Mới nhất",
            "Đang được giảm giá",
            "Sản phẩm cao cấp"
        ];

        foreach ($specials as $special) {
            Category::create([
                'name' => $special,
                'type' => 'special',
            ]);
        }
    }
}
