<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ApprovalManagement extends Model
{
    protected $table = 'secure.approvalmgmt';
    protected $fillable = [
      'recordownerid','moduleid','tgltransaksi','keterangan','datareportingheader','datareportingdetail','closed','createdby','createdon','lastupdatedby','lastupdatedon'
    ];
    protected $dates = ['lastupdatedon', 'createdon'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    public function setCreatedonAttribute($date) 
    {
        $this->attributes['createdon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function setLastupdatedonAttribute($date)
    {
        $this->attributes['lastupdatedon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function getCreatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function getTglTransaksiAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function modules()
    {
        return $this->belongsTo('App\Models\ApprovalModule','moduleid');
    }

    public function details()
    {
        return $this->hasMany('App\Models\ApprovalManagementDetail','approvalmgmtid');
    }
}
