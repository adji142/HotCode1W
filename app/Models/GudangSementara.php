<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GudangSementara extends Model
{
    protected $table = 'stk.gudangsementara';

    public function getTgltransaksiAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTgllinkAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function details()
    {
        return $this->hasMany('App\Models\GudangSementaraDetail','gudangsementaraid');
    }
}
