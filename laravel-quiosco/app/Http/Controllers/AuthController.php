<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistroRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 

class AuthController extends Controller
{
    //
    public function register(RegistroRequest $request)
    {
        //Validar registro
        $data = $request->validated();

        //Crear usuario

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //Retornar respuesta

        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];

    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        //Revisar el password
        if(!Auth::attempt($data)){
            return response([
                'errors' => ['El email o password son incorrectos']
            ], 422);
        }

        //Autenticar el usuario
        $user = Auth::user();
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
        
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return [
            'user' => null
        ];
    }
}
