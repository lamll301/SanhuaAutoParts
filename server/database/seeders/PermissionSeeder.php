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
            'view',
            'articles.approve', 
            'promotions.approve', 
            'vouchers.approve', 
            'imports.approve',
            'exports.approve',
            'disposals.approve',
            'checks.approve',
            'cancels.approve',
            'orders.approve',
        ];
        $modules = [
            'users',
            'products',
            'categories',
            'orders',
            'roles',
            'permissions',
            'orders',
            'inventories',
            'vouchers',
            'promotions',
            'articles',
            'suppliers',
            'units',
            'locations',
            'stock.receipts'
        ];

        foreach ($modules as $module) {
            Permission::create([
                'name' => "$module.manage",
            ]);
        }

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }
    }
}
