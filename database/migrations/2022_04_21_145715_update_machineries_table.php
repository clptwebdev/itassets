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
        Schema::table('machineries', function (Blueprint $table) {
            $table->string('order_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->integer('warranty')->nullable();
            $table->foreignId('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machineries', function (Blueprint $table) {
            $table->dropColumn('order_no');
            $table->dropColumn('serial_no');
            $table->dropColumn('warranty');
            $table->dropColumn('user_id');
        });
    }
};
