<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    
    public function up(): void{
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('payment_id')->nullable();
            $table->string('payment_mode');
            $table->string('tracking_no');
            $table->tinyInteger('status')->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('orders');
    }
};