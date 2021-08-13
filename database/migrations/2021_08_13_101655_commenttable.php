<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Commenttable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId("comment_id")->references("id")->on("comments")->onDelete("cascade");;
            $table->integer('commentables_id');
            $table->string('commentables_type');

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commentables');
    }
}
