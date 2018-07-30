<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagingReturPembelian extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'returpembelian';
    const UPDATED_AT      = 'lastupdatedon';
    const CREATED_AT      = 'createdon';
    protected $fillable   = [
        'recordownerid','tglprb','noprb','kodesupplier','tglkirim','staffidpemeriksa00','kodeexpedisi','qtykoli','nokoli',
        'tglnrj11','nonrj11','staffpemeriksa11','keterangan','nprint','ohpsdepo','isarowid','createdby','createdon','lastupdatedby','lastupdatedon',
    ];
}
