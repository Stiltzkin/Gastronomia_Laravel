<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->increments('id_ingrediente');
            $table->integer('id_unidade_medida')->unsigned();
            $table->softDeletes();

            $table->string('nome_ingrediente');
            $table->float('quantidade_calorica_ingrediente');
            $table->float('aproveitamento_ingrediente');
            $table->float('quantidade_estoque_ingrediente');
            $table->float('quantidade_reservada_ingrediente');
            $table->float('valor_ingrediente');

            $table->timestamps();
        });
        Schema::table('ingredientes', function (Blueprint $table) {
            $table->foreign('id_unidade_medida')->references('id_unidade_medida')->on('unidade_medidas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredientes');
    }
}
