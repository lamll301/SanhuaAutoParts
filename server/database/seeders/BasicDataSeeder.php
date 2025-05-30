<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BasicDataSeeder extends Seeder
{
    private const PHONE_PREFIXES = ['032', '033', '034', '035', '036', '037', '038', '039', '070', '079', '077', '076', '078', '083', '084', '085', '081', '082'];
    private $faker;
    private $usersId;

    public function __construct()
    {
        $this->faker = Faker::create('vi_VN');
        $this->usersId = User::whereNotNull('role_id')->pluck('id')->toArray();
    }

    private function createVouchers(): void {
        for ($i = 0; $i < 10; $i++) {
            Voucher::create([
                'created_by' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->optional()->randomElement($this->usersId),
                'code' => 'THANHCONG' . $i,
                'value' => $this->faker->numberBetween(10000, 200000),
                'usage_limit' => $this->faker->numberBetween(20, 100),
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(30),
            ]);
        }
        Voucher::create([
            'created_by' => $this->faker->randomElement($this->usersId),
            'code' => 'THATBAI1',
            'value' => 1000000,
            'usage_limit' => 100,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
        ]);
        Voucher::create([
            'created_by' => $this->faker->randomElement($this->usersId),
            'approved_by' => $this->faker->randomElement($this->usersId),
            'code' => 'THATBAI2',
            'value' => 1000000,
            'usage_limit' => 100,
            'used_count' => 100,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
        ]);
    }
    private function createCategories(): void {
        $brands = [
            'Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan',
            'BMW', 'Mercedes-Benz', 'Volkswagen', 'Audi', 'Lexus',
            'Hyundai', 'Kia', 'Mazda', 'Mitsubishi', 'Subaru',
            'Suzuki', 'Porsche', 'Jaguar', 'Land Rover', 'Volvo',
        ];
        $parts = [
            "Phụ tùng động cơ", "Phụ tùng gầm xe", "Phụ tùng thân & vỏ", "Phụ tùng điện", "Phụ tùng điều hòa", "Hệ thống phanh",
            "Hệ thống treo", "Hệ thống làm mát", "Hệ thống nhiên liệu", "Hệ thống chiếu sáng", "Phụ tùng lốp xe",
            "Gương và kính xe", "Bộ ly hợp và hộp số", "Hệ thống lái", "Hệ thống xả", "Phụ kiện trang trí nội thất",
            "Phụ kiện trang trí ngoại thất", "Phụ kiện công nghệ và tiện ích"
        ];
        foreach ($brands as $brand) {
            Category::create([
                'name' => $brand,
                'type' => 'brand',
            ]);
        }
        foreach ($parts as $part) {
            Category::create([
                'name' => $part,
                'type' => 'part',
            ]);
        }
        Category::create(['name' => 'Cao cấp', 'type' => 'high-class']);
    }
    private function createSuppliers(): void {
        $companyNames = [
            'Công ty TNHH Phụ tùng Ô tô Việt Nhật', 'Công ty TNHH Thiết bị Gầm Máy Đại Hưng', 'Công ty TNHH SX Phụ tùng Cơ khí An Khang', 'Công ty TNHH Phụ tùng Ô tô Trường Hải',
            'Công ty Cổ phần Linh kiện Ô tô Á Châu', 'Công ty TNHH Thiết bị và Linh kiện Xe Phúc An', 'Công ty TNHH SX & TM Phụ tùng Đại Phát', 'Công ty TNHH Phụ tùng và Dầu nhờn Hòa Bình',
            'Công ty TNHH Công nghệ Ô tô Nam Việt', 'Công ty TNHH Cơ khí và Phụ tùng Minh Tâm', 'Công ty TNHH Linh kiện Máy lạnh Ô tô Toàn Cầu', 'Công ty TNHH Phụ tùng Điện Ô tô Việt Đức',
            'Công ty TNHH Vật tư và Thiết bị Ô tô Thành Đạt', 'Công ty Cổ phần Phụ tùng Xe Tải Đông Nam', 'Công ty TNHH Thiết bị Phanh và Lốp Vĩnh Phát', 'Công ty TNHH Phụ tùng Cầu Xe Hoàng Gia',
            'Công ty TNHH Linh kiện Khung Gầm Ô tô Đại Việt', 'Công ty TNHH SX Ống xả và Phụ kiện Ô tô Quang Minh', 'Công ty TNHH Phụ tùng Nhiệt độ và Làm mát An Tín', 'Công ty TNHH Phụ tùng và Đèn Chiếu sáng Hồng Hà',
        ];
        foreach ($companyNames as $companyName) {
            Supplier::create([
                'name' => $companyName,
                'email' => $this->faker->unique()->email,
                'phone' => $this->faker->randomElement(self::PHONE_PREFIXES) . $this->faker->numberBetween(1000000, 9999999),
                'address' => $this->faker->address,
            ]);
        }
    }
    private function createUnits(): void {
        $units = [
            'cái', 'bộ', 'chiếc', 'hộp', 'thùng'
        ];
        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit,
                'description' => $this->faker->sentence,
            ]);
        }
    }
    private function createPromotions(): void {
        $promotions = [
            'Ưu đãi Mừng Tết Nguyên Đán', 'Khuyến mãi Ngày Quốc tế Phụ nữ', 'Chương trình Giáng Sinh Vui Vẻ', 'Flash Sale Ngày Độc Thân',
            'Ưu đãi Sinh Nhật Công Ty', 'Ưu đãi Mùa Hè Rực Rỡ', 'Khuyến mãi Lễ 30/4 - 1/5', 'Chương trình Tri Ân Khách Hàng',
            'Ưu đãi Mừng Năm Mới', 'Khuyến mãi Black Friday', 'Ưu đãi Ngày Nhà Giáo Việt Nam', 'Chương trình Lễ Vu Lan',
            'Flash Sale Cuối Năm', 'Khuyến mãi Ngày Của Cha', 'Ưu đãi Ngày Gia Đình Việt Nam',
        ];
        foreach ($promotions as $promotion) {
            $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
            $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');
            $now = new \DateTime();
            if ($now < $startDate) {
                $status = 0;
            } elseif ($now >= $startDate && $now <= $endDate) {
                $status = 1;
            } else {
                $status = 2;
            }
            Promotion::create([
                'created_by' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->optional()->randomElement($this->usersId),
                'name' => $promotion,
                'discount_percent' => $this->faker->numberBetween(10, 50),
                'max_discount_amount' => $this->faker->optional()->numberBetween(100000, 500000),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
            ]);
        }
    }
    public function run(): void
    {
        $this->createCategories();
        $this->createVouchers();
        $this->createSuppliers();
        $this->createUnits();
        $this->createPromotions();
    }
} 