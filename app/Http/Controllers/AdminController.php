<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;

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

        //validando formulário 

        $request->validate(
            [
                'company_logo'=>'image|mimes:jpeg,png|dimensions:width:200,heigh:200',
                'company_name'=>'required|max:100 |unique:companies,company_name',
                'address'=>'required|max:255',
                'phone'=>'required|max:20',
                'email'=>'required|email|max:255',
                'status'=>'required|in:active,inactive',
                'admin_email'=>'required|email|max:50|unique:users,email',
            ],
            [
                'company_logo.image' => 'O arquivo enviado não é uma imagem válida',
                'company_logo.mimes' => 'A imagem deve estar no formato JPEG ou PNG',
                'company_logo.dimensions' => 'A imagem deve ter extamente 200x200 pixeis',
                'company_name.required'=>'O nome da empresa é obrigatório',
                'company_name.max'=>'O nome da empresa não pode exceder 100 caracteres',
                'company_name.unique'=>'Já existe um empresa com esse nome',
                'address.required'=>'O endereço é obrigatório',
                'address.max'=>'O endereço não pode exceder 255 caracteres',
                'phone.required'=>'O numero de telefone é obrigatório',
                'phone.max'=>'O numero de telefone não pode exceder 20 caracteres',
                'email.required'=>'O e-mail é obrigatório',
                'email.email'=>'deve ser um e-mail valido',
                'email.max'=>'O e-mail não pode execeder 100 caracteres',
                'status.required'=>'O status é obrigatório',
                'status.in'=>'status inválido',
                'admin_email.required'=>'O e-mail do administrador é obrigatório',
                'admin_email.email'=>'O e-mail do administrador deve ser uma endereço valido',
                'admin_email.max'=>'O e-mail do administrador não deve passar dos 50 caracteres',
                'admin_email.unique'=>'Esse e-mail já existe',
            ]
        );

        
        try{
            //enviando um e-mail de teste para o cliente
             
            Mail::raw('Email de teste (corpo do e-mail)',function($message){
                $message->to('admin@teste.com')->subject('email de teste');
            });
            
            echo'success';

        }catch(\Exception $e){

        }

        dd($request->all());
    }

    
}
