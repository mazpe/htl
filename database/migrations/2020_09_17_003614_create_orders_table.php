<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('vehicle_id');
            $table->unsignedInteger('key_id');
            $table->unsignedInteger('technician_id');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();
            // Enable Soft Deletes (optional)
            $table->softDeletes();
            // Foreign Keys Constrains
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('key_id')->references('id')->on('keys');
            $table->foreign('technician_id')->references('id')->on('technicians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
