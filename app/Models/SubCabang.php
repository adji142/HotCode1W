<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCabang extends Model
{
    protected $table = 'mstr.subcabang';
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'secure.subcabanguser');
    }

    public function cabang()
    {
    	return $this->hasOne('App\Models\Cabang', 'id', 'cabangid');
    }
}
