<?php

use Illuminate\Support\Facades\Route;
  use App\Http\Controllers\TaskController;


/*Route::get('/tasks', function () {
    return view('index');
});*/
 Route::resource('tasks', TaskController::class);
// Routes pour les tâches, protégées par JWT (middleware 'auth:api' ou votre middleware JWT)
    /* Route::middleware(['auth:api'])->group(function () { // Remplacez 'auth:api' par votre middleware JWT si différent
        
     });*/
// Route::get('/tasks', [TaskController::class, 'index']);
// Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
// routes/web.php
//Route::view('/login', 'auth.login')->name('login');

Route::get('/tasks', [TaskController::class, 'indexView'])->name('tasks.index');