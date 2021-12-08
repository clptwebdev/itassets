<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //columns and column modifiers
            $table->integer('role_id')->unsigned()->default(0);
            $table->integer('photo_id')->unsigned()->nullable()->default(0);
            $table->integer('location_id')->unsigned()->nullable()->default(0);
            $table->string('telephone', 14)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
