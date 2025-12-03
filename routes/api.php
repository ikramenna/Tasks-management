<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Route publique : test
Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

// // Routes d'authentification (publiques)
// Route::post('/login', [AuthController::class, 'login'])->name('api.login');
// Route::post('/register', [AuthController::class, 'register'])->name('api.register');

// // Routes protégées par JWT
// Route::middleware('jwt')->group(function () {
//     // Récupérer l'utilisateur connecté
//     Route::get('/user', [AuthController::class, 'getUser']);
//     // Mettre à jour l'utilisateur
//     Route::put('/user', [AuthController::class, 'updateUser']);
//     // Déconnexion
//     Route::post('/logout', [AuthController::class, 'logout']);
// });

// Route::get('/tasks', [TaskController::class, 'index']);



//Route::middleware('jwt')->group(function () {
   
//});
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
 Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/tasks', [TaskController::class, 'index']);
 Route::post('/tasks', [TaskController::class, 'store']);