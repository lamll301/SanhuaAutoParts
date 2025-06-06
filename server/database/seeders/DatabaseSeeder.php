<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            BasicDataSeeder::class,
            ArticleSeeder::class,
            ProductSeeder::class,
            WarehouseSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            ProfitSeeder::class,
        ]);
    }
}
