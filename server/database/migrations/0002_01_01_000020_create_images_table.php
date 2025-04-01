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
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('article_id')->nullable();
            // $table->unsignedInteger('product_id')->nullable();
            // $table->unsignedInteger('review_id')->nullable();
            // $table->unsignedInteger('message_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
            // $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
