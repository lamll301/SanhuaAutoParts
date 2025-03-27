<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'full-access', 'description' => 'Có thể truy cập và chỉnh sửa tất cả dữ liệu'],
            ['name' => 'manage-users', 'description' => 'Có toàn quyền với người dùng'],
            ['name' => 'manage-permissions', 'description' => 'Có toàn quyền với phân quyền'],
            ['name' => 'manage-orders', 'description' => 'Quản lý đơn hàng'],
            ['name' => 'manage-products', 'description' => 'Quản lý phụ tùng ô tô'],
            ['name' => 'manage-units', 'description' => 'Quản lý đơn vị tính'],
            ['name' => 'manage-categories', 'description' => 'Quản lý danh mục'],
            ['name' => 'manage-promotions', 'description' => 'Quản lý khuyến mãi'],
            ['name' => 'manage-vouchers', 'description' => 'Quản lý voucher'],
            ['name' => 'manage-articles', 'description' => 'Quản lý tin tức'],
            ['name' => 'manage-suppliers', 'description' => 'Quản lý nhà cung cấp'],
            ['name' => 'manage-inventories', 'description' => 'Quản lý hàng tồn kho'],
            ['name' => 'manage-imports', 'description' => 'Quản lý phiếu nhập'],
            ['name' => 'manage-exports', 'description' => 'Quản lý phiếu xuất'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
