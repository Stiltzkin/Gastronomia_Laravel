<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAulaReceitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aula_receitas', function (Blueprint $table) {
            $table->integer('id_aula')->unsigned();
            $table->integer('id_receita')->unsigned();
        });

        Schema::table('aula_receitas', function (Blueprint $table) {
            $table->foreign('id_aula')->references('id_aula')->on('aulas');
            $table->foreign('id_receita')->references('id_receita')->on('receitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aula_receitas');
    }
}
