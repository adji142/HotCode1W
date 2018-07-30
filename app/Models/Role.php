<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'secure.roles';

    public function user()
    {
        return $this->belongsToMany('App\Models\User','secure.roleuser');
    }

    public function permission()
    {
        return $this->belongsToMany('App\Models\Permission','secure.permissionrole'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }

    public function approvalmodule()
    {
        return $this->belongsToMany('App\Models\ApprovalModule','secure.approvalgroup','rolesid','approvalmoduleid'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }

    public function customreportgroup()
    {
        return $this->belongsToMany('App\Models\CustomreportGroup','secure.customreportgrouprole','role_id','customreportgroup_id'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }
}
