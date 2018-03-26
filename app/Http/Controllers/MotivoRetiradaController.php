<?php

namespace App\Http\Controllers;

use App\MotivoRetirada;

class MotivoRetiradaController extends Controller
{
    public function index()
    {
        $motivo = MotivoRetirada::all();
        return response()->json(['data' => $motivo, 'status' => true]);
    }

    /// TODO: listar os motivos pela id_ingrediente
    public function show($id)
    {
        $motivo = MotivoRetirada::find($id);
        return $motivo;
    }
}
