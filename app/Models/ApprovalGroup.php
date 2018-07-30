<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalGroup extends Model
{
    protected $table = 'secure.approvalgroup';
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

    public function user()
    {
        return $this->belongsToMany('App\Models\User','secure.roleuser');
    }

    public function permission()
    {
        return $this->belongsToMany('App\Models\Permission','secure.permissionrole'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }
}
