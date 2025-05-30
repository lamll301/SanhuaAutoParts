<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    private const PHONE_PREFIXES = ['032', '033', '034', '035', '036', '037', '038', '039', '070', '079', '077', '076', '078', '083', '084', '085', '081', '082'];
    private const COMPANY_NAMES = [
        'Công ty TNHH Phụ tùng Ô tô Việt Nhật',
        'Công ty TNHH Thiết bị Gầm Máy Đại Hưng',
        'Công ty TNHH SX Phụ tùng Cơ khí An Khang',
        'Công ty TNHH Phụ tùng Ô tô Trường Hải',
        'Công ty Cổ phần Linh kiện Ô tô Á Châu',
        'Công ty TNHH Thiết bị và Linh kiện Xe Phúc An',
        'Công ty TNHH SX & TM Phụ tùng Đại Phát',
        'Công ty TNHH Phụ tùng và Dầu nhờn Hòa Bình',
        'Công ty TNHH Công nghệ Ô tô Nam Việt',
        'Công ty TNHH Cơ khí và Phụ tùng Minh Tâm',
        'Công ty TNHH Linh kiện Máy lạnh Ô tô Toàn Cầu',
        'Công ty TNHH Phụ tùng Điện Ô tô Việt Đức',
        'Công ty TNHH Vật tư và Thiết bị Ô tô Thành Đạt',
        'Công ty Cổ phần Phụ tùng Xe Tải Đông Nam',
        'Công ty TNHH Thiết bị Phanh và Lốp Vĩnh Phát',
        'Công ty TNHH Phụ tùng Cầu Xe Hoàng Gia',
        'Công ty TNHH Linh kiện Khung Gầm Ô tô Đại Việt',
        'Công ty TNHH SX Ống xả và Phụ kiện Ô tô Quang Minh',
        'Công ty TNHH Phụ tùng Nhiệt độ và Làm mát An Tín',
        'Công ty TNHH Phụ tùng và Đèn Chiếu sáng Hồng Hà',
    ];

    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        for ($i = 0; $i < 20; $i++) {
            Supplier::create([
                'name' => $faker->randomElement(self::COMPANY_NAMES),
                'email' => $faker->unique()->email,
                'phone' => $faker->randomElement(self::PHONE_PREFIXES) . $faker->numberBetween(1000000, 9999999),
                'address' => $faker->address,
            ]);
        }
    }
}
