<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {

         //se o cliente errar a validação a avarivel erros recebe o valor do erro
         //então quando o formulario voltar eu vou mandar os erros de volta pra ele ver
         if(session('errors')){
             dd(session()->all(),old());
         }
          
          return view('auth/login_frm',['subtitle'=>'Login'] );
    }

    public function loginSubmit(Request $request){

       $request->validate(
           [
             'username'=>'required|email',
             'password'=>'required|regex:/([a-z]+:\/\/)([a-z0-9\-_]+\.[a-z0-9-\_\.]+)(\/[a-z0-9\-_\/]+)*/'
           ],[
             'username.required'=>'O usuario é obrigatório',
             'username.email'=>'O usuário deve ter uma e-mail valido',
             'password.required'=>'A senha é obrigatória',
             'password.regex'=>' a senha deve conter de 6 a 16 caracteres, ter uma maiuscula, uma minuscula e um algarismo'
           ]
       );

       echo'passou pela validação !';
       
    }

    public  function logout(){
        echo "logout ";
    }
}
