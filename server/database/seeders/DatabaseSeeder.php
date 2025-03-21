<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $permissions = [
            ['name' => 'full-access', 'description' => 'Có thể truy cập và chỉnh sửa tất cả dữ liệu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-users', 'description' => 'Có toàn quyền với người dùng', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-permissions', 'description' => 'Có toàn quyền với phân quyền', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-orders', 'description' => 'Quản lý đơn hàng', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-products', 'description' => 'Quản lý phụ tùng ô tô', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-units', 'description' => 'Quản lý đơn vị tính', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-categories', 'description' => 'Quản lý danh mục', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-promotions', 'description' => 'Quản lý khuyến mãi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-vouchers', 'description' => 'Quản lý voucher', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-articles', 'description' => 'Quản lý tin tức', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-suppliers', 'description' => 'Quản lý nhà cung cấp', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-inventories', 'description' => 'Quản lý hàng tồn kho', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-imports', 'description' => 'Quản lý phiếu nhập', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage-exports', 'description' => 'Quản lý phiếu xuất', 'created_at' => now(), 'updated_at' => now()],
        ];
        $roles= [
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'nhân viên hệ thống', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'nhân viên kho', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'nhân viên bán hàng', 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('permissions')->insert($permissions);
        DB::table('roles')->insert($roles);
    }
}
