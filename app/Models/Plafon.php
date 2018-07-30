<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plafon extends Model
{
    protected $table = 'ptg.plafon';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
