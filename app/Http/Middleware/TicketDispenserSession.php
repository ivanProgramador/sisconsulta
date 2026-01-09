<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketDispenserSession
{
    /**
     * Esse mid e pra testar se ja existe uma sessão ativa no dispensador 
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        //aqui eu testo se na sessão existe valor pra uma variavel chamada  "ticket_dispenser_credential"
        //se não tiver ele volta ao formulario   

        if(!$request->session()->has('ticket_dispenser_credential') ){
            return redirect()->route('dispenser.credentials');
        }

        return $next($request);
    }
}
