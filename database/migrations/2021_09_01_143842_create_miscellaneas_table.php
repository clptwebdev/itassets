<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiscellaneasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miscellaneas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("serial_no");
            $table->string("name");
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
            $table->string("room");
            $table->foreignId("photo_id")->default("0");;
            $table->text("notes")->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('miscellaneas');
    }
}
