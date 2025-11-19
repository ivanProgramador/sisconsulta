<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Echo_;

class CompanySeeder extends Seeder
{
    /**
     * As companias que são donas das filas de atendimento
     */
    public function run(): void
    {
        $companies = [];

        for ($index = 1; $index <= 3; $index++) {
            $companies[] = [
                'company_name'=>'Empresa ' . $index,
                'company_logo'=>'empresa_0' . $index .'.png',
                'uuid'=> Str::uuid(),
                'address'=>'Rua da empresa'.$index.', 123,Bairro Exemplo, Cidade Exemplo',
                'phone'=> '89774455'. $index,
                'email'=>'empresa'.$index.'@localhost.com',
                'status'=> 'active',
                'created_at'=> now(),
                'updated_at'=> now(),   
                'deleted_at'=> null,
            ];
        }

        DB::table('companies')->insert($companies);

        echo count($companies)." empresas cadastadas com sucesso !.\n";
    }
}
