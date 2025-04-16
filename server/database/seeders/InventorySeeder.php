<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $productIds = \App\Models\Product::pluck('id')->toArray();
        $importIds = \App\Models\Import::pluck('id')->toArray();

        foreach (range(1, 100) as $i) {
            $location = 'Khu ' . $faker->randomElement(['A', 'B', 'C', 'D']) . ' - ' .
                        'Tầng ' . $faker->numberBetween(1, 5) . ' - ' . 
                        'Kệ ' . $faker->numberBetween(1, 20);

            Inventory::create([
                'product_id' => $faker->randomElement($productIds),
                'import_id' => $faker->randomElement($importIds),
                'location' => $location,
                'batch_number' => strtoupper(Str::random(8)),
                'quantity' => $faker->numberBetween(10, 500),
                'expiry_date' => $faker->dateTimeBetween('+6 months', '+2 years')->format('Y-m-d'),
                'manufacture_date' => $faker->dateTimeBetween('-2 years', '-6 months')->format('Y-m-d'),
            ]);
        }
    }
}
