<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QueueSeeder extends Seeder
{
    /**
     * As filas de espera do sistema 
     */
    public function run(): void
    {

    
        $data=[
            [
            'id' => 1,
            'id_company' => 1,
            'name' => 'Fila numero 1',
            'description' => 'Atendimento geral de Clientes',
            'service_name'=>'Atendimento Geral',
            'service_desk'=>'Balcão 1',
            'queue_prefix'=>'A',
            'queue_total_digits'=>3,
            'queue_colors'=> json_encode([
                'prefix_bg_color'=>'#0000FF',
                'prefix_text_color'=>'#FFFFFF',
                'number_bg_color'=>'#aaaaaa',
                'number_text_color'=>'#000000'
            ]),
            'hash_code'=> Str::random(64),
            'status'=>'active',
            'created_at'=>now(),
            'updated_at'=>now(),
            'deleted_at'=>null
        ],
        [
            'id' => 2,
            'id_company' => 2,
            'name' => 'Fila numero 2',
            'description' => 'Consulta familiar',
            'service_name'=>'Consulta familiar',
            'service_desk'=>'Balcão 2',
            'queue_prefix'=>'B',
            'queue_total_digits'=>4,
            'queue_colors'=> json_encode([
                'prefix_bg_color'=>'#FFD700',
                'prefix_text_color'=>'#000000',
                'number_bg_color'=>'#FFFFFF',
                'number_text_color'=>'#000000'
            ]),
            'hash_code'=> Str::random(64),
            'status'=>'active',
            'created_at'=>now(),
            'updated_at'=>now(),
            'deleted_at'=>null
        ],
        [
            'id' => 3,
            'id_company' => 3,
            'name' => 'Fila numero 3',
            'description' =>'Consulta familiar',
            'service_name'=>'Consulta familiar',
            'service_desk'=>'Balcão 3',
            'queue_prefix'=>'C',
            'queue_total_digits'=>4,
            'queue_colors'=> json_encode([
                'prefix_bg_color'=>'#FFD700',
                'prefix_text_color'=>'#000000',
                'number_bg_color'=>'#FFFFFF',
                'number_text_color'=>'#000000'
            ]),
            'hash_code'=> Str::random(64),
            'status'=>'active',
            'created_at'=>now(),
            'updated_at'=>now(),
            'deleted_at'=>null
        ]
    ];

    DB::table('queues')->insert($data);

    echo count($data)." Filas de espera adionadas !";

    }
}
