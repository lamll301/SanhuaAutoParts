<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'nhân viên hệ thống'],
            ['name' => 'nhân viên kho'],
            ['name' => 'nhân viên bán hàng']
        ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
