<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create()
    {
        return view('cadastro'); 
    }

    public function store(Request $request)
    {
        // 1. Validação básica
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // 2. Criar o usuário
        User::create([
            'name' => $request->name,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'Conta criada com sucesso!');
    }
}