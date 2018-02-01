<?php

namespace App\Http\Controllers;

use App\MotivoRetirada;

class MotivoRetiradaController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    public function index()
    {
        $motivo = MotivoRetirada::all();
        return response()->json(['data' => $motivo, 'status' => true]);
    }

    /// TODO: listar os motivos pela id_ingrediente
    public function show($id)
    {
        // $motivo = MotivoRetirada::all();
        $motivo = MotivoRetirada::find($id);

        // $motivoSelected = MotivoRetirada::find($id);
        // $motivoSel = MotivoRetirada::where('id_ingrediente', $motivo['id_ingrediente'])->get();

        return $motivo;
        // if($motivo){
        //     return response()->json(['data' => $motivo, 'status' => true]);
        // } else {
        //     return response()->json(['data' => 'Motivo não existe', 'status' => false]);
        // }
    }
}
