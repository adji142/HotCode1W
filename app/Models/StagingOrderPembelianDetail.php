<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StagingOrderPembelianDetail extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'OrderPembelianDetail';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $fillable = [
      'id','orderpembelianid','kodebarang','qtyorder','qtypenjualanbo','qtyrataratajual','qtystokakhir','hrgsatuanbrutto','disc1','disc2','ppn',
      'hrgsatuannetto','keterangan','isarowid','createdby','createdon','lastupdatedby','lastupdatedon'
    ];
}
