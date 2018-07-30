<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderPembelianDetail extends Model
{
    protected $table = 'pb.orderpembeliandetail';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $fillable = [
      'orderpembelianid','stockid','qtyorder','qtypenjualanbo','qtyrataratajual','qtystokakhir','hrgsatuanbrutto','disc1','disc2','ppn',
      'hrgsatuannetto','keterangan','isarowid','createdby','createdon','lastupdatedby','lastupdatedon'
    ];

    public function barang()
    {
      return $this->hasOne('App\Models\Barang','id','stockid');
    }
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

    public function getPenjualanBo($tglmulai,$tglakhir)
    {
      $columns = array(
        0 => "SUM(pj.orderpenjualandetail.qtysoacc) - SUM(pj.notapenjualandetail.qtynota) as total"
      );
      $penjualanbo  = OrderPenjualan::selectRaw(collect($columns)->implode(', '))
                        ->leftJoin('pj.orderpenjualandetail', 'pj.orderpenjualandetail.orderpenjualanid', '=', 'pj.orderpenjualan.id')
                        ->leftJoin('pj.notapenjualan', 'pj.notapenjualan.orderpenjualanid', '=', 'pj.orderpenjualan.id')
                        ->leftJoin('pj.notapenjualandetail', 'pj.notapenjualandetail.notapenjualanid', '=', 'pj.notapenjualan.id')
                        ->where('pj.orderpenjualandetail.stockid','=',$this->stockid)
                        ->where("pj.orderpenjualan.tglpickinglist",'>=',Carbon::parse($tglmulai))->where("pj.orderpenjualan.tglpickinglist",'<=',Carbon::parse($tglakhir))
                        ->where('pj.orderpenjualandetail.orderpembeliandetailid','=',NULL)
                        ->groupBy('pj.orderpenjualandetail.stockid')
                        ->havingRaw('SUM(pj.orderpenjualandetail.qtysoacc) - SUM(pj.notapenjualandetail.qtynota) > 0');
      $data = ($penjualanbo->first()) ? $penjualanbo->first()->total : 0;
      return $data;
    }
}
