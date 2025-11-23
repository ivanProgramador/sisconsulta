<?php 

 /*
   funções dentro de arquivo helper podem ser chamadas em qualquer ponto do codigo 
   mas para que isso aopcnteça eu tenho que registrar o arquivo no meu autoload
   pra fazer isso no composer.json tem um objeto chamado autoload que faz o 
   carregamento das bilbiotecas e namespaces, nop exemplo abaixo eu cololoquei 
   um array dentro do autoload porque se eu precisar de mais aruivos eu a diciono
   mais um indice

    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "files":[
            "app/helpers/queue_sistem_helper.php"
        ]
    }

    lembrando que depois que fizer isso tem que executar o comando abaixo pra ele recarregar 
    as confgurações do composer.json

      composer dump-autoload


  
 
 */

 if(!function_exists('ShowValidattionError')){

    function ShowValidationError($fieldName,$validationErrors){

        if($validationErrors->has($fieldName)){
           
            return '<div class="text-sm italic text-red-500" >'.$validationErrors->first($fieldName).'</div>';

        }else{

            return '';
        }
    }


    
 }


 if(!function_exists('ShowServerError')){

    function ShowServerError(){

        if(session()->has('server_error')){
           
            return '<div class="text-sm italic text-red-500" >'.session()->get('server_error').'</div>';

        }else{

            return '';
        }
    }


    
 }

 if(!function_exists('getFormatedTicketNumber')){
    
    //Essa função serve para formatar a apresentação do prefixo das senhas das filas 

    function getFormatedTicketNumber($ticketNumber, $prefix = null, $totalDigits=3){
      
        $result ='';

        //prefix

        if($prefix){
            $result = $prefix;
        }

        //numbers
        
        if($totalDigits > 0){
            $result .= str_pad($ticketNumber, $totalDigits,'0',STR_PAD_LEFT); 

        }

        return $result;


    }
 }

 if(!function_exists('getTicketStateText')){

    function getTicketStateText($state){
        
        //os reultados vem em ingles das rotas e da base 
        //essa função vai servir para mostrar esses estados em portugues 

        $rules = [
            'waiting' => 'Aguardando',
            'called'  => 'Atendido',
            'not_attended' => 'Não atendido',
            'dismissed'=>'dispensado'
        ];

        //se por acaso por passado um estado diferente do esperado 
        //o sistema não vai travar ele vai retorenar o estado desconhecido 

        return $rules[$state]??'Desconhecido';

    }

 }