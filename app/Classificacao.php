<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classificacao extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_classificacao';
    protected $dates = ['deleted_at'];

    protected $fillable = ['descricao_classificacao'];

    public function receitas()
    {
        $this->hasMany('App\Receita', 'id_receitas');
    }
}
