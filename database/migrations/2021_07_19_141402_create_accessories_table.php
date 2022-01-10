<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("serial_no");
            $table->string("asset_tag")->nullable();
            $table->string("name");
            $table->string("model")->nullable();
            $table->foreignId("status_id")->nullable();
            $table->date("purchased_date")->nullable();
            $table->decimal("purchased_cost", 11, 2)->nullable();
            $table->integer('donated')->default(0);
            $table->foreignId("supplier_id")->nullable()->default(0);
            $table->foreignId("manufacturer_id")->nullable()->default(0);
            $table->string("order_no")->nullable();
            $table->string("warranty")->default("0")->nullable();
            $table->foreignId("location_id");
            $table->foreignId("photo_id")->default("0");
            $table->foreignId('depreciation_id')->nullable();
            $table->string('room')->nullable();
            $table->text("notes")->nullable();
            $table->foreignId('user_id');
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
        Schema::dropIfExists('accessories');
    }

}
