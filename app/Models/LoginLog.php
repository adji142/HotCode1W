<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class LoginLog extends Model
{
	protected $table    = 'secure.loginlog';
	protected $fillable = ['recordownerid','userid','username','ipaddress','browserinfo','lastlogin','statuslogin'];
	const CREATED_AT    = 'lastlogin';
	const UPDATED_AT    = 'lastlogin';

    public function setLastloginAttribute($date)
    {
        $this->attributes['lastlogin'] = Carbon::parse($date)->toDateTimeString();
    }
    public function getLastloginAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }
}
