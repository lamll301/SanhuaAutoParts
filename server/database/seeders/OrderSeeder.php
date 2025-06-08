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
    private const STATUS = [0, 1, 2, 4];
    private const ADDRESS_TYPE = ['Nhà riêng', 'Văn phòng'];
    private $faker;
    private $userIds;
    private $productIds;
    private $voucherIds;
    private $adminUserIds;

    public function __construct() {
        $this->faker = Faker::create('vi_VN');
        $this->userIds = User::whereNull('role_id')->pluck('id')->toArray();
        $this->productIds = Product::pluck('id')->toArray();
        $this->voucherIds = Voucher::pluck('id')->toArray();
        $this->adminUserIds = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->pluck('id')->toArray();
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

    private function createOrderDetails($order, $productIds): int {
        $numberOfProducts = $this->faker->numberBetween(2, 5);
        $selectedProducts = $this->faker->randomElements($productIds, $numberOfProducts);
        $totalAmount = 0;
        foreach ($selectedProducts as $productId) {
            $quantity = $this->faker->numberBetween(1, 3);
            $price = $this->faker->numberBetween(50000, 500000);
            $totalAmount += $quantity * $price;
            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $this->linkInventory($orderDetail, $quantity);
        }
        return $totalAmount;
    }

    public function createUnpaidOrders() {
        $userId = User::where('username', 'admin')->first()->id;
        for ($i = 0; $i < 10; $i++) {
            $shippingFee = $this->faker->randomElement([15000, 20000, 25000, 30000]);
            $createdAt = $this->faker->dateTimeBetween('-1 month', 'now');
            $order = Order::create([
                'user_id' => $userId,
                'approved_by' => $this->faker->randomElement($this->adminUserIds, null),
                'voucher_id' => $this->faker->boolean(30) ? $this->faker->randomElement($this->voucherIds) : null,
                'status' => Order::STATUS_PENDING,
                'shipping_fee' => $shippingFee,
                'total_amount' => 0,
                'name' => $this->faker->name,
                'phone' => '0' . $this->faker->numberBetween(300000000, 999999999),
                'shipping_address' => $this->faker->address,
                'address_type' => $this->faker->randomElement(self::ADDRESS_TYPE),
                'payment_method' => $this->faker->randomElement([Order::PAYMENT_METHOD_QR, Order::PAYMENT_METHOD_CARD, Order::PAYMENT_METHOD_EWALLET]),
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'created_at' => $createdAt,
            ]);
            $totalAmount = $this->createOrderDetails($order, $this->productIds);
            $order->update(['total_amount' => $totalAmount + $shippingFee]);
        }
    }

    private function createOrders() {
        for ($i = 0; $i < 40; $i++) {
            $userId = $this->faker->randomElement($this->userIds);
            $shippingFee = $this->faker->randomElement([15000, 20000, 25000, 30000]);
            $paymentMethod = $this->faker->randomElement(self::PAYMENT_METHODS);
            $status = $this->faker->randomElement(self::STATUS);
            $createdAt = $this->faker->dateTimeBetween('-3 months', 'now');
            $completedAt = $this->faker->dateTimeBetween('-3 months', 'now');
            $approvedBy = $this->faker->optional()->randomElement($this->adminUserIds);
            $shippedAt = null;
            $cancelledAt = null;
            $cancelReason = null;
            if ($status >= 2) {
                $shippedAt = Carbon::instance($createdAt)->addHours($this->faker->numberBetween(1, 48));
            }
            if ($status == 3) {
                $completedAt = Carbon::instance($shippedAt ?? $createdAt)->addDays($this->faker->numberBetween(1, 7));
            }
            if ($status == 4) {
                $cancelledAt = Carbon::instance($createdAt)->addHours($this->faker->numberBetween(1, 24));
                $cancelReason = $this->faker->randomElement([
                    "Thay đổi nhu cầu, không cần sản phẩm nữa", "Tìm được sản phẩm tương tự với giá rẻ hơn", "Thời gian giao hàng quá lâu không đáp ứng được nhu cầu",
                    "Nhận được thông tin không tốt về sản phẩm từ người quen", "Đổi ý muốn mua sản phẩm khác thay thế", "Không hài lòng với chính sách đổi trả/hoàn tiền của cửa hàng",
                    "Bị từ chối hỗ trợ vận chuyển/giảm giá khi yêu cầu", "Nhầm lẫn khi đặt hàng (sai màu, sai kích cỡ, sai sản phẩm)", "Không nhận được tư vấn/thông tin kịp thời từ nhân viên",
                    "Đơn hàng bị trễ hẹn giao nhiều lần gây mất niềm tin"
                ]);
            }
            $order = Order::create([
                'user_id' => $userId,
                'approved_by' => $approvedBy,
                'voucher_id' => $this->faker->boolean(30) ? $this->faker->randomElement($this->voucherIds) : null,
                'status' => $status,
                'shipping_fee' => $shippingFee,
                'total_amount' => 0,
                'name' => $this->faker->name,
                'phone' => '0' . $this->faker->numberBetween(300000000, 999999999),
                'shipping_address' => $this->faker->address,
                'address_type' => $this->faker->randomElement(self::ADDRESS_TYPE),
                'payment_method' => $paymentMethod,
                'payment_info' => $this->faker->sentence,
                'payment_status' => $status >= 1 ? 1 : $this->faker->randomElement([0, 1]),
                'shipped_at' => $shippedAt,
                'completed_at' => $completedAt,
                'cancelled_at' => $cancelledAt,
                'cancel_reason' => $cancelReason,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $totalAmount = $this->createOrderDetails($order, $this->productIds);
            $order->update(['total_amount' => $totalAmount + $shippingFee]);
        }
    }

    public function run(): void
    {
        $this->createOrders();
        $this->createUnpaidOrders();
    }
} 