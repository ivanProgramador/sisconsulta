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
            'bundle_name'=>'required'
         ]);
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
