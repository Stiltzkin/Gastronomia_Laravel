<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableReceitaIngredientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receita_ingredientes', function (Blueprint $table) {
            $table->dropColumn('custo_bruto_receita_ingrediente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('receita_ingredientes', function (Blueprint $table) {
            $table->float('custo_bruto_receita_ingrediente');
        });

    }
}
