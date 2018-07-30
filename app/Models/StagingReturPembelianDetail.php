<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagingReturPembelianDetail extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'returpembeliandetail';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
