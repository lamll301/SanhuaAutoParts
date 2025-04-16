<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Export;
use App\Models\ExportDetail;
use App\Models\Product;
use App\Models\Inventory;
use Faker\Factory as Faker;

class ExportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $inventoryItems = Inventory::with('product')->get();
        if ($inventoryItems->isEmpty()) return;

        foreach (range(1, 15) as $i) {
            $export = Export::create([
                'export_date' => $faker->dateTimeBetween('-6 months', 'now'),
                'receiver' => $faker->name,
                'address' => $faker->address,
                'reason' => $faker->randomElement(['Xuất bán', 'Xuất kiểm tra', 'Xuất bảo hành']),
                'total_amount' => 0,
            ]);

            $total = 0;
            $itemsUsed = $inventoryItems->random(rand(2, 5));

            foreach ($itemsUsed as $item) {
                $maxQuantity = max(1, $item->quantity);
                $quantity = $faker->numberBetween(1, $maxQuantity);
                $price = $faker->numberBetween(100000, 3000000);

                ExportDetail::create([
                    'export_id' => $export->id,
                    'product_id' => $item->product_id,
                    'inventory_id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $total += $quantity * $price;
            }

            $export->update(['total_amount' => $total]);
        }
    }
}
