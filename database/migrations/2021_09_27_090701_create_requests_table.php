<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function(Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('model_type')->nullable();
            $table->foreignId('model_id')->nullable();
            $table->foreignId('location_to')->nullable();
            $table->foreignId('location_from')->nullable();
            $table->foreignId("user_id");
            $table->foreignId("super_id")->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('requests');
    }

}
