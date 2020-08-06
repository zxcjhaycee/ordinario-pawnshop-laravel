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
            $table->enum('transaction_type', ['pawn', 'renew', 'redeem', 'auction']);
            $table->enum('transaction_status', ['New', 'Old']);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('item_category_id');
            $table->unsignedBigInteger('branch_id');
            $table->string('inventory_number', 20);
            $table->string('ticket_number', 20);
            $table->date('transaction_date');	
            $table->date('maturity_date')->nullable();;	
            $table->date('expiration_date')->nullable();;	
            $table->date('auction_date')->nullable();;	
            $table->double('interest', 10, 4)->nullable();
            $table->double('penalty', 10, 4)->nullable();
            $table->smallInteger('interest_percentage')->default(3);
            $table->smallInteger('penalty_percentage')->default(2);
            $table->double('discount', 10, 4)->nullable();
            $table->double('charges', 10, 4)->nullable();
            $table->double('appraised_value', 10, 4)->nullable();
            $table->double('principal', 10, 4)->nullable();
            $table->double('net', 10, 4)->nullable();
            $table->smallInteger('is_special_rate');
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
