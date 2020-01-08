<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triagearticles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('sscategorie_id')->unsigned();
            $table->foreign('sscategorie_id')->references('id')->on('sscategorie');
            $table->bigInteger('categorie_id')->unsigned();
            $table->foreign('categorie_id')->references('id')->on('categorie');
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
        Schema::dropIfExists('triagearticles');
    }
}
