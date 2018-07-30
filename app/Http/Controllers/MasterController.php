<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

use App\Models\AppSetting;
use App\Models\ArmadaKirim;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Expedisi;
use App\Models\HistoryBMK0;
use App\Models\HistoryBMK1;
use App\Models\HistoryBMK2;
use App\Models\HPP;
use App\Models\HPPA;
use App\Models\Karyawan;
use App\Models\KategoriRetur;
use App\Models\KelompokBarang;
use App\Models\Kunjungan;
use App\Models\NotaPembelianDetail;
use App\Models\NotaPenjualan;
use App\Models\NotaPenjualanDetail;
use App\Models\Numerator;
use App\Models\OrderPembelianDetail;
use App\Models\OrderPenjualan;
use App\Models\PenanggungJawabArea;
use App\Models\StatusToko;
use App\Models\SubCabang;
use App\Models\Supplier;
use App\Models\SuratJalan;
use App\Models\Toko;
use App\Models\Tokohakakses;
use App\Models\TujuanExpedisi;
use App\Models\TokoAktifPasif;

class MasterController extends Controller
{
	protected $translate = [
        'cabang'         => 'cabang',
        'gudang'         => 'gudang',
        'tujuanexpedisi' => 'tujuanexpedisi',
        'pemasok'        => 'supplier',
        'expedisi'       => 'expedisi',
        'kolektor'       => 'kolektor',
        'targetsales'    => 'targetsales',
        'kelompokbarang' => 'kelompokbarang',
        'toko'           => 'toko',
        'masterstok'     => 'barang',
        'hpp'            => 'hpp',
        'hppatable'      => 'hppa.table',
        'hppaproseshpp'  => 'hppa.proses',
        'numerator'      => 'numerator',
        'hargajual'      => 'hargajual',
        'appsetting'     => 'appsetting',
        'tokoaktifpasif' => 'tokoaktifpasif',
        'tokohakakses'   => 'tokohakakses',
    ];

	protected $class = [
        'cabang'         => Cabang::class,
        'gudang'         => SubCabang::class,
        'tujuanexpedisi' => TujuanExpedisi::class,
        'supplier'       => Supplier::class,
        'expedisi'       => Expedisi::class,
        'kolektor'       => Karyawan::class,
        'targetsales'    => Karyawan::class,
        'kelompokbarang' => KelompokBarang::class,
        'toko'           => Toko::class,
        'barang'         => Barang::class,
        'hpp'            => HPP::class,
        'hppa.table'     => HPPA::class,
        'hppa.proses'    => HPPA::class,
        'numerator'      => Numerator::class,
        'hargajual'      => Barang::class,
        'appsetting'     => AppSetting::class,
        'tokoaktifpasif' => TokoAktifPasif::class,
        'tokohakakses'   => Tokohakakses::class,
	];

    protected $original_columns = [
        'barang' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'mstr.kelompokbarang.keterangan',
            3 => 'mstr.stock.satuan',
            4 => 'mstr.stock.area1',
            5 => 'mstr.stock.statusaktif',
        ],
        'cabang' => [
            0 => 'mstr.perusahaan.nama',
            1 => 'mstr.cabang.kodecabang',
            2 => 'mstr.cabang.nama',
            3 => 'mstr.cabang.kota',
        ],
        'gudang' => [
            0 => 'kodesubcabang',
            1 => 'mstr.cabang.nama',
            2 => 'namasubcabang',
            3 => 'initsubcabang',
            4 => 'Aktif',
        ],
        'hpp' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'to_char(max(acc.historyhpp.tglaktif), \'dd-mm-yyyy\')',
            3 => 'max(acc.historyhpp.nominalhpp)',
            4 => 'acc.historyhpp.stockid'
        ],
        'hppa.table' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'to_char(max(acc.historyhppa.tglaktif), \'dd-mm-yyyy\')',
            3 => 'max(acc.historyhppa.nominalhppa)',
            4 => 'acc.historyhppa.stockid',
        ],
        'appsetting' => [
            0 => 'mstr.subcabang.kodesubcabang',
            1 => 'mstr.appsetting.keyid',
            2 => 'mstr.appsetting.keterangan',
            3 => 'mstr.appsetting.value',
        ],
        'tokoaktifpasif' => [
            0 => 'mstr.tokoaktifpasif.id',
            1 => 'mstr.tokoaktifpasif.tokoid',
            2 => 'mstr.tokoaktifpasif.tglstatus',
            3 => 'mstr.tokoaktifpasif.statusaktif',
            4 => 'mstr.tokoaktifpasif.keterangan',
            5 => 'mstr.tokoaktifpasif.lastupdatedby',
        ],
        'tokohakakses' => [
            0 => 'mstr.tokohakakses.id',
            1 => 'mstr.tokohakakses.tokoid',
            2 => 'mstr.tokohakakses.tglaktif',
            3 => 'mstr.subcabang.kodesubcabang',
            4 => 'mstr.perusahaan.initperusahaan',
            5 => 'mstr.tokohakakses.lastupdatedby',
        ],
    ];

    protected $columns = [
        'cabang' => [
            0 => 'mstr.perusahaan.nama',
            1 => 'mstr.cabang.kodecabang',
            2 => 'mstr.cabang.nama as namacabang',
            3 => 'mstr.cabang.kota',
            4 => 'mstr.cabang.id'
        ],
        'gudang' => [
            0 => 'kodesubcabang',
            1 => 'mstr.cabang.nama as namacabang',
            2 => 'namasubcabang',
            3 => 'initsubcabang',
            4 => 'Aktif',
            5 => 'mstr.subcabang.id'
        ],
        'tujuanexpedisi' => [
            0 => 'tujuan',
        ],
        'supplier' => [
            0 => 'kode',
            1 => 'nama',
            2 => 'alamat',
            3 => 'kota',
            4 => 'id'
        ],
        'expedisi' => [
            0 => 'kodeexpedisi',
            1 => 'namaexpedisi',
            2 => 'alamat',
            3 => 'telp',
            4 => 'kotatujuan',
            5 => 'aktif',
            6 => 'id'
        ],
        'kolektor' => [
            0 => 'niksystemlama',
            1 => 'namakaryawan',
            2 => 'kodecollector',
            3 => 'id'
        ],
        'targetsales' => [
            0 => 'niksystemlama',
            1 => 'nikhrd',
            2 => 'namakaryawan',
            3 => 'kodesales',
            4 => 'hr.karyawan.id'
        ],
        'kelompokbarang' => [
            0 => 'kode',
            1 => 'keterangan',
            2 => 'glpenjualan',
            3 => 'glreturjual',
            4 => 'glstock',
            5 => 'glhppa',
            6 => 'id'
        ],
        'barang' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'mstr.kelompokbarang.keterangan as kelompokbarang',
            3 => 'mstr.stock.satuan',
            4 => 'mstr.stock.area1',
            5 => 'mstr.stock.statusaktif',
            6 => 'mstr.stock.id'
        ],
        'toko' => [
            0 => 'tokoidwarisan',
            1 => 'tokoidwarisanlama',
            2 => 'kodetoko',
            3 => 'namatoko',
            4 => 'alamat',
            5 => 'propinsi',
            6 => 'kota',
            7 => 'kecamatan',
            8 => 'customwilayah',
            9 => 'telp',
            10 => 'fax',
            11 => 'penanggungjawab',
            12 => 'tgldob',
            13 => 'catatan',
            // 14 => 'tokoaktifpasif.statusaktif',
            14 => "CASE tokoaktifpasif.statusaktif WHEN 1::boolean THEN 'Pasif' ELSE 'Aktif' END as statusaktif",
            15 => 'pemilik',
            16 => 'mstr.toko.id',
            17 => 'tokoaktifpasif.tglstatus',
        ],
        'hpp' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'to_char(max(acc.historyhpp.tglaktif), \'dd-mm-yyyy\') as tglaktif',
            3 => 'max(acc.historyhpp.nominalhpp) as nominalhpp',
            4 => 'acc.historyhpp.stockid'
        ],
        'hppa.table' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'to_char(max(acc.historyhppa.tglaktif), \'dd-mm-yyyy\') as tglaktif',
            3 => 'max(acc.historyhppa.nominalhppa) as nominalhppa',
            4 => 'acc.historyhppa.stockid',
        ],
        'hppa.proses' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'tglaktif',
            3 => 'nominalhppa',
            4 => 'keterangan',
            // 0 => 'stockid',
            // 1 => 'tglaktif',
            // 2 => 'nominalhppa',
            // 3 => 'keterangan',
        ],
        'numerator' => [
            0 => 'doc',
            1 => 'depan',
            3 => 'nomor',
            4 => 'lebar'
        ],
        'hargajual' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'mstr.kelompokbarang.keterangan as kelompokbarang',
            3 => 'mstr.stock.satuan',
            4 => 'mstr.stock.id'
        ],
        'appsetting' => [
            0 => 'mstr.subcabang.kodesubcabang',
            1 => 'mstr.appsetting.keyid',
            2 => 'mstr.appsetting.keterangan',
            3 => 'mstr.appsetting.value',
        ],
        'tokoaktifpasif' => [
            0 => 'mstr.tokoaktifpasif.id',
            1 => 'mstr.tokoaktifpasif.tokoid',
            2 => 'mstr.tokoaktifpasif.tglstatus',
            3 => "CASE mstr.tokoaktifpasif.statusaktif WHEN 1::boolean THEN 'Pasif' ELSE 'Aktif' END as statusaktif",
            4 => 'mstr.tokoaktifpasif.keterangan',
            5 => 'mstr.tokoaktifpasif.lastupdatedby',
        ],
        'tokohakakses' => [
            0 => 'mstr.tokohakakses.id',
            1 => 'mstr.tokohakakses.tokoid',
            2 => 'mstr.tokohakakses.tglaktif',
            3 => 'mstr.subcabang.kodesubcabang',
            4 => 'mstr.perusahaan.initperusahaan',
            5 => 'mstr.tokohakakses.lastupdatedby',
        ],
	];

    protected $joining = [
        'cabang' => [
            0 => 'mstr.perusahaan',
            1 => 'mstr.cabang.perusahaanid',
            2 => '=',
            3 => 'mstr.perusahaan.id',
        ],
        'gudang' => [
            0 => 'mstr.cabang',
            1 => 'mstr.subcabang.cabangid',
            2 => '=',
            3 => 'mstr.cabang.id',
        ],
        'targetsales' => [
            0 => 'mstr.cabang',
            1 => 'left(kodesales,2)',
            2 => '=',
            3 => 'mstr.cabang.kodecabang',
        ],
        'barang' => [
            0 => 'mstr.kelompokbarang',
            1 => 'mstr.stock.kelompokbarangid',
            2 => '=',
            3 => 'mstr.kelompokbarang.id',
        ],
        'hpp' => [
            0 => 'mstr.stock',
            1 => 'acc.historyhpp.stockid',
            2 => '=',
            3 => 'mstr.stock.id',
        ],
        'hppa.table' => [
            0 => 'mstr.stock',
            1 => 'acc.historyhppa.stockid',
            2 => '=',
            3 => 'mstr.stock.id',
        ],
        'hppa.proses' => [
            0 => 'mstr.stock',
            1 => 'acc.historyhppa.stockid',
            2 => '=',
            3 => 'mstr.stock.id',
        ],
        'hargajual' => [
            0 => 'mstr.kelompokbarang',
            1 => 'mstr.stock.kelompokbarangid',
            2 => '=',
            3 => 'mstr.kelompokbarang.id',
        ],
        'appsetting' => [
            0 => 'mstr.subcabang',
            1 => 'mstr.appsetting.recordownerid',
            2 => '=',
            3 => 'mstr.subcabang.id',
        ],
        'toko' => [
            0 => '(SELECT distinct on (tokoid) tokoid, tglstatus, statusaktif FROM mstr.tokoaktifpasif order by tokoid, tglstatus desc) tokoaktifpasif',
            1 => 'mstr.toko.id',
            2 => '=',
            3 => 'tokoaktifpasif.tokoid',
        ],
    ];

    protected $columns_detail = [
        'hpp' => [
            0 => 'to_char(acc.historyhpp.tglaktif, \'dd-mm-yyyy\') as tglaktif',
            1 => 'acc.historyhpp.nominalhpp',
            2 => 'acc.historyhpp.keterangan',
        ],
        'hppa.table' => [
            0 => 'to_char(acc.historyhppa.tglaktif, \'dd-mm-yyyy\') as tglaktif',
            1 => 'acc.historyhppa.nominalhppa',
            2 => 'acc.historyhppa.keterangan',
        ],
        'targetsales' => [
            0 => 'to_char(batch.targetsalescabangmonthly.periode, \'dd-mm-yyyy\') as periode',
            1 => 'batch.targetsalescabangmonthly.targetfb2',
            2 => 'batch.targetsalescabangmonthly.targetfb4',
            3 => 'batch.targetsalescabangmonthly.targetfb',
            4 => 'batch.targetsalescabangmonthly.targetfe2',
            5 => 'batch.targetsalescabangmonthly.targetfe4',
            6 => 'batch.targetsalescabangmonthly.targetfe',
            7 => 'batch.targetsalescabangmonthly.targetall',
        ],
        'hargajual1' => [
            0 => 'to_char(tglaktif, \'dd-mm-yyyy\') as tglaktif',
            1 => 'qtyminb1',
            2 => 'hrgb1',
            3 => 'qtyminm1',
            4 => 'hrgm1',
            5 => 'qtymink1',
            6 => 'hrgk1',
        ],
        'hargajual2' => [
            0 => 'to_char(tglaktif, \'dd-mm-yyyy\') as tglaktif',
            1 => 'qtyminb2',
            2 => 'hrgb2',
            3 => 'qtyminm2',
            4 => 'hrgm2',
            5 => 'qtymink2',
            6 => 'hrgk2',
        ],
    ];

    // protected $custom = [
    //     'kolektor'    => "left(kodekhusus, 1) = 'C'",
    //     'targetsales' => "left(kodekhusus, 2) < '100' and left(kodekhusus, 2) >= '0'"
    // ];
    protected $custom = [
        'kolektor'    => "kodecollector != ''",
        'targetsales' => "kodesales != ''",
    ];

    protected $custom_detail = [
        'karyawan'   => "id = %u",
        'hpp'        => "stockid = %u",
        'hppa.table' => "stockid = %u",
        'hargajual'  => "stockid = %u",
    ];

    protected $filter_barang = [
        'hpp'        => "mstr.stock.namabarang ilike ",
        'hppa.table' => "mstr.stock.namabarang ilike ",
        'hargajual'  => "mstr.stock.namabarang ilike ",
    ];

    protected $filter_statusaktif = [
        'hpp'        => "mstr.stock.statusaktif %s '%u'",
        'hppa.table' => "mstr.stock.statusaktif %s '%u'",
        'hargajual'  => "mstr.stock.statusaktif %s '%u'",
    ];

    protected $filter_kelompokbarang = [
        'hpp'        => "left(mstr.stock.kodebarang, 2) %s '%s'",
        'hppa.table' => "left(mstr.stock.kodebarang, 2) %s '%s'",
        'hargajual'  => "left(mstr.stock.kodebarang, 2) %s '%s'",
    ];

    protected $ownerid = [
        'tujuanexpedisi' => true,
        'expedisi'       => true,
        'numerator'      => true,
    ];

    protected $grouping = [
        'hpp' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'acc.historyhpp.stockid',
        ],
        'hppa.table' => [
            0 => 'mstr.stock.kodebarang',
            1 => 'mstr.stock.namabarang',
            2 => 'acc.historyhppa.stockid',
        ],
    ];

    public function index()
    {
    	$index = $this->getRouteIndex();
        $bulan = ['01' => 'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL','05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'];
        $tahun = [];
        for($i = date('Y'); $i>= date('Y')-5; $i--){
            $tahun[] = $i;
        }

    	return view('master.'.$index.'.index',['bulan'=>$bulan,'tahun'=>$tahun]);
    }

    public function getRouteIndex()
    {
        $route = request()->route()->getName();
        $name  = substr($route, 0, strpos($route, '.'));
        $name  = $this->translate[$name];
    	return $name;
    }

    public function getJoin($master, $index)
    {
        if(array_key_exists($index, $this->joining)){
            $data = $this->joining[$index];
            if($index == 'hppa.proses') {
                return $master->leftJoin($data[0],DB::raw($data[1]),$data[2],DB::raw($data[3]));
            }elseif($index == 'toko') {
                return $master->leftjoin(DB::raw($data[0]),DB::raw($data[1]),$data[2],DB::raw($data[3]));
            }else{
                return $master->join($data[0],DB::raw($data[1]),$data[2],DB::raw($data[3]));//$this->class[$index]::join($data[0],DB::raw($data[1]),$data[2],DB::raw($data[3]));
            }
        }else{
            return $master;//$this->class[$index]::select();
        }
    }

    public function getCustom($master, $index, $filter = null)
    {
        if(array_key_exists($index, $this->custom)){
            return $master->whereRaw($this->custom[$index]);
        }else{
            return $master;
        }
    }

    public function getCustomDetail($master, $index, $filter = null)
    {
        if(array_key_exists($index, $this->custom_detail)){
            $query = sprintf($this->custom_detail[$index], $filter);
            return $master->whereRaw($query);
        }else{
            return $master;
        }
    }

    public function getFilterBarang($master, $index, $filter)
    {
        if(array_key_exists($index, $this->filter_barang)){
            $query = $this->filter_barang[$index]."'%".$filter."%'";
            return $master->whereRaw($query);
        }else{
            return $master;
        }
    }

    public function getFilterStatusAktif($master, $index, $filter)
    {
        if(array_key_exists($index, $this->filter_statusaktif)){
            if($index == 'hpp' || $index == 'hppa.table' || $index == 'hargajual'){
                if($filter < 2){
                    $query = sprintf($this->filter_statusaktif[$index],'=',$filter);
                }else{
                    return $master;
                }
            }else{
                $query = sprintf($this->filter_statusaktif[$index],'=',$filter);
            }
            return $master->whereRaw($query);
        }else{
            return $master;
        }
    }

    public function getFilterKelompokBarang($master, $index, $filter)
    {
        if(array_key_exists($index, $this->filter_kelompokbarang)){
            if($index == 'hpp' || $index == 'hppa.table' || $index == 'hargajual'){
                if($filter == 'fbfe'){
                    $query1 = sprintf($this->filter_kelompokbarang[$index],'=','FB');
                    $query2 = sprintf($this->filter_kelompokbarang[$index],'=','FE');
                    return $master->where(function($query) use ($query1,$query2){
                        $query->whereRaw($query1);
                        $query->orWhereRaw($query2);
                    });
                }elseif($filter == 'fb'){
                    $query = sprintf($this->filter_kelompokbarang[$index],'=','FB');
                }elseif($filter == 'fe'){
                    $query = sprintf($this->filter_kelompokbarang[$index],'=','FE');
                }elseif($filter == 'nonfbfe'){
                    $query1 = sprintf($this->filter_kelompokbarang[$index],'!=','FB');
                    $query2 = sprintf($this->filter_kelompokbarang[$index],'!=','FE');
                    return $master->where(function($query) use ($query1,$query2){
                        $query->whereRaw($query1);
                        $query->whereRaw($query2);
                    });
                }else{
                    return $master;
                }
            }else{
                $query = sprintf($this->filter_kelompokbarang[$index],'=',$filter);
            }
            return $master->whereRaw($query);
        }else{
            return $master;
        }
    }

    public function getGroupBy($master, $index)
    {
        if(array_key_exists($index, $this->grouping)){
            return $master->groupBy($this->grouping[$index]);
        }else{
            return $master;
        }
    }

    public function getOwnerid($master, $index, $subcabang)
    {
        if(array_key_exists($index, $this->ownerid)){
            return $master->where('recordownerid', $subcabang);
        }else{
            return $master;
        }
    }

    public function getData(Request $req)
    {
        $index            = $this->getRouteIndex();
        $filter_count     = 0;
        $empty_filter     = 0;
        $class            = $this->class[$index];
        $columns          = $this->columns[$index];

        // gunakan permission dari indexnya aja
        $route = request()->route()->getName();
        $name  = substr($route, 0, strpos($route, '.'));
        if($name == 'toko' || $name == 'hpp' || $name == 'hppatable' || $name == 'hppaproseshpp' || $name == 'numerator' || $name == 'hargajual') {
            if(!$req->user()->can($name.'.index')) {
                return response()->json([
                    'draw'            => $req->draw,
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                ]);
            }
        }

        if($req->namabarang){
            $req->session()->put('namabarang', $req->namabarang);
        }
        if(array_key_exists($index, $this->original_columns)){
            $original_columns = $this->original_columns[$index];
        }else{
            $original_columns = $columns;
        }
        for ($i=0; $i < count($columns); $i++) {
            if(empty($req->custom_search[$i]['text'])){
                $empty_filter++;
            }
        }

        $master = $class::selectRaw(collect($columns)->implode(', '));
        $master = $this->getCustom($master, $index);
        $master = $this->getOwnerid($master, $index, $req->session()->get('subcabang'));
        $master = $this->getJoin($master, $index);

        if($req->namabarang){
            $master = $this->getFilterBarang($master, $index, $req->namabarang);
        }
        if($req->statusaktif){
            $master = $this->getFilterStatusAktif($master, $index, $req->statusaktif);
        }
        if($req->kelompokbarang){
            $master = $this->getFilterKelompokBarang($master, $index, $req->kelompokbarang);
        }

        // HPPA rata2
        if($req->bulan && $req->tahun && $index == 'hppa.proses'){
            $master = $master->whereRaw('(EXTRACT(MONTH FROM acc.historyhppa.tglaktif), EXTRACT(YEAR FROM acc.historyhppa.tglaktif)) = ('.$req->bulan.','.$req->tahun.')');
        }

        // Toko Aktif Pasif
        if($req->tokoid){
            $master = $master->whereRaw('mstr.tokoaktifpasif.tokoid = ' . $req->tokoid);
        }

        // Toko Hak Akses
        if($req->tkid){
            $master->leftJoin('mstr.toko', 'mstr.tokohakakses.tokoid', '=', 'mstr.toko.id');
            $master->leftJoin('mstr.subcabang', 'mstr.tokohakakses.recordownerid', '=', 'mstr.subcabang.id');
            $master->leftjoin('mstr.cabang', 'mstr.subcabang.cabangid', '=', 'mstr.cabang.id');
            $master->leftjoin('mstr.perusahaan', 'mstr.cabang.perusahaanid', '=', 'mstr.perusahaan.id');
            $master->whereRaw('mstr.toko.id = ' . $req->tkid);
        }

        $master = $this->getGroupBy($master, $index);
        // limit 
        $total_data = $master->count();
        if($empty_filter < count($columns)){
            for ($i=0; $i < count($columns); $i++) {
                if(!empty($req->custom_search[$i]['text'])){
                    if($filter_count == 0 ){
                        if(array_key_exists('tipe', $req->custom_search[$i])){
                            if(($req->custom_search[$i]['tipe'] == 'number') || ($req->custom_search[$i]['tipe'] == 'boolean')){
                                $master->where($original_columns[$i],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                            }else{
                                if($req->custom_search[$i]['filter'] == '='){
                                    $master->where($original_columns[$i],'ilike','%'.$req->custom_search[$i]['text'].'%');
                                }else{
                                    $master->where($original_columns[$i],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                                }
                            }
                        }else{
                            $master->where($original_columns[$i],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                        }
                    }else{
                        if(array_key_exists('tipe', $req->custom_search[$i])){
                            if(($req->custom_search[$i]['tipe'] == 'number') || ($req->custom_search[$i]['tipe'] == 'boolean')){
                                $master->where($original_columns[$i],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                            }else{
                                if($req->custom_search[$i]['filter'] == '='){
                                    $master->where($original_columns[$i],'ilike','%'.$req->custom_search[$i]['text'].'%');
                                }else{
                                    $master->where($original_columns[$i],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                                }
                            }
                        }else{
                            $master->where($original_columns[$i],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                        }
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $master->count();
        }else{
            $filtered_data = $total_data;
        }
        $master->skip($req->start)->take($req->length);
        // if(array_key_exists($req->order[0]['column'], $columns)){
        //     $master->orderBy($columns[$req->order[0]['column']], $req->order[0]['dir']);
        //     // $master->orderByRaw($columns[$req->order[0]['column']].' '.$req->order[0]['dir']);
        // }
        // limit
        $data   = $master->get()->toArray();

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function getDataDetail(Request $req)
    {
        $index   = $this->getRouteIndex();
        $class   = $this->class[$index];
        if($index == 'hargajual'){
            $columns = $this->columns_detail[$index.$req->tipebmk];
        }else{
            $columns = $this->columns_detail[$index];
        }
        $master  = $class::selectRaw(collect($columns)->implode(', '));
        if($index == 'targetsales'){
            $master->join('batch.targetsalescabangmonthly','batch.targetsalescabangmonthly.karyawansalesid','=','hr.karyawan.id');
        }elseif($index == 'hargajual'){
            if($req->tipebmk == 1) {
                $master->join('pj.historybmk1','pj.historybmk1.stockid','=','mstr.stock.id');
                $master->orderBy('pj.historybmk1.tglaktif','desc');
            }elseif($req->tipebmk == 2) {
                $master->join('pj.historybmk2','pj.historybmk2.stockid','=','mstr.stock.id');
                $master->orderBy('pj.historybmk2.tglaktif','desc');
            }else {
                $master->join('pj.historybmk0','pj.historybmk0.stockid','=','mstr.stock.id');
                $master->orderBy('pj.historybmk0.tglaktif','desc');
            }
            $master->skip($req->start)->take($req->length);
        }
        $master  = $this->getCustomDetail($master, $index, $req->id);

        if($index == 'hargajual'){
            $tipebmk   = ($req->tipebmk) ? $req->tipebmk : 0;
            $barang    = Barang::find($req->id);
            $total     = count($master->get()->toArray());
            $mupbarang = null;

            if($barang && $barang->jenis_tipe_brg == 'FB') {
                $mupbarang = AppSetting::select('value')->where('recordownerid',$req->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
            }elseif($barang && $barang->jenis_tipe_brg == 'FE') {
                $mupbarang = AppSetting::select('value')->where('recordownerid',$req->session()->get('subcabang'))->where('keyid','MUPbarangE')->first();
            }

            $data = $master->get()->toArray();
            if($mupbarang) {
                foreach ($data as $key => $val) {
                    $data[$key]['hrgb'.$tipebmk] = $data[$key]['hrgb'.$tipebmk]+($data[$key]['hrgb'.$tipebmk]*$mupbarang->value/100);
                    $data[$key]['hrgm'.$tipebmk] = $data[$key]['hrgm'.$tipebmk]+($data[$key]['hrgm'.$tipebmk]*$mupbarang->value/100);
                    $data[$key]['hrgk'.$tipebmk] = $data[$key]['hrgk'.$tipebmk]+($data[$key]['hrgk'.$tipebmk]*$mupbarang->value/100);
                }
            }

            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => $total,
                'recordsFiltered' => $total,
                'data'            => $data,
            ]);
        }else{
            return response()->json([
                'data' => $master->get()->toArray(),
            ]);
        }
    }

    public function edit()
    {
        return 'masih developt';
    }

    // query ini jangan dihapus
    // select mstr.stock.kodebarang, mstr.stock.namabarang, a.nominalhppa, a.tglaktif
    // from acc.historyhppa a
    // inner join (select stockid, max(acc.historyhppa.tglaktif) as tglaktif
    //             from acc.historyhppa
    //             group by stockid) b
    //             on a.stockid = b.stockid AND a.tglaktif = b.tglaktif
    // inner join mstr.stock on a.stockid = mstr.stock.id
    // where mstr.stock.namabarang ilike '%accu%' and (left(mstr.stock.kodebarang, 2) = 'FB')



    // Pindahan Dari Macem2 Lookup
    public function getSupplier(Request $req, $recowner, $query=null)
    {
        $supplier = Supplier::select('id','kode','nama')
        ->where('recordownerid', '=', $recowner);
        if($query){
            $supplier->where(function ($q) use($query){
                $q->where('kode', 'ilike', '%'.$query.'%');
                $q->orWhere('nama', 'ilike', '%'.$query.'%');
            });
        }

        // Tambah Filter
        $filter_count   = 0;
        $filter_columns = [
            0 => "kode",
            1 => "nama",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $supplier->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                        }else{
                            $supplier->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                        }
                        $filter_count++;
                    }
                }
            }
        }

        return json_encode($supplier->get());
    }

    public function getQtyStockGudang($tgl, $stockid, $recownerid)
    {
        // $con = curl_init();
        // // $url = "http://dbproduction.sas-autoparts.com/wiserlogic/public/stock/fnNewGetStokAkhir/".$tgl."/".$stockid."/".$recownerid;
        // $url = "http://apppro.sas-autoparts.com/wiserlogic/public/stock/fnNewGetStokAkhir/".$tgl."/".$stockid."/".$recownerid;
        // curl_setopt($con, CURLOPT_URL, $url);
        // curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($con);
        // curl_close ($con);

        // New
        $response = collect(DB::table(DB::raw("mstr.fnnewgetstokakhir('$tgl',$stockid,$recownerid)"))->first())->toArray();
        return $response;
    }
  
    // public function getBarang(Request $req,$query, $type=null)
    public function getBarang(Request $req)
    {
        $query = $req->katakunci;
        $type  = $req->tipe;
        $appSetting = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','LOOKUPSTOCKMAXITEM')->first();
        $barang     = Barang::select('id','kodebarang','namabarang','satuan','kategoripenjualan');

        // OPTION FILTER
        if (isset($req->optfilter) && gettype($req->optfilter) == 'array') {
            foreach ($req->optfilter as $k => $v) {
                switch ($k) {
                    case 'typebarang':
                        $cnds = [];
                        $ls = explode("|", $v);
                        foreach ($ls as $lsc) $cnds[] = "kodebarang ilike '" . $this->strEscape($lsc) . "%'";
                        if (count($cnds) > 0) $barang->whereRaw("(" . join($cnds, " OR ") . ")");
                        break;
                }
            }
        }

        if ($type != null) {
            if ($type=='KC' || $type=='TC') {
                $barang->where('kodebarang','ilike','FE%');
            }elseif ($type=='K2' || $type=='T2') {
                $barang->where('kodebarang','ilike','FB2%');
            }elseif ($type=='K4' || $type=='T4') {
                // $barang->whereRaw("kodebarang not ilike 'FE%'");
                // $barang->whereRaw("kodebarang not ilike 'FB2%'");
            }
        }

        $barang->where('statusaktif','=','TRUE');
        $barang->where(function($q) use ($query){
            $q->where('kodebarang','ilike','%'.$query.'%');
            $q->orWhere(DB::raw("regexp_replace(namabarang,'\\s+','','g')"),'ilike','%'.str_replace(' ','',$query).'%');
            if(is_numeric($query)){
                $q->orWhere('id', '=', $query);
            }
        });
        $barang->orderBy('namabarang');

        // Tambah Filter
        $filter_count   = 0;
        $filter_columns = [
            0 => "kodebarang",
            1 => "namabarang",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $barang->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                        }else{
                            $barang->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                        }
                        $filter_count++;
                    }
                }
            }
        }

        $jumlah    = count($barang->get());
        $json_data = [];
        $tgl       = Carbon::now()->format('Y-m-d');

        if($jumlah > $appSetting->value){
            foreach ($barang->get() as $key=>$value) {
                $json_data[$key] = $value->toArray();
                $json_data[$key]['jmlgudang'] = '?';
            }
        }else {
            foreach ($barang->get() as $key=>$value) {
                $stockid = $value->id;
                $json    = $this->getQtyStockGudang($tgl, $stockid, $req->session()->get('subcabang'));

                $json_data[$key] = $value->toArray();
                $json_data[$key]['jmlgudang'] =  $json['total'];
            }
        }
        return json_encode($json_data);
    }

    public function getKategoriRetur(Request $req, $query = false)
    {
        $data = KategoriRetur::select('id','kode','nama');
        if($query != false){
            $data->where(function($q) use($query) {
                $q->where('kode', 'ilike', '%'.$query.'%')
                ->orWhere('nama', 'ilike', '%'.$query.'%');
            });    
        }     

        // Tambah Filter
        $filter_count   = 0;
        $filter_columns = [
            0 => "kode",
            1 => "nama",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                        }else{
                            $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                        }
                        $filter_count++;
                    }
                }
            }
        }

        return $data->get()->toJson();
    }

    public function getSubCabang(Request $req, $query){
        $subcabang = SubCabang::select('id','kodesubcabang','namasubcabang')
        ->where('kodesubcabang', 'ilike', '%'.$query.'%')
        ->orWhere('namasubcabang', 'ilike', '%'.$query.'%')
        ->orderBy('kodesubcabang','asc');

        // Tambah Filter
        $filter_count   = 0;
        $filter_columns = [
            0 => "kodesubcabang",
            1 => "namasubcabang",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $subcabang->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                        }else{
                            $subcabang->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                        }
                        $filter_count++;
                    }
                }
            }
        }

        return json_encode($subcabang->get());
    }

    public function cekSubcabang($id)
    {
        return SubCabang::find($id)->kodesubcabang;
    }

    public function getToko(Request $req,$query=null){
        $subcabang = $req->session()->get('subcabang');

        // Tambah Filter
        $filter_query   = "";
        $filter_count   = 0;
        $filter_columns = [
            0 => "namatoko",
            1 => "alamat",
            2 => "customwilayah",
            3 => "kecamatan",
            4 => "kota",
            6 => "mstr.toko.id",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $filter_query .= 'AND '.$filter_columns[$index].' ilike \'%'.$fil['text'].'%\' ';
                        }else{
                            $filter_query .= 'AND '.$filter_columns[$index].' not ilike \'%'.$fil['text'].'%\' ';
                        }
                        $filter_count++;
                    }
                }
            }
        }

        $query = "
            SELECT mstr.toko.id, 
                namatoko, 
                (CASE WHEN LEFT(statustoko.status,1) = 'B' THEN 'A'
                      WHEN LEFT(statustoko.status,1) = 'M' THEN 'G'
                      ELSE 'R'
                END) as statustoko,
                alamat, 
                customwilayah, 
                propinsi, 
                kota, 
                kecamatan, 
                kodetoko
            FROM mstr.toko
            JOIN mstr.tokohakakses ON mstr.toko.id = mstr.tokohakakses.tokoid
            JOIN (
                SELECT distinct on (tokoid) tokoid, tglaktif, status FROM mstr.statustoko order by tokoid
            )statustoko ON mstr.toko.id = statustoko.tokoid
            JOIN (
                SELECT distinct on (tokoid) tokoid, tglstatus, statusaktif FROM mstr.tokoaktifpasif order by tokoid, tglstatus desc
            ) tokoaktifpasif ON mstr.toko.id = tokoaktifpasif.tokoid
            WHERE mstr.tokohakakses.recordownerid = $subcabang
            AND tokoaktifpasif.statusaktif = TRUE
            AND namatoko ilike '%$query%'
            $filter_query
        ";
        
        $toko = DB::select(DB::raw($query));
        
        // $toko = Toko::select('mstr.toko.id','namatoko','alamat','customwilayah','propinsi','kota','kecamatan')
        // ->join('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid')
        // ->where("mstr.tokohakakses.recordownerid",'=',$req->session()->get('subcabang'))
        // ->where('namatoko', 'ilike', '%'.$query.'%')
        // // ->where('statusaktif', '=', TRUE)
        // ->orderBy("mstr.tokohakakses.tglaktif",'DESC')
        // ->get();

        return json_encode($toko);
    }

    public function getTokoDetail($idtoko){
        $toko   = Toko::find($idtoko);
        $status = StatusToko::select('status')->where([['tokoid',$idtoko],['tglaktif',StatusToko::where('tokoid',$idtoko)->max('tglaktif')]])->first();

        // Sales Fixedroute
        $sales_fr = Kunjungan::select('karyawanidsalesman','namakaryawan')
        ->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.kunjungansales.karyawanidsalesman')
        ->whereRaw("tglkunjung > NOW() - INTERVAL '30 days'")
        ->where('tokoid',$idtoko)->limit(1)->first();

        // Sales Nota
        $sales_nota = NotaPenjualan::select('karyawanidsalesman','namakaryawan')
        ->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.notapenjualan.karyawanidsalesman')
        ->whereRaw("tglproforma > NOW() - INTERVAL '30 days'")
        ->where('tokoid',$idtoko)->limit(1)->first();

        // Sales Picking
        $sales_pl = OrderPenjualan::select('karyawanidsalesman','namakaryawan')
        ->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.orderpenjualan.karyawanidsalesman')
        ->whereRaw("tglpickinglist > NOW() - INTERVAL '30 days'")
        ->where('tokoid',$idtoko)->limit(1)->first();

        // Sales Picking
        $sales_hakakses = Tokohakakses::select('karyawanidsalesman','namakaryawan')
        ->join('hr.karyawan', 'hr.karyawan.id', '=', 'mstr.tokohakakses.karyawanidsalesman')
        ->where('tokoid',$idtoko)->limit(1)->first();

        if($sales_fr) {
            $karyawanidsalesman = $sales_fr->karyawanidsalesman;
            $namasalesman = $sales_fr->namakaryawan;
        }elseif($sales_nota) {
            $karyawanidsalesman = $sales_nota->karyawanidsalesman;
            $namasalesman = $sales_nota->namakaryawan;
        }elseif($sales_pl) {
            $karyawanidsalesman = $sales_pl->karyawanidsalesman;
            $namasalesman = $sales_pl->namakaryawan;
        }elseif($sales_hakakses) {
            $karyawanidsalesman = $sales_hakakses->karyawanidsalesman;
            $namasalesman = $sales_hakakses->namakaryawan;
        }else{
            $karyawanidsalesman = null;
            $namasalesman = null;
        }

        $tokojw = $toko->tokojw()->orderBy("tglaktif", "desc")->first();

        $data = [
            'alamat'             => $toko->alamat,
            'kota'               => $toko->kota,
            'kecamatan'          => $toko->kecamatan,
            'customwilayah'      => $toko->customwilayah,
            'idtoko'             => $toko->id,
            'jwkredit'           => intval(count($tokojw) > 0 ? $tokojw->jwkredit : $toko->jangkawaktukredit),
            'jwkirim'            => intval(count($tokojw) > 0 ? $tokojw->jwkirim : $toko->harikirim),
            'jwsales'            => intval(count($tokojw) > 0 ? $tokojw->jwsales : $toko->harisales),
            'karyawanidsalesman' => $karyawanidsalesman,
            'namasalesman'       => $namasalesman,
            'statustoko'         => ($status) ? $status->status : $toko->statusaktif,
        ];

        return json_encode($data);
    }

    public function getExpedisi(Request $req, $recowner, $query= false)
    {
        $expedisi = Expedisi::select('id','kodeexpedisi','namaexpedisi')
        ->where('recordownerid', '=', $recowner);
        if($query != false){
            $expedisi->where(function ($q) use($query){
                $q->where('kodeexpedisi', 'ilike', '%'.$query.'%')
                ->orWhere('namaexpedisi', 'ilike', '%'.$query.'%');
            });
        }

        // Tambah Filter
        $filter_count   = 0;
        $filter_columns = [
            0 => "kodeexpedisi",
            1 => "namaexpedisi",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $expedisi->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                        }else{
                            $expedisi->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                        }
                        $filter_count++;
                    }
                }
            }
        }

        return response()->json($expedisi->get());
    }

    public function getNotaPembelianDetail(Request $req, $id)
    {
        if($req->session()->exists('subcabang')) {
            $data = NotaPembelianDetail::selectRaw('
                        pb.notapembelian.nonota, pb.notapembelian.tglnota, pb.notapembeliandetail.id, pb.notapembeliandetail.notapembelianid, pb.notapembeliandetail.qtynota, pb.notapembeliandetail.qtyterima, 
                        pb.notapembeliandetail.hrgsatuanbrutto, pb.notapembeliandetail.disc1, pb.notapembeliandetail.disc2, 
                        pb.notapembeliandetail.hrgsatuannetto, SUM(pb.returpembeliandetail.qtyprb) AS qtyretur')
                    ->join('pb.notapembelian', function($join) {
                        $join->on('pb.notapembelian.id', '=', 'pb.notapembeliandetail.notapembelianid');
                    })
                    ->LeftJoin('pb.returpembeliandetail', function($join) {
                        $join->on('pb.notapembeliandetail.id', '=', 'pb.returpembeliandetail.notapembeliandetailid');
                    })
                    ->where('pb.notapembeliandetail.stockid',$id)
                    ->where('pb.notapembelian.recordownerid',$req->session()->get('subcabang'))
                    ->where('pb.notapembeliandetail.newchildid',null)
                    ->groupBy('pb.notapembelian.nonota')
                    ->groupBy('pb.notapembelian.tglnota')
                    ->groupBy('pb.notapembeliandetail.id')
                    ->havingRaw('SUM(pb.returpembeliandetail.qtyprb) < pb.notapembeliandetail.qtyterima')
                    ->get();

            foreach($data as $k=>$v) {
                if($v->tglnota != null){
                    $v->tglnota = Carbon::parse($v->tglnota)->format('d-m-Y');
                }
                $v->lastupdatedon = $v->lastupdatedon;
            }

            return $data->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getNotaPenjualanDetail(Request $req)
    {
        if($req->session()->exists('subcabang')) {
            $data = NotaPenjualanDetail::selectRaw('
                        pj.notapenjualan.nonota, pj.notapenjualan.tglnota, pj.notapenjualan.tokoid, pj.notapenjualandetail.id, pj.notapenjualandetail.notapenjualanid, pj.notapenjualandetail.qtynota, pj.notapenjualandetail.stockgudang, 
                        pj.notapenjualandetail.hrgsatuanbrutto, pj.notapenjualandetail.disc1, pj.notapenjualandetail.disc2, pj.notapenjualandetail.ppn, 
                        pj.notapenjualandetail.hrgsatuannetto, SUM(COALESCE(pj.returpenjualandetail.qtympr,0)) AS qtyretur')
                    ->join('pj.notapenjualan', function($join) {
                        $join->on('pj.notapenjualan.id', '=', 'pj.notapenjualandetail.notapenjualanid');
                    })
                    ->LeftJoin('pj.returpenjualandetail', function($join) {
                        $join->on('pj.notapenjualandetail.id', '=', 'pj.returpenjualandetail.notapenjualandetailid');
                    })
                    ->where('pj.notapenjualan.tokoid',$req->tokoid)
                    ->where('pj.notapenjualandetail.stockid',$req->barangid)
                    ->where('pj.notapenjualan.recordownerid',$req->session()->get('subcabang'))
                    // ->where('pj.notapenjualandetail.newchildid',null)
                    ->groupBy('pj.notapenjualan.nonota')
                    ->groupBy('pj.notapenjualan.tglnota')
                    ->groupBy('pj.notapenjualan.tokoid')
                    ->groupBy('pj.notapenjualandetail.id')
                    ->orderBy('pj.notapenjualan.tglnota','desc')
                    ->havingRaw('SUM(COALESCE(pj.returpenjualandetail.qtympr,0)) < pj.notapenjualandetail.qtynota')
                    // ->havingRaw('SUM(pj.returpenjualandetail.qtynrj) < pj.notapenjualandetail.stockgudang')
                    // ->havingRaw('SUM(pj.returpenjualandetail.qtynrj) < pj.notapenjualandetail.qtynota')
                    // ->limit(50)
                    ->get();

            foreach($data as $k=>$v) {
                if($v->tglnota != null){
                    $v->tglnota = Carbon::parse($v->tglnota)->format('d-m-Y');
                }

                // $v->hrgdisc1 = (1-($v->disc1/100)) * $v->hrgsatuanbrutto * $v->qtyprb;
                $v->hrgdisc1 = (1-($v->disc1/100)) * $v->hrgsatuanbrutto;
                $v->hrgdisc2 = (1-($v->disc2/100)) * $v->hrgdisc1;
                $v->lastupdatedon = $v->lastupdatedon;
            }

            if(count($data)){
                return $data->toJson();
            }
        }
        return response()->json(false);
    }

    public function getTempoKirim($id,$idtoko){
        $toko     = Toko::find($idtoko);
        $expedisi = Expedisi::find($id);
        $status   = StatusToko::select('status')->where([['tokoid',$idtoko],['tglaktif',StatusToko::where('tokoid',$idtoko)->max('tglaktif')]])->first();

        if($expedisi->kodeexpedisi =='SAS' || $expedisi->kodeexpedisi =='SAP' ){
            $data = ['jwkirim' => 0,];
        }else{
            $tokojw = $toko->tokojw()->orderBy("tglaktif", "desc")->first();
            $data = ['jwkirim' => (count($tokojw) > 0 ? $tokojw->jwkirim : 0)];
        }

        return json_encode($data);
    }

    public function getHargaBMK(Request $req, $idstock, $statusbmk){
        $subcabang = $req->session()->get('subcabang');
        $statusbmk = strtolower($statusbmk);

        $harga = null;
        $barang = Barang::find($idstock);
        // $harga  = HistoryBMK0::select('hrgb0 as hrgb','hrgm0 as hrgm','hrgk0 as hrgk','pricelist')->where([['stockid', $idstock],['tglpasif',null]])->orderBy('tglaktif','DESC')->first();
        // if ($harga == null) {
        //     if (substr($statusbmk,1) == 1) {
        //         $harga = HistoryBMK1::select('hrgb1 as hrgb','hrgm1 as hrgm','hrgk1 as hrgk','pricelist')->where('stockid', $idstock)->orderBy('tglaktif','DESC')->first();
        //     }else {
        //         $harga = HistoryBMK2::select('hrgb2 as hrgb','hrgm2 as hrgm','hrgk2 as hrgk','pricelist')->where('stockid', $idstock)->orderBy('tglaktif','DESC')->first();
        //     }
        // }
        if (substr($statusbmk,1) == 1) {
            $harga = HistoryBMK1::select('hrgb1 as hrgb','hrgm1 as hrgm','hrgk1 as hrgk','pricelist')->where('stockid', $idstock)->orderBy('tglaktif','DESC')->first();
        }else {
            $harga = HistoryBMK2::select('hrgb2 as hrgb','hrgm2 as hrgm','hrgk2 as hrgk','pricelist')->where('stockid', $idstock)->orderBy('tglaktif','DESC')->first();
        }

        $mupbarang = null;
        if($barang->jenis_tipe_brg == 'FB') {
            $mupbarang = AppSetting::select('value')->where('recordownerid',$subcabang)->where('keyid','MUPbarangB')->first();
        }elseif($barang->jenis_tipe_brg == 'FE') {
            $mupbarang = AppSetting::select('value')->where('recordownerid',$subcabang)->where('keyid','MUPbarangE')->first();
        }

        if(count($mupbarang) > 0 && count($harga) > 0) {
            $harga->hrgb = $harga->hrgb+($harga->hrgb*$mupbarang->value/100);
            $harga->hrgm = $harga->hrgm+($harga->hrgm*$mupbarang->value/100);
            $harga->hrgk = $harga->hrgk+($harga->hrgk*$mupbarang->value/100);
        }

        return response()->json($harga);
    }

    public function getHPP(Request $req,$stockid)
    {
        $hpp = HPP::select('nominalhpp');
        $hpp->where('recordownerid','=',$req->session()->get('subcabang'));
        $hpp->where('stockid','=',$stockid);
        $hpp->orderBy("tglaktif","desc")->limit(1);

        return response()->json($hpp->first()->toArray());
    }

    public function getStaff(Request $req)
    {
        $query = $req->katakunci;
        if ($req->session()->exists('subcabang')) {
            $data = Karyawan::select('hr.karyawan.id','nikhrd','namakaryawan','namadepartemen','namajabatan')
                ->leftJoin('hr.karyawanposisi','hr.karyawanposisi.karyawanid','=','hr.karyawan.id')
                ->leftJoin('hr.subdepartemenjabatan','hr.karyawanposisi.subdepartemenjabatanid','=','hr.subdepartemenjabatan.id')
                ->leftJoin('hr.departemen','hr.subdepartemenjabatan.departemenid','=','hr.departemen.id')
                ->leftJoin('hr.jabatan','hr.subdepartemenjabatan.jabatanid','=','hr.jabatan.id')
                ->where('recordownerid', '=', $req->session()->get('subcabang'))
                ->where(function ($q) use($query){
                    $q->where('niksystemlama', 'ilike', '%'.$query.'%')
                        ->where('nikhrd', 'ilike', '%'.$query.'%')
                        ->orWhere('namakaryawan', 'ilike', '%'.$query.'%');
                });

            // Tambah Filter
            $filter_count   = 0;
            $filter_columns = [
                0 => "nikhrd",
                1 => "namakaryawan",
                2 => "namadepartemen",
                3 => "namajabatan",
            ];

            if($req->filter) {
                foreach($req->filter as $index=>$filter) {
                    foreach($filter as $fil) {
                        if($fil['text'] != ''){
                            if($fil['filter'] == '='){
                                $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                            $filter_count++;
                        }
                    }
                }
            }

            return $data->get()->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getSalesman(Request $req, $recowner, $query){
        $sales = Karyawan::select('id','kodesales','namakaryawan');
        $sales->where(function ($q) use($query){;
            $q->where('namakaryawan', 'ilike', '%'.$query.'%');
            $q->orWhere('kodesales', 'ilike', '%'.$query.'%');
        });
        $sales->where('recordownerid', '=', $recowner);
        $sales->where('kodesales', '<>', null);

        // Tambah Filter
        $filter_count   = 0;
        $filter_columns = [
            0 => "kodesales",
            1 => "namakaryawan",
        ];

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($fil['filter'] == '='){
                            $sales->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                        }else{
                            $sales->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                        }
                        $filter_count++;
                    }
                }
            }
        }

        $sales->orderBy('kodesales', 'asc');
        return json_encode($sales->get());
    }

    public function getSopir(Request $req)
    {
        $query = $req->katakunci;
        if ($req->session()->exists('subcabang')) {
            $data = Karyawan::select('id','nikhrd','namakaryawan','kodesopir')
                ->where('recordownerid', '=', $req->session()->get('subcabang'))
                ->where('kodesopir', '!=', null)
                ->where(function ($q) use($query){
                    $q->where('niksystemlama', 'ilike', '%'.$query.'%')
                        ->orWhere('nikhrd', 'ilike', '%'.$query.'%')
                        ->orWhere('kodesopir', 'ilike', '%'.$query.'%')
                        ->orWhere('namakaryawan', 'ilike', '%'.$query.'%');
                });

            // Tambah Filter
            $filter_count   = 0;
            $filter_columns = [
                0 => "nikhrd",
                1 => "namakaryawan",
                2 => "kodesopir",
            ];

            if($req->filter) {
                foreach($req->filter as $index=>$filter) {
                    foreach($filter as $fil) {
                        if($fil['text'] != ''){
                            if($fil['filter'] == '='){
                                $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                            $filter_count++;
                        }
                    }
                }
            }

            return $data->get()->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getKernet(Request $req)
    {
        $query = $req->katakunci;
        if ($req->session()->exists('subcabang')) {
            $data = Karyawan::select('id','nikhrd','namakaryawan','kodekernet')
                ->where('recordownerid', '=', $req->session()->get('subcabang'))
                ->where('kodekernet', '!=', null)
                ->where(function ($q) use($query){
                    $q->where('niksystemlama', 'ilike', '%'.$query.'%')
                        ->orWhere('nikhrd', 'ilike', '%'.$query.'%')
                        ->orWhere('kodekernet', 'ilike', '%'.$query.'%')
                        ->orWhere('namakaryawan', 'ilike', '%'.$query.'%');
                });

            // Tambah Filter
            $filter_count   = 0;
            $filter_columns = [
                0 => "nikhrd",
                1 => "namakaryawan",
                2 => "kodekernet",
            ];

            if($req->filter) {
                foreach($req->filter as $index=>$filter) {
                    foreach($filter as $fil) {
                        if($fil['text'] != ''){
                            if($fil['filter'] == '='){
                                $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                            $filter_count++;
                        }
                    }
                }
            }

            return $data->get()->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getArmadaKirim(Request $req)
    {
        $query = $req->katakunci;
        if ($req->session()->exists('subcabang')) {
            $data = ArmadaKirim::select('id','namakendaraan','nomorpolisi','kmtempuh')
                ->where('recordownerid', '=', $req->session()->get('subcabang'))
                ->where(function ($q) use($query){
                    $q->where('namakendaraan', 'ilike', '%'.$query.'%')
                        ->orWhere('nomorpolisi', 'ilike', '%'.$query.'%');
                });

            // Tambah Filter
            $filter_count   = 0;
            $filter_columns = [
                0 => "nomorpolisi",
                1 => "namakendaraan",
            ];

            if($req->filter) {
                foreach($req->filter as $index=>$filter) {
                    foreach($filter as $fil) {
                        if($fil['text'] != ''){
                            if($fil['filter'] == '='){
                                $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                            $filter_count++;
                        }
                    }
                }
            }

            return $data->get()->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getPj(Request $req)
    {
        $query = $req->katakunci;
        if ($req->session()->exists('subcabang')) {
            $data = PenanggungJawabArea::select('stk.penanggungjawabarea.id','stk.penanggungjawabarea.kodearea','hr.karyawan.nikhrd','hr.karyawan.namakaryawan')
                ->leftJoin('hr.karyawan','stk.penanggungjawabarea.karyawanidpenanggungjawab','=','hr.karyawan.id')
                ->where('hr.karyawan.recordownerid', '=', $req->session()->get('subcabang'))
                ->where(function ($q) use($query){
                    $q->where('hr.karyawan.niksystemlama', 'ilike', '%'.$query.'%')
                        ->where('hr.karyawan.nikhrd', 'ilike', '%'.$query.'%')
                        ->orWhere('hr.karyawan.namakaryawan', 'ilike', '%'.$query.'%');
                });

            // Tambah Filter
            $filter_count   = 0;
            $filter_columns = [
                0 => "nikhrd",
                1 => "namakaryawan",
                2 => "stk.penanggungjawabarea.kodearea",
            ];

            if($req->filter) {
                foreach($req->filter as $index=>$filter) {
                    foreach($filter as $fil) {
                        if($fil['text'] != ''){
                            if($fil['filter'] == '='){
                                $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                            $filter_count++;
                        }
                    }
                }
            }

            return $data->get()->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getSuratJalan(Request $req)
    {
        $query = $req->katakunci;
        if ($req->session()->exists('subcabang')) {
            $data = SuratJalan::select('pj.suratjalan.id','pj.suratjalan.nosj','pj.suratjalan.tglsj','pj.suratjalan.pengirimanid','pj.suratjalan.tokoid','mstr.toko.namatoko','mstr.toko.alamat')
                ->leftJoin('mstr.toko', 'pj.suratjalan.tokoid', '=', 'mstr.toko.id')
                ->where('pj.suratjalan.recordownerid', '=', $req->session()->get('subcabang'))
                ->where('pj.suratjalan.nosj', 'ilike', '%'.$query.'%')
                ->where('pj.suratjalan.pengirimanid',null);

            // Tambah Filter
            $filter_count   = 0;
            $filter_columns = [
                0 => "nosj",
                1 => "tglsj",
                2 => "namatoko",
                3 => "alamat",
            ];

            if($req->filter) {
                foreach($req->filter as $index=>$filter) {
                    foreach($filter as $fil) {
                        if($fil['text'] != ''){
                            if($fil['filter'] == '='){
                                $data->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $data->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                            $filter_count++;
                        }
                    }
                }
            }

            return $data->get()->toJson();
        }else{
            return json_encode([]);
        }
    }

    public function getHitungBO($stockid,Request $req)
    {
        $tglmulai = date("d-m-Y", strtotime("-7 day"));
        $tglakhir = date("d-m-Y",strtotime("-1 day"));
        $tglstok  ='01-07-2016';
        //dd($stockid);
        // $tglmulai ='01-12-2016';
        // $tglakhir ='12-12-2016';
        // $tglstok  ='01-07-2016';
        $columns = array(
            0 => "mstr.stock.id as stockid",
            1 => "mstr.stock.namabarang",
            2 => "mstr.stock.satuan",
            3 => "(pb.orderpembeliandetail.qtystokakhir) as qtyorder",
            4 => "stk.standarstock.rataratajual",
            5 => "(stk.standarstock.rataratajual * mstr.stock.stockmin) as qtystockmin",
            6 => "pb.orderpembeliandetail.qtystokakhir",
        );
        $pembelian = OrderPembelianDetail::selectRaw(collect($columns)->implode(', '))
            ->join('mstr.stock', 'pb.orderpembeliandetail.stockid', '=','mstr.stock.id')
            ->join('stk.standarstock', 'stk.standarstock.stockid', '=','mstr.stock.id')
            ->where('stk.standarstock.tglaktif','=',Carbon::parse($tglstok))
            ->where('stk.standarstock.stockid','=',$stockid)
            ->skip($req->start)
            ->take($req->length);
        
        $data = array();
        foreach ($pembelian->get() as $key => $d) {
            $qtybo = $d->getPenjualanBo($tglmulai,$tglakhir);
            if($d->qtystockmin-$d->qtyorder+$qtybo > 0 && $d->stockid=$stockid){
                $data = [
                    'stockid'       => $d->stockid,
                    'namabarang'    => $d->namabarang,
                    'qtyorder'      => $d->qtystockmin-$d->qtyorder+$qtybo,
                    'rataratajual'  => $d->rataratajual,
                    'qtystockmin'   => $d->qtystockmin,
                    'qtystokakhir'  => $d->qtystokakhir,
                    'qtybo'         => $qtybo,
                ];
                // array_push($data, $temp_detail);
            }
        return response()->json($data);   
        }
    }

    public function getRiwayatOrder($stockid,$tokoid)
    {
        $riwayat = NotaPenjualan::select('nonota','tglnota','hrgsatuannetto')->join('pj.notapenjualandetail', 'pj.notapenjualan.id', '=','pj.notapenjualandetail.notapenjualanid')
        ->where('pj.notapenjualan.tokoid','=',$tokoid)
        ->where('pj.notapenjualandetail.stockid','=',$stockid)
        ->orderBy('pj.notapenjualan.tglproforma','desc')
        ->limit(1)
        ->get();

        $data = ($riwayat->first()) ? $riwayat->first() : null;
        return response()->json($data);
    }

    public function getServerDate()
    {
        return Carbon::now()->toDateTimeString();
    }

    public function tambah(Request $req)
    {
        $index      = $req->index;

        $lastUserId = auth()->user()->id;

        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                if ($index == "tokoaktifpasif")
                {
                    $tap = new TokoAktifPasif;
                    $tap->tglstatus     = Carbon::parse($req->tglstatus);
                    $tap->tokoid        = $req->tokoid;
                    $tap->statusaktif   = $req->statusaktif;
                    $tap->keterangan    = $req->keterangan;
                    $tap->createdby     = auth()->user()->username;
                    $tap->lastupdatedby = auth()->user()->username;
                    $tap->save();
                }

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => "Anda tidak memiliki kewenangan untuk menambah data!"]);
    }

    public function hapus(Request $req)
    {
        $index      = $req->index;

        $lastUserId = auth()->user()->id;

        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                if ($index == "tokoaktifpasif")
                {
                    $tap = TokoAktifPasif::find($req->id);
                    $tap->delete();
                }

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => "Anda tidak memiliki kewenangan untuk menambah data!"]);
    }

}
