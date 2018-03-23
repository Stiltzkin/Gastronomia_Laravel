<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Método para registrar usuarios
    public function registrar(Request $request)
    {
        $dados = $request->all();
        if (!User::where('email', $dados['email'])->count()) {
            $dados['password'] = bcrypt($dados['password']);
            $user = User::create($dados);
            return response()->json(['data' => $user], 201);
        } else {
            return response()->json(['message' => 'Este usuário já está cadastrado'], 400);
        }
    }

    // Apenas para o frontend verificar se o token é valido
    public function verificaToken()
    {
        return response()->json(['message' => 'Token valido.']);
    }

    public function userList()
    {
        $user = User::all();

        return response()->json(['data' => $user]);
    }
}
