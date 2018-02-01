<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_ingrediente';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nome_ingrediente',
        'id_unidade_medida',
        'quantidade_calorica_ingrediente',
        'aproveitamento_ingrediente',
        'quantidade_estoque_ingrediente',
        'quantidade_reservada_ingrediente',
        'valor_ingrediente',
    ];
    protected $attributes = [
        'quantidade_reservada_ingrediente' => 0,
        'valor_ingrediente' => 0,
    ];

    public function unidade_medida()
    {
        return $this->belongsTo('App\UnidadeMedida', 'id_unidade_medida');
    }
    public function receitas()
    {
        return $this->belongsToMany('App\Receita', 'receita_ingredientes', 'id_ingrediente', 'id_receita')->withPivot('quantidade_bruta_receita_ingrediente', 'custo_bruto_receita_ingrediente');
    }
    public function motivo_retiradas()
    {
        return $this->hasMany('App\MotivoRetirada', 'id_motivo_retirada');
    }
}
