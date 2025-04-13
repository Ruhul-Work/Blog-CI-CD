<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('type', 100)->nullable();
            $table->text('account_name')->nullable();
            $table->text('bank_name')->nullable();
            $table->text('bank_branch')->nullable();
            $table->text('account_number')->nullable();
            $table->text('payment_process')->nullable();
            $table->text('icon')->nullable();
            $table->integer('status')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Insert initial data
        DB::table('payment_methods')->insert([
            [
                'id'=>1,
                'name' => 'Credit Card',
                'type' => 'Bank',
                'account_name' => 'Bank Account',
                'bank_name' => 'XYZ Bank',
                'bank_branch' => 'Main Branch',
                'account_number' => 123456789,
                'payment_process' => 'Processed within 3 days',
                'status' => 1,
            ],
            [
                'id'=>2,
                'name' => 'BKash',
                'type' => 'MFS',
                'account_name' => '',
                'bank_name' => null,
                'bank_branch' => null,
                'account_number' => '',
                'payment_process' => '',
                'status' => 1,
            ],
            [
                'id'=>3,
                'name' => 'Nagad',
                'type' => 'MFS',
                'account_name' => '',
                'bank_name' => null,
                'bank_branch' => null,
                'account_number' => '',
                'payment_process' => '',
                'status' => 1,
            ],
            [
                'id'=>4,
                'name' => 'Rocket',
                'type' => 'MFS',
                'account_name' => '',
                'bank_name' => null,
                'bank_branch' => null,
                'account_number' => '',
                'payment_process' => '',
                'status' => 1,
            ],
            [
                'id'=>5,
                'name' => 'Wallet',
                'type' => 'MFS',
                'account_name' => '',
                'bank_name' => null,
                'bank_branch' => null,
                'account_number' => '',
                'payment_process' => '',
                'status' => 1,
            ],
            [
                'id'=>6,
                'name' => 'Cash On Delivery',
                'type' => 'Cash',
                'account_name' => '',
                'bank_name' => null,
                'bank_branch' => null,
                'account_number' => '',
                'payment_process' => '',
                'status' => 1,
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};


