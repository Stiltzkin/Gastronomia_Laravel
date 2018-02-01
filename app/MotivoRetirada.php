<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoRetirada extends Model
{
    protected $primaryKey = 'id_motivo_retirada';
    protected $fillable = [
        'motivo_retirada',
        'id_ingrediente',
    ];

    public function ingrediente()
    {
        return $this->belongsTo('App\Ingrediente', 'id_ingrediente');
    }
}
