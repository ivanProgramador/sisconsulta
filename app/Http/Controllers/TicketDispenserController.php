<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
use Illuminate\Support\Facades\Hash;

class TicketDispenserController extends Controller
{
    public function index(){

        $data=[
            'subtitle' => 'Dispensador de Tickets',
        ];

        return view('ticket_dispenser.dispenser',$data);
    }


    public function credentials(){

        $data=[
            'subtitle' => 'Formulario dispensador'
        ];

        return view('ticket_dispenser.credential_frm',$data);
    }


    public function credentialsSubmit(Request $request){

        $request->validate(
            [
                'credential_username'=>'required',
                'credential_password'=>'required'
            ],
            [
                'credential_username.required'=>'A credencial username é obrigatória',
                'credential_username.size'=>'A credencial username deve ter 64 caracteres',
                'credential_password.required'=>'A credencial userpassword é obrigatória',
                'credential_password.required'=>'A credencial userpassword deve ter 64 caracteres'
            ]
        );

        $result = Bundle::where('credential_username',$request->credential_username)
                       ->first();

        if(!$result){
            return redirect()
                   ->back()
                   ->withInput()
                   ->with(['server_error'=>'Credenciais invalidas.']);
        }

        if(!Hash::check($request->credential_password, $result->credential_password)){
             return redirect()
                   ->back()
                   ->withInput()
                   ->with(['server_error'=>'Credenciais invalidas.']);

        }
        

        session()->put('ticket_dispenser_credential',$request->credential_username);

        return redirect()->route('dispenser');
    }

    


}
