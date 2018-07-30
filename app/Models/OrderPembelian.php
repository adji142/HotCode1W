<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderPembelian extends Model
{
    protected $table = 'pb.orderpembelian';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $fillable = [
      'recordownerid','tglorder','noorder','supplierid','tempo','keterangan','approvalmgmtid','isarowid','createdby','createdon','lastupdatedby',
      'lastupdatedon', 'sync11'
    ];

    public function tablecolumn() {
      list($cols, $values) = array_divide((new static)->first()->toArray());
      return $cols;
      // return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function details()
    {
      return $this->hasMany('App\Models\OrderPembelianDetail','orderpembelianid');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Models\Supplier','supplierid');
    }

    public function subcabang()
    {
    	return $this->belongsTo('App\Models\SubCabang','recordownerid');
    }

    public function setCreatedonAttribute($date)
    {
        $this->attributes['createdon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function setLastupdatedonAttribute($date)
    {
        $this->attributes['lastupdatedon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function getLastupdatedonAttribute($value)
    {
      if($value != null){
          return Carbon::parse($value)->format('d-m-Y H:i:s');
      }else{
          return $value;
      }
    }

    public function getTglorderAttribute($value)
    {
      if($value != null){
          return Carbon::parse($value)->format('d-m-Y');
      }else{
          return $value;
      }
    }

}
