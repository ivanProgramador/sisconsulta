<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Symfony\Component\Clock\now;

class AuthController extends Controller
{
    public function login()
    {

         //se o cliente errar a validação a avarivel erros recebe o valor do erro
         //então quando o formulario voltar eu vou mandar os erros de volta pra ele ver
         
         
          
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

       $user = User::where('email',trim($request->username))
                   ->where('active',true)
                   ->whereNull('deleted_at')
                   ->where(function($query){
                      $query->whereNull('bloqued_until')
                            ->orWhere('bloqued_until','<',now()); 
                   })->first();

       //verificando se o usuario existe e se a senha confere com o usuario informado
       
       if($user && Hash::check(trim($request->password), $user->password)){
            
           //logado com sucesso 
           
           auth()->login($user);

           return redirect()->route('home');
          
       }else{

           //falhou
           
           die('login invalido');
       }

       



       echo'passou pela validação !';
       
    }

    public  function logout(){
        echo "logout ";
    }
}
