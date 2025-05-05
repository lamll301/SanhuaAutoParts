<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('filename')->nullable();
            $table->integer('size')->nullable(); 
            $table->string('mime_type')->nullable();
            $table->boolean('is_thumbnail')->default(false);
            // $table->unsignedBigInteger('message_id')->nullable();
            $table->foreignId('review_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('article_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamps();

            // $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
