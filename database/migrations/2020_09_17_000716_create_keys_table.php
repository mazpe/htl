<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vehicle_id');
            $table->string('item_name')->unique();
            $table->text('description')->nullable();
            // Price can range up to $99,999.99
            $table->decimal('price',7,2)->default(0.00);
            $table->boolean('active')->default(1);
            $table->timestamps();
            // Enable Soft Deletes (optional)
            $table->softDeletes();
            // Foreign Key Constrains
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keys');
    }
}
