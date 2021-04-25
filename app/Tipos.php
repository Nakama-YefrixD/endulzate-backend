<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipos extends Model 
{
    protected $table = 'tipos';

    public function productos() {

        return $this->hasMany('App\productos', 'tipo_id');
    }
}
