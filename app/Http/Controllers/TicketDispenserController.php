<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
use Illuminate\Support\Facades\Hash;

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

      //validando credenciais recebidas 

      //testando hash do usuario

      $result = Bundle::where('credential_username', $request->credential_username)->firstOrFail();

      if(!$result){
         return back()->withInput()->withErrors(['server_error'=>'Credenciais inválidas. Por favor, tente novamente.']);
      }

      //testando se a senha bate com o hash
      if(!Hash::check($request->credential_password, $result->credential_password)){
         return back()->withInput()->withErrors(['server_error'=>'Credenciais inválidas. Por favor, tente novamente.']);
      }

      //se chegou aqui, as credenciais estão ok
      //colocvando as variaveis de sessão
      /*
       


      */
      session()->put('ticket_dispenser_credential', $request->credential_username);

      return redirect()->route('dispenser');
      
       

      }


    
                       

     




      
      



   }

