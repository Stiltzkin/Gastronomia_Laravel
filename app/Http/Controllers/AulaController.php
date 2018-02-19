<?php

namespace App\Http\Controllers;

use App\Aula;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AulaController extends Extend\PaginateController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $qtd = $request['qtd'];
            $page = $request['page'];
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $aulaAll = Aula::all();
            $aulaArray = [];
            for ($i = 0; $i < count($aulaAll); $i++) {
                $aulaFind = Aula::find($aulaAll[$i]['id_aula']);
                $aulareceita = Aula::find($aulaAll[$i]['id_aula'])->receitas;
                $aulaFind['receita'] = $aulareceita;
                array_push($aulaArray, $aulaFind);
            }

            if ($qtd == null && $page == null) {
                $aula = $aulaArray;
            }
            if ($qtd !== null && $page !== null) {
                $aula = $this->paginate($aulaArray, $qtd, $page);
                $aula = $aula->appends(Request::capture()->except('page'));
            }
            if ($qtd == null && $page !== null || $qtd !== null && $page == null) {
                return response()->json(["message" => "Comando inválido."], 400);
            }
            return response()->json(['data' => $aula], 200);
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dados = $request->all();

            $aula = Aula::create($dados);

            if ($aula) {
                $aula->receitas()->sync((array) $request->receitas);
                return response()->json(['data' => $aula], 201);
            } else {
                return response()->json(['message' => 'Dados inválidos.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
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
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $aula = Aula::find($id);

            if ($aula) {
                return response()->json(['data' => $aula], 200);
            } else {
                return response()->json(['message' => 'Aula não existe.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
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
        try{
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }
            
            $dados = $request->all();
            $aula = Aula::find($id);

            if ($aula) {
                $aula['aula_agendada'] = false;
                $aula['aula_concluida'] = false;

                $aula->update($dados);
                $aula->receitas()->detach();

                for($i=0; $i<count($request->receitas); $i++){
                    $aula->receitas()->attach($aula['id_aula'],
                    ['id_receita' => $request->receitas[$i]['id_receita'],
                    'quantidade_receita' => $request->receitas[$i]['quantidade_receita']]);
                }

                return response()->json(['data' => $dados]);
            } else {
                return response()->json(['message' => 'Aula não encontrada.'], 404);
            }
        }  catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
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
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $aula = Aula::find($id);

            if ($aula) {
                $aula->delete();
                return response()->json(['message' => 'Aula deletada com sucesso.'], 200);
            } else {
                return response()->json(['message' => 'Aula não encontrada.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    public function clonarAula($id)
    {
        try{
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }
            $aula = Aula::find($id);
            $aula->load('receitas');

            # renomeia a aula
            $nomeAula = $aula->nome_aula;
            $aula['nome_aula'] = $nomeAula . " CLONE";

            $new_aula = $aula->replicate();
            $new_aula->push();

            for($i=0; $i<count($aula->receitas); $i++){
                $new_aula->receitas()->attach($new_aula['id_aula'],
                ['id_receita' => $aula->receitas[$i]->pivot['id_receita'],
                'quantidade_receita' => $aula->receitas[$i]->pivot['quantidade_receita']]);
            }
            
            return response()->json(['data' => $new_aula], 200);
            } catch (\Exception $e) {
                return response()->json('Ocorreu um erro no servidor.', 500);
            }        
    }
}
