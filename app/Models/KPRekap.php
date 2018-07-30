<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KPRekap extends Model
{
    protected $table    = 'konsol.kprekap';

    public $timestamps = false;
    public $tag = null;
}
