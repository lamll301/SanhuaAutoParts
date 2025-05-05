<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $voucherIds = Voucher::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();
        $addressTypes = ['Nhà riêng', 'Văn phòng'];
        $paymentMethods = ['Mã QR', 'Thẻ tín dụng / thẻ ghi nợ', 'Ví điện tử', 'Thanh toán khi nhận hàng'];

        for ($i = 0; $i < 10; $i++) {
            $totalAmount = $faker->numberBetween(100000, 1000000);
            $shippingFee = $faker->numberBetween(20000, 50000);
            $order = Order::create([
                'user_id' => $faker->randomElement($userIds),
                'voucher_id' => $faker->randomElement([null, ...$voucherIds]),
                'status' => $faker->numberBetween(0, 5),
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'city_id' => $faker->numberBetween(1, 63),
                'district_id' => $faker->numberBetween(1, 700),
                'ward_id' => $faker->numberBetween(1, 10000),
                'shipping_address' => $faker->address,
                'address_type' => $faker->randomElement($addressTypes),
                'payment_method' => $faker->randomElement($paymentMethods),
                'payment_status' => $faker->numberBetween(0, 1),
            ]);

            $numDetails = $faker->numberBetween(1, 3);
            for ($j = 0; $j < $numDetails; $j++) {
                $productId = $faker->randomElement($productIds);
                $quantity = $faker->numberBetween(1, 5);
                $price = $faker->numberBetween(50000, 500000);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                // if ($faker->boolean(50)) {
                //     Review::create([
                //         'user_id' => $order->user_id,
                //         'product_id' => $productId,
                //         'order_id' => $order->id,
                //         'rating' => $faker->numberBetween(1, 5),
                //         'comment' => $faker->optional()->sentence,
                //     ]);
                // }
            }
        }

        $files = collect(Storage::disk('public')->allFiles('default/product'))
            ->filter(function ($file) {
                return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
            })->values()->all();

        for ($i = 0; $i < 50; $i++) {
            $userId = $faker->randomElement($userIds);
            $orderId = $faker->numberBetween(1, 10);
        
            if (Review::where('user_id', $userId)
                ->where('product_id', 1)
                ->where('order_id', $orderId)
                ->exists()) {
                continue;
            }
        
            $review = Review::create([
                'product_id' => 1,
                'user_id' => $userId,
                'order_id' => $orderId,
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->optional()->sentence,
            ]);
        
            $images = [];
            for ($j = 0; $j < 5; $j++) {
                $images[] = [
                    'review_id' => $review->id,
                    'filename' => 'product_image_' . Str::random(5),
                    'path' => '/storage/' . $faker->randomElement($files),
                    'is_thumbnail' => false,
                    'size' => rand(100000, 800000),
                    'mime_type' => 'image/jpeg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        
            Image::insert($images);
        }
    }
}