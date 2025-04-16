<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\Supplier;
use Faker\Factory as Faker;

class ImportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $supplierIds = Supplier::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        foreach (range(1, 20) as $i) {
            $import = Import::create([
                'supplier_id' => $faker->optional()->randomElement($supplierIds),
                'deliverer' => $faker->name,
                'address' => $faker->address,
                'import_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'total_amount' => 0,
            ]);

            $total = 0;
            $productsUsed = $faker->randomElements($productIds, rand(2, 5));

            foreach ($productsUsed as $productId) {
                $quantity = $faker->numberBetween(1, 100);
                $price = $faker->numberBetween(100000, 2000000);

                ImportDetail::create([
                    'import_id' => $import->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $total += $quantity * $price;
            }

            $import->update(['total_amount' => $total]);
        }
    }
}
