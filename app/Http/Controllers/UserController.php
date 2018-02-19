<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    // Método para registrar usuarios
    public function registrar(Request $request){
        $dados = $request->all();
        if (!User::where('email', $dados['email'])->count()) {
            $dados['password'] = bcrypt($dados['password']);
            $user = User::create($dados);
            return response()->json(['data'=>$user], 201);
        } else {
            return response()->json(['message'=>'Este e-mail já está cadastrado'], 400);
        }
    }
}
