<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Queue;
use Illuminate\Http\Request;

class TicketCallerController extends Controller
{
    public function index(){

       //colocando dados da empresa para o retorno na tela 

       $company = Company::where('id',auth()->user()->company->id)
                         ->select('company_name')
                         ->first();

       //consultando as informações necessárias para chamar um ticket
       //o model queues traz todas as filas de forma geral 
       //como existe uma relação entre fila e tickets os tickets vem junto 

       $queues = Queue::with('tickets')
                 ->withCount([
                    'tickets as total_tickets'=> function($query){
                        $query->where('deleted_at',null);
                    },
                    'tickets as total_waiting' => function($query){
                        $query->where('queue_ticket_status','waiting');
                    },
                    'tickets as total_called' => function($query){
                        $query->where('queue_ticket_status','called');
                    },
                    'tickets as total_not_attended' => function($query){
                        $query->where('queue_ticket_status','not_attended');
                    },
                    'tickets as total_dismissed' => function($query){
                        $query->where('queue_ticket_status','dismissed');
                    }
                 ])
                 ->where('id_company',auth()->user()->id_company)
                 ->orderBy('id','desc')
                 ->get();

           dd($queues);

                 
        $data = [
            'subtitle' => 'Chamadas',
            'company'  =>  $company,
            'queues'   =>  $queues
        ];
        
        return view('ticket_caller.home',$data);
    }
}
