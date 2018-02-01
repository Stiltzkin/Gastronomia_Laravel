<?php

namespace App\Http\Controllers\Calculos;

use App\Aula;
use Illuminate\Http\Request;

class AgendarAulaController extends CalculosAulaController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    public function agendarAula(Request $request, $id)
    {
        $dados = $request->all();
        $aula = Aula::find($id);

        # localizado em Calculos/CalculosAulaController
        $ing = $this->agendarConcluirAula($dados, $aula, $id);
        $ingredienteArray = $ing[0];
        $ingredienteCalculadoArray = $ing[1];
        $errosAula = $ing[2];
        $errosIngredienteArray = $ing[3];

        # agenda a aula efetivamente
        if (empty($errosAula) && empty($errosIngredienteArray)) {
            if ($aula) {
                $aula->update($dados);

                for ($n = 0; $n < count($ingredienteArray); $n++) {
                    $ingredienteArray[$n]->update($ingredienteCalculadoArray[$n]);
                }

                return response()->json(['data' => "Aula agendada com sucesso.", 'status' => true]);
            } else {
                return response()->json(['data' => 'Não foi possível agendar a aula.', 'status' => false]);
            }
        } else {
            return response()->json(['erros_aula' => $errosAula, 'erros_ingredientes' => $errosIngredienteArray, 'status' => false]);
        }

    }
}
