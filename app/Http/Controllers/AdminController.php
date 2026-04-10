<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class AdminController extends Controller
{
    public function index()
    {

         $data=[

             'subtitle' => 'Asministração',
             'clients'  => $this->getClientList()
         ];
         return view('admin.home',$data);

    }
     private function getClientList(){
        //pegando todas as empresas cadastradas

        return Company::withTrashed()->withCount('users')->get();
        
    }

    public function createCompany(){
        $data=[
             'subtitle'=>'Novo Cliente'
        ];

        return view('admin.create_company_frm',$data);
    }

    public function createCompanySubmit(Request $request){

        dd($request->all());
    }

    
}
