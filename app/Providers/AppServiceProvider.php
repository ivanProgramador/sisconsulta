<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
           No sistema eu tenho 3 tipos de usuarios até agora, são eles 

           1 - sys-admin
           2 - client-admin 
           3 - client-user 

           Nesse caso cada um desses tipos terá uma gate que definine as suas 
           autorizações. Basicamente ela vai receber o usuário logado como párametro
           e testar a que grupo ele pertence no caso abaixo ela testa se o usuario 
           recebido como parametro tem o role igual ao 'sys-admin' se tiver ela retorna true
           se não tiver ela retorna false.   


            Gate::define('sys-admin',function($user){
                return $user->role === 'sys-admin';
            });

          as gates podem ser chamdas em qualquer ponto do sistema, isso possbilita 
          que eu faça esses testes em todas as página que o usuário entrar nisso eu 
          posso remover u adionar recursos com base no retrono das gates criadas.   



        */

        //gate para o sys-admin

        Gate::define('sys-admin',function($user){

         return $user->role === 'sys-admin';

        });

        //gate para o client-admin

        Gate::define('client-admin',function($user){

         return $user->role === 'client-admin';

        });

        //gate para o client-user

        Gate::define('client-user',function($user){

         return $user->role === 'client-user';

        });
        
        
        
        
    }
}
