<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableMotivoRetirada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motivo_retiradas', function (Blueprint $table) {
            $table->dropColumn('descricao');
            $table->string('motivo_retirada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motivo_retiradas', function (Blueprint $table) {
            $table->dropColumn('motivo_retirada');
            $table->string('descricao');
        });
    }
}
