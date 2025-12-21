<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bundle extends Model
{
    use SoftDeletes;

    //realação entre coleções de filas e empresas 
    //uma coleção so pode pertencer a uma empresa

    public function company()
    {
       return $this->belongsTo(Company::class,'id_company');
    }
    
    
}
