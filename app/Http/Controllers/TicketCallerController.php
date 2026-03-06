<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketCallerController extends Controller
{
    public function index(){
        $data = [
            'subtitle' => 'Chamadas'
        ];
        
        return view('ticket_caller.home',$data);
    }
}
