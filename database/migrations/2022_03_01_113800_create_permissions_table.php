<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function(Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('model');
            $table->integer('create')->default(0);
            $table->integer('update')->default(0);
            $table->integer('view')->default(0);
            $table->integer('delete')->default(0);
            $table->integer('archive')->default(0);
            $table->integer('transfer')->default(0);
            $table->integer('request')->default(0);
            $table->integer('spec_reports')->default(0);
            $table->integer('fin_reports')->default(0);
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
        Schema::dropIfExists('permissions');
    }

}
