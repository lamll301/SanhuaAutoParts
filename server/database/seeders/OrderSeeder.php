<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Product;
use App\Models\Voucher;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    private const PAYMENT_METHODS = ['Mã QR', 'Thẻ tín dụng / thẻ ghi nợ', 'Ví điện tử', 'Thanh toán khi nhận hàng'];
    private const STATUS = [0, 1, 2, 3, 4];
    private const ADDRESS_TYPE = ['Nhà riêng', 'Văn phòng'];

    private function createOrderDetailInventory($orderDetail, $faker)
    {
        $inventories = Inventory::where('product_id', $orderDetail->product_id)
            ->where('quantity', '>', 0)
            ->get();

        if ($inventories->isEmpty()) {
            return;
        }
        $remainingQuantity = $orderDetail->quantity;
        foreach ($inventories as $inventory) {
            if ($remainingQuantity <= 0) break;
            $quantityToTake = min($remainingQuantity, $inventory->quantity);
            DB::table('order_detail_inventory')->insert([
                'order_detail_id' => $orderDetail->id,
                'inventory_id' => $inventory->id,
                'quantity' => $quantityToTake,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            $remainingQuantity -= $quantityToTake;
        }
    }

    private function createOrderDetails($faker, $order, $productIds): int {
        $numberOfProducts = $faker->numberBetween(2, 5);
        $selectedProducts = $faker->randomElements($productIds, $numberOfProducts);
        $totalAmount = 0;
        foreach ($selectedProducts as $productId) {
            $quantity = $faker->numberBetween(1, 3);
            $price = $faker->numberBetween(50000, 500000);
            $totalAmount += $quantity * $price;
            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $this->createOrderDetailInventory($orderDetail, $faker);
        }
        return $totalAmount;
    }

    private function createCompletedOrders($faker, $userIds, $productIds, $voucherIds, $adminUserIds) {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        for ($i = 0; $i < 200; $i++) {
            $shippingFee = $faker->randomElement([15000, 20000, 25000, 30000]);
            $createdAt = $faker->dateTimeBetween($startOfYear, $endOfYear);
            $shippedAt = Carbon::instance($createdAt)->addHours($faker->numberBetween(1, 48));
            $completedAt = Carbon::instance($shippedAt)->addDays($faker->numberBetween(1, 7));

            $order = Order::create([
                'user_id' => $faker->randomElement($userIds),
                'approved_by' => $faker->randomElement($adminUserIds),
                'voucher_id' => $faker->boolean(30) ? $faker->randomElement($voucherIds) : null,
                'status' => 3,
                'shipping_fee' => $shippingFee,
                'total_amount' => 0,
                'name' => $faker->name,
                'phone' => '0' . $faker->numberBetween(300000000, 999999999),
                'shipping_address' => $faker->address,
                'address_type' => $faker->randomElement(self::ADDRESS_TYPE),
                'payment_method' => $faker->randomElement(self::PAYMENT_METHODS),
                'payment_info' => $faker->sentence,
                'payment_status' => 1,
                'shipped_at' => $shippedAt,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $totalAmount = $this->createOrderDetails($faker, $order, $productIds);
            $order->update(['total_amount' => $totalAmount + $shippingFee]);
        }
    }

    public function createUnpaidOrders($faker, $productIds, $voucherIds, $adminUserIds) {
        $userId = User::where('username', 'admin')->first()->id;
        for ($i = 0; $i < 20; $i++) {
            $shippingFee = $faker->randomElement([15000, 20000, 25000, 30000]);
            $createdAt = $faker->dateTimeBetween('-1 month', 'now');
            
            $order = Order::create([
                'user_id' => $userId,
                'approved_by' => $faker->randomElement($adminUserIds, null),
                'voucher_id' => $faker->boolean(30) ? $faker->randomElement($voucherIds) : null,
                'status' => 0,
                'shipping_fee' => $shippingFee,
                'total_amount' => 0,
                'name' => $faker->name,
                'phone' => '0' . $faker->numberBetween(300000000, 999999999),
                'shipping_address' => $faker->address,
                'address_type' => $faker->randomElement(self::ADDRESS_TYPE),
                'payment_method' => $faker->randomElement(self::PAYMENT_METHODS),
                'payment_status' => 0,
                'created_at' => $createdAt,
            ]);
            $totalAmount = $this->createOrderDetails($faker, $order, $productIds);
            $order->update(['total_amount' => $totalAmount + $shippingFee]);
        }
    }

    private function createOrders($faker, $userIds, $productIds, $voucherIds, $adminUserIds) {
        for ($i = 0; $i < 100; $i++) {

            $userId = $faker->randomElement($userIds);
            $shippingFee = $faker->randomElement([15000, 20000, 25000, 30000]);
            $paymentMethod = $faker->randomElement(self::PAYMENT_METHODS);
            $status = $faker->randomElement(self::STATUS);
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
            $completedAt = $faker->dateTimeBetween('-3 months', 'now');
            $approvedBy = !empty($adminUserIds) ? $faker->randomElement($adminUserIds) : null;
            $shippedAt = null;
            $completedAt = null;
            $cancelledAt = null;
            $cancelReason = null;
            
            if ($status >= 2) {
                $shippedAt = Carbon::instance($createdAt)->addHours($faker->numberBetween(1, 48));
            }
            
            if ($status == 3) {
                $completedAt = Carbon::instance($shippedAt ?? $createdAt)->addDays($faker->numberBetween(1, 7));
            }
            
            if ($status == 4) {
                $cancelledAt = Carbon::instance($createdAt)->addHours($faker->numberBetween(1, 24));
                $cancelReason = $faker->randomElement([
                    'Khách hàng hủy đơn',
                    'Hết hàng',
                    'Địa chỉ giao hàng không chính xác',
                    'Khách hàng thay đổi ý định',
                    'Sản phẩm lỗi'
                ]);
            }

            $order = Order::create([
                'user_id' => $userId,
                'approved_by' => $approvedBy,
                'voucher_id' => $faker->boolean(30) ? $faker->randomElement($voucherIds) : null,
                'status' => $status,
                'shipping_fee' => $shippingFee,
                'total_amount' => 0,
                'name' => $faker->name,
                'phone' => '0' . $faker->numberBetween(300000000, 999999999),
                'shipping_address' => $faker->address,
                'address_type' => $faker->randomElement(self::ADDRESS_TYPE),
                'payment_method' => $paymentMethod,
                'payment_info' => $faker->sentence,
                'payment_status' => $status >= 1 ? 1 : $faker->randomElement([0, 1]),
                'shipped_at' => $shippedAt,
                'completed_at' => $completedAt,
                'cancelled_at' => $cancelledAt,
                'cancel_reason' => $cancelReason,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $totalAmount = $this->createOrderDetails($faker, $order, $productIds);
            $order->update(['total_amount' => $totalAmount + $shippingFee]);
        }
    }

    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $userIds = User::whereNull('role_id')->pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();
        $voucherIds = Voucher::pluck('id')->toArray();
        $adminUserIds = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->pluck('id')->toArray();

        $this->createOrders($faker, $userIds, $productIds, $voucherIds, $adminUserIds);
        $this->createCompletedOrders($faker, $userIds, $productIds, $voucherIds, $adminUserIds);
        $this->createUnpaidOrders($faker, $productIds, $voucherIds, $adminUserIds);
    }
} 