<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

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
            ProductSeeder::class,
            WarehouseSeeder::class,
        ]);

        $productIds = Product::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();
        $roleIds = Role::pluck('id')->toArray();
        $permissionIds = Permission::pluck('id')->toArray();

        $usedRolePermission = [];
        for ($i = 0; $i < 20; $i++) {
            $roleId = $faker->randomElement($roleIds);
            $permissionId = $faker->randomElement($permissionIds);
            $key = "$roleId-$permissionId";

            if (!isset($usedRolePermission[$key])) {
                DB::table('role_permission')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
                $usedRolePermission[$key] = true;
            }
        }
    }
}
