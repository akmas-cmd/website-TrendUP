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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('city');
            $table->string('zip');
            $table->string('payment_method');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('shipping_fee');
            $table->unsignedBigInteger('total');
            $table->string('status')->default('pending'); // pending, processing, shipped, completed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};