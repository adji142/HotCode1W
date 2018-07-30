<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaPenjualan;
use App\Models\NotaPenjualanDetailKoli;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\SuratJalanDetailTitipan;
use App\Models\Toko;

use Carbon\Carbon;
use PDF;

class SuratJalanController extends Controller
{
	protected $original_column = array(
        1 => "pj.suratjalan.tglsj",
        2 => "pj.suratjalan.nosj",
        3 => "case when pj.suratjalan.tokoid is null then 'TITIPAN' else mstr.toko.namatoko end ilike ?",
        4 => "mstr.toko.customwilayah",
        5 => "pj.suratjalan.pengirimanid",
    );

    public function index()
    {
    	return view('transaksi.suratjalan.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('suratjalan.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        // jika lolos, tampilkan data
        $req->session()->put('tglmulai', $req->tglmulai);
        $req->session()->put('tglselesai', $req->tglselesai);

        $filter_count = 0;
        $empty_filter = 0;
        $columns = array(
            0 => "pj.suratjalan.tglsj",
            1 => "pj.suratjalan.nosj",
            2 => "case when pj.suratjalan.tokoid is null then 'TITIPAN' else mstr.toko.namatoko end as namatoko",
            3 => "mstr.toko.customwilayah as wilid",
            4 => "pj.suratjalan.pengirimanid as expedisi",
            5 => "pj.suratjalan.id",
            6 => "pj.suratjalan.tokoid",
            7 => "pj.suratjalan.pengirimanid",
        );
        for ($i=1; $i < 6; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        $suratjalan = SuratJalan::selectRaw(collect($columns)->implode(', '));
        $suratjalan->leftJoin('mstr.toko', 'pj.suratjalan.tokoid', '=', 'mstr.toko.id');
        $suratjalan->where("pj.suratjalan.recordownerid",$req->session()->get('subcabang'))->where("pj.suratjalan.tglsj",'>=',Carbon::parse($req->tglmulai))->where("pj.suratjalan.tglsj",'<=',Carbon::parse($req->tglselesai));
        $total_data = $suratjalan->count();
        if($empty_filter < 5){
            for ($i=1; $i < 6; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index == 2 || $index > 3){
                        if($req->custom_search[$i]['filter'] == '='){
                            $suratjalan->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $suratjalan->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }elseif($index == 3){
                        $suratjalan->whereRaw($this->original_column[$index],['%'.$req->custom_search[$i]['text'].'%']);
                    }else{
                        $suratjalan->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $suratjalan->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $suratjalan->orderBy('pj.suratjalan.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $suratjalan->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $suratjalan->skip(0)->take($req->length);
        }else{
            $suratjalan->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($suratjalan->get() as $key => $jalan) {
            $jalan->tglsj = $jalan->tglsj;
            $data[$key]   = $jalan->toArray();
            $data[$key]['DT_RowId'] = 'gv1_'.$jalan->id;
            if($jalan->tglsj == Carbon::now()){
                if($jalan->tokoid){
                    $data[$key]['tambahsjd'] = 'tambah';
                }else{
                    $data[$key]['tambahsjd'] = 'Tidak bisa tambah record nota di surat jalan barang titipan.';
                }

                if($jalan->pengirimandetail->isEmpty()){
                    $data[$key]['tambahkoli'] = 'tambah';
                    $data[$key]['tokokoli'] = $jalan->tokoid_status;
                }else{
                    $data[$key]['tambahkoli'] = 'Tidak bisa tambah kolian. Surat jalan sudah dibawa expedisi. Hubungi manager anda.';
                    $data[$key]['tokokoli'] = $jalan->tokoid_status;
                }
            }else{
                $data[$key]['tambahsjd'] = 'Tidak bisa tambah record. Tanggal server tidak sama dengan tanggal Surat Jalan. Hubungi Manager Anda.';
                $data[$key]['tambahkoli'] = 'Tidak bisa tambah record. Tanggal server tidak sama dengan tanggal Surat Jalan. Hubungi Manager Anda.';
            }
            if($jalan->pengirimanid){
                $data[$key]['hapussj'] = 'Tidak bisa hapus record. Surat jalan sudah dibawa expedisi. Hubungi manager anda.';
            }else{
                $data[$key]['hapussj'] = 'auth';
            }
            // if($jalan->details->isEmpty() && $jalan->titipan->isEmpty()){
            //     $data[$key]['hapussj'] = 'auth';
            // }else{
            //     $data[$key]['hapussj'] = 'Tidak bisa hapus record. Sudah ada record di Surat Jalan Detail atau Suara Jalan Detail Titipan. Hubungi manager anda.';
            // }
        }
        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }

    public function getSuratJalanData(Request $req)
    {
        $sj = SuratJalan::find($req->id);

        return response()->json([
            'tglsj'             => $sj->tglsj,
            'nosj'              => $sj->nosj,
            'toko'              => ($sj->toko == null) ? '' : $sj->toko->namatoko,
            'alamat'            => ($sj->toko == null) ? '' : $sj->toko->alamat,
            'kota'              => ($sj->toko == null) ? '' : $sj->toko->kota,
            'daerah'            => ($sj->toko == null) ? '' : $sj->toko->kecamatan,
            'wilid'             => ($sj->toko == null) ? '' : $sj->toko->customwilayah,
            'idtoko'            => ($sj->toko == null) ? '' : $sj->toko->id,
            'print'             => $sj->print,
            'tglprintsj'        => $sj->tglprintsj,
            'keterangansj'      => $sj->keterangansj,
            'titipanketerangan' => $sj->titipanketerangan,
            'titipandari'       => $sj->titipandari,
            'titipanuntuk'      => $sj->titipanuntuk,
            'titipanalamat'     => $sj->titipanalamat,
            'titipannotelepon'  => $sj->titipannotelepon,
            'totalkoli'         => $sj->totalkoli(),
            'lastupdatedby'     => $sj->lastupdatedby,
            'lastupdatedon'     => $sj->lastupdatedon,
        ]);
    }

    public function getSuratJalanDetailData(Request $req)
    {
        $sjd = SuratJalanDetail::find($req->id);
        
        return response()->json([
            'tglproforma'   => $sjd->nota->tglproforma,
            'nonota'        => $sjd->nota->nonota,
            'salesman'      => $sjd->nota->karyawanidsalesman,
            'tipetransaksi' => $sjd->nota->tipetransaksi,
            'tglcheck'      => $sjd->nota->tglcheck,
            'checker1'      => $sjd->nota->karyawanidchecker1,
            'checker2'      => $sjd->nota->karyawanidchecker1,
            'lastupdatedby' => $sjd->lastupdatedby,
            'lastupdatedon' => $sjd->lastupdatedon,
        ]);
    }

    public function getSuratJalanDetailTitipanData(Request $req)
    {
        $titipan = SuratJalanDetailTitipan::find($req->id);

        return response()->json([
            "nokoli"        => $titipan->nokoli,
            "keterangan"    => $titipan->keterangan,
            "lastupdatedby" => $titipan->lastupdatedby,
            "lastupdatedon" => $titipan->lastupdatedon,
        ]);
    }

    public function getDataDetail(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('suratjalan.index')) {
            return response()->json([
                'suratjalandetail' => [],
                'sjdt'             => [],
            ]);
        }

        // jika lolos, tampilkan data
        $suratjalan              = SuratJalan::find($req->id);
        $suratjalandetail        = $suratjalan->details;
        $suratjalandetailtitipan = $suratjalan->titipan;
        $suratjalankoli          = $suratjalan->npjdk;
        $node_suratjalandetail   = array();
        $node_sjdt               = array();

        if(!$suratjalan->pengirimandetail->isEmpty()){
            $hapus_sjdt = 'Tidak bisa hapus record. Surat Jalan sudah dibawa expedisi. Hubungi Manager anda.';
        }else{
            $hapus_sjdt = 'auth';
        }

        if(!$suratjalan->npjdk->isEmpty()){
            $hapus = 'auth';
            foreach ($suratjalan->npjdk as $npjdk) {
                if($npjdk->statussj == 1){
                    $hapus = 'Tidak bisa hapus record. Nota penjualan detail koli sudah diinput. Hubungi manager anda.';
                    break;
                }
            }
        }else{
            $hapus = 'auth';
        }

        foreach ($suratjalandetail as $detail) {
            $temp_suratjalandetail = [
                'tglproforma' => $detail->nota->tglproforma,
                'nonota'      => $detail->nota->nonota,
                'temponota'   => $detail->nota->temponota,
                'id'          => $detail->id,
                'hapus'       => $hapus,
            ];
            array_push($node_suratjalandetail, $temp_suratjalandetail);
        }

        foreach ($suratjalandetailtitipan as $titipan) {
            $temp_sjdt = [
                'nokoli'     => $titipan->nokoli,
                'keterangan' => $titipan->keterangan,
                'tipe'       => 'sjdt',
                'id'         => $titipan->id,
                'hapus'      => $hapus_sjdt,
            ];
            array_push($node_sjdt, $temp_sjdt);
        }

        foreach ($suratjalankoli as $npjdk) {
            $temp_sjdt = [
                'nokoli'     => $npjdk->nokoli,
                'keterangan' => $npjdk->keterangan,
                'tipe'       => 'npjdk',
                'id'         => $npjdk->id,
                'hapus'      => $hapus_sjdt,
            ];
            array_push($node_sjdt, $temp_sjdt);
        }

        return response()->json([
            'suratjalandetail' => $node_suratjalandetail,
            'sjdt'             => $node_sjdt,
        ]);
    }

    public function getDataTitipan(Request $req)
    {
        $sjd       = SuratJalanDetail::find($req->id);
        $sjdt      = $sjd->titipan;
        $npj       = $sjd->nota;
        $node_sjdt = array();

        if(!$sjd->pd->isEmpty()){
            $hapus_sjdt = 'Tidak bisa hapus record. Surat Jalan sudah dibawa expedisi. Hubungi Manager anda.';
        }else{
            $hapus_sjdt = 'auth';
        }

        if(!$npj->details->isEmpty()){
            foreach ($npj->details as $detail) {
                if(!$detail->koli->isEmpty()){
                    foreach ($detail->koli as $koli) {
                        $temp_sjdt = [
                            'nokoli'     => $koli->nokoli,
                            'keterangan' => $koli->keterangan,
                            'tipe'       => 'npjdk',
                            'id'         => $koli->id,
                            'hapus'      => $hapus_sjdt,
                        ];
                        array_push($node_sjdt, $temp_sjdt);
                    }
                }
            } 
        }

        foreach ($sjdt as $titipan) {
            $temp_sjdt = [
                'nokoli'     => $titipan->nokoli,
                'keterangan' => $titipan->keterangan,
                'tipe'       => 'sjdt',
                'id'         => $titipan->id,
                'hapus'      => $hapus_sjdt,
            ];
            array_push($node_sjdt, $temp_sjdt);
        }

        return response()->json([
            'sjdt' => $node_sjdt,
        ]);
    }

    public function createSuratJalan(Request $req)
    {
        $npjNoSjId = $this->getNpjNoSjId($req->toko,$req->tglproforma);
        if($npjNoSjId->count() > 0){
            $unique = $npjNoSjId->unique(function($item){
                return substr($item['tipetransaksi'],0,1);
            });
            foreach ($unique as $npj) {
                $sj = SuratJalan::create([
                    'recordownerid' => $req->session()->get('subcabang'),
                    'nosj'          => $this->getNextSJNo($req->session()->get('subcabang')),
                    'tglsj'         => Carbon::now(),
                    'tokoid'        => $req->toko,
                    'keterangansj'  => $req->keterangansj,
                    'tglproforma'   => Carbon::parse($req->tglproforma),
                    'tipetransaksi' => substr($npj->tipetransaksi,0,1),
                    'createdby'     => strtoupper(auth()->user()->username),
                    'lastupdatedby' => strtoupper(auth()->user()->username),
                ]);
                $sjid = $sj->id;

                
                if(!$sj->getNpjNoSjId(substr($npj->tipetransaksi,0,1))->isEmpty()){
                    foreach ($sj->getNpjNoSjId(substr($npj->tipetransaksi,0,1)) as $npj) {
                        $sjd = SuratJalanDetail::create([
                            'notapenjualanid' => $npj->id,
                            'suratjalanid'    => $sj->id,
                            'createdby'       => strtoupper(auth()->user()->username),
                            'lastupdatedby'   => strtoupper(auth()->user()->username),
                        ]);
                    }

                    foreach ($sj->getNpjdkNoSjId(substr($npj->tipetransaksi,0,1)) as $npj) {
                        $npjdk = NotaPenjualanDetailKoli::find($npj->id);
                        if($npjdk) {
                            $npjdk->suratjalanid = $sjid;
                            $npjdk->save();
                        }
                    }
                }
            }

            

            return response()->json([
                'success' => true,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'error' => 'Toko '.Toko::find($req->toko)->namatoko.' tidak ditemukan Proforma Invoice dengan tanggal '.$req->tglproforma.' atau Proforma sudah dibuat Surat Jalan',
            ]);
        }
    }

    public function createSuratJalanDetailTitipan(Request $req)
    {
        $sj = SuratJalan::create([
            'recordownerid'     => $req->session()->get('subcabang'),
            'nosj'              => $this->getNextSJNo($req->session()->get('subcabang')),
            'tglsj'             => Carbon::now(),
            'titipanketerangan' => $req->keterangankoli,
            'titipandari'       => $req->dari,
            'titipanuntuk'      => $req->untuk,
            'titipanalamat'     => $req->alamattitipan,
            'titipannotelepon'  => $req->notelp,
            'createdby'         => strtoupper(auth()->user()->username),
            'lastupdatedby'     => strtoupper(auth()->user()->username),
        ]);

        if(strpos($req->nokolititipan, ',')){
            $nokoli = explode(',', $req->nokolititipan);
        }elseif(strpos($req->nokolititipan, '-')){
            $parsed = explode('-', $req->nokolititipan);
            $nokoli = array();
            for ($j=$parsed[0]; $j <= $parsed[1]; $j++) {
                array_push($nokoli, $j);
            }
        }else{
            $nokoli = $req->nokolititipan;
        }

        for ($j=0; $j < count($nokoli); $j++) {
            $sjdt = SuratJalanDetailTitipan::create([
                'nokoli'        => $nokoli[$j],
                'suratjalanid'  => $sj->id,
                'createdby'     => strtoupper(auth()->user()->username),
                'lastupdatedby' => strtoupper(auth()->user()->username),
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteSj($sjId)
    {
        $sj = SuratJalan::find($sjId);
        if(!$sj->details->isEmpty()){
            foreach ($sj->details as $detail) {
                if(!$detail->titipan->isEmpty()){
                    foreach ($detail->titipan as $koli) {
                        $koli->delete();
                    }
                }
                $detail->delete();
            }
        }
        if(!$sj->npjdk->isEmpty()){
            foreach ($sj->npjdk as $npjdk) {
                $npjdk->statussj = 0;
                $npjdk->suratjalanid = null;
                $npjdk->save();
            }
        }
        $sj->delete();

        return true;
    }

    public function deleteSjd($sjdId)
    {
        $sjd = SuratJalanDetail::find($sjdId);
        if(!$sjd->titipan->isEmpty()){
            foreach ($sjd->titipan as $koli) {
                $koli->delete();
            }
        }
        $sjd->delete();

        return true;
    }

    public function deleteSjdt($sjdtId)
    {
        $sjdt = SuratJalanDetailTitipan::find($sjdtId);
        $sjdt->delete();

        return true;
    }

    public function deleteNpjdk($npjdkId)
    {
        $sjdt           = NotaPenjualanDetailKoli::find($npjdkId);
        $sjdt->statussj = 0;
        $sjdt->save();

        return true;
    }

    public function getNpjNoSjId($tokoid,$tglproforma)
    {
        $npj = NotaPenjualan::select(['pj.notapenjualan.id','pj.notapenjualan.tipetransaksi','pj.notapenjualandetailkoli.nokoli'])
                              ->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid')
                              ->leftJoin('pj.notapenjualandetailkoli','pj.notapenjualandetail.id','=','pj.notapenjualandetailkoli.notapenjualandetailid')
                              ->where('pj.notapenjualan.tokoid',$tokoid)
                              ->where('pj.notapenjualan.tglproforma',Carbon::parse($tglproforma))
                              ->where('pj.notapenjualan.tglprintproforma','!=',null)
                              ->where('pj.notapenjualandetailkoli.nokoli','!=',null)
                              ->where('pj.notapenjualandetailkoli.suratjalanid',null)
                              ->distinct()
                              ->get();                    
        return $npj;
    }

    public function getNpjdkNoSjId($tokoid,$tglproforma)
    {
        $npjdk = NotaPenjualan::select(['pj.notapenjualandetailkoli.id','pj.notapenjualandetailkoli.nokoli'])
                              ->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid')
                              ->leftJoin('pj.notapenjualandetailkoli','pj.notapenjualandetail.id','=','pj.notapenjualandetailkoli.notapenjualandetailid')
                              ->where('pj.notapenjualan.tokoid',$tokoid)
                              ->where('pj.notapenjualan.tglproforma',Carbon::parse($tglproforma))
                              // ->where('pj.notapenjualan.tglprintproforma','!=',null)
                              ->where('pj.notapenjualandetailkoli.suratjalanid',null)
                              ->get();                    
        return $npjdk;
    }

    public function cekKoli(Request $req)
    {
        $nokoli = $req->nokoli;
        $nokolititipan = $req->nokolititipan;
        if($nokoli && !$nokolititipan){
            if(strpos($nokoli, ',')){
                $koli = explode(',', $nokoli);
            }elseif(strpos($nokoli, '-')){
                $parsed = explode('-', $nokoli);
                $koli = array();
                for ($j=$parsed[0]; $j <= $parsed[1]; $j++) {
                    array_push($koli, $j);
                }
            }else{
                $koli = $nokoli;
            }

            $koli = collect($koli);
            $npjdk = array();
            $sj = SuratJalan::find($req->id);

            foreach ($sj->getNpjdkNoSjId() as $npj) {
                array_push($npjdk, $npj->nokoli);
            }

            if($koli->diff($npjdk)->isEmpty()){
                foreach ($koli as $npj) {
                    $npjdkoli = NotaPenjualanDetailKoli::find($sj->getNpjdkNoSjId()->where('nokoli',$npj)->first()->id);
                    $npjdkoli->suratjalanid = $sj->id;
                    $npjdkoli->save();
                }

                return response()->json([
                    'success' => true,
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'error'   => 'Nomor kolian '.implode(',', $koli->diff($npjdk)->toArray()).' tidak ada di koleksi nomor koli Nota Penjualan Detail Koli. Tidak bisa simpan tambahan koli. Hubungi Manager anda.',
                ]);
            }
        }else{
            if(strpos($nokolititipan, ',')){
                $koli = explode(',', $nokolititipan);
            }elseif(strpos($nokolititipan, '-')){
                $parsed = explode('-', $nokolititipan);
                $koli = array();
                for ($j=$parsed[0]; $j <= $parsed[1]; $j++) {
                    array_push($koli, $j);
                }
            }else{
                $koli = $nokolititipan;
            }

            $koli = collect($koli);
            $sjdt = array();
            $sj = SuratJalan::find($req->id);
            foreach ($sj->titipan as $sjdtitipan) {
                array_push($sjdt, $sjdtitipan->nokoli);
            }
            
            $same = $koli->intersect($sjdt);
            if($same->isEmpty()){
                $diff = $koli->diff($sjdt);
                foreach ($diff as $spjdtitipan) {
                    $sjdt = SuratJalanDetailTitipan::create([
                        'nokoli'        => $spjdtitipan,
                        'suratjalanid'  => $sj->id,
                        'createdby'     => strtoupper(auth()->user()->username),
                        'lastupdatedby' => strtoupper(auth()->user()->username),
                    ]);
                }

                return response()->json([
                    'success' => true,
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'error'   => 'Nomor kolian '.implode(',', $same->toArray()).' sudah ada di koleksi nomor koli Surat Jalan Detail Titipan. Tidak bisa simpan tambahan koli. Hubungi Manager anda.',
                ]);
            }
        }
    }

    public function cetak(Request $req)
    {
        if($req->tipe == 'pl'){
            $title      = 'PACKING LIST';
            $view       = 'transaksi.suratjalan.pl';
            $format     = [215.9,162.56];
        }elseif($req->tipe == 'amplop'){
            $title      = 'AMPLOP';
            $view       = 'transaksi.suratjalan.amplop';
            $format     = [215.9,162.56];
        }elseif($req->tipe == 'sj'){
            $title      = 'SURAT JALAN';
            $view       = 'transaksi.suratjalan.sj';
            $format     = [215.9,162.56];
        }
        $config = [
            'mode'                 => '',
            'format'               => $format,
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 40,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'P',
            'title'                => $title,
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $sj  = SuratJalan::find($req->id);
        if(!$sj->tglprintsj){
          $sj->tglprintsj = Carbon::now();
        }
        $sj->print = ($sj->print)+1;
        $sj->save();
        $pdf = PDF::loadView($view,[
            'sj' => $sj,
        ],[],$config);
        return $pdf->stream('SJ-'.$sj->nosj.'.pdf');
    }

    public function getNoSj(Request $req){
        return response()->json([
            'nosj' => $this->getNextSJNo($req->session()->get('subcabang')),
        ]);
    }

    public function getNextSJNo($recordownerid){
        $next_no = '';
        $max_order = SuratJalan::select('id','nosj')->where('id', SuratJalan::where('recordownerid', $recordownerid)->max('id'))->first();
        if ($max_order == null) {
            $next_no = sprintf("%010d", 1);
        }elseif (strlen($max_order->nosj)<10) {
            $next_no = sprintf("%010d", 1);
        }elseif ($max_order->nosj == '9999999999') {
            $next_no = sprintf("%010d", 1);
        }else {
            $next_no = sprintf("%010d", ltrim($max_order->nosj,'0')+1);
        }
        return $next_no;
    }

    public function cekKewenangan(Request $req)
    {
        $lastUserId = auth()->user()->id;
        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                if ($req->about == 'deletesj') {
                    $this->deleteSj($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif ($req->about == 'deletesjd') {
                    $this->deleteSjd($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif ($req->about == 'deletesjdt') {
                    $this->deleteSjdt($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif ($req->about == 'deletenpjdk') {
                    $this->deleteNpjdk($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }
            }
        }
        return response()->json([
            'success' => false,
        ]);
    }


    public function synchHeader(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $nota = SuratJalan::select([
                0 => "pj.suratjalan.id",
                1 => "pj.suratjalan.recordownerid",
                2 => "pj.suratjalan.pengirimanid",
                3 => "pj.suratjalan.tglproforma",
                4 => "pj.suratjalan.nosj",
                5 => "pj.suratjalan.tglsj",
                6 => "pj.suratjalan.tokoid",
                7 => "pj.suratjalan.tipetransaksi",
                8 => "pj.suratjalan.tglrencanakeluarexpedisi",
                9 => "pj.suratjalan.shift",
                10 => "pj.suratjalan.print",
                11 => "pj.suratjalan.tglprintsj",
                12 => "pj.suratjalan.alamattambahan",
                13 => "pj.suratjalan.keterangansj",
                14 => "pj.suratjalan.titipanketerangan",
                15 => "pj.suratjalan.titipandari",
                16 => "pj.suratjalan.titipanuntuk",
                17 => "pj.suratjalan.titipanalamat",
                18 => "pj.suratjalan.titipannotelepon",
                19 => "pj.suratjalan.createdby",
                20 => "pj.suratjalan.lastupdatedby",
                21 => "pj.suratjalan.isarowid",
                22 => "pj.suratjalan.nprintpackinglist",
                23 => "pj.suratjalan.tglprintpackinglist",
                24 => "mstr.toko.kodetoko",
                25 => "mstr.toko.namatoko",
            ])
            ->leftjoin("mstr.toko", "pj.notapenjualanmurni.tokoid", "=", "mstr.toko.id")
            ->join("mstr.subcabang as subcbowner", "pj.notapenjualanmurni.recordownerid", "=", "subcbowner.id")
            ->where(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.orderpenjualan.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.orderpenjualan.createdon::date"), "<=", $tglselesai);
            })
            ->where("subcbowner.kodesubcabang", $cabang)
            ->get();

        $data = [];
        foreach ($nota as $key => $value) {
            $data[$key] = $value->toArray();
        }

        return json_encode($data);
    }
}
