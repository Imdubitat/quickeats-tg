<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quickeats', [Controller::class, 'exibirIndex'])->name('index');
Route::get('/clientes', [Controller::class, 'exibirIndexCliente'])->name('index_cliente');
Route::get('/restaurantes', [Controller::class, 'exibirIndexRestaurante'])->name('index_restaurante');