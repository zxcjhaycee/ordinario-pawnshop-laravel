<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePawnTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pawn_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pawn_id'); // pawn and repawn
            $table->unsignedBigInteger('ticket_id')->nullable(); // renew / redeem
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
        Schema::dropIfExists('pawn_tickets');
    }
}
