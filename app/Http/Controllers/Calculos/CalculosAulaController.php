<?php

namespace App\Http\Controllers\Calculos;

use App\Http\Controllers\Controller;
use App\Ingrediente;
use App\Receita;

class CalculosAulaController extends Controller
{
    public function agendarConcluirAula($dados, $aula, $id)
    {
        # seleciona os dados da tabela pivot aula_receitas
        $aulaReceitas = $aula->receitas()->get()->pluck('pivot');

        # cria array com pivot receita_ingredientes das receitas usadas na aula
        $receitaArr = [];
        for ($n = 0; $n < count($aulaReceitas); $n++) {
            $receita = Receita::find($aulaReceitas[$n]['id_receita'])->ingredientes->pluck('pivot');
            array_push($receitaArr, $receita);
        }

        # chama a funçao para fazer os calculos
        $ingredientesReservadosTotal = $this->calculos($receitaArr, $aulaReceitas);

        # cria array dos ingredientes para ser atualizado
        $ingredienteArray = $this->arrayIngredientesParaAtualizar($ingredientesReservadosTotal);

        return [$ingredienteArray, $ingredientesReservadosTotal];
    }

    public function calculos($receitaArr, $aulaReceitas)
    {
        # cria array de ingredientes usadas nas receitas (ocorrem repeticoes de ingredientes)
        $ingredienteVal = [];
        $arrayId = [];

        # array de ingredientes no formato desejado
        # a quantidade_receita da tabela aula_receias foi inserido nele para facilitar os calculos
        $postArr = [];
        for ($i = 0; $i < count($receitaArr); $i++) {
            for ($j = 0; $j < count($receitaArr[$i]); $j++) {
                $ingredienteVal['quantidade_receita'] = $aulaReceitas[$i]['quantidade_receita'];
                $ingredienteVal['id_ingrediente'] = $receitaArr[$i][$j]['id_ingrediente'];
                $ingredienteVal['id_receita'] = $receitaArr[$i][$j]['id_receita'];
                $ingredienteVal['quantidade_reservada_ingrediente'] = $receitaArr[$i][$j]['quantidade_bruta_receita_ingrediente'];
                $post_data = json_decode(json_encode(array('ingredientes' => $ingredienteVal), JSON_FORCE_OBJECT));

                array_push($arrayId, $receitaArr[$i][$j]['id_ingrediente']);
                array_push($postArr, $post_data);
            }
        }

        # calcula quantidade_reservada dos ingredientes de cada receita
        # pode haver repetiçoes de ingredientes pois um ingrediente pode ser usado em outras receitas
        $reservaAula = [];
        $reservaAulaArray = [];
        for ($i = 0; $i < count($postArr); $i++) {
            $reservaAula['id_ingrediente'] = $postArr[$i]->ingredientes->id_ingrediente;
            $reservaAula['quantidade_reservada_ingrediente'] = $postArr[$i]->ingredientes->quantidade_receita * $postArr[$i]->ingredientes->quantidade_reservada_ingrediente;
            array_push($reservaAulaArray, $reservaAula);
        }

        # soma os ingredientes repetidos gerado na operaçao anterior
        $reservaAulaTotal = [];
        $reservaAulaTotalArray = [];
        foreach ($arrayId as $id) {
            $qtdReservaTotal = 0;
            for ($j = 0; $j < count($reservaAulaArray); $j++) {
                if ($id == $reservaAulaArray[$j]['id_ingrediente']) {
                    $qtdReservaTotal += $reservaAulaArray[$j]['quantidade_reservada_ingrediente'];
                    $reservaAulaTotal['id_ingrediente'] = $reservaAulaArray[$j]['id_ingrediente'];
                    $reservaAulaTotal['quantidade_reservada_ingrediente'] = $qtdReservaTotal;
                }
            }
            array_push($reservaAulaTotalArray, $reservaAulaTotal);
        }

        # ainda há ingredientes repetidos da operaçao anterior, mas com os valores completamente iguais
        # aqui é eliminado as repetiçoes
        $ingredientesReservadosTotal = array_unique($reservaAulaTotalArray, SORT_REGULAR);

        return $ingredientesReservadosTotal;
    }

    public function arrayIngredientesParaAtualizar($ingredientesReservadosTotal)
    {
        # cria array dos ingredientes que serao atualizados
        $ingredienteArray = [];
        for ($l = 0; $l < count($ingredientesReservadosTotal); $l++) {
            $ingrediente = Ingrediente::find($ingredientesReservadosTotal[$l]['id_ingrediente']);
            array_push($ingredienteArray, $ingrediente);
        }
        return $ingredienteArray;
    }
}
