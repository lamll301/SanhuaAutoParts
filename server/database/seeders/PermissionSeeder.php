<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'users',
            'products',
            'categories',
            'orders',
            'roles',
            'permissions',
            'orders',
            'inventories',
            'imports',
            'exports',
            'vouchers',
            'promotions',
            'articles',
            'suppliers',
            'units'
        ];

        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "$action $module",
                    'description' => ucfirst($action) . " " . str_replace('_', ' ', $module),
                ]);
            }
        }
        
        Permission::create([
            'name' => 'full access',
            'description' => 'Toàn quyền hệ thống',
        ]);
    }
}
