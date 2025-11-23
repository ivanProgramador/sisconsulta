<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    public function index():View
    {
        $queues = $this->getQueuesList();

        $data=[
            'subtitle'=>'Home',
            'queues'  => $queues
        ];

       

        return view('main.home',$data);
    }

    private function getQueuesList(){
        
        $companyId = Auth::user()->id_company;
        return Queue::where('id_company',Auth::user()->id_company)
                      ->withCount([

                        //abaixo estou criando uma lista de queries por estado dos tikests relacionados 
                        //a cada fila parar devolver esses dados para a dashboard do gestor
                         
                        
                         'tickets as total_tickets' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->whereNull('deleted_at');
                         },

                         'tickets as total_dismissed' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','dismissed')
                                   ->whereNull('deleted_at');
                         },
                         'tickets as total_not_attended' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','not_attended')
                                   ->whereNull('deleted_at');
                         },
                         'tickets as total_called' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','called')
                                   ->whereNull('deleted_at');
                         },
                         'tickets as total_waiting' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','waiting')
                                   ->whereNull('deleted_at');
                         },
                         


                         
                      ])
                      ->get()->SortBy('name');
    }

    public function queueDetails($id){

        //o id vai chegar aqui encriptado
        //então eu vou decodificar ele 
        //pra depois usar 

        try{
            $id = Crypt::decrypt($id);
        }catch(\Exception $e){
            
            //se a hash do id for alatrada de forma manual ela vai ficar invalida
            //e essa mensagem será devolvida
            abort(403,'ID de fila invalido !');
        }

        // verficando se a fila consultada existe e se essa fila pertence a empresa do usuario que fez a consulta
        
        $queue = Queue::where('id',$id)
               ->where('id_company',Auth::user()->id_company)
               ->firstOrFail();

        if(!$queue){
            abort(404,'Fila não encontrada !');
        }

        //trazendo os tickets da fila 
        
        $tickets = $queue->tickets()->get();

        $data = [
            'subtitle'=> 'Detalhes',
            'queue' => $queue,
            'tickets' => $tickets
        ];

        return view('main.queue_details',$data);

    }
}
