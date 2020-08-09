<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('transaction_status', ['New', 'Old']);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('item_category_id');
            $table->unsignedBigInteger('branch_id');
            $table->string('inventory_number', 20);
            $table->string('ticket_number', 20);
            $table->date('transaction_date');	
            $table->date('maturity_date')->nullable();	
            $table->date('expiration_date')->nullable();	
            $table->date('auction_date')->nullable();	
            $table->unsignedBigInteger('processed_by');
            $table->softDeletes();
            $table->smallInteger('status')->default(0); // 0 - open , 1 - closed, 2 - cancelled
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
        Schema::dropIfExists('inventories');
    }
}
