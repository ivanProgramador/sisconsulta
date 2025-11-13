<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    

    //uma empresa pode ter varios usuarios 

    public function users(){
          
        return $this->hasMany(User::class,'id_company');
    }
   
    //uma empresa pode ter varias filas de espera 
    
    public function queues(){
          
        return $this->hasMany(Queue::class,'id_company');
    }
   
}
