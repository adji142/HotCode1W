<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriRetur extends Model
{
    protected $table = 'mstr.kategoriretur';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
