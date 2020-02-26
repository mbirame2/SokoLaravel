<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('categorie_id')->unsigned();
            $table->foreign('categorie_id')->references('id')->on('categorie');
          
            $table->text('Titre');
            $table->text('Nom');
            $table->integer('Prix');
            $table->text('Description');
            $table->text('Couleur');
            $table->text('Condition');
            $table->text('Disponible');
            $table->text('ImageName')->nullable();
            $table->text('ImageName1')->nullable();
            $table->text('ImageName2')->nullable();
            $table->text('ImageName3')->nullable();
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
        Schema::dropIfExists('article');
    }
}
