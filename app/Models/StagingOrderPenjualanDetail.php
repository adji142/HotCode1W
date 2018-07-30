<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagingOrderPenjualanDetail extends Model
{
    protected $connection = 'sqlsrv';
    // protected $table = 'orderpenjualandetail';
    protected $table = 'StgOrderPenjualanDetail';
    const UPDATED_AT = 'LastUpdatedTime';
    const CREATED_AT = 'CreatedOn';
}
