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

# ingredientes
Route::get('/ingredientes', 'IngredienteController@index');
Route::get('/ingredientes/{ingrediente}', 'IngredienteController@show');
Route::post('/ingredientes', 'IngredienteController@store');
Route::put('/ingredientes/{ingrediente}', 'IngredienteController@update');
Route::put('/ingredientes/soma/{ingrediente}', 'Calculos\IngredienteController@soma');
Route::put('/ingredientes/subtrai/{ingrediente}', 'Calculos\IngredienteController@subtrai');
Route::delete('/ingredientes/{ingrediente}', 'IngredienteController@destroy');

# motivo de retirada
Route::get('motivo_retiradas', 'MotivoRetiradaController@index');

# reeitas
Route::get('/receitas', 'ReceitaController@index');
Route::get('/receitas/{receita}', 'ReceitaController@show');
Route::post('/receitas', 'ReceitaController@store');
Route::put('/receitas/{receita}', 'ReceitaController@update');
Route::delete('/receitas/{receita}', 'ReceitaController@destroy');

# aulas
Route::get('/aulas', 'AulaController@index');
Route::get('/aulas/{aula}', 'AulaController@show');
Route::post('/aulas', 'AulaController@store');
Route::put('/aulas/{aula}', 'AulaController@update');
Route::delete('/aulas/{aula}', 'AulaController@destroy');
Route::put('/aulas/agendar/{aula}', 'Calculos\AgendarAulaController@agendarAula');
Route::put('/aulas/desagendar/{aula}', 'Calculos\DesagendarAulaController@desagendarAula');
Route::put('/aulas/concluir/{aula}', 'Calculos\ConcluirAulaController@concluirAula');

# categoria
Route::get('/categorias', 'CategoriaController@index');
Route::get('/categorias/{categoria}', 'CategoriaController@show');
Route::post('/categorias', 'CategoriaController@store');
Route::put('/categorias/{categoria}', 'CategoriaController@update');
Route::delete('/categorias/{categoria}', 'CategoriaController@destroy');

# classificacao
Route::get('/classificacoes', 'ClassificacaoController@index');
Route::get('/classificacoes/{classificacao}', 'ClassificacaoController@show');
Route::post('/classificacoes', 'ClassificacaoController@store');
Route::put('/classificacoes/{classificacao}', 'ClassificacaoController@update');
Route::delete('/classificacoes/{classificacao}', 'ClassificacaoController@destroy');
