<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration')->unique();
            $table->decimal("purchased_cost", 11, 2)->nullable();
            $table->integer("depreciation");
            $table->dateTime("purchased_date");
            $table->foreignId('supplier_id');
            $table->foreignId('location_id');
            $table->integer('donated')->nullable();
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
        Schema::dropIfExists('vehicles');
    }

}
