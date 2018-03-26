<?php

namespace App\Http\Controllers;

use App\Aula;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AulaController extends Extend\PaginateController
{
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
                return response()->json(["message" => "Comando inválido."],400);
            }
            return response()->json(['data' => $aula]);
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
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
            $dados = $request->all();

            $aula = Aula::create($dados);

            if ($aula) {
                $aula->receitas()->sync((array) $request->receitas);
                return response()->json(['message' => $aula->nome_aula . " criado com sucesso!"]);
            } else {
                return response()->json(['message' => 'Dados inválidos.'],400);
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
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }

            $aula = Aula::find($id);

            if ($aula) {
                return response()->json(['data' => $aula]);
            } else {
                return response()->json(['message' => 'Aula não existe.'],404);
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
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }

            $dados = $request->all();
            $aula = Aula::find($id);

            if ($aula) {
                $aula['aula_agendada'] = false;
                $aula['aula_concluida'] = false;

                $aula->update($dados);
                $aula->receitas()->detach();

                for ($i = 0; $i < count($request->receitas); $i++) {
                    $aula->receitas()->attach($aula['id_aula'],
                        ['id_receita' => $request->receitas[$i]['id_receita'],
                            'quantidade_receita' => $request->receitas[$i]['quantidade_receita']]);
                }

                return response()->json(['message' => $aula->nome_aula . " editado com sucesso!"]);
            } else {
                return response()->json(['message' => 'Aula não encontrada.'],404);
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
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }

            $aula = Aula::find($id);

            if (app('App\Http\Controllers\Calculos\DesagendarAulaController')->desagendarAula($id)) {
                $aula->receitas()->detach();
                if ($aula) {
                    $aula->delete();
                    return response()->json(['message' => 'Aula deletada com sucesso.']);
                } else {
                    return response()->json(['message' => 'Aula não encontrada.'],404);
                }
            }
       
    }

    public function clonarAula($id)
    {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'],400);
            }
            $aula = Aula::find($id);
            $aula->load('receitas');

            # renomeia a aula
            $nomeAula = $aula->nome_aula;
            $aula['nome_aula'] = $nomeAula . " CLONE";

            $new_aula = $aula->replicate();
            $new_aula->push();

            for ($i = 0; $i < count($aula->receitas); $i++) {
                $new_aula->receitas()->attach($new_aula['id_aula'],
                    ['id_receita' => $aula->receitas[$i]->pivot['id_receita'],
                        'quantidade_receita' => $aula->receitas[$i]->pivot['quantidade_receita']]);
            }

            return response()->json(['message' => $new_aula->nome_aula . " clonado com sucesso!"]);
        
    }
}
