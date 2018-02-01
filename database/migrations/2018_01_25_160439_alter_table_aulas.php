<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAulas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aulas', function (Blueprint $table) {
            $table->integer('id_periodo_aula')->unsigned();
            $table->foreign('id_periodo_aula')->references('id_periodo_aula')->on('periodos');
            $table->dropColumn('periodo_aula');
            $table->string('nome_aula')->nullable()->change();
            $table->string('descricao_aula')->nullable()->change();
            $table->date('data_aula')->nullable()->change();
        });

        Schema::table('aula_receitas', function (Blueprint $table) {
            $table->integer('quantidade_receita');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aulas', function (Blueprint $table) {
            $table->string('periodo_aula');
            $table->dropForeign(['id_periodo_aula']);
            $table->dropColumn('id_periodo_aula');
            $table->string('nome_aula')->nullable(false)->change();
            $table->string('descricao_aula')->nullable(false)->change();
            $table->date('data_aula')->nullable(false)->change();
        });

        Schema::table('aula_receitas', function (Blueprint $table) {
            $table->dropColumn('quantidade_receita');
        });

    }
}
