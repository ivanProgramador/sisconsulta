<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QueueTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //limpando a tabela de tickets antes de inserir novos dados 
        DB::table('queue_tickets')->truncate();
        
        //pegando os ids das filas existentes e colocando em um array

        $queueIds = DB::table('queues')->pluck('id')->toArray();    

        //fazendo um for para ler o id de cada uma das filas dentro do array
        foreach($queueIds as $queueId){

            //gerando um numero aleatorio de tickets para cada fila entre 50 e 200
          
            $totalTickets = rand(50, 200); 

            //definindo data/hora de criação 
            $createdAt = now();

            //definindo a data/hora que senha foi chamada
            //e adicionando 2 minutos ao tempo 
            
            $calledAt = now()->addMinutes(2);
           
            //eu vou gerar uma numerpo aleatrio de tickets 
            //nisso esse numero vai para a varivel total tickes 
            //qua será comparada ao contador $i e enquanto o contador 
            //não for igual ao $totaltickes o ciclo de garação de tickes não vai parar

            for($i = 1;$i < $totalTickets; $i++){

                //status possiveis de um ticeke da fila
                // 'waiting' 'called' 'not_attended' 'dismissed'

                $status = '';

                // a cada loop a função rand vai gerar um numero aleatorio 
                //de 1 a 4 como eu tenho 4 estados de atendimento a cada loop 
                //um estado sera definido de forma aletoria pra cada tiket de fila criado 

                $statusTmp = rand(1,4);

                //definindo o estado 

                if($statusTmp == 1){
                    $status = 'waiting';

                }elseif($statusTmp == 2){
                    $status = 'called';

                }elseif($statusTmp == 3){
                    $status = 'not_attended';

                }elseif($statusTmp == 4){
                    $status = 'dismissed';
                }

                //criando o ticket na base de dados

                DB::table('queue_tickets')->insert([
                    'id_queue'=> $queueId,
                    'queue_ticket_number' => $i + 1,
                    'queue_ticket_created_at'=> $createdAt,
                    'queue_ticket_called_at' => $status === 'called' ? $calledAt : null,
                    'queue_ticket_called_by' => $status === 'called' ? 'user_'.rand(1,10) : null,
                    'queue_ticket_status'=> $status,
                    'created_at' => now(),
                    'updated_at' =>now()
                ]);

                $createdAt = $createdAt->addMinutes(2);
                $calledAt = $calledAt->addMinutes(2);







               







            }





            



        }

        
               
         

    }

    

}