<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('deliverer', 64)->nullable();
            $table->string('address')->nullable();
            $table->date('import_date')->index();
            $table->unsignedInteger('total_amount')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('import_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->timestamps();

            $table->index(['import_id', 'product_id']);
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('location', 128);
            $table->string('batch_number', 64);
            $table->unsignedInteger('quantity');
            $table->date('expiry_date')->index();
            $table->date('manufacture_date');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('import_id')->nullable()->constrained()->nullOnDelete();

            $table->index(['product_id', 'batch_number']);
        });

        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->date('export_date')->index();
            $table->string('receiver', 64)->nullable();
            $table->string('address')->nullable();
            $table->string('reason')->nullable();
            $table->unsignedInteger('total_amount')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('export_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('export_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->timestamps();

            $table->index(['export_id', 'product_id', 'inventory_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_details');
        Schema::dropIfExists('exports');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('import_details');
        Schema::dropIfExists('imports');
    }
};
