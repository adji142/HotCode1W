<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokoAktifPasif extends Model
{
    protected $table = 'mstr.tokoaktifpasif';
    protected $fillable = ['tglstatus','statusaktif','keterangan','createdby','lastupdatedby'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
