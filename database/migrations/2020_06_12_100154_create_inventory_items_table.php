<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('item_type_id');
            $table->string('item_name', 100); // this is temporary to be changed to id
            $table->double('item_type_weight', 10, 4)->nullable();
            $table->double('item_name_weight', 10, 4)->nullable();
            $table->smallInteger('item_karat')->nullable();
            $table->double('item_karat_weight', 10, 4)->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->smallInteger('status')->default(0); // 1 - auction, 2 - redeem
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
}
