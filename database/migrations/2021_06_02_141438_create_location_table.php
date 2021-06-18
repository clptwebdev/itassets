<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->integer('photo_id')->nullable();
            $table->string('icon', 7);
            $table->string('name');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('county');
            $table->string('postcode', 11);
            $table->string('telephone' , 14)->nullable();           
            $table->string('email')->nullable();           
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
        Schema::dropIfExists('location');
    }
}
