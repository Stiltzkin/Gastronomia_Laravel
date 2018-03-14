<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receita extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_receita';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nome_receita',
        'modo_preparo_receita',
        'id_categoria',
        'id_classificacao',
    ];

    public function ingredientes()
    {
        return $this->belongsToMany('App\Ingrediente', 'receita_ingredientes', 'id_receita', 'id_ingrediente')->withPivot('quantidade_bruta_receita_ingrediente');
    }

    public function classificacao()
    {
        return $this->belongsTo('App\Classificacao', 'id_classificacao');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categoria', 'id_categoria');
    }

    public function aulas()
    {
        return $this->belongsToMany('App\Aula', 'aula_receitas', 'id_receita', 'id_aula')->withPivot('quantidade_receita');
    }
}
