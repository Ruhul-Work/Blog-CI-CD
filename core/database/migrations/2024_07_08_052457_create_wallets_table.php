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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('transaction_type')->comment('debit','credit');
            $table->integer('amount');
            $table->string('w_type')->comment('deposit','purchase','refund','reword');
            $table->integer('order_id')->nullable();
            $table->integer('return_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('note')->nullable();
            $table->integer('status')->comment('pending','confirmed');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
