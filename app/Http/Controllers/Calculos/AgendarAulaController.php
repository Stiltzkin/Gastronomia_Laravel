<?php

namespace App\Http\Controllers\Calculos;

use App\Aula;
use App\Http\Controllers\Extend\CalculosAulaController;
use App\UnidadeMedida;
use Illuminate\Http\Request;

class AgendarAulaController extends CalculosAulaController
{
    public function agendarAula(Request $request, $id)
    {
        try {
            if ($id < 0) {
                return response()->json(['message' => 'ID menor que zero, por favor, informe um ID válido.']);
            }

            $dados = $request->all();
            $aula = Aula::find($id);

            # update da aula antes da operacao
            # o usuario tem opcao de alterar valores no ultimo momento -.-
            if ($aula) {
                if ($aula['aula_agendada'] == true) {
                    return response()->json(['message' => "Aula já está agendada."]);
                }
            } else {
                return response()->json(['message' => "Aula não existe."]);
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
                # começa o agendamento

            } else {
                return response()->json(['message' => 'Aula não encontrada.']);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor ao dar update.');
        }

        # começo do agendamento
        try {
            # localizado em Calculos/Extend/CalculosAulaController
            $ing = $this->agendarConcluirAula($aula, $id);
            $ingredienteArray = $ing[0];
            $ingredientesReservadosTotal = $ing[1];

            # calculo de ingredientes usados na aula + quantidade_reservada de outras aulas
            $reservadoTotal = [];
            $reservadoTotalArray = [];
            for ($i = 0; $i < count($ingredienteArray); $i++) {
                $reservadoTotal['id_ingrediente'] = $ingredienteArray[$i]['id_ingrediente'];
                $reservadoTotal['quantidade_reservada_ingrediente'] = $ingredientesReservadosTotal[$i]['quantidade_reservada_ingrediente'] + $ingredienteArray[$i]['quantidade_reservada_ingrediente'];
                array_push($reservadoTotalArray, $reservadoTotal);
            }

            # validacoes
            $errosAula = $this->validacaoAula($dados);

            $errosIngrediente = [];
            for ($j = 0; $j < count($ingredienteArray); $j++) {
                $ingrediente = $ingredienteArray[$j];
                $ingredienteReservadoAula = $ingredientesReservadosTotal[$j];
                $errosIng = $this->validacaoIngrediente($ingrediente, $ingredienteReservadoAula);
                if (!is_null($errosIng)) {
                    array_push($errosIngrediente, $errosIng);
                }
            }

            // # agenda a aula efetivamente
            if (empty($errosAula) && empty($errosIngrediente)) {
                if ($aula) {
                    $aula['aula_agendada'] = true;
                    $aula->update($dados);

                    for ($n = 0; $n < count($ingredienteArray); $n++) {
                        $ingrediente = $ingredienteArray[$n];
                        $ingrediente->update($reservadoTotalArray[$n]);
                    }

                    return response()->json(['message' => "Aula agendada com sucesso."]);
                } else {
                    return response()->json(['message' => 'Aula não encontrada.']);
                }
            } else {
                return response()->json(['message' => $errosAula, 'erros_ingredientes' => $errosIngrediente]);
            }
        } catch (\Exception $e) {
            return response()->json('Ocorreu um erro no servidor.');
        }
    }

    public function validacaoAula($dados)
    {
        $date = date('m/d/Y');
        $date_timestamp = strtotime($date);
        if (!isset($dados['data_aula'])) {
            return "Selecione a data da aula";
        }

        $erros = [];
        if ($date_timestamp < $dados['data_aula']) {
            array_push($erros, "A data não pode ser anterior a hoje.");
        }
        if ($dados['data_aula'] == "" || $dados['data_aula'] == null) {
            array_push($erros, "Selecione a data da aula.");
        }
        return $erros;
    }

    public function validacaoIngrediente($ingrediente, $ingredienteReservadoAula)
    {
        if ($ingrediente['quantidade_estoque_ingrediente'] < $ingredienteReservadoAula['quantidade_reservada_ingrediente']) {
            $unidade_medida = UnidadeMedida::find($ingrediente['id_unidade_medida']);
            $qtdFaltante = $ingredienteReservadoAula['quantidade_reservada_ingrediente'] - $ingrediente['quantidade_estoque_ingrediente'];
            return "Faltam " . $qtdFaltante . " " . $unidade_medida['simbolo_unidade_medida'] . " de " . $ingrediente['nome_ingrediente'] . " em estoque.";
        }
    }
}
