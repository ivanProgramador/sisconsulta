<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BundlesController extends Controller
{
    public function index()
    {
        $data =[
            'subtitle'=>'Bundles',
            'bundles' => auth()->user()->company->bundles()->get(),

        ];

         
        return view('bundles.home',$data);
    }

    public function createBundles(){

         $data =[
            'subtitle'=>'Bundles',
            'queues' => auth()->user()->company->queues()->get()  
        ];

        return view('bundles.create_bundle_frm',$data);



    }

    public function createBundlesSubmit(Request $request){
         
        
        $request->validate([
            'bundle_name'=>'required|string|min:5|max:100',
            'credential_username' =>'required|string:size:64',
            'credential_password' =>'required|string:size:64',
            'queues_list' => 'required'
           ],[
            'bundle_name.required'=>'o nome do grupo é obrigatório ',
            'bundle_name.string' =>'o nome precisar ser um texto ',
            'bundle_name.min' =>'O nome do grupo deve ter no minimo 5 caracteres',
            'bundle_name.max' => 'O nome pode ter no maximo 100 caracteres',
            'credential_username.required'=>'a credencial do nome é obrigatória',
            'credential_username.string'=>'a credencial do nome deve ser um texto',
            'credential_username.size' => 'o usuario da credencial deve ter pelo menos 64 caracteres',
            'credential_password.required'=>'a senha é obrigatoria',
            'credential_password.string'=>'a senha deve ser um texto',
            'credential_password.size' => 'a senha deve ter pelo menos 64 caracteres'
         ]);

         //verificando se a lista de filas é um json valido e se não esta vazio
         if(
            empty($request->queues_list) ||
            json_decode($request->queues_list) == null || 
            empty(json_decode($request->queues_list)) 
           ){
             return redirect()->back()->withInput()->withErrors(['queues_list' =>'A lista de filas é obrigatória ']);
           }
           
         //verificando se  nome da fila ja existe na base de dados
         
         $bundle_name = $request->bundle_name; 
         $bundleExists = auth()->user()->company->bundles()
                       ->where('name',$bundle_name)
                       ->exists();
         
        if($bundleExists){
            return redirect()->back()->withInput()->withErrors(['bundle_name'=>'já existe um grupo com esse nome ']);
        }
             
        dd($request->all());
    }


    public function generateCredentialValue($num_chars){

        //validando o pedido de crenciais 
        if(!is_numeric($num_chars) || $num_chars <=0 || $num_chars > 64 ){
            return response()->json(['error'=>'Numero invalido de caracteres']);
        }

        //gerando um consjunto aleatorio de caracteres "hash"

        $credentialValue = Str::random($num_chars);

        while(Bundle::where('credential_username',$credentialValue)->exists()){
            $credentialValue = Str::random($num_chars);
        }
        
        return response()->json(['hash'=>$credentialValue]);
        

    }


}
