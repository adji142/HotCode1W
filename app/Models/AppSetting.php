<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $table = 'mstr.appsetting';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
