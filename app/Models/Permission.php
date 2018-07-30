<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'secure.permissions';

    public function role()
    {
        return $this->belongsToMany('App\Models\Role','secure.permissionrole')->where('groupapps','TRADING'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }
}
