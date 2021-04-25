<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class entradas extends Model
{
    protected $table = 'entradas';

    public function productosEntradas()
    {
        return $this->hasMany(
        	'App\productosEntradas',
        	'entrada_id',
        	'id'
        );
    }
}
