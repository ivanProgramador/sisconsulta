<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketDispenserController extends Controller
{
   public function index(){
      
        echo 'dispensador de tickets';
   }

   public function credentials(){
       
    return view('ticket_dispenser.credential_frm');
      
   }

   public function credentialsSubmit(Request $request){

      //validação dos dados do formulario

      $request->validate([
         'credential_username'=>'required|string|size:64',
         'credential_password'=>'required|string|size:64'
      ],[
         'credential_username.required'=>'O nome de usuário é obrigatório',
         'credential_username.size'=>'O nome de usuário deve ter 64 caracteres',
         'credential_password.required'=>'A senha é obrigatória',
         'credential_password.size'=>'A senha deve ter 64 caracteres'
      ]);

      /*
         Exemplo de credenciais válidas para testes

        95stCQyWgTnFKvomcXPtG0ou5NSahvbphfWfWEEgab4xMxbGt73zX89Nfe78jebl
        Np0YudcycEVfjpU3CRxbES857HcBGPnkInAJjK8JXWXEe2kJT6AxOylZpNTzedFu
      
      
      */

      dd($request->all());




      
      



   }
}
