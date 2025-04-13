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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('c_type')->nullable();
            $table->text('type_details')->nullable();
            $table->string('title')->nullable();
            $table->string('code');
            $table->decimal('discount', 8, 2);
            $table->string('discount_type');
            $table->integer('min_buy')->default(0);
            $table->integer('max_discount')->default(0);
            $table->integer('is_valid_first_order')->nullable();
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('user_type');
            $table->integer('stock');
            $table->integer('individual_max_use');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes(); // Adds a deleted_at column for soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
