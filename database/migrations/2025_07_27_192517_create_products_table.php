<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('price');
            $table->foreignId('category_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('product_gender');
            $table->string('quantity');
            $table->boolean('visible');
            $table->timestamps();
        });

    Schema::create('product_sizes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
    $table->string('size');
    $table->integer('stock')->default(0);
    $table->timestamps();
    });

    Schema::create('product_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
    $table->string('image_url');
    $table->boolean('is_thumbnail');
    $table->timestamps();
});


}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_sizes');
        Schema::dropIfExists('product_images');
    }
};
