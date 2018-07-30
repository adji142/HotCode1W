<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\SubCabang;

class User extends Authenticatable
{
    use Notifiable;

    protected $table    = 'secure.users';
    protected $fillable = [
        'username','name','password','active','createdby','updatedby','karyawanid'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
        Relationship Model
    */
    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanid');
    }

    public function role()
    {
        return $this->belongsToMany('App\Models\Role', 'secure.roleuser')->where('groupapps','TRADING');
    }

    public function akses()
    {
        return $this->belongsToMany(SubCabang::class, 'secure.subcabanguser');
    }

    /*
        Permission and Akses
    */
    public function hasAkses($id)
    {
        foreach($this->akses as $role) {
            if ($role->id == $id) {
                return true;
            }
        }

        return false;
    }
 
    public function assignAkses($akses)
    {
        // if (is_string($akses)) {
        //     $akses = Akses::where('name', $akses)->first();
        // }
        $akses = SubCabang::find($akses);
 
        return $this->akses()->attach($akses);
    }
 
    public function revokeAkses($akses)
    {
        // if (is_string($akses)) {
        //     $akses = Akses::where('name', $akses)->first();
        // }
        $akses = SubCabang::find($akses);
 
        return $this->akses()->detach($akses);
    }

    // ACL
    public function can($ability=null, $arguments = [])
    {
        return !is_null($ability) && $this->checkPermission($ability);
    }

    protected function checkPermission($perm)
    {
        $permissions     = $this->getAllPermissionsAllRoles();
        $permissionArray = is_array($perm) ? $perm : [$perm];

        return count(array_intersect($permissions, $permissionArray));
    }

    protected function getAllPermissionsAllRoles()
    {
        $permissionsArray = $this->role->load(['permission' => function ($query) { $query->select(['slug']); }])->pluck('permission')->toArray();
        $permissionsFlat  = array_map('strtolower', array_unique(array_flatten(array_map(function ($permission) {
            return array_pluck($permission, 'slug');
        }, $permissionsArray))));

        return $permissionsFlat;
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->role->where('groupapps','TRADING')->contains('name', $role);
        }
        return !! $role->contains($this->role);
    }
}
