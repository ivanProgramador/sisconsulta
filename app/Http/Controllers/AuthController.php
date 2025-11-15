<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
          return view('auth/login_frm',['subtitle'=>'Login'] );
    }

    public function loginSubmit(){
        echo "tratamento do formulario de login ";
    }

    public  function logout(){
        echo "logout ";
    }
}
