<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('voucher_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('shipping_fee');
            $table->unsignedInteger('total_amount');
            $table->string('name');
            $table->string('phone');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('district_id');
            $table->unsignedInteger('ward_id');
            $table->string('shipping_address');
            $table->enum('address_type', ['Nhà riêng', 'Văn phòng'])->default('Nhà riêng');
            $table->enum('payment_method', ['Mã QR', 'Thẻ tín dụng / thẻ ghi nợ', 'Ví điện tử', 'Thanh toán khi nhận hàng'])->default('Thanh toán khi nhận hàng');
            $table->tinyInteger('payment_status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price'); 
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned()->check('rating >= 1 AND rating <= 5');
            $table->text('comment')->nullable();
            $table->timestamps();
            // đảm bảo user chỉ đánh giá 1 sản phẩm trong 1 đơn hàng 1 lần
            $table->unique(['user_id', 'product_id', 'order_id'], 'unique_user_product_order_review');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};
