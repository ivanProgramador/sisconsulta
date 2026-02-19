<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueuesDisplayController extends Controller
{
    public function index(){
        // apresentar a tela de exibição das filas 
        echo"Exibir filas";
    }

    public function credentials(){
         
         $data=[
            'subtitle'=>'Apresentador de filas'
         ];

         return view('ticket_display.credential_frm',$data);
    }

    public function credentialsSubmit(){
        // apresentar a tela de exibição das filas 
        echo"Submissão de credenciais";
    }

    public function getBundleData(Request $request){

     //pegando os dados da fila de forma assincrona
     echo"Dados do grupo de filas"; 

    }
    

}
