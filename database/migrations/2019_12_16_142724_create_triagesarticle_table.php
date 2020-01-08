<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriagesarticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triagesarticle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('article');
            $table->bigInteger('categorie_id')->unsigned();
            $table->foreign('categorie_id')->references('id')->on('categorie');
            $table->bigInteger('sscategorie_id')->unsigned();
            $table->foreign('sscategorie_id')->references('id')->on('sscategorie');

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
        Schema::dropIfExists('triagesarticle');
    }
}
