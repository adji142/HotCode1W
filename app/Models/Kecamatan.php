<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table    = 'mstr.kecamatan';

    public $timestamps = false;
    public $tag = null;
}
