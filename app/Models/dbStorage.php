<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dbStorage extends Model
{
    protected $table = 'mstr.storage';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
