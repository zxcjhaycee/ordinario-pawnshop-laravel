<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_flow_id')->constrained();
            $table->string('denomination', 20);
            $table->integer('count');
            $table->decimal('subtotal', 8, 2);
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
        Schema::dropIfExists('cash_flows_details');
    }
}
