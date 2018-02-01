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

        # localizado em Calculos/CalculosAulaController
        $ing = $this->agendarConcluirAula($dados, $aula, $id);
        $ingredienteArray = $ing[0];
        $ingredienteCalculadoArray = $ing[1];
        // $errosAula = $ing[2];
        $errosIngredienteArray = $ing[3];
        $ingredienteReservadoAulaArray = $ing[4];

        # calculos para conclusao da aula
        $subtraidoEstoque = [];
        $subtraidoEstoqueArray = [];

        for ($i = 0; $i < count($ingredienteCalculadoArray); $i++) {
            $subtraidoEstoque['id_ingrediente'] = $ingredienteCalculadoArray[$i]['id_ingrediente'];
            $subtraidoEstoque['quantidade_estoque_ingrediente'] = $ingredienteArray[$i]['quantidade_estoque_ingrediente'] - $ingredienteReservadoAulaArray[$i]['quantidade_reservada_ingrediente'];
            $subtraidoEstoque['quantidade_reservada_ingrediente'] = $ingredienteArray[$i]['quantidade_reservada_ingrediente'] - $ingredienteReservadoAulaArray[$i]['quantidade_reservada_ingrediente'];

            array_push($subtraidoEstoqueArray, $subtraidoEstoque);
        }

        $erros = $this->validacao($subtraidoEstoqueArray);

        # conclui a aula efetivamente
        if (empty($errosIngredienteArray)) {
            if ($aula) {
                $aula->update($dados);

                for ($n = 0; $n < count($ingredienteArray); $n++) {
                    $ingredienteArray[$n]->update($ingredienteCalculadoArray[$n]);
                }

                return response()->json(['data' => "Aula concluida com sucesso.", 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível concluir a aula.', 'status' => false]);
            }
        } else {
            return response()->json(['erros_ingredientes' => $errosIngredienteArray, 'status' => false]);
        }
    }

    public function validacao($subtraidoEstoqueArray)
    {
        $erros = [];

        for ($j = 0; $j < count($subtraidoEstoqueArray); $j++) {
            if ($subtraidoEstoqueArray[$j]['quantidade_estoque_ingrediente'] < 0) {
                array_push($erros, "A quantidade de estoque não pode ficar negativo.");
            }
            if ($subtraidoEstoqueArray[$j]['quantidade_reservada_ingrediente'] < 0) {
                array_push($erros, "A quantidade de reservada não pode ficar negativo.");
            }

        }
        return $erros;
    }

}
