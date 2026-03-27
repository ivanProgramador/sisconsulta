<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
            $clients = $this->getClientList();

            dd($clients);
    }


     private function getClientList(){
        //pegando todas as empresas cadastradas

        return Company::withTrashed()->get();
        
    }
}
