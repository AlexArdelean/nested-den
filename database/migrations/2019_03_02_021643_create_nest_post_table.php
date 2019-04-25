<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNestPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nest_post', function (Blueprint $table) {
            $table->increments('id')->unsigned();
                  
            $table->integer('post_id')->unsigned();
            $table->foreign('post_id')->references('id')
                  ->on('posts')->onDelete('cascade');

            $table->integer('nest_id')->unsigned();
            $table->foreign('nest_id')->references('id')
                  ->on('nests')->onDelete('cascade');

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
        Schema::dropIfExists('nest_post');
    }
}
