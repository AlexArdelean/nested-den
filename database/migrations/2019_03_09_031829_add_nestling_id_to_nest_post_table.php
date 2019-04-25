<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNestlingIdToNestPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nest_post', function (Blueprint $table) {
            $table->integer('nestling_id')->unsigned()->nullable();
            $table->foreign('nestling_id')->references('id')
                  ->on('nestlings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nest_post', function (Blueprint $table) {
            //
        });
    }
}
