<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenanggungJawabArea extends Model
{
    protected $table = 'stk.penanggungjawabarea';

    public function karyawan()
    {
    	return $this->belongsTo('App\Models\Karyawan','karyawanidpenanggungjawab');
    }
}
