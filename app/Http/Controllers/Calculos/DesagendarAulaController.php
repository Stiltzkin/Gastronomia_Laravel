<?php

namespace App\Http\Controllers\Calculos;

use App\Aula;
use Illuminate\Http\Request;

class DesagendarAulaController extends CalculosAulaController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    public function desagendarAula(Request $request, $id)
    {
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.'], 400);
            }

            $dados = $request->only(['aula_agendada']);
            $aula = Aula::find($id);

            if ($aula) {
                if ($aula['aula_agendada'] == false) {
                    return response()->json(['message' => "Aula ainda não está agendada."], 400);
                }
            } else {
                return response()->json(['message' => "Aula não existe."], 404);
            }

            # localizado em Calculos/CalculosAulaController
            $ing = $this->agendarConcluirAula($dados, $aula, $id);
            $ingredienteArray = $ing[0];
            $ingredientesReservadosTotal = $ing[1];

            # calculo, tira da reserva os ingredientes
            $calculado = [];
            $calculadoArray = [];
            for ($i = 0; $i < count($ingredienteArray); $i++) {
                $calculado['id_ingrediente'] = $ingredienteArray[$i]['id_ingrediente'];
                $calculado['quantidade_reservada_ingrediente'] = $ingredienteArray[$i]['quantidade_reservada_ingrediente'] - $ingredientesReservadosTotal[$i]['quantidade_reservada_ingrediente'];
                array_push($calculadoArray, $calculado);
            }

            # agenda a aula efetivamente
            if ($aula) {
                $aula['aula_agendada'] = false;
                $aula['aula_concluida'] = false;
                $aula->update($dados);

                for ($n = 0; $n < count($ingredienteArray); $n++) {
                    $ingrediente = $ingredienteArray[$n];
                    $ingrediente->update($calculadoArray[$n]);
                }

                return response()->json(['message' => "Aula desagendada com sucesso."], 204);
            } else {
                return response()->json(['message' => 'Aula não existe.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.', 500);
        }

    }
}
