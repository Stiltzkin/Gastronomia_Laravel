<?php

namespace App\Http\Controllers;

use App\UnidadeMedida;

class UnidadeMedidaController extends Controller
{
    public function index()
    {
        $unidade = UnidadeMedida::all();
        return response()->json(['data' => $unidade]);
    }
}
