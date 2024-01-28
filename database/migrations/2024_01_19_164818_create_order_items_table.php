<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->integer('qty');
            $table->float('price');
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('order_items');
    }
};