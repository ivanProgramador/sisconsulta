<?php

namespace App\Http\Controllers;
use App\Mail\NewClientAdminMail;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index(){

         $data=[

             'subtitle' => 'Administração',
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

        $code = Str::random(64);

        
        try{
            Mail::to($request->admin_email)
                ->send(new NewClientAdminMail($code, $request->company_name));

        }catch(\Exception $e){

         dd($e);

          return redirect()
                   ->back()
                   ->withInput()
                   ->with('server_error','Erro ao enviar o e-mail por favor tente de novo');
         
        }

        //definindo o logo da compania 
        if($request->hasFile('company_logo')){

            //criando um nome unico para o aqruivo do logo 
            
            $fileName = Str::uuid(). '.' . $request->company_logo->extension();

            //gavando a imagem do logo em uma pasta publica

            $request->company_logo->move(public_path('assets/images/company_logos/'), $fileName);


            $company_logo = $fileName;
             
        }else{
             
             //sem logo 
             $company_logo ='_no_logo.png';
        }

        //criando a compania (cliente)

        $company = new Company();
        $company->company_name = $request->company_name;
        $company->company_logo = $company_logo;
        $company->uuid = Str::uuid();
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->status = $request->status;
        $company->save();

        //pegando o id da compania

        $id_company = $company->id;

        //criando o usuario administrador que pertence a empresa 
        
        $user = new User();
        $user->email = $request->admin_email;
        $user->id_company = $id_company;
        $user->role = 'client-admin';
        $user->code = $code;
        $user->code_expiration = now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION'));
        $user->status = $request->status === 'active' ? 1 : 0;
        $user->save();

        //mostrando a pagina de sucesso
        
        $data=[
            'subtitle' => 'Sucesso',
            'company_name' => $request->company_name,
            'admin_email' => $request->admin_email
        ];

        return view('admin.create_company_success',$data);
    }

    public function companyDetails($id){

        //desencriptando o id  parar poder usa-lo na consulta  

        try{

          $id = Crypt::decrypt($id);

        }catch(\Exception $e){

           return redirect()->route('admin.home');
        }


        //consultando os dados da empresa 

        $company = Company::find($id);


        if(!$company){
             return redirect()->route('admin.home');
        }

        //consultando dados dos usuarios associados a ela  

        $users = User::where('id_company',$id)->get(); 

        //pegando as filas e os tckets da empresa por status 

        $queues = Queue::withTrashed('tickets')
                 ->where('id_company',$id)
                 ->withCount([
                    'tickets as total_tickets'=> function($query){
                        $query->where('deleted_at',null);
                    },
                    'tickets as total_waiting' => function($query){
                        $query->where('queue_ticket_status','waiting');
                    },
                    'tickets as total_called' => function($query){
                        $query->where('queue_ticket_status','called');
                    },
                    'tickets as total_not_attended' => function($query){
                        $query->where('queue_ticket_status','not_attended');
                    },
                    'tickets as total_dismissed' => function($query){
                        $query->where('queue_ticket_status','dismissed');
                    }
                 ])->get();

                 $data=[
                    'subtitle' => 'Detalhes do cliente',
                    'company'  =>  $company,
                    'queues'   =>  $queues
                 ];

                 return view('admin.company_details',$data);}


    public function CompanyControlAccess($id){

       // desencriptando o id recebido
       
       try{
          $id = Crypt::decrypt($id);
        }catch(\Exception $e){
           return redirect()->route('admin.home');
        }

        //consultando os dados da companhia 
        $company = Company::find($id);

        if(!$company){
             return redirect()->route('admin.home');
        }

        $data=[
            'subtitle'=>'Controle de acesso',
             'company' => $company
        ];

        

        return view('admin.company_control_access',$data);
    }


    public function companyControlAccessSubmit(Request $request)
    {
        // testando se os dados sumetidos são validos pra atualizar o status da empresa
        
        if(!$request->has('id') || !$request->has('action')){
            return redirect('admin.home');
        }

        // desencriptando o id recebido
       
       try{
          $id = Crypt::decrypt($request->id);
        }catch(\Exception $e){
           return redirect()->route('admin.home');
        }

        $action = $request->action;

        //pegando os dados da companhia

        $company = Company::withTrashed()->find($id);

        if(!$company){
            return redirect()->route('admin.home');
        }

        //atualizando o status

        if($action === 'disable'){

            $company->status = 'inactive';
            $company->save();

        }else if($action ==='enable'){

             $company->status = 'active';
            $company->save();
        }

        return redirect()->route('admin.home');
    }


    public function companyDelete($id){

         // desencriptando o id recebido
       
            try{
                $id = Crypt::decrypt($id);
                }catch(\Exception $e){
                return redirect()->route('admin.home');
                }

                //consultando os dados da companhia 
                $company = Company::find($id);

                if(!$company){
                    return redirect()->route('admin.home');
            }

            $data=[
                 'subtitle'=>'Excluir cliente',
                 'company' => $company
            ];

            return view('admin.delete_company_confirm',$data);
        }

      public function deleteCompanyConfirm($id)
      {

              // desencriptando o id recebido
       
            try{
                $id = Crypt::decrypt($id);
                }catch(\Exception $e){
                return redirect()->route('admin.home');
                }

                //consultando os dados da companhia 
                $company = Company::find($id);

                if(!$company){
                    return redirect()->route('admin.home');
             }

             //executando um soft delete 
             $company->delete(); 

             return redirect()->route('admin.home');          
      }

      public function restoreCompany($id)
      {

            // desencriptando o id recebido
       
            try{
                $id = Crypt::decrypt($id);
                }catch(\Exception $e){
                return redirect()->route('admin.home');
                }

                //consultando os dados da companhia 
                $company = Company::withTrashed()->find($id);

                if(!$company){
                    return redirect()->route('admin.home');
             }

             //recuperando
             $company->restore(); 

             return redirect()->route('admin.home');          
         
      }

      public function statiscs(){

          $data = [
              'subtitle' => 'Estatiscas',
              'statsCompanies' => $this->getActiveAndInactiveCompany(),
              'statsUsersByState' => $this->getUsersByState()
          ];
          

          return view('admin.statiscs',$data);
      }

      //função privada que vai pegar os dados e empresas ativas e inativas 

      private function getActiveAndInactiveCompany(){
          
         $totalCompanies = Company::withTrashed()->count();

         $totalActive = Company::where('status','active')->whereNull('deleted_at')->count();

         $totalInactive = Company::withTrashed()
                        ->where(function($query){
                            $query->where('status','inactive')->orWhereNotNull('deleted_at');
                        })->count();

          return [
             'total'  =>  $totalCompanies,
             'active' => $totalActive,
             'inactive' => $totalInactive
          ];
      }

      private function getUsersByState(){
          
          $totalUsers = User::withTrashed()->where('role','!=','sys-admin')->count();

          $totalUsersActive = User::where('active',1)->where('role','!=','sys-admin')->count();

          $totalUsersInactive = User::withTrashed()->where(function($query){
              $query->where('active',0)
                    ->orWhereNull('deleted_at');
          })->where('role','!=','sys-admin')->count();

          $totalUsersBlocked = User::where('blocked_until','>',now())->where('role','!=','sys-admin')->count();

          $totalUsersWithoutPassword = User::whereNull('password')->where('role','!=','sys-admin')->count();

          return[
             'total'=> $totalUsers,
             'active'=>$totalUsersActive,
             'inactive'=>$totalUsersInactive,
             'blocked'=>$totalUsersBlocked,
             'without_password'=> $totalUsersWithoutPassword
          ];


      }



}
