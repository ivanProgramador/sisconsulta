<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PhpParser\Node\Stmt\TryCatch;

use function Symfony\Component\Clock\now;

class AuthController extends Controller
{
    public function login(){

         //se o cliente errar a validação a variavel erros recebe o valor do erro
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
                   ->where('status','active')
                   ->whereNull('deleted_at')
                   ->where(function($query){
                      $query->whereNull('blocked_until')
                            ->orWhere('blocked_until','<',now()); 
                   })->first();

       //verificando se o usuario existe e se a senha confere com o usuario informado
       
       if($user && Hash::check(trim($request->password), $user->password)){

           //verificando se o usuario pertence a uma empresa que está ativa

           if($user->role !== 'sys-admin' && ($user->company->deleted_at || $user->company->status != 'active' )){

             return redirect()
                    ->back()
                    ->withInput()
                    ->with('server_error','Login invalido.');

           }        
           

            
           //o login será executado por uma outra função 

           $this->loginUser($user);

           //redirecionando
           //caso o usuario que solicitou o login seja o administrador do sistema(master) 
           //essa função vai direcionar para uma pagina administrativa diferente  

           if($user->role === 'sys-admin'){

              return redirect()->route('admin.home');

           }else{

             return redirect()->route('home');

           }
           
          
           
          
          
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

    public function changePassword():View{
          return view('auth/change_password_frm',['subtitle'=>'Alterar Senha']);
    }

    public function changePasswordSubmit(Request $request){

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

         session()->flush();
         
        //limpando os dados da sessão 
        session()->invalidate();

        //regenerando o token da sessão
        session()->regenerateToken();

        //redirecionando para a pagina de login
        return redirect()->route('login');
    }

    public function concludeRegistration($code)
    {
      
      //testando se o codigo é valido 
       try{
            $code = Crypt::decrypt($code);

         }catch(DecryptException $e){

          return redirect()->route('login');
       }

       //pegando o codigo e o usuario 

       $user = User::where('code',$code)->first();

       if(!$user){
           return redirect()->route('login');
       }

       //verificando se o codigo expirou 

       if($user->code_expiration < now()){
          
         $user->company()->forceDelete();

         $user->forceDelete();
         
         return redirect()->route('login');


       }else{
           
          //colocando as variaveis de controle dentro da sessão 
          //depois que o usuario clica no link ele deve ser direcionado 
          //para uma rota onde ele va definir a senha de acesso
          //as variveis de sessão servem pra verificar se esse cliente realmente 
          //deve ir pra essa pagina
          
          session()->put('define_password',true);
          session()->put('user_id',Crypt::encrypt($user->id));

          return redirect()->route('define.password');

       }
    }


    public function definePassword(){

       /*
         antes de iniciar um processo de definição de senha eu tenho que checar se realmente estou dentro desse processo,
         parrar fazer isso eu tenho que conseultarrr as variaveis de sessão que recebem valores asim que porcesso de definicção 
         de senha é iniciado pelo usuário 

         Se as variáveis estiverem vazias se trata de uma tentativa de acesso direto então o usuarios sera redirecionado para o login 
       */
      if(!session()->has('define_password') ||  !session()->has('user_id')){
         return redirect()->route('login');
      }
      
      $data=[
         'subtitle'=>'Definir senha',
         'user' => User::find(Crypt::decrypt(session()->get('user_id'))) 
      ];

      return view('auth.define_password_frm',$data);




    }

    public function definePasswordSubmit(Request $request){

      /*
          dd(
           Crypt::decrypt(session()->get('user_id')),
           session_get('define_password'),
           $request()->all()
           );
      */

      if(!session()->has('define_password') ||  !session()->has('user_id')){
         return redirect()->route('login');
      }

      $request->validate(
            [
               'password' => [
                  'required',
                  'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}$/'
               ],

               'password_confirmation' => [
                  'required',
                  'same:password'
               ]
            ],
            [
               'password.required' => 'A senha é obrigatória',

               'password.regex' =>
                  'A senha deve conter entre 6 e 16 caracteres, uma letra minúscula, uma maiúscula e um número',

               'password_confirmation.same' =>
                  'As duas senhas não são iguais'
            ]
      );

       //pegando o usuario 

       $user = User::find(Crypt::decrypt(session()->get('user_id')));

       //se não tiver usuario 

       if(!$user){
         return redirect()->route('login');
      }

      //atualizar o campo senha com os dados passados no formulario 
      //e limpar todos os campos 

      $user->password = bcrypt($request->password);
      $user->code = null;
      $user->code_expiration = null;
      $user->save();

       return redirect()->route('define.password.success');

      
    }

    public function definePassworSuccess(){

       if(!session()->has('define_password') ||  !session()->has('user_id')){
         return redirect()->route('login');
      }

      //quando o usuario chega nesse ponto eu não preciso mais das variavesi de controle de sessão 
      //então eu vou limpar elas 
      
      session()->forget('define_password');
      session()->forget('user_id');

      return view('auth.define_password_success',['subtitle'=>'Success']);
       
    }




}
