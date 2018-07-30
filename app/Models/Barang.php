<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
	protected $table = 'mstr.stock';

	public function getStatusaktifmutateAttribute()
	{
		return $this->statusaktif ? 1 : 0;
	}

	public function getJenisTipeBrgAttribute()
	{
		return substr($this->kodebarang,0,2);
	}

	public function stop()
	{
		return $this->hasMany('App\Models\StockOpnameHistory','stockid');
	}

	public function npjd()
	{
		return $this->hasMany('App\Models\NotaPenjualanDetail','stockid');
	}

	public function npbd()
	{
		return $this->hasMany('App\Models\NotaPembelianDetail','stockid');
	}

	public function rpjd()
	{
		return $this->hasMany('App\Models\ReturPenjualanDetail','stockid');
	}

	public function rpbd()
	{
		return $this->hasMany('App\Models\ReturPembelianDetail','stockid');
	}

	public function gsd()
	{
		return $this->hasMany('App\Models\GudangSementaraDetail','stockid');
	}

	public function agd()
	{
		return $this->hasMany('App\Models\AntarGudangDetail','stockid');
	}

	public function mtd()
	{
		return $this->hasMany('App\Models\MutasiDetail','stockid');
	}

	public function standarstock()
	{
		return $this->hasMany('App\Models\StandarStock','stockid');
	}

	// public function latest_standarstock()
	// {
	// 	return $this->standarstock()->orderBy('tglaktif','desc')->first();
	// }


}
