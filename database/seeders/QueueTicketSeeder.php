<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

            



        }

        
               
         

    }

    

}