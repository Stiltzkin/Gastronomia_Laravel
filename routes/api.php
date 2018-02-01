<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('ingredientes', 'IngredienteController');
Route::put('/ingredientes/soma/{ingrediente}', ['uses' => 'Calculos\IngredienteController@soma', 'as' => 'ingrediente.soma']);
Route::put('/ingredientes/subtrai/{ingrediente}', ['uses' => 'Calculos\IngredienteController@subtrai', 'as' => 'ingrediente.subtrai']);

Route::get('motivo_retiradas', ['uses' => 'MotivoRetiradaController@index', 'as' => 'motivo_retirada.index']);

Route::resource('receitas', 'ReceitaController');
Route::resource('categorias', 'CategoriaController');
Route::resource('classificacoes', 'ClassificacaoController');

Route::resource('aulas', 'AulaController');
Route::put('/aulas/agendar/{aulas}', ['uses' => 'Calculos\AgendarAulaController@agendarAula', 'as' => 'aula.agendar']);
Route::put('/aulas/concluir/{aulas}', ['uses' => 'Calculos\ConcluirAulaController@concluirAula', 'as' => 'aula.concluir']);
