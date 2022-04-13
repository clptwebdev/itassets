<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function(Blueprint $table) {
            $table->id();
            $table->string("asset_model");
            $table->string("model_type");
            $table->text('name');
            $table->string("asset_tag")->nullable();
            $table->string("serial_no")->nullable();
            $table->dateTime("purchased_date")->nullable();;
            $table->string("purchased_cost")->nullable();
            $table->string("archived_cost")->nullable();
            $table->foreignId("supplier_id")->nullable()->default(0);
            $table->string("order_no")->nullable();
            $table->string("warranty")->default("0");
            $table->foreignId("location_id");
            $table->foreignId("manufacturer_id")->nullable();
            $table->string("room")->nullable();
            $table->foreignId("created_user");
            $table->timestamp("created_on");
            $table->foreignId("user_id");
            $table->foreignId("super_id");
            $table->text('comments')->nullable();
            $table->text('logs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('date');
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
        Schema::dropIfExists('archive');
    }

}
