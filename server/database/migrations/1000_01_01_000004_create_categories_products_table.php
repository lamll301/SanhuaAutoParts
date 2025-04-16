<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('type', 32)->nullable();
            $table->string('slug', 66)->unique();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('slug', 130)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('original_price');
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity')->default(0);
            $table->string('dimensions', 64)->nullable();  // kich thuoc
            $table->string('weight', 32)->nullable();     // trong luong
            $table->string('color', 32)->nullable();      // mau sac
            $table->string('material', 64)->nullable();   // chat lieu
            $table->string('compatibility', 128)->nullable();      // tuong thich

            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('set null');
        });

        Schema::create('product_category', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->primary(['product_id', 'category_id']);
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
