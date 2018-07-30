<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarStock extends Model
{
    protected $table = 'stk.standarstock';
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';
}
