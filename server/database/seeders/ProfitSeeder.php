<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfitSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        $customerIds = User::whereNull('role_id')->pluck('id')->toArray();
        $adminIds = User::whereNotNull('role_id')->pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        $this->createInventories($faker, $productIds);
        $this->createCompletedOrders($faker, $customerIds, $adminIds, $productIds);
    }

    private function createInventories($faker, $productIds)
    {
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) continue;

            for ($i = 0; $i < 3; $i++) {
                $importPrice = (int)($product->price * $faker->numberBetween(60, 80) / 100);
                
                Inventory::create([
                    'product_id' => $productId,
                    'batch_number' => 'BATCH' . $productId . '_' . $i,
                    'quantity' => $faker->numberBetween(100, 200),
                    'price' => $importPrice,
                    'manufacture_date' => $faker->dateTimeBetween('-1 year', '-3 months'),
                    'expiry_date' => $faker->dateTimeBetween('+6 months', '+2 years'),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createCompletedOrders($faker, $customerIds, $adminIds, $productIds)
    {
        for ($i = 0; $i < 100; $i++) {
            $createdAt = $faker->dateTimeBetween('-6 months', 'now');
            $completedAt = Carbon::instance($createdAt)->addDays($faker->numberBetween(1, 5));
            
            $order = Order::create([
                'user_id' => $faker->randomElement($customerIds),
                'approved_by' => $faker->randomElement($adminIds),
                'status' => Order::STATUS_COMPLETED,
                'shipping_fee' => $faker->randomElement([15000, 20000, 25000, 30000]),
                'total_amount' => 0,
                'name' => $faker->name,
                'phone' => '0' . $faker->numberBetween(300000000, 999999999),
                'shipping_address' => $faker->address,
                'address_type' => $faker->randomElement(['Nhà riêng', 'Văn phòng']),
                'payment_method' => $faker->randomElement(['Mã QR', 'Thẻ tín dụng / thẻ ghi nợ', 'Ví điện tử', 'Thanh toán khi nhận hàng']),
                'payment_info' => 'Thanh toán hoàn tất',
                'payment_status' => Order::PAYMENT_STATUS_PAID,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $this->createOrderDetails($faker, $order, $productIds);
        }
    }

    private function createOrderDetails($faker, $order, $productIds)
    {
        $itemsCount = $faker->numberBetween(1, 3);
        $totalAmount = 0;

        for ($i = 0; $i < $itemsCount; $i++) {
            $productId = $faker->randomElement($productIds);
            $product = Product::find($productId);
            $quantity = $faker->numberBetween(1, 5);
            $sellingPrice = (int)($product->price * $faker->numberBetween(95, 110) / 100);

            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $sellingPrice,
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ]);

            $this->linkInventory($orderDetail, $quantity);
            $totalAmount += $quantity * $sellingPrice;
        }

        $order->update(['total_amount' => $totalAmount + $order->shipping_fee]);
    }

    private function linkInventory($orderDetail, $quantity)
    {
        $inventories = Inventory::where('product_id', $orderDetail->product_id)
            ->where('quantity', '>', 0)
            ->get();

        $remainingQuantity = $quantity;

        foreach ($inventories as $inventory) {
            if ($remainingQuantity <= 0) break;

            $quantityToTake = min($remainingQuantity, $inventory->quantity);

            DB::table('order_detail_inventory')->insert([
                'order_detail_id' => $orderDetail->id,
                'inventory_id' => $inventory->id,
                'quantity' => $quantityToTake,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $inventory->decrement('quantity', $quantityToTake);
            $remainingQuantity -= $quantityToTake;
        }
    }
}