<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArmadaKirim extends Model
{
    protected $table = 'mstr.armadakirim';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
