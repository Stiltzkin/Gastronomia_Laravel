<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aula extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_aula';
    protected $dates = ['deleted_at', 'data_aula'];

    // protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'data_aula',
        'descricao_aula',
        'aula_agendada',
        'aula_concluida',
        'id_periodo_aula',
        'nome_aula',
    ];

    protected $attributes = [
        'aula_agendada' => 0,
        'aula_concluida' => 0,
    ];

    public function receitas()
    {
        return $this->belongsToMany('App\Receita', 'aula_receitas', 'id_aula', 'id_receita')->withPivot('quantidade_receita');
    }

    public function periodo()
    {
        return $this->belongsTo('App\Periodo', 'id_periodo_aula');
    }

}
