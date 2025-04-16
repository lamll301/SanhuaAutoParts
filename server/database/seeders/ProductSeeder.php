<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Supplier;
use App\Models\Unit;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $promotionIds = Promotion::pluck('id')->toArray();
        $supplierIds = Supplier::pluck('id')->toArray();
        $unitIds = Unit::pluck('id')->toArray();

        $autoParts = [
            'Van EGR Hyundai Tucson', 'Van PCV Honda Civic', 'Van hằng nhiệt Toyota Camry',
            'Van điều áp nhiên liệu Mazda 3', 'Bugi NGK', 'Bugi Denso', 'Dây curoa tổng', 'Dây curoa cam',
            'Dầu động cơ Castrol', 'Dầu động cơ Mobil 1', 'Gạt mưa Bosch', 'Gương chiếu hậu bên trái',
            'Gương chiếu hậu bên phải', 'Đèn pha LED', 'Cảm biến oxy', 'Cảm biến ABS',
            'Lọc gió động cơ', 'Lọc gió điều hòa', 'Lọc nhớt', 'Lọc xăng',
            'Má phanh trước', 'Má phanh sau', 'Bình ắc quy GS', 'Bình ắc quy Varta',
            'Ống xả', 'Bộ gioăng động cơ', 'Mô tơ quạt két nước', 'Két nước làm mát',
            'Thanh giằng', 'Rotuyn lái ngoài', 'Rotuyn cân bằng', 'Cao su chân máy',
        ];

        foreach ($autoParts as $part) {
            Product::create([
                'name' => $part . ' ' . $faker->bothify('##??'),
                'description' => $faker->paragraphs(2, true),
                'original_price' => $faker->numberBetween(100000, 5000000),

                'dimensions' => $faker->randomElement([
                    '10x5x5 cm', '20x15x10 cm', '30x20x10 cm', '40x25x15 cm'
                ]),
                'weight' => $faker->randomElement([
                    '0.2kg', '0.5kg', '1kg', '2.5kg', '5kg'
                ]),
                'color' => $faker->randomElement([
                    'Đen', 'Bạc', 'Xám', 'Trắng', 'Đỏ', 'Xanh dương'
                ]),
                'material' => $faker->randomElement([
                    'Nhôm', 'Thép không gỉ', 'Cao su', 'Nhựa ABS', 'Sắt hợp kim'
                ]),
                'compatibility' => $faker->randomElement([
                    'Toyota', 'Honda', 'Ford', 'Hyundai', 'Mazda', 'Kia', 'Nissan', 'Suzuki'
                ]),

                'promotion_id' => $faker->optional()->randomElement($promotionIds),
                'unit_id' => $faker->optional()->randomElement($unitIds),
                'supplier_id' => $faker->optional()->randomElement($supplierIds),

                'status' => $faker->randomElement([0, 1]),
            ]);
        }
    }
}
