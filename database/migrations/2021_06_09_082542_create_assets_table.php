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
            $table->text('name');
            $table->integer("asset_tag")->unique();
            $table->string("serial_no");
            $table->foreignId("status_id");
            $table->dateTime("purchased_date")->nullable();;
            $table->string("purchased_cost")->nullable();
            $table->foreignId("supplier_id")->nullable()->default(0);
            $table->string("order_no")->nullable();
            $table->string("warranty")->default("0");
            $table->foreignId("location_id");
            $table->string("room")->nullable();
            $table->foreignId("user_id")->nullable();
            $table->date("audit_date")->nullable();
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
        Schema::dropIfExists('assets');
    }
}
