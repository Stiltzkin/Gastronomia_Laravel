<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadeMedida extends Model
{
    protected $primaryKey = 'id_unidade_medida';

    public function ingredientes()
    {
        return $this->hasMany('App\Ingrediente', 'id_ingrediente');
    }
}
