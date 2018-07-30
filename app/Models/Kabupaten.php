<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table    = 'mstr.kabkota';

    public $timestamps = false;
    public $tag = null;
}
