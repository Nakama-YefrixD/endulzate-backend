<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class proveedores extends Model
{
    protected $table = 'proveedores';

    public function entradas()
    {
        return $this->hasMany('App\entradas', 'proveedor_id');
    }
}
