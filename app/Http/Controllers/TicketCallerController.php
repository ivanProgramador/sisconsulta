<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
                 ->withTrashed()
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

           

                 
        $data = [
            'subtitle' => 'Chamadas',
            'company'  =>  $company,
            'queues'   =>  $queues
        ];
        
        return view('ticket_caller.home',$data);
    }

    public function queueDetails($id){
        
    try{
       
      $id = Crypt::decrypt($id);

      }catch(\Exception $e){

      return redirect()->route('caller.home');

    }

    //pegando os detalhes dos tickets associados a fila
        $queue = Queue::with('tickets')
                 ->withTrashed()
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
                 ->where('id',$id)
                 ->where('id_company',auth()->user()->id_company)
                 ->first();

                 if(!$queue){
                    return redirect()->route('caller.home');
                 }


            $company = Company::where('id',auth()->user()->company->id)
                              ->select('company_name')
                              ->first();

            $data=[
                 'subtitle' =>'Detalhes da fila',
                 'company'  => $company,
                 'queue'    => $queue
            ];

            dd($data);





    }
}
