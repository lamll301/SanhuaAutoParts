<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Công ty TNHH Thực Phẩm Sạch',
                'contact_name' => 'Nguyễn Văn A',
                'address' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
                'email' => 'supplier1@example.com',
                'phone' => '0901234567',
            ],
            [
                'name' => 'Nhà Cung Cấp Hòa Bình',
                'contact_name' => 'Trần Thị B',
                'address' => '456 Đường Trần Hưng Đạo, Quận 5, TP.HCM',
                'email' => 'supplier2@example.com',
                'phone' => '0912345678',
            ],
            [
                'name' => 'Công ty Cổ phần Phát Triển TM',
                'contact_name' => 'Lê Văn C',
                'address' => '789 Đường Nguyễn Trãi, Quận 3, TP.HCM',
                'email' => 'supplier3@example.com',
                'phone' => '0923456789',
            ],
            [
                'name' => 'Nhà Cung Ứng Minh Phát',
                'contact_name' => 'Hoàng Thị D',
                'address' => '321 Đường Võ Văn Kiệt, Quận 2, TP.HCM',
                'email' => 'supplier4@example.com',
                'phone' => '0934567890',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
