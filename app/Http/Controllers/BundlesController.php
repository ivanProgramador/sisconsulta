<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


}
