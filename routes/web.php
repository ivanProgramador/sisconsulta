<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BundlesController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TicketDispenserController;
use App\Http\Middleware\TicketDispenserSession;
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

    #Filas ======================================================================

    //rota home 
    Route::get('/', [MainController::class,'index'])->name('home');

    //rotas para criar uma nova fila 
    Route::get('/queue/create',[MainController::class,'createQueue'])->name('queue.create');
    Route::post('/queue/create',[MainController::class,'createQueueSubmit'])->name('queue.create.submit');

    //rotas para editar uma fila
    Route::get('/queue/edit/{id}',[MainController::class,'editQueue'])->name('queue.edit');
    Route::post('/queue/edit',[MainController::class,'editQueueSubmit'])->name('queue.edit.submit'); 

    //rota de clonagem de fila
    Route::get('/queue/clone/{id}',[MainController::class,'cloneQueue'])->name('queue.clone');
    Route::post('/queue/clone',[MainController::class,'cloneQueueSubmit'])->name('queue.clone.submit');  

    //rota parar deletar fila com soft delete
    Route::get('/queue/delete/{id}',[MainController::class,'deleteQueue'])->name('queue.delete');
    Route::get('/queue/delete-confirm/{id}',[MainController::class,'deleteQueueConfirm'])->name('queue.delete.confirm');

    //rota para restaurar fila
    /*
      O comando que restaura fila deve estar disponivel somente para os gestores 
      mediante aurização da empresa eles podem cancelar ou recuprar a existencia de uma fila a qualquer momento 
      conforme a necessidade da operação, o fato de uma fila apagada causar um soft dele nos tickets 
      associados ainda esta em dicussão. 
    
    */
    Route::get('/queue/restore/{id}',[MainController::class,'restoreQueue'])->name('queue.restore');
     

    //rota pra gerar a hash da fila 
    Route::get('/queue/generate-hash',[MainController::class,'generateQueueHash'])->name('queue.generate.hash');


    //rota para detalhes da fila 
    Route::get('/queue/{id}',[MainController::class,'queueDetails'])->name('queue.details');

    #GRUPOS DE FILAS =================================================================================================
    
     Route::get('/bundles',[BundlesController::class,'index'])->name('bundles.home');

     //rota apara o formulario d cadastro de coleção de filas 
     Route::get('/bundles/create',[BundlesController::class,'createBundles'])->name('bundles.create');

     //rota para a função de gravação de coleção de fila no banco 
     Route::post('/bundles/create',[BundlesController::class,'createBundlesSubmit'])->name('bundles.create.submit');

     //rota pra gerar hash das credenciais 
     Route::get('/bundles/generate-credential-value/{num_chars}',[BundlesController::class,'generateCredentialValue'])->name('bundles.generate.credential.value');

     //rota para o formulario de edição 
     Route::get('/bundle/edit/{id}',[BundlesController::class,'edit'])->name('bundle.edit');

     //rota para executar a edição 
     Route::post('/bundle/edit/',[BundlesController::class,'editSubmit'])->name('bundle.edit.submit');

     //rota aparar o formulario de apagar 
     Route::get('/bundle/delete/{id}',[BundlesController::class,'delete'])->name('bundle.delete');

     //rota para a confirmação 
     Route::get('/bundle/delete-confirm/{id}',[BundlesController::class,'deleteConfirm'])->name('bundle.delete.confirm');

     //rota pra restaurar
     Route::get('/bundle/restore/{id}',[BundlesController::class,'restore'])->name('bundle.restore');






    # USUARIOS =================================================================================================

    //rotas para alterar a senha 
    Route::get('/change-password',[AuthController::class,'changePassword'])->name('change.password');
    
    Route::post('/change-password',[AuthController::class,'changePasswordSubmit'])->name('change.password.submit');
    
    //rota de logout 
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    
    });

    #Dispensador ===============================================================================================


    Route::middleware([TicketDispenserSession::class])->group(function(){

       Route::get('/dispenser',[TicketDispenserController::class,'index'])->name('dispenser');

       Route::get('/dispenser/get-bundle-data/{credential}',[TicketDispenserController::class,'getBundleData'])->name('dispenser.get.bundle.data');
       
    });



    Route::get('/dispenser/credentials',[TicketDispenserController::class,'credentials'])->name('dispenser.credentials');

    Route::post('/dispenser/credentials',[TicketDispenserController::class,'credentialsSubmit'])->name('dispenser.credentials.submit');


    /*
    
     cria e remove variavel de sessão 

    Route::get('/dispenser/teste-add-session',function(){
      session()->put('ticket_dispenser_credential','abc123');
    });

    Route::get('/dispenser/teste-remove-session',function(){
      session()->forget('ticket_dispenser_credential');
    });
    
    */













