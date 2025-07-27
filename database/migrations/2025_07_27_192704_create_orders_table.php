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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum( 'status',['Pending', 'Paid', 'Packed', 'Shipped']);
            $table->double('total_price');
            $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('price');
            $table->string('item_size');
            $table->timestamps();
    });
    Schema::create('orders_per_hours', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->time('time');
        $table->integer('order_count');
        $table->decimal('revenue', 10, 2);
        $table->timestamps();
    });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
