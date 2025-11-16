<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticable
{
    use SoftDeletes;

    //um usuário só pode pertencer a uma empresa 

     public function company(){
         return $this->belongsTo(Company::class,'id_company');
    }
}
