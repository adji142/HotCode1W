<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    protected $table = 'secure.logintoken';

    public $timestamps = false;

    public function User()
    {
        return $this->belongsTo('App\Models\User','userid');
    }
}
