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

   }
}
