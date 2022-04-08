<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('software', function (Blueprint $table) {
            //
            $table->foreignId('manufacturer_id')->nullable();
            $table->integer('warranty')->nullable();
            $table->integer('donated')->default(0);
            $table->string('order_no')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('software', function (Blueprint $table) {
            $table->dropColumn('manufacturer_id');
            $table->dropColumn('warranty');
            $table->dropColumn('donated');
            $table->dropColumn('order_no');
        });
    }
};
