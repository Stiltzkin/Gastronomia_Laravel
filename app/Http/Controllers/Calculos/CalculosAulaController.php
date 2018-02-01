<?php

namespace App\Http\Controllers\Calculos;

use App\Http\Controllers\Controller;
use App\Ingrediente;
use App\Receita;
use App\UnidadeMedida;

class CalculosAulaController extends Controller
{
    public function agendarConcluirAula($dados, $aula, $id)
    {
        # seleciona os dados da tabela pivot aula_receitas
        $aulaReceitas = $aula->receitas()->get()->pluck('pivot');

        // =============================================== //

        $receita = Receita::find($aulaReceitas);

        # seleciona os dados da tabela pivot receita_ingredientes e armazena na array $receitaArr
        $receitaArr = [];
        foreach ($receita as $receitaIngrediente) {
            array_push($receitaArr, $receitaIngrediente->ingredientes->pluck('pivot'));
        }

        // =============================================== //

        # array com os ingredientes calculados
        $ingredienteData = $this->calculos($receitaArr);
        $ingredienteArray = $ingredienteData[0];
        $ingredienteReservadoTotalArray = $ingredienteData[1];
        $ingredienteReservadoAulaArray = $ingredienteData[2];

        # validacao da aula
        $errosAula = $this->validacaoAula($dados);
        $errosIngredienteArray = [];

        for ($m = 0; $m < count($ingredienteReservadoTotalArray); $m++) {
            $errosIngrediente = $this->validacaoIngrediente($ingredienteArray[$m], $ingredienteReservadoTotalArray[$m]);
            array_push($errosIngredienteArray, $errosIngrediente);
        }

        return [$ingredienteArray, $ingredienteReservadoTotalArray, $errosAula, $errosIngredienteArray, $ingredienteReservadoAulaArray];
    }

    public function calculos($receitaArr)
    {
        # cria array de ingredientes usadas nas receitas (ocorrem repeticoes de ingredientes)
        $ingredienteVal = [];
        $arrayId = [];

        # array de ingredientes no formato desejado
        $postArr = [];

        for ($i = 0; $i < count($receitaArr); $i++) {
            for ($j = 0; $j < count($receitaArr[$i]); $j++) {

                $ingredienteVal['id_ingrediente'] = $receitaArr[$i][$j]['id_ingrediente'];
                $ingredienteVal['quantidade_reservada_ingrediente'] = $receitaArr[$i][$j]['quantidade_bruta_receita_ingrediente'];
                $post_data = json_decode(json_encode(array('ingredientes' => $ingredienteVal), JSON_FORCE_OBJECT));

                array_push($arrayId, $receitaArr[$i][$j]['id_ingrediente']);
                array_push($postArr, $post_data);
            }
        }

        # cria array com quantas vezes um mesmo ingrediente aparece na array
        $repeticoesArr = array_count_values($arrayId);
        $key = array_keys($repeticoesArr);

        # remove ingredientes duplicados na array, um mesmo ingrediente pode existir em receitas diferentes
        $unique = array_unique($postArr, SORT_REGULAR);

        # cria array dos ingredientes que serao atualizados
        $ingredienteArray = [];
        for ($l = 0; $l < count($key); $l++) {
            $ingrediente = Ingrediente::find($key[$l]);
            array_push($ingredienteArray, $ingrediente);
        }

        $ingredienteNaoCalculadoArr = [];
        $ingredienteReservadoAula = [];

        $ingredienteReservadoAulaArray = [];
        $ingredienteReservadoTotalArray = [];
        for ($k = 0; $k < count($key); $k++) {
            $porra = (string) $key[$k];

            # ingredientes reservado da aula especifica
            $ingredienteReservadoAula['id_ingrediente'] = $key[$k];
            $ingredienteReservadoAula['quantidade_reservada_ingrediente'] = $repeticoesArr[$porra] * $unique[$k]->ingredientes->quantidade_reservada_ingrediente;

            # ingredientes reservado total
            $ingredienteNaoCalculadoArr['id_ingrediente'] = $key[$k];
            $ingredienteNaoCalculadoArr['quantidade_reservada_ingrediente'] = ($repeticoesArr[$porra] * $unique[$k]->ingredientes->quantidade_reservada_ingrediente) + $ingredienteArray[$k]['quantidade_reservada_ingrediente'];

            array_push($ingredienteReservadoAulaArray, $ingredienteReservadoAula);
            array_push($ingredienteReservadoTotalArray, $ingredienteNaoCalculadoArr);
        }

        return [$ingredienteArray, $ingredienteReservadoTotalArray, $ingredienteReservadoAulaArray];
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

    public function validacaoIngrediente($ingrediente, $ingredienteCalculado)
    {
        $erros = [];
        if ($ingrediente['quantidade_estoque_ingrediente'] < $ingredienteCalculado['quantidade_reservada_ingrediente']) {
            $unidade_medida = UnidadeMedida::find($ingrediente['id_unidade_medida']);
            $qtdFaltante = $ingredienteCalculado['quantidade_reservada_ingrediente'] - $ingrediente['quantidade_estoque_ingrediente'];
            array_push($erros, "Faltam " . $qtdFaltante . " " . $unidade_medida['simbolo_unidade_medida'] . " de " . $ingrediente['nome_ingrediente'] . " em estoque.");
        }

        return $erros;
    }
}
