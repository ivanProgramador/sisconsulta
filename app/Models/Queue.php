<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Queue extends Model
{
    use SoftDeletes;

    //uma fila de espera so pode pertencer a uma empresa 

     public function company(){
         return $this->belongsTo(Company::class,'id_company');
    }
}
