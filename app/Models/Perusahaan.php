<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'mstr.perusahaan';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
