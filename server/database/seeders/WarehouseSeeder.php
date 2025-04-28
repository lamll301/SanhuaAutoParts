<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\{ Supplier, User, Product, Category };
use App\Models\{ Import, ImportDetail, Inventory, Location, Export, ExportDetail, Disposal, DisposalDetail, Check, CheckDetail, Cancel, CancelDetail };

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $supplierIds = Supplier::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        $locations = [];
        for ($i = 0; $i < 20; $i++) {
            $locations[] = Location::create([
                'zone' => $faker->randomElement(['A', 'B', 'C']),
                'aisle' => str_pad($faker->numberBetween(1, 5), 2, '0', STR_PAD_LEFT),
                'rack' => str_pad($faker->numberBetween(1, 10), 2, '0', STR_PAD_LEFT),
                'shelf' => str_pad($faker->numberBetween(1, 5), 2, '0', STR_PAD_LEFT),
                'bin' => str_pad($faker->numberBetween(1, 5), 2, '0', STR_PAD_LEFT),
                'status' => $faker->numberBetween(0, 3),
                'category_id' => $faker->randomElement($categoryIds),
            ]);
        }

        // Tạo imports và import details
        for ($i = 0; $i < 10; $i++) {
            $import = Import::create([
                'supplier_id' => $faker->randomElement($supplierIds),
                'created_by' => $faker->randomElement($userIds),
                'approved_by' => $faker->randomElement($userIds),
                'deliverer' => $faker->name,
                'address' => $faker->address,
                'date' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            for ($j = 0; $j < 5; $j++) {
                $quantity = $faker->numberBetween(10, 100);
                $price = $faker->numberBetween(10000, 100000);
                $amount = $quantity * $price;
                $totalAmount += $amount;

                $importDetail = ImportDetail::create([
                    'import_id' => $import->id,
                    'product_id' => $faker->randomElement($productIds),
                    'quantity' => $quantity,
                    'actual_quantity' => $quantity,
                    'price' => intval($price),
                ]);

                // Tạo inventory
                $inventory = Inventory::create([
                    'batch_number' => strtoupper($faker->bothify('??##??##')),
                    'quantity' => $quantity,
                    'manufacture_date' => $faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d'),
                    'expiry_date' => $faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
                    'product_id' => $importDetail->product_id,
                    'import_id' => $import->id,
                ]);

                // Gán inventory vào location
                $location = $faker->randomElement($locations);
                DB::table('inventory_location')->insert([
                    'inventory_id' => $inventory->id,
                    'location_id' => $location->id,
                    'quantity' => $quantity,
                ]);
            }

            $import->update(['total_amount' => (int) $totalAmount]);
        }

        // Tạo exports và export details
        for ($i = 0; $i < 10; $i++) {
            $export = Export::create([
                'created_by' => $faker->randomElement($userIds),
                'approved_by' => $faker->randomElement($userIds),
                'date' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'receiver' => $faker->name,
                'address' => $faker->address,
                'reason' => $faker->randomElement(['Xuất sản xuất', 'Xuất bán hàng', 'Xuất nội bộ']),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            $inventories = Inventory::where('quantity', '>', 0)->inRandomOrder()->limit(5)->get();
            
            foreach ($inventories as $inventory) {
                $quantity = $faker->numberBetween(1, min(10, $inventory->quantity));
                $price = $inventory->product->price;
                $amount = $quantity * $price;
                $totalAmount += $amount;

                ExportDetail::create([
                    'export_id' => $export->id,
                    'inventory_id' => $inventory->id,
                    'quantity' => $quantity,
                    'actual_quantity' => $quantity,
                    'price' => intval($price),
                ]);

                $inventory->decrement('quantity', $quantity);
            }

            $export->update(['total_amount' => (int) $totalAmount]);
        }

        // Tạo disposals và disposal details
        for ($i = 0; $i < 10; $i++) {
            $disposal = Disposal::create([
                'created_by' => $faker->randomElement($userIds),
                'approved_by' => $faker->randomElement($userIds),
                'date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'reason' => $faker->randomElement(['Hết hạn', 'Hư hỏng', 'Lỗi kỹ thuật']),
                'method' => $faker->randomElement(['Tiêu hủy', 'Bán thanh lý', 'Tặng']),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            $inventories = Inventory::where('quantity', '>', 0)->inRandomOrder()->limit(5)->get();
            
            foreach ($inventories as $inventory) {
                $quantity = $faker->numberBetween(1, min(5, $inventory->quantity));
                $price = $inventory->product->price * 0.5; // Giá thanh lý 50%
                $amount = $quantity * $price;
                $totalAmount += $amount;

                DisposalDetail::create([
                    'disposal_id' => $disposal->id,
                    'inventory_id' => $inventory->id,
                    'quantity' => $quantity,
                    'actual_quantity' => $quantity,
                    'price' => intval($price),
                ]);

                $inventory->decrement('quantity', $quantity);
            }

            $disposal->update(['total_amount' => (int) $totalAmount]);
        }

        // Tạo checks và check details
        for ($i = 0; $i < 10; $i++) {
            $check = Check::create([
                'created_by' => $faker->randomElement($userIds),
                'approved_by' => $faker->randomElement($userIds),
                'date' => $faker->dateTimeBetween('-2 weeks', 'now')->format('Y-m-d'),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            $inventories = Inventory::inRandomOrder()->limit(5)->get();
            
            foreach ($inventories as $inventory) {
                $quantity = $inventory->quantity;
                $actualQuantity = max(0, $quantity + $faker->numberBetween(-3, 3));
                $price = $inventory->product->price;
                $amount = $actualQuantity * $price;
                $totalAmount += $amount;

                CheckDetail::create([
                    'check_id' => $check->id,
                    'inventory_id' => $inventory->id,
                    'quality' => $faker->randomElement(['Còn tốt 100%', 'Kém phẩm chất', 'Mất phẩm chất']),
                    'quantity' => $quantity,
                    'actual_quantity' => $actualQuantity,
                    'price' => intval($price),
                ]);
            }

            $check->update(['total_amount' => (int) $totalAmount]);
        }

        // Tạo cancels và cancel details
        for ($i = 0; $i < 10; $i++) {
            $cancel = Cancel::create([
                'created_by' => $faker->randomElement($userIds),
                'approved_by' => $faker->randomElement($userIds),
                'date' => $faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d'),
                'reason' => $faker->randomElement(['Hư hỏng', 'Mất phẩm chất', 'Nhầm lẫn']),
                'method' => $faker->randomElement(['Tiêu hủy', 'Trả lại nhà cung cấp']),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            $inventories = Inventory::where('quantity', '>', 0)->inRandomOrder()->limit(5)->get();
            
            foreach ($inventories as $inventory) {
                $quantity = $faker->numberBetween(1, min(3, $inventory->quantity));
                $price = $inventory->product->price;
                $amount = $quantity * $price;
                $totalAmount += $amount;

                CancelDetail::create([
                    'cancel_id' => $cancel->id,
                    'inventory_id' => $inventory->id,
                    'quantity' => $quantity,
                    'price' => intval($price),
                ]);

                $inventory->decrement('quantity', $quantity);
            }

            $cancel->update(['total_amount' => (int) $totalAmount]);
        }
    }
}
