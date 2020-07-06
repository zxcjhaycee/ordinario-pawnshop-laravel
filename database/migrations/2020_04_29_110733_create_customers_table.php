<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('suffix', 10);
            $table->date('birthdate');
            $table->enum('sex', ['male', 'female']);
            $table->enum('civil_status', ['single', 'married','seperated', 'divorced', 'widowed']);
            $table->string('email', 100);
            $table->string('contact_number', 15);
            $table->string('alternate_number', 15);
            $table->string('present_address', 150);
            $table->string('present_address_two', 150);
            $table->string('present_area', 100);
            $table->string('present_city', 50);
            $table->smallInteger('present_zip_code');
            $table->string('permanent_address', 150);
            $table->string('permanent_address_two', 150);
            $table->string('permanent_area', 100);
            $table->string('permanent_city', 50);
            $table->smallInteger('permanent_zip_code');
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
        Schema::dropIfExists('customers');
    }
}
