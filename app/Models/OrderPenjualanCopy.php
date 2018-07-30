<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPenjualanCopy extends Model
{
    //
    protected $table = 'pj.orderpenjualancopy';
    public $timestamps = false;
    protected $guarded = ['id']; 
}