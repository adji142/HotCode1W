<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagingOrderPenjualan extends Model
{
    protected $connection = 'sqlsrv';
    // protected $table = 'orderpenjualan';
    protected $table = 'StgOrderPenjualan';
    const UPDATED_AT = 'LastUpdatedTime';
    const CREATED_AT = 'CreatedOn';
}
