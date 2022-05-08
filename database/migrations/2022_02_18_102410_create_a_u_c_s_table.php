<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAUCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_u_c_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('type');
            $table->decimal("purchased_cost",11,2)->nullable();
            $table->integer("depreciation");
            $table->dateTime("purchased_date");
            $table->foreignId('location_id');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('a_u_c_s');
    }
}
