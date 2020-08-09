<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('transaction_type', ['renew', 'redeem']); // renew = interest / penalty, redeem = principal
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('pawn_ticket_id');
            $table->string('or_number', 20);
            $table->double('amount', 10, 4)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('payments');
    }
}
