<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AddressesController;

Route::get('/', function () {
    return view('welcome');
});



// Rotas CRUD normais
Route::resource('users', UserController::class);
Route::resource('clients', ClientController::class);
Route::resource('adressesses',AddressesController::class);

// Rota para promover cliente a admin
Route::put('/users/{user}/promote', [UserController::class, 'promoteToAdmin'])
     ->name('users.promote')
     ->middleware('auth'); // protege para usuários logados




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

