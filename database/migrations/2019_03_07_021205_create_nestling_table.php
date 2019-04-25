<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNestlingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nestlings', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->foreign('id')->references('id')
                  ->on('entities')->onDelete('cascade');

            $table->integer('nest_id');
            $table->foreign('nest_id')->references('id')
                  ->on('nests')->onDelete('cascade');

            $table->string('title');
            $table->string('description');
            $table->increments('priority')->unique()->unsigned();

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
        Schema::dropIfExists('nestling');
    }
}
