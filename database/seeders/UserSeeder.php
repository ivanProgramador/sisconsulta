<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * pra executar o seed abaixo use esse comando 
     *  php artisan db:seed --class=UserSeeder
     * Essa seed cadastra so usuarios de administradores dos clientes
     */
    public function run(): void
    {
        $users = [
                [
                    'email'=>'useradmin@localhost.com',
                    'password' => bcrypt('Aa123456'),
                    'id_company' => 0,
                    'role' => 'client-admin',
                    'active' => true
                ],
                [
                    'email'=>'useradmin1@localhost.com',
                    'password' => bcrypt('Aa123456'),
                    'id_company' => 1,
                    'role' => 'client-admin',
                    'active' => true
                ],
                [
                    'email'=>'useradmin2@localhost.com',
                    'password' => bcrypt('Aa123456'),
                    'id_company' => 2,
                    'role' => 'client-admin',
                    'active' => true
                ],
            ];

        DB::table('users')->insert($users);
        echo count($users)." usuarios criados\n";
    }
}
