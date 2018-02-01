<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->increments('id_aula');
            $table->softDeletes();

            $table->date('data_aula');
            $table->string('descricao_aula');
            $table->boolean('aula_agendada');
            $table->boolean('aula_concluida');
            $table->string('periodo_aula');
            $table->string('nome_aula');

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
        Schema::dropIfExists('aulas');
    }
}
