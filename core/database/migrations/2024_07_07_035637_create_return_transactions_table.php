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
        Schema::create('return_transactions', function (Blueprint $table) {
            $table->id();
            $table->text('return_number')->notNullable();
            $table->integer('return_id')->notNullable();
            $table->integer('user_id')->nullable();
            $table->integer('method_id')->notNullable();
            $table->string('method_name')->nullable()->comment('bkash,nagad,rocket,wallet etc');
            $table->text('transaction_id')->nullable();
            $table->float('amount')->notNullable();
            $table->tinyInteger('status')->default(0)->comment('0: pending, 1: verified');
            $table->string('verify_by', 256)->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('is_posted')->nullable()->comment('for accounts transaction if need');
            $table->integer('created_by')->nullable()->comment('auth id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_transactions');
    }
};
