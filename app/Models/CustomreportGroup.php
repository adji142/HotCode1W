<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomreportGroup extends Model
{
    protected $table = 'report.customreportgroup';
    public $timestamps = false;

    public function role()
    {
        return $this->belongsToMany('App\Models\Role','secure.customreportgrouprole','customreportgroup_id','role_id'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }

    public function report()
    {
        return $this->hasMany('App\Models\Reportcustom','customreportgroupid'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }
}
