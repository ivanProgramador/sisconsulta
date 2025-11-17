<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


//rotas para visitantes 
Route::middleware(['guest'])->group(function () {
    
    //rota do formulario de login
    Route::get('/login',[AuthController::class,'login'])->name('login');

    //rota de tratamento do formulario de login
    Route::post('/login',[AuthController::class,'loginSubmit'])->name('login.submit');
    
});

//rotas para usuarios
Route::middleware(['auth'])->group(function () {

    //rota home 
    Route::get('/', [MainController::class,'index'])->name('home');

    //rotas para alterar a senha 
    Route::get('/change-password',[AuthController::class,'changePassword'])->name('change.password');
    Route::post('/change-password',[AuthController::class,'changePasswordSubmit'])->name('change.password.submit');
    

    //rota de logout 
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    
});
