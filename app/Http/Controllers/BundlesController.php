<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class BundlesController extends Controller
{
    public function index(){
        $data =[
            'subtitle'=>'Bundles',
            'bundles' => auth()->user()->company->bundles()->withTrashed()->get(),

        ];

         
        return view('bundles.home',$data);
    }
    public function createBundles(){

         $data =[
            'subtitle'=>'Bundles',
            'queues' => auth()->user()->company->queues()->get()  
        ];

        return view('bundles.bundle_create_frm',$data);



    }
    public function createBundlesSubmit(Request $request){
         
        
        $request->validate([
            'bundle_name'=>'required|string|min:5|max:100',
            'credential_username' =>'required|string:size:64',
            'credential_password' =>'required|string:size:64',
            'queues_list' => 'required'
           ],[
            'bundle_name.required'=>'o nome do grupo é obrigatório ',
            'bundle_name.string' =>'o nome precisar ser um texto ',
            'bundle_name.min' =>'O nome do grupo deve ter no minimo 5 caracteres',
            'bundle_name.max' => 'O nome pode ter no maximo 100 caracteres',
            'credential_username.required'=>'a credencial do nome é obrigatória',
            'credential_username.string'=>'a credencial do nome deve ser um texto',
            'credential_username.size' => 'o usuario da credencial deve ter pelo menos 64 caracteres',
            'credential_password.required'=>'a senha é obrigatoria',
            'credential_password.string'=>'a senha deve ser um texto',
            'credential_password.size' => 'a senha deve ter pelo menos 64 caracteres'
         ]);

         //verificando se a lista de filas é um json valido e se não esta vazio
         if(
            empty($request->queues_list) ||
            json_decode($request->queues_list) == null || 
            empty(json_decode($request->queues_list)) 
           ){
             return redirect()->back()->withInput()->withErrors(['queues_list' =>'A lista de filas é obrigatória ']);
           }
           
         //verificando se  nome da fila ja existe na base de dados
         
         $bundle_name = $request->bundle_name; 
         $bundleExists = auth()->user()->company->bundles()
                       ->where('name',$bundle_name)
                       ->exists();
         
        if($bundleExists){
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['bundle_name'=>'já existe um grupo com esse nome ']);
        }

        //validação pra não deixar passar filas que pertencem a outras empresas
        //eu vou validar cada uma delas
        
        //vou começar pegando o json que vem na requisição
        //ao decodificar ele esse json vira um array 
        //eu coloquei um tru como segundo parametro para ter certeza que ele vaio ser um array associativo 
        //não é algo necessário é só uma garantia 

         $queues_list = json_decode($request->queues_list,true);

        /*
         Eu vou recebr as filas nesse fomato 

         array:3 [▼ // app\Http\Controllers\BundlesController.php:91
                0 => array:2 [▼
                 "hash_code" => "CqErFiJ9yEjYdqkaGIghiqTD4tSzfrm4lykdJZRpXaZWJL6VQDZplDlcHNhjdsQe"
                 "name" => "Fila numero 1 teste"
                ]
                 1 => array:2 [▼
                   "hash_code" => "z41SggVrsLqZnLFP5Z7QXQnpVDmRPLN8fyGwXKdSgoJwxbIvTzk3K7HEfxSBoHSy"
                   "name" => "Fila numero 2"
                 ]
                 2 => array:2 [▼
                   "hash_code" => "0358f824f310bf4f8573de921b59ed27e0435cc222d3c687561764f1ec250cf6"
                   "name" => "triagem cirurgia plastica"
                 ]
                ]
        */
        
        //oque torna uma fila unica é a hash dela então nesse array associativo 
        //eu vou receber o nome da fila(que não me interessa)
        //e a hash que será usada para a validação
        //o array map receb 2 argumentos 
        // 1 - a função com uma varivel que vai receber os dados mapeados e retornar 
        // 2 - o array que sera mapeado 

        $queues_hash_codes = array_map(function($queue){

            //retonando somente o conteudo do indice hash_code
            return $queue['hash_code'];

        },$queues_list);

        //agora eu vou testar se todas as filas petencem a empres do usuario que esta logado no sistema 
        
        $valid_queues = auth()->user()->company->queues()
                        ->whereIn('hash_code',$queues_hash_codes)
                        ->pluck('hash_code')
                        ->toArray();

        if(count($valid_queues) !==  count($queues_hash_codes)){
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['queues_list'=>'Algumas filas selecionadas não existem ou não pertencem a sua empresa']);
        }

        //gravando os dados 
        $newBundle = new Bundle();
        $newBundle->id_company = auth()->user()->company->id;
        $newBundle->name = $bundle_name;
        $newBundle->queues = json_encode($valid_queues);
        $newBundle->credential_username = $request->credential_username;
        $newBundle->credential_password = bcrypt($request->credential_password);
        $newBundle->save();

        return redirect()->route('bundles.home');


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
    public function edit($id){
        
        // verificando se o id da lista de filas é valido
        try{
          $id = Crypt::decrypt($id);
        }catch(\Exception $e){
            abort(403,'ID de bundle invalido ');
        }

        //buscando pela lista de filas 
        $bundle  = Bundle::find($id);

        if(!$bundle || $bundle->id_company !== auth()->user()->company->id){
            return redirect()->route('bundles.home');
        }

        $data=[
            'subtitle'=>'Editar grupo',
            'bundle' => $bundle,
            'bundle_queue_list' => $this->getBundleQueuList($id), 
            'queues'=> auth()->user()->company->queues()->get()
        ];

       

        return view('bundles.bundle_edit_frm',$data);

    }
    private function getBundleQueuList($id){

        $bundle = Bundle::find($id);

        

        //aqui eu pego as as filas que ja existem associadas ao grupo 

       $queues = is_array($bundle->queues)? $bundle->queues: json_decode($bundle->queues, true);
      
      

        //aqui eu pego todas as filas que estão associadas a empresa que o usuario logado esta associado 
        //e coloco essas filas dentro de um array 

        $companyQueues = Queue::where('id_company',auth()->user()->company->id)
                                ->whereIn('hash_code',$queues)
                                ->get();

                                   
        $queueList = [];
        
        //agora eu vou fazer um foreach para pegar todas as filas que vieram
        //nomes e hashes eu vou precisar disso pra mostrar tanto as filas que ja estão no grupo 
        //quando as filas que o usuario pode escolher  
        
        foreach($companyQueues as $queue){
            $queueList[]=[
                'name'=> $queue->name,
                'hash_code' => $queue->hash_code
            ];

        }

        //retornando a lista 
        return $queueList;

     } 

    

    public function editSubmit(Request $request){
        
         
        $request->validate([
            'bundle_name'=>'required|string|min:5|max:100',
            'queues_list' => 'required'
           ],[
            'bundle_name.required'=>'o nome do grupo é obrigatório ',
            'bundle_name.string' =>'o nome precisar ser um texto ',
            'bundle_name.min' =>'O nome do grupo deve ter no minimo 5 caracteres',
            'bundle_name.max' => 'O nome pode ter no maximo 100 caracteres',
            'queues_list.required' =>'A lista de filas é obrigatória'

         ]);

         //verificando se o id que veio é valido

         if(empty($request->bundle_id)){
             return redirect()->route('bundles.home');
         }

         //desencriptando o bundle_id 
         Try{

             $bundle_id = Crypt::decrypt($request->bundle_id);

         }catch(\Exception $e){

           abort(403,'ID invalido');   

         }
         
         if(
            empty($request->queues_list) ||
            json_decode($request->queues_list) == null || 
            empty(json_decode($request->queues_list)) 
           ){
             return redirect()->back()->withInput()->withErrors(['queues_list' =>'A lista de filas é obrigatória ']);
           }
           
         
         
         $bundle_name = trim($request->bundle_name); 

         $bundleExists = auth()->user()->company->bundles()
                       ->where('name',$bundle_name)
                       ->where('id','!=',$bundle_id) //estou validando o id porque se o nome do grupo não for alterado na edição ele não vai deixar salvar 
                       ->exists();
         
        if($bundleExists){
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['bundle_name'=>'já existe um grupo com esse nome ']);
        }

       

        $queues_list = json_decode($request->queues_list,true);
        $queues_hash_codes = array_map(function($queue){
            return $queue['hash_code'];
        },$queues_list);
        
        
        $valid_queues = auth()->user()->company->queues()
                        ->whereIn('hash_code',$queues_hash_codes)
                        ->pluck('hash_code')
                        ->toArray();

        if(count($valid_queues) !==  count($queues_hash_codes)){
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['queues_list'=>'Algumas filas selecionadas não existem ou não pertencem a sua empresa']);
        }

        //a possibilidade do update afetar um grupo que não pertença a empresa e pequena mas mesmo 
        //assim vou colocar uma camada extra de segurança
        
        $bundle = Bundle::find($bundle_id);
        if(!$bundle || $bundle->id_company != auth()->user()->company->id ){
            return redirect()->route('bundles.home');
        }

        //gravando as alterações 

        $bundle->name = $bundle_name;
        $bundle->queues = json_encode($valid_queues);
        $bundle->save();

        return redirect()->route('bundles.home');

    }
    public function delete($id){

         //desencriptando o bundle_id 
         Try{
             $id = Crypt::decrypt($id);
         }catch(\Exception $e){
           return redirect()->route('bundles.home'); 
         }
         
         $bundle = Bundle::find($id);

         if(!$bundle || $bundle->id_company != auth()->user()->company->id){
            return redirect()->route('bundles.home');
         }

         //preparando os dados para  a view de confirmação 

         $data = [
            'subtitle' => 'Eliminar Grupo',
            'bundle'   => $bundle
         ];

         return view('bundles.bundle_delete_confirm',$data);




    }
    public function deleteConfirm($id){

         //desencriptando o bundle_id 
         Try{
             $id = Crypt::decrypt($id);
         }catch(\Exception $e){
           return redirect()->route('bundles.home'); 
         }
         
         $bundle = Bundle::find($id);
         if(!$bundle || $bundle->id_company != auth()->user()->company->id){
            return redirect()->route('bundles.home');
         }

         //isso não vai apagar o registro da base so vai preencher o campo deleted_at porque é soft delete

         $bundle->delete();

          return redirect()->route('bundles.home');



         
    }
    public function restore($id){

         //desencriptando o bundle_id 
         Try{
             $id = Crypt::decrypt($id);
         }catch(\Exception $e){
           return redirect()->route('bundles.home'); 
         }
         
         $bundle = Bundle::withTrashed()->find($id);
         if(!$bundle || $bundle->id_company != auth()->user()->company->id){
            return redirect()->route('bundles.home');
         }

         //restaurando o grupo 
         $bundle->restore();

         return redirect()->route('bundles.home');




    }




}
