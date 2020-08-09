<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('attachment_id');
            $table->string('ticket_number', 20);
            $table->enum('transaction_type', ['pawn', 'renew', 'redeem', 'repawn']);
            $table->date('transaction_date');	
            $table->date('maturity_date')->nullable();
            $table->date('expiration_date')->nullable();	
            $table->date('auction_date')->nullable();	
            $table->double('advance_interest', 10, 4)->nullable();
            $table->double('interest', 10, 4)->nullable();
            $table->double('penalty', 10, 4)->nullable();
            $table->double('interest_text', 10, 4)->nullable();
            $table->double('penalty_text', 10, 4)->nullable();
            $table->double('discount', 10, 4)->nullable();
            $table->double('charges', 10, 4)->nullable();
            $table->integer('attachment_number');
            $table->double('appraised_value', 10, 4)->nullable();
            $table->double('principal', 10, 4)->nullable();
            $table->double('net', 10, 4)->nullable();
            $table->smallInteger('interbranch')->nullable();
            $table->smallInteger('interbranch_renewal')->nullable();
            $table->smallInteger('authorized_representative')->nullable();
            $table->smallInteger('interest_percentage')->default(3);
            $table->smallInteger('penalty_percentage')->default(2);
            $table->smallInteger('is_special_rate')->default(0);
            $table->unsignedBigInteger('processed_by');
            $table->softDeletes();
            $table->smallInteger('status')->default(0); // 0 - open , 1 - redeem, 2 - cancelled
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
        Schema::dropIfExists('tickets');
    }
}
