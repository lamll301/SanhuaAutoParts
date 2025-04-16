<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->unique();
            $table->string('description', 128)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('address');
            $table->string('email', 128)->unique();
            $table->string('phone', 16);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16)->unique();
            $table->unsignedInteger('value');
            $table->unsignedInteger('usage_limit');
            $table->unsignedInteger('used_count')->default(0);
            $table->dateTime('start_date')->index();
            $table->dateTime('end_date')->index();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->unsignedInteger('discount_percent');
            $table->unsignedInteger('max_discount_amount')->nullable();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 128);
            $table->string('slug', 130)->unique();
            $table->string('highlight', 64)->nullable();
            $table->string('author', 64);
            $table->dateTime('publish_date')->nullable()->index();
            $table->longText('content')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('units');
    }
};
