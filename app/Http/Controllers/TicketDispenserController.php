<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketDispenserController extends Controller
{
   public function index(){
      
        echo 'dispensador de tickets';
   }

   public function credentials(){
       echo 'formulário de autenticação';
      
   }

   public function credentialsSubmit(Request $request){

   }
}
