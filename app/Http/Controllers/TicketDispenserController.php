<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
use App\Models\Queue;
use App\Models\QueueTicket;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use PhpParser\Node\Stmt\TryCatch;

use function Symfony\Component\Clock\now;

class TicketDispenserController extends Controller
{
    public function index()
    {
       
        $data=[
            'subtitle' => 'Dispensador de Tickets',
            'credential' => session()->get('ticket_dispenser_credential') 
        ];

        return view('ticket_dispenser.dispenser',$data);
    }


    public function credentials(){

        $data=[
            'subtitle' => 'Formulario dispensador'
        ];

        return view('ticket_dispenser.credential_frm',$data);
    }


    public function credentialsSubmit(Request $request){

        $request->validate(
            [
                'credential_username'=>'required',
                'credential_password'=>'required'
            ],
            [
                'credential_username.required'=>'A credencial username é obrigatória',
                'credential_username.size'=>'A credencial username deve ter 64 caracteres',
                'credential_password.required'=>'A credencial userpassword é obrigatória',
                'credential_password.required'=>'A credencial userpassword deve ter 64 caracteres'
            ]
        );

        $result = Bundle::where('credential_username',$request->credential_username)
                       ->first();

        if(!$result){
            return redirect()
                   ->back()
                   ->withInput()
                   ->with(['server_error'=>'Credenciais invalidas.']);
        }

        if(!Hash::check($request->credential_password, $result->credential_password)){
             return redirect()
                   ->back()
                   ->withInput()
                   ->with(['server_error'=>'Credenciais invalidas.']);

        }
        

        session()->put('ticket_dispenser_credential',$request->credential_username);

        return redirect()->route('dispenser');
    }

    

    public function getBundleData(Request $request)
    {
        if($request->has('credential')){

            Try{

                $credential = Crypt::decrypt($request->credential);

            }catch(\Exception $e){
                
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Credencial de grupo invalida'
                    
                    ]);
            }
        }else{
             return response()->json(
                [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'A credencial é obrigatória'
                
                ],404);
        }



        $bundle = Bundle::where('credential_username', $credential)->first();



        //se neneui  grupo de filas for encontrado, retorna essa mensagem  
        if(!$bundle) {
             return response()->json(
                [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Nenhum grupo de filas encontrado'
                
                ],404);
        }


        //pegando as informações do grupo de filas que será exibido
        $queues = Queue::whereIn('hash_code',json_decode($bundle->queues))
                        ->where('status','active')
                        ->where('deleted_at',null)
                        ->get();

        //se o grupos de filas não tiver filas ativas ou associadas retorna essa mensagem
        if($queues->isEmpty()){
            return response()->json(
                [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Nenhuma fila ativa encontrada ou associada ao grupo'
                
                ],404);
        }

        //preparando o retorno dos dados do grupo de filas e suas filas associadas

        return response()->json(
            //a funcão que converte a resposta em json recebe 2 parametros
            //abaixo eu tenho esse primeiro que retorna os dados que vieram apos a consulta

            [
              'status'=>'success',
              'code'=>200,
              'queues'=>$queues->map(function($queue){
                 return
                 [
                       'id'=>$queue->id,
                       'name'=>$queue->name,
                       'description'=>$queue->description,
                       'service'=>$queue->service_name,
                       'desk'=>$queue->service_desk,
                       'prefix'=>$queue->queue_prefix,
                       'digits'=>$queue->queue_total_digits,
                       'colors'=>json_decode($queue->queue_colors, true),
                       'hash_code'=> $queue->hash_code
                 ];
              })
            ],
            //nesse segundo parámetro são os dados de cabeçalho da requisição
            //como codigo http de retorno e a identificação do tipo de conteudo
            //que eu estou retornando (json nesse caso) as constantes
            // JSON_UNESCAPED_UNICODE e JSON_PRETTY_PRINT servem para formatar
            //o json de forma legível e sem escapar caracteres unicode  
            //como acentos e caracteres especiais 
            200,
            ['Content-Type'=>'application/json'],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT      


        );





        

        


        
    }

    public function getTicket(Request $request)
    {
      
       //testando se existe uma hash na requisição

      if(!$request->has('hash_code')){

           return response()->json(
             [
                'status'=>'error',
                'code'=>404,
                'message'=>'A hash e obrigatória ',
                'hash_code'=> $request->hash_code,

             ]
           );
        }

       //testando se a hash que existe é valida 
       $queue  = Queue::where('hash_code',$request->hash_code)
                      ->where('status','active')
                      ->where('deleted_at',null)
                      ->get();

        if($queue->isEmpty()){
             
            return response()->json(
             [
                'status'=>'error',
                'code'=>404,
                'message'=>'Fila não encontrada ou vazia'

             ]
           );

           // pegando a primeira fila do resultado 
           $queue = $queue->first();

           //pegando o ticket mais recente registrado 
           $last_ticket = $queue->tickets()->lastest()->first();

           //criando um novo ticket 
           $newTicketNumber = (!$last_ticket)? 1 : $last_ticket->queue_ticket_number + 1;

           $newTicket = new QueueTicket();
           $newTicket->id_queue = $queue->id;
           $newTicket->queue_ticket_number = $newTicketNumber;
           $newTicket->queue_ticket_created_at = now();
           $newTicket->queue_ticket_status = 'waiting';
           $newTicket->save();

           //retornando os dados do ticket como json
           
           return response()->json([
             'status'=>'success',
             'code' =>200,
             'message'=>'success',
             'ticket'=>[
                'queue_service'=>$queue->service_name,
                'service_desk'=>$queue_service_desk,
                'prefix'=>$queue->queue_prefix,
                'number'=>str_pad($newTicketNumber,$queue->queue_total_digits,'0',STR_PAD_LEFT),
                'created_at' => now()
             ]
           ]);














        }
       


    }



    


}
