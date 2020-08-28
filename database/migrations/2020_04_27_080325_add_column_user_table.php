<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropUnique('users_email_unique');	
            $table->dropColumn(['email','email_verified_at']);
            $table->string('name', 50)->change();
            $table->string('last_name', 50)->after('name');
            $table->renameColumn('name', 'first_name');
            $table->unsignedBigInteger('branch_id')->after('password');
            $table->enum('access', ['Administrator', 'Manager', 'Staff'])->after('branch_id');
            $table->integer('auth_code')->after('access');
            $table->string('username', 50)->unique()->after('last_name');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
