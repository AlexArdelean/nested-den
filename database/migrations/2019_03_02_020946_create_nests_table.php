<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nests', function (Blueprint $table) {
            $table->integer('id')->unique()->unsigned();
            $table->foreign('id')->references('id')
                  ->on('entities')->onDelete('cascade');

            $table->string('slug')->unique();
            $table->string('name');
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
        Schema::dropIfExists('nestlings');
        Schema::dropIfExists('nests');
    }
}
