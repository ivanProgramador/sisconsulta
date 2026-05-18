<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;

    //um usuário só pode pertencer a uma empresa 

     public function company(){
         return $this->belongsTo(Company::class,'id_company');
    }

    //foi adicionado pra correção da comparação da datat de expiração do codigo
    protected $casts = [
        'code_expiration' => 'datetime',
        'last_login' => 'datetime',
        'blocked_until' => 'datetime',
     ];
}
