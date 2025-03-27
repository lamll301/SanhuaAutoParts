<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
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
