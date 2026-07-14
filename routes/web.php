<?php

use Illuminate\Support\Facades\Route;

// Vista de Login y Registro
Route::get('/', function () {
    return view('login');
})->name('login');

// Vista de la Tienda (Pública)
Route::get('/tienda', function () {
    return view('store');
});

// Vista del Panel de Control (Dashboard - Punto 5)
Route::get('/dashboard', function () {
    return view('dashboard');
});