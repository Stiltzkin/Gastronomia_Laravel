<?php

namespace App\Http\Controllers;

use App\UnidadeMedida;

class UnidadeMedidaController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    public function index()
    {
        $unidade = UnidadeMedida::all();
        return response()->json(['data' => $unidade]);
    }
}
