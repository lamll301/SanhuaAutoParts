<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            UnitSeeder::class,
            SupplierSeeder::class,
            VoucherSeeder::class,
            PromotionSeeder::class,
            ArticleSeeder::class,
            CategorySeeder::class,
        ]);
        
        $rolePermissions = [
            'admin' => ['full-access'],
            'nhân viên hệ thống' => [
                'manage-products',
                'manage-categories',
                'manage-promotions',
                'manage-vouchers',
                'manage-articles',
                'manage-suppliers',
            ],
            'nhân viên kho' => [
                'manage-inventories',
                'manage-imports',
                'manage-exports',
            ],
            'nhân viên bán hàng' => [
                'manage-orders',
                'manage-users',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $permissions = Permission::whereIn('name', $permissionNames)->pluck('id');
                $role->permissions()->attach($permissions);
            }
        }
    }
}
