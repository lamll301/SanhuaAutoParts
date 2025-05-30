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
    private $faker;
    private $customerIds;
    private $adminIds;
    private $productIds;

    public function __construct() {
        $this->faker = Faker::create('vi_VN');
        $this->customerIds = User::whereNull('role_id')->pluck('id')->toArray();
        $this->adminIds = User::whereNotNull('role_id')->pluck('id')->toArray();
        $this->productIds = Product::pluck('id')->toArray();
    }
    public function run(): void
    {
        $this->createCompletedOrders();
    }
    private function createCompletedOrders()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        for ($i = 0; $i < 500; $i++) {
            $shippingFee = $this->faker->randomElement([15000, 20000, 25000, 30000]);
            $createdAt = $this->faker->dateTimeBetween($startOfYear, $endOfYear);
            $shippedAt = Carbon::instance($createdAt)->addHours($this->faker->numberBetween(1, 48));
            $completedAt = Carbon::instance($shippedAt)->addDays($this->faker->numberBetween(1, 7));
            $order = Order::create([
                'user_id' => $this->faker->randomElement($this->customerIds),
                'approved_by' => $this->faker->randomElement($this->adminIds),
                'status' => Order::STATUS_COMPLETED,
                'shipping_fee' => $shippingFee,
                'total_amount' => 0,
                'name' => $this->faker->name,
                'phone' => '0' . $this->faker->numberBetween(300000000, 999999999),
                'shipping_address' => $this->faker->address,
                'address_type' => $this->faker->randomElement(['Nhà riêng', 'Văn phòng']),
                'payment_method' => $this->faker->randomElement(['Mã QR', 'Thẻ tín dụng / thẻ ghi nợ', 'Ví điện tử', 'Thanh toán khi nhận hàng']),
                'payment_info' => 'Mã giao dịch: ' . $this->faker->randomNumber(8),
                'payment_status' => Order::PAYMENT_STATUS_PAID,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $totalAmount = $this->createOrderDetails($order);
            $order->update(['total_amount' => $totalAmount + $shippingFee]);
        }
    }
    private function createOrderDetails($order)
    {
        $j = $this->faker->numberBetween(1, 3);
        $totalAmount = 0;
        for ($i = 0; $i < $j; $i++) {
            $productId = $this->faker->randomElement($this->productIds);
            $product = Product::find($productId);
            $quantity = $this->faker->numberBetween(1, 5);
            $price = (int)($product->price * $this->faker->numberBetween(95, 110) / 100);
            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $this->linkInventory($orderDetail, $quantity);
            $totalAmount += $quantity * $price;
        }
        return $totalAmount;
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