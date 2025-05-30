<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\{
    Location, Import, ImportDetail, Inventory, Export, ExportDetail,
    Check, CheckDetail, Disposal, DisposalDetail, Cancel, CancelDetail,
    Category, Product, Supplier, User
};
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    private $faker;
    private $productIds;
    private $supplierIds;
    private $usersId;
    private $inventoryIds = [], $locationIds = [];

    public function __construct()
    {
        $this->faker = Faker::create('vi_VN');
        $this->productIds = Product::pluck('id')->toArray();
        $this->supplierIds = Supplier::pluck('id')->toArray();
        $this->usersId = User::whereNotNull('role_id')->pluck('id')->toArray();
    }

    private function createLocations(): void {
        Category::create(['name' => 'Khu hàng mới nhập', 'type' => 'location']);
        Category::create(['name' => 'Khu hàng chờ xuất', 'type' => 'location']);
        Category::create(['name' => 'Khu thanh lý', 'type' => 'location']);
        Category::create(['name' => 'Khu hàng trả về', 'type' => 'location']);
        $locationCategoryIds = Category::where('type', 'location')->pluck('id')->toArray();
        for ($i = 0; $i < 25; $i++) {
            $location = Location::create([
                'zone' => $this->faker->randomElement(['A', 'B', 'C']),
                'aisle' => $this->faker->randomElement(['01', '02', '03']),
                'rack' => $this->faker->randomElement(['R01', 'R02', 'R03']),
                'shelf' => $this->faker->randomElement(['S01', 'S02', 'S03']),
                'bin' => $this->faker->randomElement(['B01', 'B02', 'B03']),
                'status' => $this->faker->randomElement([0, 1]),
                'category_id' => $this->faker->optional()->randomElement($locationCategoryIds),
            ]);
            $this->locationIds[] = $location->id;
        }
    }
    private function createInventories(): void {
        for ($i = 0; $i < 25; $i++) {
            $inventory = Inventory::create([
                'product_id' => $this->faker->randomElement($this->productIds),
                'manufacture_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'expiry_date' => $this->faker->dateTimeBetween('now', '+1 year'),
                'batch_number' => $this->faker->randomLetter() . $this->faker->numerify('##-##'),
                'quantity' => $this->faker->numberBetween(100, 1000),
                'price' => $this->faker->numberBetween(100000, 1000000),
            ]);
            $this->inventoryIds[] = $inventory->id;
        }
    }
    private function createImports(): void {
        for ($i = 0; $i < 25; $i++) {
            $import = Import::create([
                'supplier_id' => $this->faker->randomElement($this->supplierIds),
                'created_by' => $this->faker->randomElement($this->usersId),
                'deliverer' => $this->faker->name,
                'address' => $this->faker->address,
                'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ]);
            $j = $this->faker->numberBetween(1, 5);
            $total_amount = 0;
            for ($k = 0; $k < $j; $k++) {
                $detail = ImportDetail::create([
                    'import_id' => $import->id,
                    'product_id' => $this->faker->randomElement($this->productIds),
                    'quantity' => $this->faker->numberBetween(1, 100),
                    'price' => $this->faker->numberBetween(100000, 1000000),
                ]);
                $total_amount += $detail->price * $detail->quantity;
            }
            $import->update(['total_amount' => $total_amount]);
        }
    }
    private function createExports(): void {
        for ($i = 0; $i < 25; $i++) {
            $export = Export::create([
                'created_by' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->optional()->randomElement($this->usersId),
                'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'receiver' => $this->faker->name,
                'address' => $this->faker->address,
                'reason' => $this->faker->randomElement(['Xuất bán hàng', 'Xuất chuyển kho', 'Xuất trả hàng NCC', 'Xuất thanh lý/hủy hàng']),
            ]);
            $j = $this->faker->numberBetween(1, 5);
            $total_amount = 0;
            for ($k = 0; $k < $j; $k++) {
                $detail = ExportDetail::create([
                    'export_id' => $export->id,
                    'inventory_id' => $this->faker->randomElement($this->inventoryIds),
                    'quantity' => $this->faker->numberBetween(1, 100),
                    'price' => $this->faker->numberBetween(100000, 1000000),
                ]);
                $total_amount += $detail->price * $detail->quantity;
            }
            $export->update(['total_amount' => $total_amount]);
        }
    }
    private function createChecks(): void {
        for ($i = 0; $i < 25; $i++) {
            $check = Check::create([
                'created_by' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->optional()->randomElement($this->usersId),
                'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ]);
            $j = $this->faker->numberBetween(1, 5);
            $total_amount = 0;
            for ($k = 0; $k < $j; $k++) {
                $detail = CheckDetail::create([
                    'check_id' => $check->id,
                    'inventory_id' => $this->faker->randomElement($this->inventoryIds),
                    'quality' => $this->faker->randomElement(['Còn tốt 100%', 'Kém phẩm chất', 'Mất phẩm chất']),
                    'quantity' => $this->faker->numberBetween(1, 100),
                    'price' => $this->faker->numberBetween(100000, 1000000),
                ]);
                $total_amount += $detail->price * $detail->quantity;
            }
            $check->update(['total_amount' => $total_amount]);
        }
    }
    private function createDisposals(): void {
        for ($i = 0; $i < 25; $i++) {
            $disposal = Disposal::create([
                'created_by' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->optional()->randomElement($this->usersId),
                'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'reason' => $this->faker->randomElement(['Hàng hết hạn sử dụng', 'Hàng bị lỗi không thể sử dụng', 'Hàng bị hư hỏng']),
            ]);
            $j = $this->faker->numberBetween(1, 5);
            $total_amount = 0;
            for ($k = 0; $k < $j; $k++) {
                $detail = DisposalDetail::create([
                    'disposal_id' => $disposal->id,
                    'inventory_id' => $this->faker->randomElement($this->inventoryIds),
                    'quantity' => $this->faker->numberBetween(1, 100),
                    'price' => $this->faker->numberBetween(100000, 1000000),
                    'method' => $this->faker->randomElement(['Bán phế liệu', 'Tiêu hủy', 'Bán lại']),
                ]);
                $total_amount += $detail->price * $detail->quantity;
            }
            $disposal->update(['total_amount' => $total_amount]);
        }
    }
    private function createCancels(): void {
        for ($i = 0; $i < 25; $i++) {
            $cancel = Cancel::create([
                'created_by' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->optional()->randomElement($this->usersId),
                'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'reason' => $this->faker->randomElement(['Hàng hết hạn sử dụng', 'Hàng bị lỗi không thể sử dụng', 'Hàng bị hư hỏng']),
            ]);
            $j = $this->faker->numberBetween(1, 5);
            $total_amount = 0;
            for ($k = 0; $k < $j; $k++) {
                $detail = CancelDetail::create([
                    'cancel_id' => $cancel->id,
                    'inventory_id' => $this->faker->randomElement($this->inventoryIds),
                    'quantity' => $this->faker->numberBetween(1, 100),
                    'price' => $this->faker->numberBetween(100000, 1000000),
                    'method' => $this->faker->randomElement(['Bán phế liệu', 'Tiêu hủy', 'Bán lại']),
                ]);
                $total_amount += $detail->price * $detail->quantity;    
            }
            $cancel->update(['total_amount' => $total_amount]);
        }
    }
    private function createInventoryLocation(): void {
        foreach ($this->inventoryIds as $inventoryId) {
            $quantity = Inventory::find($inventoryId)->quantity;
            $numLocations = $this->faker->numberBetween(1, 3);
            $splits = $this->splitQuantity($quantity, $numLocations);
            foreach ($splits as $q) {
                DB::table('inventory_location')->insert([
                    'inventory_id' => $inventoryId,
                    'location_id' => $this->faker->randomElement($this->locationIds),
                    'quantity' => $q,
                ]);
            }
        }
    }
    private function splitQuantity(int $total, int $parts): array {
        if ($parts === 1) return [$total];
        $splits = [];
        $remaining = $total;
        for ($i = 0; $i < $parts - 1; $i++) {
            $max = $remaining - ($parts - $i - 1);
            $value = rand(1, $max);
            $splits[] = $value;
            $remaining -= $value;
        }
        $splits[] = $remaining;
        shuffle($splits);
        return $splits;
    }
    public function run(): void
    {
        $this->createLocations();
        $this->createInventories();
        $this->createImports();
        $this->createExports();
        $this->createChecks();
        $this->createDisposals();
        $this->createCancels();
        $this->createInventoryLocation();
    }
}
