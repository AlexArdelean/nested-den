<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('id')->references('id')
                  ->on('entities')->onDelete('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('id')->references('id')
                  ->on('entities')->onDelete('cascade');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->integer('entity_id')->unsigned();
            $table->foreign('entity_id')->references('id')
                  ->on('entities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('votes');
        Schema::dropIfExists('comments');
    }
}
