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
# Rota de registrar usuario
Route::post('/cadastro', 'UserController@registrar');

Route::group(['middleware' => ['auth:api']], function () {
    # ingredientes
    Route::get('/ingredientes', 'IngredienteController@index')->middleware('scope:administrador,usuario');
    Route::get('/ingredientes/{ingrediente}', 'IngredienteController@show')->middleware('scope:administrador,usuario');
    Route::post('/ingredientes', 'IngredienteController@store')->middleware('scope:administrador,usuario');
    Route::post('/ingredientes/update/{ingrediente}', 'IngredienteController@update')->middleware('scope:administrador,usuario');
    Route::post('/ingredientes/soma/{ingrediente}', 'Calculos\IngredienteController@soma')->middleware('scope:administrador,usuario');
    Route::post('/ingredientes/subtrai/{ingrediente}', 'Calculos\IngredienteController@subtrai')->middleware('scope:administrador,usuario');
    Route::post('/ingredientes/delete/{ingrediente}', 'IngredienteController@destroy')->middleware('scope:administrador,usuario');

    # receitas
    Route::get('/receitas', 'ReceitaController@index')->middleware('scope:administrador');
    Route::get('/receitas/{receita}', 'ReceitaController@show')->middleware('scope:administrador');;
    Route::post('/receitas', 'ReceitaController@store')->middleware('scope:administrador');;
    Route::post('/receitas/update/{receita}', 'ReceitaController@update')->middleware('scope:administrador');;
    Route::post('/receitas/delete/{receita}', 'ReceitaController@destroy')->middleware('scope:administrador');;

    # aulas
    Route::get('/aulas', 'AulaController@index')->middleware('scope:administrador');;
    Route::get('/aulas/{aula}', 'AulaController@show')->middleware('scope:administrador');;
    Route::post('/aulas', 'AulaController@store')->middleware('scope:administrador');;
    Route::post('/aulas/update/{aula}', 'AulaController@update')->middleware('scope:administrador');;
    Route::post('/aulas/delete/{aula}', 'AulaController@destroy')->middleware('scope:administrador');;
    Route::post('/aulas/agendar/{aula}', 'Calculos\AgendarAulaController@agendarAula')->middleware('scope:administrador');;
    Route::post('/aulas/desagendar/{aula}', 'Calculos\DesagendarAulaController@desagendarAula')->middleware('scope:administrador');;
    Route::post('/aulas/concluir/{aula}', 'Calculos\ConcluirAulaController@concluirAula')->middleware('scope:administrador');;
    Route::post('/aulas/clone/{aula}', 'AulaController@clonarAula')->middleware('scope:administrador');

    # categoria
    Route::get('/categorias', 'CategoriaController@index')->middleware('scope:administrador');;
    Route::get('/categorias/{categoria}', 'CategoriaController@show')->middleware('scope:administrador');;
    Route::post('/categorias', 'CategoriaController@store')->middleware('scope:administrador');;
    Route::post('/categorias/update/{categoria}', 'CategoriaController@update')->middleware('scope:administrador');;
    Route::post('/categorias/delete/{categoria}', 'CategoriaController@destroy')->middleware('scope:administrador');;

    # classificacao
    Route::get('/classificacoes', 'ClassificacaoController@index')->middleware('scope:administrador');;
    Route::get('/classificacoes/{classificacao}', 'ClassificacaoController@show')->middleware('scope:administrador');;
    Route::post('/classificacoes', 'ClassificacaoController@store')->middleware('scope:administrador');;
    Route::post('/classificacoes/update/{classificacao}', 'ClassificacaoController@update')->middleware('scope:administrador');;
    Route::post('/classificacoes/delete/{classificacao}', 'ClassificacaoController@destroy')->middleware('scope:administrador');;

    # unidade_medida
    Route::get('/unidades', 'UnidadeMedidaController@index')->middleware('scope:administrador');;

    # periodo
    Route::get('/periodos', 'PeriodoController@index')->middleware('scope:administrador');;

    # motivo de retirada
    Route::get('motivo_retiradas', 'MotivoRetiradaController@index')->middleware('scope:administrador,usuario');

    # rota para verificar se token é valido
    Route::get('/verificatoken', 'UserController@verificaToken')->middleware('scope:administrador,usuario');
});
