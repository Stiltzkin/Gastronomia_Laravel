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
Route::get('/ingredientes/listall', 'IngredienteController@listAll');
Route::get('/ingredientes/{ingrediente}', 'IngredienteController@show');
Route::post('/ingredientes', 'IngredienteController@store');
Route::post('/ingredientes/update/{ingrediente}', 'IngredienteController@update');
Route::post('/ingredientes/soma/{ingrediente}', 'Calculos\IngredienteController@soma');
Route::post('/ingredientes/subtrai/{ingrediente}', 'Calculos\IngredienteController@subtrai');
Route::post('/ingredientes/delete/{ingrediente}', 'IngredienteController@destroy');

# motivo de retirada
Route::get('motivo_retiradas', 'MotivoRetiradaController@index');

# reeitas
Route::get('/receitas', 'ReceitaController@index');
Route::get('/receitas/listall', 'ReceitaController@listAll');
Route::get('/receitas/{receita}', 'ReceitaController@show');
Route::post('/receitas', 'ReceitaController@store');
Route::post('/receitas/update/{receita}', 'ReceitaController@update');
Route::post('/receitas/delete/{receita}', 'ReceitaController@destroy');

# aulas
Route::get('/aulas', 'AulaController@index');
Route::get('/aulas/{aula}', 'AulaController@show');
Route::post('/aulas', 'AulaController@store');
Route::post('/aulas/update/{aula}', 'AulaController@update');
Route::post('/aulas/delete/{aula}', 'AulaController@destroy');
Route::post('/aulas/agendar/{aula}', 'Calculos\AgendarAulaController@agendarAula');
Route::post('/aulas/desagendar/{aula}', 'Calculos\DesagendarAulaController@desagendarAula');
Route::post('/aulas/concluir/{aula}', 'Calculos\ConcluirAulaController@concluirAula');
Route::post('/aulas/clone/{aula}', 'AulaController@clonarAula');

# categoria
Route::get('/categorias', 'CategoriaController@index');
Route::get('/categorias/{categoria}', 'CategoriaController@show');
Route::post('/categorias', 'CategoriaController@store');
Route::post('/categorias/update/{categoria}', 'CategoriaController@update');
Route::post('/categorias/delete/{categoria}', 'CategoriaController@destroy');

# classificacao
Route::get('/classificacoes', 'ClassificacaoController@index');
Route::get('/classificacoes/{classificacao}', 'ClassificacaoController@show');
Route::post('/classificacoes', 'ClassificacaoController@store');
Route::post('/classificacoes/update/{classificacao}', 'ClassificacaoController@update');
Route::post('/classificacoes/delete/{classificacao}', 'ClassificacaoController@destroy');

# unidade_medida
Route::get('/unidades', 'UnidadeMedidaController@index');

# periodo
Route::get('/periodos', 'PeriodoController@index');
