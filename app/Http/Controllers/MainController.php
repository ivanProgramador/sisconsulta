<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index():View
    {
        $queues = $this->getQueuesList();

        $data=[
            'subtitle'=>'Home',
            'queues'  => $queues
        ];

        dd($data);

        return view('home',$data);
    }

    private function getQueuesList(){
        
        $companyId = Auth::user()->id_company;
        return Queue::where('id_company', $companyId)
                    ->where('status','active')
                    ->whereNull('deleted_at')
                    ->withCount([
                        
                        'tickets as total_tickets' => function($query){
                            $query->whereNotNull('queue_ticket_status')
                                  ->whereNull('deleted_at');
                        },

                        'tickets as total_dismissed'=> function($query){
                            $query->where('queue_ticket_status','dismissed')
                                  ->where('deleted_at','null');
                        },
                         'tickets as total_called'=> function($query){
                            $query->where('queue_ticket_status','called')
                                  ->where('deleted_at','null');
                        },
                        'tickets as total_waiting'=> function($query){
                            $query->where('queue_ticket_status','waiting')
                                  ->where('deleted_at','null');
                        }

                    ])->get();
    }
}
