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
        Schema::create('subscription_shippings', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->foreignId('subscription_order_id')->constrained('subscription_orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name', 191);
            $table->text('mobile_number');
            $table->text('address');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_shippings');
    }
};
