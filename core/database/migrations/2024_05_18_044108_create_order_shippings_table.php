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

        Schema::create('order_shippings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->text('order_number');
            $table->unsignedBigInteger('user_id');
            $table->string('name')->notNullable();
            $table->text('phone')->notNullable();
            $table->text('alternate_phone')->nullable();
            $table->text('email')->nullable();
            $table->unsignedInteger('country_id')->notNullable();
            $table->unsignedInteger('city_id')->notNullable();
            $table->unsignedInteger('upazila_id')->notNullable();
            $table->text('address')->notNullable();
            $table->unsignedInteger('zip_code')->nullable();
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_shippings', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('order_shippings');
    }
};
