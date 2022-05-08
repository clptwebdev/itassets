<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFFESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_f_e_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("serial_no")->nullable();
            $table->foreignId("status_id")->nullable();
            $table->date("purchased_date")->nullable();
            $table->decimal("purchased_cost",11,2)->nullable();
            $table->integer('donated')->default(0);
            $table->foreignId("supplier_id")->nullable()->default(0);
            $table->foreignId("manufacturer_id")->nullable()->default(0);
            $table->string("order_no")->nullable();
            $table->string("warranty")->default("0")->nullable();
            $table->foreignId("depreciation_id");
            $table->foreignId("location_id");
<<<<<<< HEAD
            $table->foreignId("user_id");
            $table->string("room");
=======
            $table->string("room")->nullable();
            $table->foreignId("photo_id")->default("0");;
>>>>>>> 4659c60b629b7b177aea2e9c3f39ec3ef3cdf417
            $table->text("notes")->nullable();
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
        Schema::dropIfExists('f_f_e_s');
    }
}
