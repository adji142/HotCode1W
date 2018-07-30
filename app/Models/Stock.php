<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table    = 'mstr.stock';
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';
}
