<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class ReturPembelianDetail extends Model
{
    protected $table = 'pb.returpembeliandetail';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    protected $fillable = [
        'returpembelianid', 'historispembelian', 'notapembeliandetailid', 'stockid', 'qtyprb', 'qtynrj', 'qtyrq11',
        'hargabruttonrj', 'disc1', 'disc2', 'ppn', 'harganettonrj', 'kategoriprbid', 'nokoli', 'keteranganprb',
        'koreksirpbparentid', 'newchildid', 'isarowid', 'createdby', 'createdon', 'lastupdatedby', 'lastupdatedon',
    ];


    /* MUTATOR */
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

    /* RELATIONS */
    public function barang()
    {
        return $this->hasOne('App\Models\Barang','id','stockid');
    }

    public function returpembelian()
    {
        return $this->belongsTo('App\Models\ReturPembelian','returpembelianid');
    }

    public function notapembeliandetail()
    {
        return $this->belongsTo('App\Models\NotaPembelianDetail','notapembeliandetailid');
    }

    public function kategoriretur()
    {
        return $this->hasOne('\App\Models\KategoriRetur','id','kategoriprbid');
    }
}
