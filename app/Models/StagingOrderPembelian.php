<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StagingOrderPembelian extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'OrderPembelianHeader';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $fillable = [
      'id','recordownerid','recordownersubcabang','tglorder','noorder','supplierid','tempo','keterangan','approvalmgmtid','isarowid','createdby',
      'createdon','lastupdatedby','lastupdatedon'
    ];

}
