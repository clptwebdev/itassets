<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("asset_model");
            $table->integer("asset_tag")->unique();
            $table->integer("serial_no");
            $table->foreignId("status_id");
            $table->date("purchased_date");
            $table->string("purchased_cost");
            $table->foreignId("supplier_id");
            $table->foreignId("manufacturer_id");
            $table->string("order_no");
            $table->string("warranty")->default("0");
            $table->foreignId("location_id");
            $table->foreignId("user_id");
            $table->date("audit_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
