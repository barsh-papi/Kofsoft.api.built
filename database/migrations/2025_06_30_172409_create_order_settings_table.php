<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('orderingSet');
            $table->double('tax')->nullable();
            $table->boolean('cashPayment')->nullable();
            $table->boolean('mobilePayment')->nullable();
            $table->boolean('guestCheckout')->nullable();
            $table->string('deliveryInstruction')->nullable();
            $table->string('shortText')->nullable();
            $table->boolean('requiredBtn')->nullable();
            $table->foreignId('restaurant_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_settings');
    }
};
