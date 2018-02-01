<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->increments('id_receita');
            $table->integer('id_categoria')->unsigned();
            $table->integer('id_classificacao')->unsigned();
            $table->softDeletes();

            $table->string('nome_receita');
            $table->string('modo_preparo_receita');

            $table->timestamps();
        });

        Schema::table('receitas', function (Blueprint $table) {
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias');
            $table->foreign('id_classificacao')->references('id_classificacao')->on('classificacaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receitas');
    }
}
