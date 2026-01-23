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

        
        if(!$request->session()->has('ticket_dispenser_credential') ){
            return redirect()->route('dispenser.credentials');
        }

        return $next($request);
    }
}
