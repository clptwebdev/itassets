<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function(Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->foreignId('supplier_id');
            $table->foreignId('location_id');
            $table->text('contact')->nullable();
            $table->dateTime('expiry')->nullable();
            $table->decimal("purchased_cost", 11, 2)->nullable()->default(0);
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
        Schema::dropIfExists('licenses');
    }

};
