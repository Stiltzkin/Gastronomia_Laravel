<?php

namespace App\Http\Controllers;

use App\Aula;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aula = Aula::all();
        return response()->json(['data' => $aula, 'status' => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all();

        $aula = Aula::create($dados);

        if ($aula) {
            $aula->receitas()->sync((array) $request->receitas);
            return response()->json(['data' => $dados, 'status' => true]);
        } else {
            return response()->json(['data' => 'Não foi possível criar a aula.', 'status' => false]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aula = Aula::find($id);

        if ($aula) {
            return response()->json(['data' => $aula, 'status' => true]);
        } else {
            return response()->json(['data' => 'Aula não existe.', 'status' => false]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dados = $request->all();
        $aula = Aula::find($id);

        if ($aula) {
            $aula->update($dados);
            $aula->receitas()->sync((array) $request->receitas);
            return response()->json(['data' => 'Aula atualizada com sucesso.', 'status' => true]);
        } else {
            return response()->json(['data' => 'Não foi possível atualizar a aula.', 'status' => false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aula = Aula::find($id);

        if ($aula) {
            $aula->delete();
            return response()->json(['data' => 'Aula deletada com sucesso.', 'status' => true]);
        } else {
            return response()->json(['data' => 'Não foi possível atualizar a aula.', 'status' => false]);
        }
    }
}
