<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('item_type_id');
            $table->integer('karat')->nullable();
            $table->double('gram', 8, 4)->nullable();
            $table->double('regular_rate', 10, 4)->nullable();
            $table->double('special_rate', 10, 4)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'item_type_id', 'karat']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
