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

            //pegando os dados do ultimo ticket chamado
            $lastTicket = $queue->tickets()
                        ->where('queue_ticket_status','called')
                        ->where('deleted_at',null)
                        ->orderBy('id','desc')
                        ->first();

            //pegando os dados do proximo ticket da fila
            $nextTicket = $queue->tickets()
                        ->where('queue_ticket_status','waiting')
                        ->where('deleted_at',null)
                        ->orderBy('id','asc')
                        ->first();


           



            //Pegando os dados da empresa pra colocar na pagina 
            $company = Company::where('id',auth()->user()->company->id)
                              ->select('company_name')
                              ->first();

            

            $data=[
                 'subtitle' =>'Detalhes da fila',
                 'company'  => $company,
                 'queue'    => $queue,
                 'nextTicket'=>$nextTicket,
                 'lastTicket'=>$lastTicket
            ];

            return view("ticket_caller.queue_details",$data);





    }

    public function queueCaller($queue_id,$ticket_id,$status){

         //verificando se os dados foram realmente preenchidos 
         try{

            $queue_id = Crypt::decrypt($queue_id);
            $ticket_id = Crypt::decrypt($ticket_id);

         }catch(\Exception $e){

             return redirect()->route('caller.home');

         }

        

         //pegando o ticket pelo id
         // a consulta tem a função first porque podem ter 
         //varios tickets com esses mesmos atrbutos mas o retronado será sempre o primeiro 

         $queue = Queue::with('tickets')
                      ->where('id',$queue_id)
                      ->where('id_company',auth()->user()->id_company)
                      ->first();

          if(!$queue){
             return redirect()->route('caller.home');
          }

          
          //pegando o ticket 

          $ticket = $queue->tickets()
                    ->where('id',$ticket_id)
                    ->where('deleted_at',null)
                    ->first();

         
          if(!$ticket){
             return redirect()->route('caller.home');
          }
          
          //verificando se o status que veio na requisição é valido 
          //dentro do array eu coloco os 3 status que eu espero 

          $validStatus = ['called','not_attended','dismissed'];
         
          //aqui eu testo se dentro da variavel $status 
          //existe um dos valores que eu pre-difini dentro do array 
          //se não tiver o usuario volta pra home e a calseificação não acontece
           
          if(!in_array($status,$validStatus)){
             return redirect()->route('caller.home');
          }




          //Mudando o status para called
          $ticket->queue_ticket_status = $status;
          $ticket->queue_ticket_called_at = now();
          $ticket->updated_at = now();
          $ticket->queue_ticket_called_by = auth()->user()->email;
          $ticket->save();

          return  redirect()->route('caller.queue.details',['id'=>Crypt::encrypt($queue->id)]);
    }

    public function massiveDismiss($queue_id)
    {

        try{
            
            $queue_id = Crypt::decrypt($queue_id);

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
                 ->where('id',$queue_id)
                 ->where('id_company',auth()->user()->id_company)
                 ->first();

                 if(!$queue){
                    return redirect()->route('caller.home');
                 }


                 $data=[
                    'subtitle'=>'Resposta massiva',
                    'queue'=>$queue
                 ];

                 return view('ticket_caller.massive_dismissed',$data);




      

    }

   


}
