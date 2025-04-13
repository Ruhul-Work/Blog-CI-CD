<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // Added DB facade
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->tinyInteger('status')->unsigned()->comment('Status of the order');
            $table->integer('created_by')->nullable()->comment('Auth id');
            $table->timestamps();
        });

        // Insert initial data
        DB::table('order_statuses')->insert([
            ['id' => 1, 'name' => 'Pending', 'status' => 1],
            ['id' => 2, 'name' => 'Confirmed', 'status' => 1],
            ['id' => 3, 'name' => 'Packaging', 'status' => 1],
            ['id' => 4, 'name' => 'On the way', 'status' => 1],
            ['id' => 5, 'name' => 'Cancelled', 'status' => 1],
            ['id' => 6, 'name' => 'Return', 'status' => 1],
            ['id' => 7, 'name' => 'Completed', 'status' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
