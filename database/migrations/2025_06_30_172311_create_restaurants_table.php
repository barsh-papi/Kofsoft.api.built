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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('restaurantName')->unique();
            $table->text('restaurantDescription')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('primaryColor');
            $table->string('isBrandingVisible');
            $table->string('currency')->default('GHS');
            $table->string('restaurantEmail');
            $table->string('restaurantPhone');
            $table->string('secondaryColor');
            $table->string('orderStatus');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
