<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class MutasiDetail extends Model
{
    protected $table = 'stk.mutasidetail';
    protected $fillable = [
      'mutasiid','coupleid','stockid','qtymutasi','isarowid','createdby','createdon','lastupdatedby','lastupdatedon'
    ];
    protected $dates = ['lastupdatedon', 'createdon'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    public function barang()
    {
    	return $this->belongsTo('App\Models\Barang','stockid');
    }

    public function mt()
    {
    	return $this->belongsTo('App\Models\Mutasi','mutasiid');
    }

    public function hppa()
    {
    	return $this->belongsTo('App\Models\HPPA','stockid')->orderBy('tglaktif','desc');
    }
}
