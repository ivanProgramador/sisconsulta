<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ClientAdminController extends Controller
{
    public function index()
    {
        $data=[
             'subtitle'=>'Adminitração de usuários'
        ];

        return view('client_admin.home');
    }
}
