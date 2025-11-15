<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        echo "Formulário de login ";
    }

    public function loginSubmit(){
        echo "tratamento do formulario de login ";
    }

    public  function logout(){
        echo "logout ";
    }
}
