<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bundle;
use App\Models\Queue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;


class QueuesDisplayController extends Controller
{
    public function index(){
        $data=[
            'subtitle'=>'Apresentador de filas',
            'credential'=>session()->get('queues_display_credential')
        ];

        return view('ticket_display.display',$data);
    }

    public function credentials(){
         
         $data=[
            'subtitle'=>'Apresentador de filas'
         ];

         return view('ticket_display.credential_frm',$data);
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


        $result = Bundle::where('credential_username',$request->credential_username)->first();

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
        

        session()->put('queues_display_credential',$request->credential_username);

        return redirect()->route('queues.display');
    }








    public function getBundleData(Request $request){

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



        //se nenhum  grupo de filas for encontrado, retorna essa mensagem  
        if(!$bundle) {
             return response()->json(
                [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Nenhum grupo de filas encontrado'
                
                ],404);
        }

        //pegando todas as filas e o primeiro ticket com o status 'called'
        $queues = Queue::whereIn('hash_code',json_decode($bundle->queues))
                  ->where('deleted_at',null)
                  ->with(['tickets'=>function($query){
                     $query->where('queue_ticket_status','called')
                           ->orderBy('queue_ticket_created_at','desc')
                           ->limit(1);
                  }])->get();
                  
        //retornando dados tratados 
        
        return response()->json([
             'status'=>'success',
             'code'=>200,
             'message' => 'success',
             'data'=>[
                'bundle'=>$bundle,
                'queues'=>$queues
             ]    
        ]);
        
         
         
        
    }
    

}
