<?php

namespace App\Http\Controllers\Calculos;

use App\Aula;
use Illuminate\Http\Request;

class ConcluirAulaController extends CalculosAulaController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    public function concluirAula(Request $request, $id)
    {
        $dados = $request->only(['aula_agendada', 'aula_concluida']);
        $aula = Aula::find($id);

        if ($aula) {
            if ($aula['aula_concluida'] == true) {
                return response()->json(['data' => "Aula já está concluida.", 'status' => false]);
            }
        } else {
            return response()->json(['data' => "Aula não existe.", 'status' => false]);
        }

        # localizado em Calculos/CalculosAulaController
        $ing = $this->agendarConcluirAula($dados, $aula, $id);
        $ingredienteArray = $ing[0];
        $ingredientesReservadosTotal = $ing[1];

        # calculo de subtraçao dos ingredientes do estoque e da quantidade_reservada
        $estoqueSubtraido = [];
        $estoqueSubtraidoArray = [];
        for ($i = 0; $i < count($ingredientesReservadosTotal); $i++) {
            $estoqueSubtraido['id_ingrediente'] = $ingredientesReservadosTotal[$i]['id_ingrediente'];
            $estoqueSubtraido['quantidade_estoque_ingrediente'] = $ingredienteArray[$i]['quantidade_estoque_ingrediente'] - $ingredientesReservadosTotal[$i]['quantidade_reservada_ingrediente'];
            $estoqueSubtraido['quantidade_reservada_ingrediente'] = $ingredienteArray[$i]['quantidade_reservada_ingrediente'] - $ingredientesReservadosTotal[$i]['quantidade_reservada_ingrediente'];

            array_push($estoqueSubtraidoArray, $estoqueSubtraido);
        }

        $erros = $this->validacao($estoqueSubtraidoArray);

        # conclui a aula efetivamente
        if (empty($errosIngredienteArray) && empty($erros)) {
            if ($aula) {
                $aula->update($dados);

                for ($n = 0; $n < count($ingredienteArray); $n++) {
                    $ingrediente = $ingredienteArray[$n];
                    $ingrediente->update($estoqueSubtraidoArray[$n]);
                }

                return response()->json(['data' => "Aula concluida com sucesso.", 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível concluir a aula.', 'status' => false]);
            }
        } else {
            return response()->json(['erros_ingredientes' => $errosIngredienteArray, 'erros_conclusao' => $erros, 'status' => false]);
        }
    }

    public function validacao($estoqueSubtraidoArray)
    {
        $erros = [];

        for ($j = 0; $j < count($estoqueSubtraidoArray); $j++) {
            if ($estoqueSubtraidoArray[$j]['quantidade_estoque_ingrediente'] < 0) {
                array_push($erros, "A quantidade de estoque não pode ficar negativo.");
            }
            if ($estoqueSubtraidoArray[$j]['quantidade_reservada_ingrediente'] < 0) {
                array_push($erros, "A quantidade reservada não pode ficar negativo.");
            }
        }
        return $erros;
    }
}
