<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bgc extends Model
{
    protected $table    = 'ksr.bgc';
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    protected $dates = ['tglbgc', 'tgljtbgc'];
}
