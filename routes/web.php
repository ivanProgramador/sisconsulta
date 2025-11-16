<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


//rotas para visitantes 
Route::middleware(['guest'])->group(function () {
    
    //rota do formulario de login
    Route::get('/login',[AuthController::class,'login'])->name('login');

    //rota de tratamento do formulario de login
    Route::post('/login',[AuthController::class,'loginSubmit'])->name('login.submit');
    
});

//rotas parara usuarios
Route::middleware(['auth'])->group(function () {

    //rota home 
    Route::get('/', function () {
        echo "pagina home ";
    })->name('home');

    //rota de logout 
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    
});
