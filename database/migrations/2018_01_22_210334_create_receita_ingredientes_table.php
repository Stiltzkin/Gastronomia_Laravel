<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitaIngredientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receita_ingredientes', function (Blueprint $table) {
            $table->integer('id_ingrediente')->unsigned();
            $table->integer('id_receita')->unsigned();

            $table->float('quantidade_bruta_receita_ingrediente');
            $table->float('custo_bruto_receita_ingrediente');
        });

        Schema::table('receita_ingredientes', function (Blueprint $table) {
            $table->foreign('id_ingrediente')->references('id_ingrediente')->on('ingredientes');
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
        Schema::dropIfExists('receita_ingredientes');
    }
}
