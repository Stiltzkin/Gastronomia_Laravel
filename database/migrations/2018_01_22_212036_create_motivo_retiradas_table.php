<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivoRetiradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivo_retiradas', function (Blueprint $table) {
            $table->increments('id_motivo_retirada');
            $table->integer('id_ingrediente')->unsigned();

            $table->string('descricao');
            $table->timestamps();
        });

        Schema::table('motivo_retiradas', function (Blueprint $table) {
            $table->foreign('id_ingrediente')->references('id_ingrediente')->on('ingredientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivo_retiradas');
    }
}
