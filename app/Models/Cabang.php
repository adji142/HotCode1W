<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'mstr.cabang';

    /*public function subcabang()
    {
    	return $this->belongsTo('App\Models\SubCabang','cabangid');
    }*/
}
