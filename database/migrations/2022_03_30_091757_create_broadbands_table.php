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
        Schema::create('broadbands', function(Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->foreignId('location_id');
            $table->foreignId('supplier_id');
            $table->text('package');
            $table->decimal("purchased_cost", 11, 2)->nullable();
            $table->dateTime('purchased_date')->nullable();
            $table->dateTime('renewal_date');
            $table->timestamps();
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
        Schema::dropIfExists('broadbands');
    }

};
