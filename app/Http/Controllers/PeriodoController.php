<?php

namespace App\Http\Controllers;

use App\Periodo;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodo = Periodo::all();
        return response()->json(['data' => $periodo], 200);
    }
}
