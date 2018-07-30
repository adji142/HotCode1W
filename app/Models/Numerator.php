<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Numerator extends Model
{
    protected $table = 'mstr.numerator';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
