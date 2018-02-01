<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_categoria';
    protected $dates = ['deleted_at'];

    protected $fillable = ['descricao_categoria'];

    public function receitas()
    {
        $this->hasMany('App\Receita', 'id_receita');
    }
}
