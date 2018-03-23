<?php

namespace App\Http\Controllers;

use App\Receita;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ReceitaController extends Extend\PaginateController
{
    public function listAll()
    {
        $receita = Receita::all();

        $receitaArray = [];
        for ($i = 0; $i < count($receita); $i++) {
            $receitaFind = Receita::find($receita[$i]['id_receita']);
            $receitaPivot = Receita::find($receita[$i]['id_receita'])->ingredientes;
            $receitaFind['pivot'] = $receitaPivot;
            array_push($receitaArray, $receitaFind);
        }

        return response()->json(['data' => $receitaArray]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $qtd = $request['qtd'];
        $page = $request['page'];
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $receitaAll = Receita::all();
        $receitaArray = [];
        for ($i = 0; $i < count($receitaAll); $i++) {
            $receitaFind = Receita::find($receitaAll[$i]['id_receita']);
            $receitaPivot = Receita::find($receitaAll[$i]['id_receita'])->ingredientes;
            $receitaFind['pivot'] = $receitaPivot;
            array_push($receitaArray, $receitaFind);
        }

        if ($qtd == null && $page == null) {
            $receita = $receitaArray;
        }
        if ($qtd !== null && $page !== null) {
            $receita = $this->paginate($receitaArray, $qtd, $page);
            $receita = $receita->appends(Request::capture()->except('page'));
        }
        if ($qtd == null && $page !== null || $qtd !== null && $page == null) {
            return response()->json(["message" => "Comando inválido."]);
        }
        return response()->json(['data' => $receita]);

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
            $erros = $this->validacoes($dados);

            if (empty($erros)) {

                $receita = Receita::create($dados);

                if ($receita) {
                    $receita->ingredientes()->sync((array) $request->ingredientes);
                    return response()->json(['data' => $receita]);
                } else {
                    return response()->json(['message' => 'Dados inválidos.']);
                }
            } else {
                return response()->json(['data' => $erros]);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.');
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $receita = Receita::find($id);

            $receitaPivot = Receita::find($receita['id_receita'])->ingredientes;
            $receita['pivot'] = $receitaPivot;

            if ($receita) {
                return response()->json(['data' => $receita]);
            } else {
                return response()->json(['message' => 'Receita não encontrado.']);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.');
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }
            $receita = Receita::find($id);
            $dados = $request->all();

            $receita->update($dados);

            $erros = $this->validacoes($dados);

            if ($receita) {
                $receita->ingredientes()->detach();

                for ($i = 0; $i < count($request->ingredientes); $i++) {
                    $receita->ingredientes()->attach($receita['id_receita'],
                        ['id_ingrediente' => $request->ingredientes[$i]['id_ingrediente'],
                            'quantidade_bruta_receita_ingrediente' => $request->ingredientes[$i]['quantidade_bruta_receita_ingrediente']]);
                }
            } else {
                return response()->json(['message' => 'Receita não encontrado.']);
            }
            return response()->json(['data' => $dados]);
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.');
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
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $receita = Receita::find($id);

            if ($receita) {
                $receita->delete();
                return response()->json(['data' => $receita->nome_receita . ' deletado com sucesso.', 'status' => true]);
            } else {
                return response()->json(['message' => 'Receita não encontrado.', 'status' => false]);
            }
        } catch (\Exception $e) {
            return response()->json('Erro no servidor.');
        }
    }

    public function validacoes($dados)
    {
        $erros = [];

        if (strlen($dados['nome_receita']) == 0 || strlen($dados['nome_receita']) == null) {
            array_push($erros, "Insira o nome da receita.");
        }
        if (strlen($dados['nome_receita']) > 60) {
            array_push($erros, "Nome da receita deve ter no máximo 60 digitos.");
        }

        return $erros;
    }

    public function imageUpload($request, $dados)
    {
        $data['image'] = $dados->image;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($dados->image) {
                $name = $dados->image;
            } else {
                $name = $dados->id_receita . kebab_case($dados->nome_receita);
            }

            $entension = $request->image->extension();
            $fileName = "{$name}.{$extension}";

            $data['image'] = $fileName;
            $upload = $request->image->storeAs('dados', $fileName);
        }
    }
}
