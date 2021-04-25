<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productos extends Model {

    protected $table = 'productos';
    protected $fillable = ['cantidad', 'total'];

    public function tipos() {

    	return $this->belongsTo('App\Tipos', 'tipo_id', 'nombre');
    }
}
