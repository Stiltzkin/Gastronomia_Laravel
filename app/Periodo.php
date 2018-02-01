<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $primaryKey = 'id_periodo_aula';

    public function aulas()
    {
        return $this->hasMany('App\Aula', 'id_aula');
    }
}
