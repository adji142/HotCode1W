<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GudangSementaraDetail extends Model
{
    protected $table = 'stk.gudangsementaradetail';

    public function barang()
    {
    	return $this->belongsTo('App\Models\Barang','stockid');
    }

    public function gs()
    {
    	return $this->belongsTo('App\Models\GudangSementara','gudangsementaraid');
    }
}
