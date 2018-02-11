<?php

namespace App\Http\Controllers\Calculos;

use App\Http\Controllers\Controller;
use App\Ingrediente;
use App\MotivoRetirada;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    public function soma(Request $request, $id)
    {
        try {
            if ($id < 0) {
                return response()->json(['data' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $dados = $request->only(['quantidade_estoque_ingrediente', 'valor_ingrediente']);
            $ingrediente = Ingrediente::find($id);

            $erros = $this->validaSoma($dados);

            if ($ingrediente) {
                if (empty($erros)) {
                    $estoqueBd = $ingrediente['quantidade_estoque_ingrediente'];
                    $estoqueView = $dados['quantidade_estoque_ingrediente'];
                    $estoqueTotal = $estoqueBd + $estoqueView;

                    $dados['quantidade_estoque_ingrediente'] = $estoqueTotal;

                    $ingrediente->update($dados);
                    return response()->json(['data' => $ingrediente], 204);
                } else {
                    return response()->json(['data' => $erros], 400);
                }
            } else {
                return response()->json(['message' => 'Ingrediente não existe.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    public function subtrai(Request $request, $id)
    {
        try {
            if ($id < 0) {
                return response()->json(['data' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $dados = $request->only(['quantidade_estoque_ingrediente']);
            $dadosMotivo = $request->only(['motivo_retirada']);
            $ingrediente = Ingrediente::find($id);

            if ($ingrediente) {
                if ($dados) {
                    $estoqueBd = $ingrediente['quantidade_estoque_ingrediente'];
                    $estoqueView = $dados['quantidade_estoque_ingrediente'];
                    $estoqueTotal = $estoqueBd - $estoqueView;

                    $dados['quantidade_estoque_ingrediente'] = $estoqueTotal;
                } else {
                    return response()->json(['message' => "Dados inválidos."], 400);
                }
            } else {
                return response()->json(['message' => "Ingrediente não existe."], 404);
            }

            # validacao
            $erros = $this->validaSubtrai($dados, $ingrediente, $dadosMotivo);

            if (empty($erros)) {

                // insere id_ingrediente no JSON para ser salvo na tabela motivo_retiradas
                $dadosMotivo['id_ingrediente'] = $ingrediente['id_ingrediente'];

                // Atualiza o estoque do ingrediente e insere o motivo da retirada
                $ingrediente->update($dados);
                MotivoRetirada::create($dadosMotivo);

                return response()->json(['data' => $dados, 'motivo_retirada' => $dadosMotivo], 204);
            } else {
                return response()->json(['data' => $erros], 400);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }
    }

    public function validaSoma($dados)
    {
        $erros = [];
        if ($dados['quantidade_estoque_ingrediente'] == 0 || $dados['quantidade_estoque_ingrediente'] == null) {
            array_push($erros, "Insira a quantidade a ser somado.");
        }
        if (empty($dados['valor_ingrediente'])) {
            array_push($erros, "Insira o preço do ingrediente.");
        }
        if ($dados['quantidade_estoque_ingrediente'] < 0) {
            array_push($erros, "Não é possivel acrescentar valor negativo.");
        }
        if ($dados['valor_ingrediente'] < 0) {
            array_push($erros, "Valor do ingrediente não pode ser negativo.");
        }

        return $erros;
    }

    public function validaSubtrai($dados, $ingrediente, $dadosMotivo)
    {
        $erros = [];
        if ($dados['quantidade_estoque_ingrediente'] == 0 || $dados['quantidade_estoque_ingrediente'] == null) {
            array_push($erros, "Insira a quantidade a ser somado.");
        }
        if ($dados['quantidade_estoque_ingrediente'] > $ingrediente['quantidade_estoque_ingrediente']) {
            array_push($erros, "Estoque não pode ficar negativo.");
        }
        if ($dados['quantidade_estoque_ingrediente'] < 0) {
            array_push($erros, "Não é possivel subtrair valor negativo.");
        }
        if (strlen($dadosMotivo['motivo_retirada']) == 0 || strlen($dadosMotivo['motivo_retirada']) == null) {
            array_push($erros, "Insira o motivo de subtrair o estoque.");
        }
        if ($ingrediente['quantidade_reservada_ingrediente'] > $dados['quantidade_estoque_ingrediente']) {
            array_push($erros, "Existem ingredientes ja reservados desta quantidade.");
        }
        return $erros;
    }
}
