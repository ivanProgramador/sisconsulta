<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\QueueTicket;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class MainController extends Controller
{
    public function index():View {
       
        $data=[
            'subtitle'=>'Home',
            'queues'  => $this->getQueuesList(),
            'companyName' => Auth::user()->company->company_name,
            'companyTotals' => $this->getCompanyTotals()
        ];

       

        return view('main.home',$data);
    }

    private function getCompanyTotals(){

        $companyId = Auth::user()->id_company;

        $totalQueues = Queue::where('id_company',$companyId)->count();

        //pegando todos os tickets ligados a empresa do usuario que esta logado

        $tickets = QueueTicket::whereHas('queue',function($query) use ($companyId){
            $query->where('id_company',$companyId);
        })->get();

        //fazendo o retorno dos dados com base nos tickets buscados acima

        return [
            'total_queues'=>$totalQueues,
            'total_tickets'=>$tickets->count(),
            'total_dismissed'=> $tickets->where('queue_ticket_status','dismissed')->count(),
            'total_not_attended'=> $tickets->where('queue_ticket_status','not_attended')->count(),
            'total_called'=> $tickets->where('queue_ticket_status','called')->count(),
            'total_waiting'=> $tickets->where('queue_ticket_status','waiting')->count()


        ];       
    }

    private function getQueuesList(){
        
        $companyId = Auth::user()->id_company;

        
        return Queue::where('id_company',Auth::user()->id_company)
                      ->withCount([

                        //abaixo estou criando uma lista de queries por estado dos tikests relacionados 
                        //a cada fila parar devolver esses dados para a dashboard do gestor
                         
                        
                         'tickets as total_tickets' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->whereNull('deleted_at');
                         },

                         'tickets as total_dismissed' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','dismissed')
                                   ->whereNull('deleted_at');
                         },
                         'tickets as total_not_attended' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','not_attended')
                                   ->whereNull('deleted_at');
                         },
                         'tickets as total_called' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','called')
                                   ->whereNull('deleted_at');
                         },
                         'tickets as total_waiting' => function($query){
                             $query->whereNotNull('queue_ticket_status')
                                   ->where('queue_ticket_status','waiting')
                                   ->whereNull('deleted_at');
                         },
                         


                         
                      ])
                      ->get()->SortBy('name');
    }

    public function queueDetails($id){

        //o id vai chegar aqui encriptado
        //então eu vou decodificar ele 
        //pra depois usar 

        try{
            $id = Crypt::decrypt($id);
        }catch(\Exception $e){
            
            //se a hash do id for alatrada de forma manual ela vai ficar invalida
            //e essa mensagem será devolvida
            abort(403,'ID de fila invalido !');
        }

        // verficando se a fila consultada existe e se essa fila pertence a empresa do usuario que fez a consulta
        
        $queue = Queue::where('id',$id)
               ->where('id_company',Auth::user()->id_company)
               ->withCount(
                [
                    'tickets as total_tickets' => function($query){
                        $query->whereNotNull('queue_ticket_status')
                              ->whereNull('deleted_at');
                    },
                    'tickets as total_dismissed'=> function($query){
                        $query->where('queue_ticket_status','dismissed')
                              ->whereNull('deleted_at');
                    },
                    'tickets as total_not_attended'=> function($query){
                        $query->where('queue_ticket_status','not_attended')
                              ->whereNull('deleted_at');
                    },
                    'tickets as total_called'=> function($query){
                        $query->where('queue_ticket_status','called')
                              ->whereNull('deleted_at');
                    },
                    'tickets as total_waiting'=> function($query){
                        $query->where('queue_ticket_status','waiting')
                              ->whereNull('deleted_at');
                    }

                ]
               )
               ->firstOrFail();

        if(!$queue){
            abort(404,'Fila não encontrada !');
        }

        //trazendo os tickets da fila 
        
        $tickets = $queue->tickets()->get();

        $data = [
            'subtitle'=> 'Detalhes',
            'queue' => $queue,
            'tickets' => $tickets
        ];

        return view('main.queue_details',$data);

    }

    public function createQueue():View
    {       
            $data=[
                'subtitle'=>'Criar Fila'
            ];
            return view('main.queue_create_frm',$data);
    }

    public function createQueueSubmit(Request $request){

       

        $request->validate(
          [
                    'name'=>'required|min:5|max:100',
                    'description'=>'required|min:5|max:255',
                    'service' =>'required|min:3|max:50',
                    'desk'=>'required|min:1|max:20',
                    'prefix'=>'required|regex:/^[A-Z\-]{1}$/',
                    'total_digits'=>'required|integer|min:2|max:4',
                    'color_1'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'color_2'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'color_3'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'color_4'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'hidden_hash_code'=>'required|size:64',
                    'status' =>'required|in:active,inactive',
        ],
        [
                    'name.required' => 'O campo nome é obrigatório.',
                    'name.min' => 'O nome deve ter no mínimo 5 caracteres.',
                    'name.max' => 'O nome deve ter no máximo 100 caracteres.',

                    'description.required' => 'O campo descrição é obrigatório.',
                    'description.min' => 'A descrição deve ter no mínimo 5 caracteres.',
                    'description.max' => 'A descrição deve ter no máximo 255 caracteres.',

                    'service.required' => 'O campo serviço é obrigatório.',
                    'service.min' => 'O serviço deve ter no mínimo 3 caracteres.',
                    'service.max' => 'O serviço deve ter no máximo 50 caracteres.',

                    'desk.required' => 'O campo guichê é obrigatório.',
                    'desk.min' => 'O guichê deve ter no mínimo 1 caractere.',
                    'desk.max' => 'O guichê deve ter no máximo 20 caracteres.',

                    'prefix.required' => 'O campo prefixo é obrigatório.',
                    'prefix.regex' => 'O prefixo deve conter apenas uma letra maiúscula seguida de um hífen (ex: A-).',

                    'total_digits.required' => 'O campo total de dígitos é obrigatório.',
                    'total_digits.integer' => 'O total de dígitos deve ser um número inteiro.',
                    'total_digits.min' => 'O total de dígitos deve ser no mínimo 2.',
                    'total_digits.max' => 'O total de dígitos deve ser no máximo 4.',

                    'color_1.required' => 'A cor 1 é obrigatória.',
                    'color_1.regex' => 'A cor 1 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'color_2.required' => 'A cor 2 é obrigatória.',
                    'color_2.regex' => 'A cor 2 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'color_3.required' => 'A cor 3 é obrigatória.',
                    'color_3.regex' => 'A cor 3 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'color_4.required' => 'A cor 4 é obrigatória.',
                    'color_4.regex' => 'A cor 4 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'hidden_hash_code.required' => 'O código hash é obrigatório.',
                    'hidden_hash_code.size' => 'O código hash deve ter exatamente 64 caracteres.',

                    'status.required' => 'O campo status é obrigatório.',
                    'status.in' => 'O status deve ser "active" ou "inactive".',
                ]
           );

           //testando se ja existe uma fla de espera com um nome igual para a mesma empresa

           $companyId = Auth::user()->id_company;
           $queueExists = Queue::where('id_company',$companyId)
                               ->where('name',$request->name)
                               ->exists();
           if($queueExists){
              return redirect()->back()->withInput()->with(['server_error' =>'Já existe uma fila com esse nome para essa empresa ']);
           }

           //verificar novamente se a hash code da fila é unica em toda a base de dados

           $hashCode = $request->hidden_hash_code; 
           $hashExists = Queue::where('hash_code',$hashCode)->exists();
           if($hashExists){
              return redirect()->back()->withInput()->with(['server_error' =>'Esse hash code ja esta sendo usado por favor gere um novo']);
           }

           //preparando os dados pra gravar
           
           $newQueue = new Queue();
           $newQueue->id_company = Auth::user()->id_company;
           $newQueue->name = trim($request->name);
           $newQueue->description = trim($request->description);
           $newQueue->service_name = trim($request->service);
           $newQueue->service_desk = trim($request->desk);
           $newQueue->queue_prefix = strtoupper(trim($request->prefix));
           $newQueue->queue_total_digits = (int) trim($request->total_digits);
           //em relação a cores eu vou receber um array e converter ele para um json
           $newQueue->queue_colors = json_encode([
             'prefix_bg_color' => trim($request->color_1),
             'prefix_text_color' => trim($request->color_2),
             'number_bg_color' => trim($request->color_3),
             'number_text_color' => trim($request->color_4),
           ]);
           $newQueue->hash_code = trim($request->hidden_hash_code);
           $newQueue->status = trim($request->status);

           //gravando na base
           
           $newQueue->save();

           return redirect()->route('home'); 
        
        }

    public function generateQueueHash(){

        //gerando uma hash unica de  64 bits

        $hash = hash('sha256',Str::random(40));

        //tendo certeza que ela é unica enquanto não for será gerada uma nova hash 
        
        while(Queue::where('hash_code',$hash)->exists()){
            $hash = hash('sha256',Str::random(40));
        }

        //retornando a hash
        
        return response()->json(['hash'=>$hash]);
    }

    public function editQueue($id):View
    {
       //decodificando o id recebido pela requisição
       //e testando se é um numero de id valido 

       try{
          $id = Crypt::decrypt($id);
       }catch(\Exception $e){
          abort(403,'ID de fila invalido ');
       }

       //verificando se a fila existe e se pertence ao usuario 
       //que esta logado 
       
       $queue = Queue::where('id',$id)
                     ->where('id_company',Auth::user()->id_company)
                     ->firstOrFail();

       //testando se alguma fila foi achada na consulta

       if(!$queue){
          abort(404,'Fila não encontrada !');
       }

       //caso exista os dados dela serão passado pra esse array 
       //o queue colors vai chegar como um json por isso eu tenho que decodificar 
       //para que ele possa ser lido pela interface
       //como um array associativo
       
       $data = [
          'subtitle' =>'Editar Fila',
          'queue' =>$queue,
          'queueColors' => json_decode($queue->queue_colors,true)
       ];
       
       return view('main.queue_edit_frm',$data);

    }

    public function editQueueSubmit(Request $request){

         $request->validate(
          [
                    'name'=>'required|min:5|max:100',
                    'description'=>'required|min:5|max:255',
                    'service' =>'required|min:3|max:50',
                    'desk'=>'required|min:1|max:20',
                    'prefix'=>'required|regex:/^[A-Z\-]{1}$/',
                    'color_1'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'color_2'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'color_3'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'color_4'=>'required|regex:/^\#[a-f0-9]{6}$/',
                    'status' =>'required|in:active,inactive',
        ],
        [
                    'name.required' => 'O campo nome é obrigatório.',
                    'name.min' => 'O nome deve ter no mínimo 5 caracteres.',
                    'name.max' => 'O nome deve ter no máximo 100 caracteres.',

                    'description.required' => 'O campo descrição é obrigatório.',
                    'description.min' => 'A descrição deve ter no mínimo 5 caracteres.',
                    'description.max' => 'A descrição deve ter no máximo 255 caracteres.',

                    'service.required' => 'O campo serviço é obrigatório.',
                    'service.min' => 'O serviço deve ter no mínimo 3 caracteres.',
                    'service.max' => 'O serviço deve ter no máximo 50 caracteres.',

                    'desk.required' => 'O campo guichê é obrigatório.',
                    'desk.min' => 'O guichê deve ter no mínimo 1 caractere.',
                    'desk.max' => 'O guichê deve ter no máximo 20 caracteres.',

                    'prefix.required' => 'O campo prefixo é obrigatório.',
                    'prefix.regex' => 'O prefixo deve conter apenas uma letra maiúscula seguida de um hífen (ex: A-).',

                    'total_digits.integer' => 'O total de dígitos deve ser um número inteiro.',
                    'total_digits.min' => 'O total de dígitos deve ser no mínimo 2.',
                    'total_digits.max' => 'O total de dígitos deve ser no máximo 4.',

                    'color_1.required' => 'A cor 1 é obrigatória.',
                    'color_1.regex' => 'A cor 1 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'color_2.required' => 'A cor 2 é obrigatória.',
                    'color_2.regex' => 'A cor 2 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'color_3.required' => 'A cor 3 é obrigatória.',
                    'color_3.regex' => 'A cor 3 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'color_4.required' => 'A cor 4 é obrigatória.',
                    'color_4.regex' => 'A cor 4 deve estar no formato hexadecimal (ex: #a1b2c3).',

                    'status.required' => 'O campo status é obrigatório.',
                    'status.in' => 'O status deve ser "active" ou "inactive".',
                ]
           );


           //testando se o id veio do formulario 
           if(!$request->has('queue_id')){
              abort(403,'Operação invalida');
           }

           //testando se o id foi alterado manualmente

            try{
                Crypt::decrypt($request->queue_id);
            }catch(\Exception $e){
                abort(403,'Operação invalida ');
            } 

            //tentando se o id da fila editada pertence a mesma compania 
            //do usuario que esta logado

            $queueId = Crypt::decrypt($request->queue_id);
            $companyId = Auth::user()->id_company;

            $queue = Queue::where('id',$queueId)
                          ->where('id_company',$companyId)
                          ->firstOrFail();

            if(!$queue){
                abort(404,'Fila não encontrada !');
            }

            //testando se o nome da fila que foi alterado ja esta sendo usado 
            //por outra fila da mesma empresa
            
            $queueExists = Queue::where('id_company',$companyId)
                                ->where('name',$request->name)
                                ->where('id','!=',$queueId)
                                ->exists();

            if($queueExists){
                return redirect()->back()->withInput()->with(['server_error' =>'Já existe uma fila com esse nome para essa empresa ']);
            }
        

          //preparando os dados pra salvar 
          $queue->name = trim($request->name);
          $queue->description = trim($request->description);
          $queue->service_name = trim($request->service);
          $queue->service_desk = trim($request->desk);
          $queue->queue_prefix = trim($request->prefix);
          $queue->queue_colors = json_encode([
            'prefix_bg_color'=>trim($request->color_1),
            'prefix_text_color'=>trim($request->color_2),
            'number_bg_color'=>trim($request->color_3),
            'number_text_color'=>trim($request->color_4),
          ]);
          $queue->status = trim($request->status);

          $queue->save();

         return redirect()->route('home');
          
    }


    public function cloneQueue($id){

    }

    public function cloneQueueSubmit(Request $request){
        
        dd($request->all());
    }






    



}
