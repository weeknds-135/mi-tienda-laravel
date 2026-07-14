<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registro de usuarios (API / Backend)
    public function registrar(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:usuarios,username|max:50',
            'password' => 'required|string|min:6',
        ]);

        $usuario = Usuario::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Encriptación segura
            'rol' => 'cliente', // Por defecto todos son clientes
        ]);

        return response()->json([
            'mensaje' => 'Usuario registrado con éxito',
            'usuario' => $usuario
        ], 201);
    }

    // Login (API / Backend)
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('username', $request->username)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Iniciar sesión en Laravel (manejo de sesión del Punto 4)
        Auth::login($usuario);

        return response()->json([
            'mensaje' => 'Login exitoso',
            'usuario' => $usuario
        ], 200);
    }

    // Cerrar Sesión
    public function logout()
    {
        Auth::logout();
        return response()->json(['mensaje' => 'Sesión cerrada correctamente']);
    }
}