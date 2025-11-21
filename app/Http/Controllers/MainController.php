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
        return Queue::where('id_company',Auth::user()->id_company)
                      ->where('status','active')
                      ->withCount('tickets')
                      ->get()->SortBy('name');
    }
}
