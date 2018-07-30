<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Toko extends Model
{
    protected $table = 'mstr.toko';
    /*const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';*/
    public $timestamps    = false;

    // free data object
    public $tag = null;

    public function toko00() {
      return $this->hasOne('App\Models\Toko00', 'tokoid');
    }

    public function tokojw()
    {
      //return Tokojw::where("tokoid", $this->toko00->id)->first();
      return $this->hasMany('App\Models\Tokojw','tokoid');
    }

    public function status()
    {
      return $this->hasMany('App\Models\StatusToko','tokoid');
    }

    public function salesman()
    {
      return $this->belongsTo('App\Models\Karyawan','karyawanidsalesman');
    }

    public function notapenjualan()
    {
      return $this->hasMany('App\Models\NotaPenjualan','tokoid');
    }

    public function orderpenjualan()
    {
      return $this->hasMany('App\Models\OrderPenjualan','tokoid');
    }

    public function is_notnotapenjualan()
    {
      return $this->notapenjualan->where('tglnota',null);
    }

    public function is_opjpiutang()
    {
      $opj = OrderPenjualan::selectRaw('pj.orderpenjualan.id, sum(pj.notapenjualandetail.qtynota*pj.notapenjualandetail.hrgsatuannetto) as total')
                           ->leftJoin('pj.notapenjualan','pj.orderpenjualan.id','=','pj.notapenjualan.orderpenjualanid')
                           ->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid')
                           ->where('pj.orderpenjualan.tokoid',$this->id)
                           ->where('pj.orderpenjualan.noaccpiutang','!=',null)
                           ->where('pj.orderpenjualan.rpaccpiutang','!=',0)
                           ->havingRaw('pj.orderpenjualan.id not in (select pj.notapenjualan.orderpenjualanid FROM pj.notapenjualan)')
                           ->orHavingRaw('sum(pj.notapenjualandetail.qtynota*pj.notapenjualandetail.hrgsatuannetto) < pj.orderpenjualan.rpaccpiutang')
                           ->groupBy('pj.orderpenjualan.id')
                           ->get();
      return $opj;
    }
}