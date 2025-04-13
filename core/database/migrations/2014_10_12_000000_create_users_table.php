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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id'); // Using big integer for id
            $table->string('name');
            $table->string('email',99)->nullable()->unique();
            $table->string('username',30)->nullable()->unique();
            $table->string('phone',20)->unique();
            $table->string('alternate_phone')->nullable();
            $table->enum('user_type', ['customer', 'shop', 'admin', 'guest'])->default('customer');
            $table->decimal('wallet_balance', 10, 2)->default(0.00);
            $table->integer('points')->default(0);
            $table->string('user_role')->default(0);
            $table->string('user_permission')->nullable();
            $table->string('image')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('upazilla')->nullable();
            $table->string('address')->nullable();
            $table->integer('is_admin')->default(0);
            $table->integer('status')->default(0);
            $table->string('password')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('user_token')->nullable();
            $table->enum('gender', ['male', 'female', 'others'])->nullable();
            $table->string('cover_image')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('last_otp_send')->nullable();
            $table->softDeletes(); // Adding soft delete functionality
            $table->timestamps();
            $table->rememberToken();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
