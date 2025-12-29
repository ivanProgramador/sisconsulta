<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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
             'password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}$/'
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
                      $query->whereNull('blocked_until')
                            ->orWhere('blocked_until','<',now()); 
                   })->first();

       //verificando se o usuario existe e se a senha confere com o usuario informado
       
       if($user && Hash::check(trim($request->password), $user->password)){
            
           //o login será executador por uma outra função 

           $this->loginUser($user);

           //redirecionando
           
           return redirect()->route('home');
           
          
          
       }else{

           //falhou
           
              return redirect()
                    ->back()
                    ->withInput()
                    ->with('server_error','Usuário ou senha inválidos');
       }

       



       echo'passou pela validação !';
       
    }

    private function loginUser(User $user){
          
         $user->last_login = now();
         $user->code = null;
         $user->code_expiration = null;
         $user->blocked_until = null;
         $user->save();

         //armazenando os dados do usuario na sessão
         auth()->login($user);
          
        
    }

    public function changePassword():View
    {
          return view('auth/change_password_frm',['subtitle'=>'Alterar Senha']);
    }

     public function changePasswordSubmit(Request $request)
    {

        $request->validate(
           [
             'current_password'=>'required',
             'new_password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}$/|confirmed'
           ],[
             'current_password'=>'a senha atual é obrigatória',
             'new_password'=>'a nova senha é obrigatória',
             'new_password.regex'=>' a nova senha deve conter de 6 a 16 caracteres, ter uma maiuscula, uma minuscula e um algarismo',
             'new_password.confirmed'=>'a nova senha e a confirmação devem ser iguais'
           ]
       );

       //buscando o usuario que esta logado 

       $user = auth()->user();

       //testando se a senha atual esta correta 
       
       if(Hash::check($request->current_password,$user->password)){
         
          //atualizando a senha
          $user->password = Hash::make($request->new_password);
          $user->save();
          
          return redirect()->route('home')->with('message','Senha alterada com sucesso!');

       }else{
           return redirect()->back()->with('server_error','Senha atual invalida !');
       }
    }





    public  function logout(){

        //executando o logout 
        auth()->logout();
         
        //limpando os dados da sessão 
        session()->invalidate();

        //regenerando o token da sessão
        session()->regenerateToken();

        //redirecionando para a pagina de login
        return redirect()->route('login');
    }
}
