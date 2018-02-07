<?php

namespace App\Http\Controllers;

use App\Aula;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AulaController extends Controller
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
            $aula = Aula::paginate($qtd);
            $aula = $aula->appends(Request::capture()->except('page'));
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
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $dados = $request->all();
            $aula = Aula::find($id);

            if ($aula) {
                $aula['aula_agendada'] = false;
                $aula['aula_concluida'] = false;

                $aula->update($dados);
                $aula->receitas()->sync((array) $request->receitas);
                return response()->json(['message' => 'Aula atualizada com sucesso.'], 204);
            } else {
                return response()->json(['message' => 'Aula não encontrada.'], 404);
            }
        } catch (\Exception $e) {
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
}
