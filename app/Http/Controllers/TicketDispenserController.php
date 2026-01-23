<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
use App\Models\Queue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class TicketDispenserController extends Controller
{
   public function index(){
      
        $data=[
          'subtitle' =>'Dispensador',
          'credential' => session()->get('ticket_dispenser_credential') 
        ];

        return view('ticket_dispenser.dispenser', $data);
      
       
   }

   public function credentials(){

    $data=[
       'subtitle' =>'Dispensador'
    ];
       
    return view('ticket_dispenser.credential_frm',$data);
      
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
      name 
      C0bkdp0HlcfNWUWuWng0QvdoBxLUovYkXC7y9G1OVNKp9vCOSHWMSTjNZ7QxnvH6
      pass
      9U46KuXGyqcsF9T6ScVlauMHM9YFRASYMFsSI1xXE1CNtrOO7OoiZMrnx96qOYi3

      */




      /*
       


      */
      session()->put('ticket_dispenser_credential', $request->credential_username);

      return redirect()->route('dispenser');
      
       

      }


      public function getBundleData(Request $request){

         //validando se a crencial veio no post
         if($request->has('credential')){
           try{
             $credential = Crypt::decrypt($request->credential);

              }catch(\Exception $e){

                   return response()->json([
                     'status'=>'error',
                     'code'=>'400',
                     'message' =>'Credencial inválida'
                   ]);

             }
         }else{

             return response()->json([
                     'status'=>'error',
                     'code'=>'400',
                     'message' =>'Credencial não informada'
                   ]);
         }



         

         //validação dos dados do formulario

         $request->validate([
            'credential'=>'required|string|size:64'
         ],[
            'credential.required'=>'A credencial é obrigatória',
            'credential.size'=>'A credencial deve ter 64 caracteres'
         ]);

         $credential = $request->credential;
        
        /*
         preparando um json com os dados dos grupos pra fazer o retorno 
        */

         $bundle = Bundle::where('credential_username',$credential)->first();

         if(!$bundle){
            return response()->json([
               'status'=>'error',
               'code'=>'404',
               'message' =>'Grupo não encontrado'
            ]);
         }

         //pegando os dados de todas as filas que fazem parte do grupo 
         //para que uma fila possa aparecer no dispensador ela deve estar 
         //ativa e com o ampo deleted_at nulo , essas duas condições servem parara garantir 
         //que nenehum cliente vai ficar esperando por um atadimento que não existe 

         $queues = Queue::whereIn('hash_code',json_decode($bundle->queues))
                          ->where('status','active')
                          ->where('deleted_at',null)
                          ->get();

         if($queues->isEmpty()){

            return response()->json([
               'status'=>'error',
               'code'=>'404',
               'message' =>'Não existem filas atva nesse grupo'
            ]);
        }

        //preparando os dados parar o retorno 

            return response()->json([

               'status'=>'success',
               'code'=>'200',
               'message' =>'success',
               'queues' => $queues->map(function($queue){

                  return[
                     'id'  =>   $queue->id,
                     'name'=> $queue->name,
                     'description' => $queue->description,
                     'service' => $queue->service_name,
                     'desk' => $queue->service_desk,
                     'prefix' => $queue->queue_prefix,
                     'digits' => $queue->queue_total_digits,
                     'colors' => json_decode($queue->queue_colors,true),
                     'hash_code' => $queue->hash_code
                     ];
               }),

            ],
            200,
            ['Content-Type'=>'application/json'],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,

            
            );
        
         
        


      }
   }

