<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\KartuPiutang;
use App\Models\KartuPiutangDetail;
use App\Models\KoreksiPenjualanDetail;
use App\Models\NotaPenjualan;
use App\Models\NotaPenjualanDetail;
use App\Models\NotaPenjualanDetailKoli;
use App\Models\NotaPenjualanMurni;
use App\Models\Numerator;
use App\Models\OrderPenjualan;
use App\Models\OrderPenjualanDetail;

use Carbon\Carbon;
use DB;
use PDF;

class NotaPenjualanController extends Controller
{
	protected $original_column = array(
        1 => "pj.orderpenjualan.tglpickinglist",
        2 => "pj.orderpenjualan.nopickinglist",
        3 => "mstr.toko.namatoko",
        4 => "hr.karyawan.namakaryawan",
    );

    public function index()
    {
    	return view('transaksi.notapenjualan.index');
    }

    public function getData(Request $req, $search_type = null)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapenjualan.index')) {
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
            0 => "pj.orderpenjualan.tglpickinglist",
            1 => "pj.orderpenjualan.nopickinglist",
            2 => "mstr.toko.namatoko",
            3 => "hr.karyawan.namakaryawan",
            4 => "pj.orderpenjualan.id",
            5 => "pj.orderpenjualan.noaccpiutang",
            6 => "pj.orderpenjualan.print",
        );
        for ($i=1; $i < 5; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        $notapenjualan = OrderPenjualan::selectRaw(collect($columns)->implode(', '));
        $notapenjualan->leftJoin('mstr.toko', 'pj.orderpenjualan.tokoid', '=', 'mstr.toko.id');
        $notapenjualan->leftJoin('hr.karyawan', 'pj.orderpenjualan.karyawanidsalesman', '=', 'hr.karyawan.id');
        $notapenjualan->where("pj.orderpenjualan.recordownerid",$req->session()->get('subcabang'))->where("pj.orderpenjualan.tglpickinglist",'>=',Carbon::parse($req->tglmulai))->where("pj.orderpenjualan.tglpickinglist",'<=',Carbon::parse($req->tglselesai));
        $total_data = $notapenjualan->count();
        if($empty_filter < 4){
            for ($i=1; $i < 5; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index > 1){
                        if($req->custom_search[$i]['filter'] == '='){
                            $notapenjualan->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $notapenjualan->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                        $notapenjualan->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $notapenjualan->count();
        }else{
            $filtered_data = $total_data;
        }
        if(array_key_exists($req->order[0]['column'], $this->original_column)){
            $notapenjualan->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }
        if($search_type == 'find'){
            return $this->findData($notapenjualan->select('pb.orderpenjualan.id')->take($total_data)->get()->toArray(), $req->id);
        }else{
            if($req->start > $filtered_data){
                $notapenjualan->skip(0)->take($req->length);
            }else{
                $notapenjualan->skip($req->start)->take($req->length);
            }
        }

        $data = array();
        $array_piutang = [];
        foreach ($notapenjualan->get() as $key => $jual) {
            $jual->tglpickinglist = $jual->tglpickinglist;
            $data[$key]           = $jual->toArray();
            $data[$key]['DT_RowId'] = 'gv1_'.$jual->id;
            if($jual->noaccpiutang == null || substr($jual->noaccpiutang, 0, 1) == 'T'){
                $tambah = 'Tidak bisa proses proforma Invoice. No. ACC masih kosong. Silahkan diisi dahulu';
            }else{
                if($jual->print == 0){
                    $tambah = 'Picking List belum dicetak. Silahkan cetak PiL dahulu.';
                }else{
                    $orderpenjualan = \DB::table('pj.orderpenjualan')->where('id',$jual->id)->first();
                    $server_time_backup = Carbon::now('Asia/Jakarta');
                    $server_time = Carbon::now('Asia/Jakarta');


                    if(!empty($orderpenjualan->tglprintpickinglist)){
                        $tanggal_picking_print = Carbon::parse($orderpenjualan->tglprintpickinglist);
                       
                        if(($server_time->format('d') - $tanggal_picking_print->format('d')) != 0){
                            if(strtotime($tanggal_picking_print->format('G:i')) >=  strtotime('16:00') && 
                                strtotime($server_time->format('G:i')) <  strtotime('16:00') &&
                                    //$server_time->diffInDays($tanggal_picking_print) == 1)
                                    ($server_time->format('d') - $tanggal_picking_print->format('d')) == 1)
                            {
                                $tambah = 'kartupiutang';
                            }else{
                                $tambah = 'Tidak bisa proses Proforma Invoice. Picking List sudah kadaluwarsa. Hubungi Manager Anda';
                            }
                        }else{
                            $tambah = 'tambah';
                        }
                    }else{
                        $tambah = 'tambah';
                    }

                    // $tambah = 'tambah';
                }
            }
            $data[$key]['tambah'] = $tambah;
        }
        
        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data
        ]);
    }

    public function getDataNota(Request $req)
    {
        $message = null;
        $array_piutang = [];

        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapenjualan.index')) {
           return response()->json(['notapenjualan' => []]);
        }
       
         // jika lolos, cek data
        $orderpenjualan = \DB::table('pj.orderpenjualan')->where('id',$req->id)->first();
        $server_time_backup = Carbon::now('Asia/Jakarta');
        $server_time = Carbon::now('Asia/Jakarta');


        if(!empty($orderpenjualan->tglprintpickinglist)){
            $tanggal_picking_print = Carbon::parse($orderpenjualan->tglprintpickinglist);
            
            //if($tanggal_picking_print->diffInDays($server_time) != 0){
            if(($server_time->format('d') - $tanggal_picking_print->format('d')) != 0){
                if(strtotime($tanggal_picking_print->format('G:i')) >=  strtotime('16:00') && 
                    strtotime($server_time->format('G:i')) <  strtotime('16:00') &&
                        //$server_time->diffInDays($tanggal_picking_print) == 1)
                        ($server_time->format('d') - $tanggal_picking_print->format('d')) == 1)
                {
                
                    $toko_id = $orderpenjualan->tokoid;
                   
                    $saldos = DB::select("
                        select
                        *
                        from
                            (select
                                sum(pd.nomtrans) as saldo,
                                ph.*
                                
                                from ptg.kartupiutangdetail pd, ptg.kartupiutang ph
                                where
                                    pd.kartupiutangid = ph.id and
                                    pd.kodetrans in ('BGC', 'TRN') and
                                    pd.nomtrans > 0 and
                                    ph.tokoid = ?
                                group by ph.id
                            ) 
                        as perhitungan_saldo
                        where perhitungan_saldo.saldo > 0	
                        order by perhitungan_saldo.id
                    ",[$toko_id]);
                   
                    foreach ($saldos as $s_key => $saldo){
                        $array_piutang[$s_key]['tglterima'] = $saldo->tglterima;
                        $array_piutang[$s_key]['nonota'] = $saldo->nonota;
                        $array_piutang[$s_key]['saldo'] = $saldo->saldo;
                    }
                    
                    // return response()->json(['datakartupiutang' => $array_piutang]);
                }else{
                    $message = "Tidak bisa proses Proforma Invoice. Picking List sudah kadaluwarsa. Hubungi Manager Anda";
                    // return response()->json(['error_message' => "Tidak bisa proses Proforma Invoice. Picking List sudah kadaluwarsa. Hubungi Manager Anda"]);
                }
            }else{
                $message = "tambah";
                // return response()->json(['error_message' => "tambah"]);
            }
        }//else{
            //return response()->json(['notapenjualan' => []]);
        //}

       

        // jika lolos, tampilkan data
        $orderpenjualan = OrderPenjualan::find($req->id);
        $notapenjualan = $orderpenjualan->nota;

        $node_nota = array();
        foreach ($notapenjualan as $nota) {
            $notapenjualandetails = $nota->details;
            $hrgtotalnota = 0;
            foreach ($notapenjualandetails as $detail) {
                $hrgtotalnota += $detail->qtynota*$detail->hrgsatuannetto;
            }

            if(Carbon::parse($nota->tglproforma)->toDateString() == Carbon::now()->toDateString()){
                $tglproformasama = true;
            }else{
                $tglproformasama = false;
            }

            if(Carbon::parse($nota->tglnota)->toDateString() == Carbon::now()->toDateString()){
                $tglnotasama = true;
            }else{
                $tglnotasama = false;
            }

            if($nota->printproforma == 0){
                if($tglproformasama){
                    $delete = 'auth';
                }else{
                    $delete = 'Tidak bisa hapus record profroma invoice. Tanggal Proforma invoice tidak sama dengan tanggal server. Hubungi Manager anda.';
                }
            }else{
                $delete = 'Tidak bisa hapus Record proforma invoice. Proforma Invoice sudah dicetak. Hubungi Manager anda.';
            }

            if($nota->printproforma == 3){
                $cetakproforma = 'Proforma Invoice sudah dicetak 3 kali. Tidak bisa cetak lagi. Hubungi Manager anda.';
            }else{
                if($tglproformasama){
                    $cetakproforma = 'cetak';
                }else{
                    $cetakproforma = 'auth';
                }
            }

            if($nota->printnota == 3){
                $cetaknota = 'Nota sudah dicetak 3 kali. Tidak bisa cetak lagi. Hubungi Manager anda.';
            }else{
                if($tglnotasama){
                    $cetaknota = 'cetak';
                }else{
                    $cetaknota = 'auth';
                }
            }

            if($nota->tglnota){
                $isichecker = 'Tidak bisa update nama Checker dan nomor koli. Nota sudah diterima toko. Hubungi Manager anda.';
                // if dibawah ini perlu pengecekan ulang, tanya pas konferens
                if(Carbon::now()->diffInDays(Carbon::parse($nota->tglnota)) > 7){
                    $isitglterima = 'Tidak bisa batal tanggal terima. Umur tanggal terima lebih besar dari 7 hari. Hubungi manager anda.';
                }elseif(date('m') != Carbon::parse($nota->tglnota)->month){
                    $isitglterima = 'Tidak bisa batal tanggal terima. Bulan terima berbeda dengan bulan berjalan. Hubungi manager anda.';
                }else{
                    $isitglterima = 'hapus';
                }

                if(!$nota->kp->isEmpty()){
                    if(!$nota->kp[0]->details->isEmpty()){
                        $isitglterima = 'Tidak bisa batal tanggal terima. Sudah ada transaksi di kartu piutang detail. Hubungi Manager anda.';
                    }
                }
                // if(count($nota->kpd)){
                //     $isitglterima = 'Tidak bisa batal tanggal terima. Sudah ada transaksi di kartu piutang detail. Hubungi Manager anda.';
                // }
            }else{
                $isichecker = 'isi';
                $isitglterima = 'isi';
            }

            // Check Surat Jalan
            if($nota->suratjalandetail()->exists()){
                $isichecker = 'Tidak bisa update nama Checker dan nomor koli. Surat Jalan sudah dibuat. Hubungi Manager anda.';
            }

            $temp_nota = [
                'tglproforma'   => $nota->tglproforma,
                'tglnota'       => $nota->tglnota,
                'nonota'        => $nota->nonota,
                'hrgtotalnota'  => number_format($hrgtotalnota,0,',','.'),
                'id'            => $nota->id,
                'delete'        => $delete,
                'cetakproforma' => $cetakproforma,
                'cetaknota'     => $cetaknota,
                'isichecker'    => $isichecker,
                'isitglterima'  => $isitglterima,
            ];
            array_push($node_nota, $temp_nota);
        }

        return response()->json([
            'notapenjualan' => $node_nota,
            'error_message' => $message,
            'datakartupiutang' => $array_piutang
        ]);
    }

    public function getDataNotaDetail(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapenjualan.index')) {
           return response()->json(['notapenjualandetail' => []]);
        }

        // jika lolos, tampilkan data
        $notapenjualan   = NotaPenjualan::find($req->id);
        $cekkoreksi_kp   = ($notapenjualan->kp) ? 0 : 1;
        $cekkoreksi_kpd  = ($notapenjualan->kpd) ? 0 : 1;

        $node_notadetail = array();
        foreach ($notapenjualan->details as $detail) {
            $hrgnota = $detail->qtynota*$detail->hrgsatuannetto;


            if ($detail->newchildid == NULL) {
                if($detail->nota->printproforma == 0){
                    if(Carbon::parse($detail->nota->tglproforma)->toDateString() == Carbon::now()->toDateString()){
                        if($detail->koreksipjparentid){
                           $delete = 'Tidak bisa hapus Record detail proforma invoice. Hubungi Manager anda.';
                        }else{
                           $delete = 'auth';
                        }
                    }else{
                        $delete = 'Tidak bisa hapus record detail profroma invoice. Tanggal Proforma invoice tidak sama dengan tanggal server. Hubungi Manager anda.';
                    }
                }else{
                    $delete = 'Tidak bisa hapus Record detail proforma invoice. Proforma Invoice sudah dicetak. Hubungi Manager anda.';
                }
                if($detail->qtynota == 0) {
                    $koreksi = 'Tidak bisa koreksi barang dengan nilai Qty. Nota = 0. Tidak ada yang bisa dikoreksi. Hubungi Manager anda.';
                }else{
                    if($cekkoreksi_kp){
                        $koreksi = 'Tidak bisa buat Nota Koreksi Jual. Nota jual belum di link ke Piutang. Hubungi manager anda.';
                    }
                    else if($cekkoreksi_kpd){
                        $koreksi = 'Tidak bisa buat Nota Koreksi Jual. Nota jual belum di link ke Piutang. Hubungi manager anda.';
                    }
                    else if($detail->koreksipjparentid && $detail->qtynota < 0){
                        $koreksi = 'Tidak bisa buat Nota Koreksi Jual. Qty Nota < 0. Hubungi manager anda.';
                    }
                    else if($detail->koreksipjparentid && $detail->qtynota > 0){   
                        $koreksi = 'koreksi';
                    }
                    else{
                        $koreksi = 'koreksi';
                    }
                }
            } else {
                $delete  = 'Tidak bisa hapus Record detail proforma invoice. Data sudah dikoreksi. Hubungi Manager anda.';
                $koreksi = 'Tidak bisa buat Nota Koreksi Jual. Nota jual sudah dikoreksi. Hubungi manager anda.';
            }

            $temp_notadetail = [
                'namabarang'     => $detail->barang->namabarang,
                'satuan'         => $detail->barang->satuan,
                'qtynota'        => $detail->qtynota,
                'hrgsatuannetto' => number_format($detail->hrgsatuannetto,2,',','.'),
                'hrgtotal'       => number_format($hrgnota,0,',','.'),
                'id'             => $detail->id,
                'delete'         => $delete,
                'koreksi'        => $koreksi,
                'editqty'        => ($notapenjualan->printproforma) ? 'Tidak bisa Edit Qty Barang. Nota jual sudah dicetak. Hubungi manager anda.' : 'editqty',
    		];
    		array_push($node_notadetail, $temp_notadetail);
    	}

    	return response()->json([
    		'notapenjualandetail' => $node_notadetail,
		]);
    }

    public function getDataNotaDetailKoli(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapenjualan.index')) {
           return response()->json(['koli' => []]);
        }

        // jika lolos, tampilkan data
        $notapenjualandetail = NotaPenjualanDetail::find($req->id);
        $node_notakoli = array();
        foreach ($notapenjualandetail->koli as $koli) {
            $temp_notakoli = [
                'nokoli'     => $koli->nokoli,
                'keterangan' => $koli->keterangan,
                'id'         => $koli->id,
            ];
            array_push($node_notakoli, $temp_notakoli);
        }

        return response()->json([
            'koli' => $node_notakoli,
        ]);
    }

    public function getHeaderDetail(Request $req)
    {
    	$notapenjualan = NotaPenjualan::find($req->id);
    	return response()->json([
			"c1"               => $notapenjualan->c1==null? null:$notapenjualan->c1->namasubcabang,
			"c2"               => $notapenjualan->c2==null? null:$notapenjualan->c2->namasubcabang,
			"tglpickinglist"   => $notapenjualan->orderpenjualan->tglpickinglist,
			"nopickinglist"    => $notapenjualan->orderpenjualan->nopickinglist,
			"tglproforma"      => $notapenjualan->tglproforma,
			"nonota"           => $notapenjualan->nonota,
			"tglnota"          => $notapenjualan->tglnota,
			"toko"             => $notapenjualan->toko->namatoko,
			"alamat"           => $notapenjualan->toko->alamat,
			"kota"             => $notapenjualan->toko->kota,
			"kecamatan"        => $notapenjualan->toko->kecamatan,
			"wilid"            => $notapenjualan->toko->customwilayah,
			"idtoko"           => $notapenjualan->toko->id,
			"statustoko"       => '?',
			"salesman"         => $notapenjualan->salesman==null? null:$notapenjualan->salesman->namakaryawan,
			"tipetransaksi"    => $notapenjualan->tipetransaksi,
			"temponota"        => $notapenjualan->temponota,
			"tempokirim"       => $notapenjualan->tempokirim,
			"temposalesman"    => $notapenjualan->temposalesman,
			"printproforma"    => $notapenjualan->printproformastatus,
			"tglprintproforma" => $notapenjualan->tglprintproforma,
			"printnota"        => $notapenjualan->printnotastatus,
			"tglprintnota"     => $notapenjualan->tglprintnota,
			"tglcheck"         => $notapenjualan->tglcheck,
			"checker1"         => $notapenjualan->checker1==null? null:$notapenjualan->checker1->namakaryawan,
			"checker2"         => $notapenjualan->checker2==null? null:$notapenjualan->checker2->namakaryawan,
			"lastupdatedby"    => $notapenjualan->lastupdatedby,
			"lastupdatedon"    => $notapenjualan->lastupdatedon,
		]);
    }

    public function getDataDetail(Request $req)
    {
    	$detail = NotaPenjualanDetail::find($req->id);
    	$hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
        $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;
        $hrgppn = (1+($detail->ppn/100)) * $hrgdisc2;
        $hrgtotal = $hrgppn * $detail->qtynota;

    	return response()->json([
			"barang"          => $detail->barang->namabarang,
			"sat"             => $detail->barang->satuan,
			"qtynota"         => $detail->qtynota,
			"hrgsatuanbrutto" => $detail->hrgsatuanbrutto,
			"disc1"           => $detail->disc1,
			"hrgsetelahdisc1" => $hrgdisc1,
			"disc2"           => $detail->disc2,
			"hrgsetelahdisc2" => $hrgdisc2,
			"ppn"             => $detail->ppn,
			"hrgsatuannetto"  => $hrgppn,
			"hrgtotalnetto"   => $hrgtotal,
			"qtystockgudang"  => $detail->stockgudang,
			"lastupdatedby"   => $detail->lastupdatedby,
			"lastupdatedon"   => $detail->lastupdatedon,
		]);
    }
    public function getDataDetailNota(Request $req)
    {
        $detail = NotaPenjualanDetail::find($req->id);
        $nota   = $detail->nota;
        if($nota->checker1 != null){
            $checker1 = $nota->checker1->namakaryawan;
            $checker1_id = $nota->checker1->id;
        }else{
            $checker1 = '';
            $checker1_id = '';
        }
        if($nota->checker2 != null){
            $checker2 = $nota->checker2->namakaryawan;
            $checker2_id = $nota->checker2->id;
        }else{
            $checker2 = '';
            $checker2_id = '';
        }
        $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
        $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;
        $hrgppn = (1+($detail->ppn/100)) * $hrgdisc2;
        $hrgtotal = $hrgppn * $detail->qtynota;

        return response()->json([
            "success"               => true,
            'notaid'                => $nota->id,
            'notadetailid'          => $detail->id,
            'omsetsubcabangid'      => $nota->c1->namasubcabang,
            'pengirimsubcabangid'   => $nota->c2->pengirimsubcabangid,
            'nonota'                => $nota->nonota,
            'tglnota'               => $nota->tglnota,
            'tglproforma'           => $nota->tglproforma,
            'toko'                  => $nota->toko->namatoko,
            'karyawan'              => ($nota->salesman) ? $nota->salesman->namakaryawan : '',
            'tglcheck'              => $nota->tglcheck,
            'tipetransaksi'         => $nota->tipetransaksi,
            'temponota'             => $nota->temponota,
            'tempokirim'            => $nota->tempokirim,
            'temposalesman'         => $nota->temposalesman,
            'karyawanchecker1'      => $checker1,
            'karyawanchecker1id'    => $checker1_id,
            'karyawanchecker2'      => $checker2,
            'karyawanchecker2id'    => $checker2_id,
            'namabarang'            => $detail->barang->namabarang,
            'satuan'                => $detail->barang->satuan,
            'qtynota'               => $detail->qtynota,
            'qtyorder'              => ($detail->orderpenjualandetail) ? $detail->orderpenjualandetail->qtysoacc : $detail->qtynota,
            'hrgsatuanbrutto'       => $detail->hrgsatuanbrutto,
            'disc1'                 => $detail->disc1,
            'hrgdisc1'              => $hrgdisc1,
            'disc2'                 => $detail->disc2,
            'hrgdisc2'              => $hrgdisc2,
            'ppn'                   => $detail->ppn,
            'hrgsatuannetto'        => $detail->hrgsatuannetto,
            'lastupdatedby'         => $detail->lastupdatedby,
            'lastupdatedon'         => $detail->lastupdatedon,
        ]);
    }

    public function simpanKoreksi(Request $req)
    {
        $numerator = Numerator::find(Numerator::where('doc','KOREKSI PENJUALAN')->value('id'));
        $depan     = $numerator->depan;
        $lebar     = $numerator->lebar-strlen($numerator->depan);
        $nomor     = sprintf("%0".$lebar."d", $numerator->nomor);
        
        $notalama  = NotaPenjualan::find($req->data['nota']);
        $kp        = $notalama->kp->first();

        $nota = new NotaPenjualan;
        $nota->recordownerid       = $notalama->recordownerid;
        $nota->orderpenjualanid    = $notalama->orderpenjualanid;
        $nota->omsetsubcabangid    = $notalama->omsetsubcabangid;
        $nota->pengirimsubcabangid = $notalama->pengirimsubcabangid;
        $nota->nonota              = $depan.$nomor;
        $nota->tglnota             = Carbon::now()->toDateString();
        $nota->tglproforma         = Carbon::parse($req->data['tglproforma']);
        $nota->tokoid              = $notalama->tokoid;
        $nota->karyawanidsalesman  = $notalama->karyawanidsalesman;
        $nota->tipetransaksi       = $notalama->tipetransaksi;
        $nota->temponota           = $notalama->temponota;
        $nota->tempokirim          = $notalama->tempokirim;
        $nota->temposalesman       = $notalama->temposalesman;
        $nota->karyawanidchecker1  = $notalama->karyawanidchecker1;
        $nota->karyawanidchecker2  = $notalama->karyawanidchecker2;
        $nota->createdby           = strtoupper($req->user()->username);
        $nota->lastupdatedby       = strtoupper($req->user()->username);
        $nota->save();
        $numerator->nomor++;
        $numerator->save();
       
        $detaillama   = NotaPenjualanDetail::find($req->data['notadetail']);
        $detail_minus = new NotaPenjualanDetail;

        // $detail_minus->id                  = $detail_minus->nextstatementid;
        $detail_minus->notapenjualanid        = $nota->id;
        $detail_minus->stockid                = $detaillama->stockid;
        $detail_minus->qtynota                = $detaillama->qtynota*-1;
        $detail_minus->hrgsatuanbrutto        = $detaillama->hrgsatuanbrutto;
        $detail_minus->disc1                  = $detaillama->disc1;
        $detail_minus->disc2                  = $detaillama->disc2;
        $detail_minus->ppn                    = $detaillama->ppn;
        $detail_minus->orderpenjualandetailid = $detaillama->orderpenjualandetailid;
        $detail_minus->hrgsatuannetto         = $detaillama->hrgsatuannetto;
        // $detail_minus->koreksipjparentid      = $detaillama->id;
        $detail_minus->createdby              = strtoupper($req->user()->username);
        $detail_minus->lastupdatedby          = strtoupper($req->user()->username);
        $detail_minus->save();

        $detail = new NotaPenjualanDetail;
        // $detail->id                  = $detail->nextstatementid;
        $detail->notapenjualanid        = $nota->id;
        $detail->stockid                = $detaillama->stockid;
        $detail->qtynota                = $detaillama->qtynota;
        $detail->hrgsatuanbrutto        = $detaillama->hrgsatuanbrutto;
        $detail->disc1                  = $detaillama->disc1;
        $detail->disc2                  = $detaillama->disc2;
        $detail->ppn                    = $detaillama->ppn;
        $detail->orderpenjualandetailid = $detaillama->orderpenjualandetailid;
        $detail->hrgsatuannetto         = $req->data['hrgsatuannetto'];
        $detail->koreksipjparentid      = $detaillama->id;
        $detail->createdby              = strtoupper($req->user()->username);
        $detail->lastupdatedby          = strtoupper($req->user()->username);
        $detail->save();

        $detaillama->newchildid         = $detail->id;
        $detaillama->save();

        //CREATE RECORD KARTU PIUTANG DETAIL
        $hrgnotabaruminus = $detail_minus->qtynota*$detail_minus->hrgsatuannetto;
        $hrgnotabaruplus  = $detail->qtynota*$detail->hrgsatuannetto;

        $kpdetail = new KartuPiutangDetail;
        $kpdetail->kartupiutangid = $kp->id;
        $kpdetail->notaid         = $nota->id;
        $kpdetail->nomtrans       = $hrgnotabaruminus + $hrgnotabaruplus;
        $kpdetail->tgltrans       = Carbon::parse($nota->tglnota);
        $kpdetail->kodetrans      = 'KPJ';
        $kpdetail->createdby      = strtoupper($req->user()->username);
        $kpdetail->lastupdatedby  = strtoupper($req->user()->username);
        $kpdetail->save();

        return response()->json([
            'success' =>true,
            'message' => 'Nota Penjualan berhasil dibuat pada tanggal '.date('d-m-Y',strtotime($nota->tglnota)).' , dengan nonota '.$nota->nonota.'.',
        ]);
    }

    public function simpanEditQty(Request $req)
    {
        $detail = NotaPenjualanDetail::find($req->notadetail);
        $detail->qtynota       = $req->qtynota;
        $detail->lastupdatedby = strtoupper($req->user()->name);
        $detail->save();

        return response()->json(['success' =>true]);
    }

    public function getDataDetailKoli(Request $req)
    {
        $koli = NotaPenjualanDetailKoli::find($req->id);

        return response()->json([
            "nokoli"        => $koli->nokoli,
            "keterangan"    => $koli->keterangan,
            "lastupdatedby" => $koli->lastupdatedby,
            "lastupdatedon" => $koli->lastupdatedon,
        ]);
    }

    public function deleteNota($notaid)
    {
        $nota = NotaPenjualan::find($notaid);
        if(!$nota->details->isEmpty()){
            foreach ($nota->details as $detail) {
                if(!$detail->koli->isEmpty()){
                    foreach ($detail->koli as $koli) {
                        $koli->delete();
                    }
                }
                $detail->delete();
            }
        }
        $nota->delete();

        return true;
    }

    public function deleteNotaDetail($notaid)
    {
        $detail = NotaPenjualanDetail::find($notaid);
        if(!$detail->koli->isEmpty()){
            foreach ($detail->koli as $koli) {
                $koli->delete();
            }
        }
        $detail->delete();
        return true;
    }

    public function cetakProforma(Request $req)
    {
        $nilai_cetak = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','proformainvoice')->value('value');
        $config = [
            'mode'                 => '',
            'format'               => [162.56,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 40,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 0,
            'orientation'          => 'L',
            'title'                => 'PROFORMA INVOICE',
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $nota    = NotaPenjualan::find($req->id);
        $catatan = $nota->orderpenjualan->catatanpembayaran;
        if(!$nota->tglprintproforma) {
            $nota->tglprintproforma = Carbon::now();
        }
        $nota->printproforma++;
        $nota->save();

        $hrgtotalnetto = 0;
        $hrgdisc       = 0;
        $hrgppn        = 0;
        $hrgnota       = 0;
        foreach ($nota->details as $detail) {
            $hrgnota       += $detail->qtynota*$detail->hrgsatuanbrutto;
            $hrgdisc       += $detail->qtynota*$detail->totaldiscount;
            $hrgppn        += $detail->ppn;
        }
        $hrgtotalnetto = $hrgtotalnetto = $hrgnota-$hrgdisc;;

        $pdf   = PDF::loadView('transaksi.notapenjualan.proforma',[
            'nota'          => $nota,
            'hrgtotalnetto' => $hrgtotalnetto,
            'hrgnota'       => $hrgnota,
            'hrgdisc'       => $hrgdisc,
            'hrgppn'        => $hrgppn,
            'catatan'       => $catatan,
            'nilai_cetak'   => $nilai_cetak,
            ],[],$config);
        return $pdf->stream('PI-'.$req->id.'.pdf');
    }

    public function cetakNota(Request $req)
    {
        $nilai_cetak = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','proformainvoice')->value('value');
        $config = [
            'mode'                  => '',
            'format'                => [215.9,162.56],
            'default_font_size'     => '9',
            'default_font'          => 'sans-serif',
            'margin_left'           => 10,
            'margin_right'          => 10,
            'margin_top'            => 40,
            'margin_bottom'         => 8,
            'margin_header'         => 0,
            'margin_footer'         => 0,
            'orientation'           => 'P',
            'title'                 => 'NOTA JUAL',
            'author'                => '',
            'watermark'             => '',
            'show_watermark'        => true,
            'show_watermark_image'  => true,
            'watermark_font'        => 'sans-serif',
            'display_mode'          => 'fullpage',
            'watermark_text_alpha'  => 0.2,
        ];

        $nota    = NotaPenjualan::find($req->id);
        $catatan = $nota->orderpenjualan->catatanpembayaran;
        if(!$nota->tglprintnota) {
            $nota->tglprintnota = Carbon::now();
        }
        $nota->printnota++;
        $nota->save();

        $hrgtotalnetto = 0;
        $hrgdisc       = 0;
        $hrgppn        = 0;
        $hrgnota       = 0;
        foreach ($nota->details as $detail) {
            $hrgnota       += $detail->qtynota*$detail->hrgsatuanbrutto;
            $hrgdisc       += $detail->qtynota*$detail->totaldiscount;
            $hrgppn        += $detail->ppn;
        }
        $hrgtotalnetto = $hrgnota-$hrgdisc;

        $pdf   = PDF::loadView('transaksi.notapenjualan.nota',[
            'nota'          => $nota,
            'hrgtotalnetto' => $hrgtotalnetto,
            'hrgnota'       => $hrgnota,
            'hrgdisc'       => $hrgdisc,
            'hrgppn'        => $hrgppn,
            'catatan'       => $catatan,
            'nilai_cetak'   => $nilai_cetak,
            ],[],$config);
        return $pdf->stream('NPJ-'.$req->id.'.pdf');
    }

    public function tambah(Request $req)
    {
        $orderpenjualan = OrderPenjualan::find($req->id);
        $data = array();
        $data_temp = array();

        foreach ($orderpenjualan->details as $detail) {
            if(!$detail->notadetail->isEmpty()){
                $notadetails = $detail->notadetail;
                foreach ($notadetails as $notadetail) {
                    $temp = [
                        'stockid' => $notadetail->stockid,
                        'qtynota' => $notadetail->qtynota,
                    ];
                    array_push($data_temp, $temp);
                }
            }
        }

        $tgl = Carbon::now()->format('Ymd');
        foreach ($orderpenjualan->details as $detail) {
            $stockid = $detail->stockid;
            $json = app('App\Http\Controllers\MasterController')->getQtyStockGudang($tgl, $stockid, $req->session()->get('subcabang'));

            $row_stock = collect($data_temp)->where('stockid',$stockid);
            if($row_stock->isEmpty()){
                $temp_detail = [
                    'namabarang'     => $detail->barang->namabarang,
                    'satuan'         => $detail->barang->satuan,
                    'qtysoacc'       => $detail->qtysoacc,
                    'hrgsatuannetto' => $detail->hrgsatuannetto,
                    'qtygudang'      => $json['total'],
                    'id'             => $detail->id,
                ];
                array_push($data, $temp_detail);
            }else{
                $count = $row_stock->sum('qtynota');
                if($detail->qtysoacc > $count){
                    $temp_detail = [
                        'namabarang'     => $detail->barang->namabarang,
                        'satuan'         => $detail->barang->satuan,
                        'qtysoacc'       => $detail->qtysoacc,
                        'hrgsatuannetto' => $detail->hrgsatuannetto,
                        'qtygudang'      => $json['total'],
                        'id'             => $detail->id,
                    ];
                    array_push($data, $temp_detail);
                }
            }
        }

        $dataOrder = [
            'id'             => $orderpenjualan->id,
            'tglpickinglist' => $orderpenjualan->tglpickinglist,
            'nopickinglist'  => $orderpenjualan->nopickinglist,
            'toko'           => $orderpenjualan->toko->namatoko,
            'alamat'         => $orderpenjualan->toko->alamat,
            'kota'           => $orderpenjualan->toko->kota,
            'kecamatan'      => $orderpenjualan->toko->kecamatan,
            'wilid'          => $orderpenjualan->toko->customwilayah,
            'idtoko'         => $orderpenjualan->toko->id,
            'rpaccpiutang'   => number_format($orderpenjualan->rpaccpiutang,0,',','.'),
        ];

        return response()->json([
            'success' => true,
            'order'   => $dataOrder,
            'barang'  => $data,
        ]);
    }

    public function isiCheckerNota(Request $req)
    {
        $notapenjualan = NotaPenjualan::find($req->id);
        $details = $notapenjualan->details;
        $data = array();
        foreach ($details as $detail) {
            $temp_detail = [
                'namabarang' => $detail->barang->namabarang,
                'satuan'     => $detail->barang->satuan,
                'qtynota'    => $detail->qtynota,
                'id'         => $detail->id,
            ];
            array_push($data, $temp_detail);
        }
        $dataNota = [
            'id'             => $notapenjualan->id,
            'tglpickinglist' => $notapenjualan->orderpenjualan->tglpickinglist,
            'nopickinglist'  => $notapenjualan->orderpenjualan->nopickinglist,
            'tglproforma'    => $notapenjualan->tglproforma,
            'nonota'         => $notapenjualan->nonota,
            'tglnota'        => $notapenjualan->tglnota,
            'tipetransaksi'  => $notapenjualan->tipetransaksi,
            'toko'           => $notapenjualan->toko->namatoko,
            'alamat'         => $notapenjualan->toko->alamat,
            'kota'           => $notapenjualan->toko->kota,
            'kecamatan'      => $notapenjualan->toko->kecamatan,
            'wilid'          => $notapenjualan->toko->customwilayah,
            'idtoko'         => $notapenjualan->toko->id,
        ];
        return response()->json([
            'success' => true,
            'nota'    => $dataNota,
            'barang'  => $data,
        ]);
    }

    public function isiTglTerimaNota(Request $req)
    {
        $notapenjualan = NotaPenjualan::find($req->id);

        $dataNota = [
            'id'             => $notapenjualan->id,
            'tglpickinglist' => $notapenjualan->orderpenjualan->tglpickinglist,
            'nopickinglist'  => $notapenjualan->orderpenjualan->nopickinglist,
            'tglproforma'    => $notapenjualan->tglproforma,
            'nonota'         => $notapenjualan->nonota,
            'tglnota'        => $notapenjualan->tglnota,
            'tipetransaksi'  => $notapenjualan->tipetransaksi,
            'toko'           => $notapenjualan->toko->namatoko,
            'alamat'         => $notapenjualan->toko->alamat,
            'kota'           => $notapenjualan->toko->kota,
            'kecamatan'      => $notapenjualan->toko->kecamatan,
            'wilid'          => $notapenjualan->toko->customwilayah,
            'idtoko'         => $notapenjualan->toko->id,
            'temponota'      => $notapenjualan->temponota,
            'temposalesman'  => $notapenjualan->temposalesman,
        ];

        return response()->json([
            'success'=>true,
            'nota'    => $dataNota,
        ]);
    }

    public function submitCheckerNota(Request $req)
    {
        $notapenjualan = NotaPenjualan::find($req->notaid);
        $notapenjualan->karyawanidchecker1 = $req->checker1;
        $notapenjualan->karyawanidchecker2 = $req->checker2;
        $notapenjualan->save();

        for ($i=0; $i < count($req->koli); $i++) {
            if(strpos($req->koli[$i]['nomorkoli'], ',')){
                $nokoli = explode(',', $req->koli[$i]['nomorkoli']);
            }elseif(strpos($req->koli[$i]['nomorkoli'], '-')){
                $parsed = explode('-', $req->koli[$i]['nomorkoli']);
                $nokoli = array();
                for ($j=$parsed[0]; $j <= $parsed[1]; $j++) {
                    array_push($nokoli, $j);
                }
            }else{
                $nokoli = $req->koli[$i]['nomorkoli'];
            }
            for ($j=0; $j < count($nokoli); $j++) {
                $npjdkoli = New NotaPenjualanDetailKoli;
                $npjdkoli->notapenjualandetailid = $req->koli[$i]['id'];
                $npjdkoli->nokoli                = $nokoli[$j];
                $npjdkoli->keterangan            = $req->koli[$i]['keterangan'];
                $npjdkoli->createdby             = auth()->user()->username;
                $npjdkoli->lastupdatedby         = auth()->user()->username;
                $npjdkoli->save();
            }
        }

        return response()->json(['success'=>true]);
    }

    public function addNota(Request $req)
    {
        $orderpenjualan = OrderPenjualan::find($req->id);
        $hrgtotalnota = 0;
        foreach ($orderpenjualan->nota as $nota) {
            foreach ($nota->details as $notadetail) {
                $hrgtotalnota += $notadetail->qtynota*$notadetail->hrgsatuannetto;
            }
        }

        if($hrgtotalnota > $orderpenjualan->rpaccpiutang){
            return response()->json([
                'success' => false,
            ]);
        }else{
            $notadata = collect($req->notadata)->where('qty','>',0);
            $nota = New NotaPenjualan;
            $nota->recordownerid       = $orderpenjualan->recordownerid;
            $nota->orderpenjualanid    = $orderpenjualan->id;
            $nota->omsetsubcabangid    = $orderpenjualan->omsetsubcabangid;
            $nota->pengirimsubcabangid = $orderpenjualan->pengirimsubcabangid;

            $nota->tokoid              = $orderpenjualan->tokoid;
            $nota->karyawanidsalesman  = $orderpenjualan->karyawanidsalesman;
            $nota->tipetransaksi       = $orderpenjualan->tipetransaksi;
            $nota->temponota           = $orderpenjualan->temponota;
            $nota->tempokirim          = $orderpenjualan->tempokirim;
            $nota->temposalesman       = $orderpenjualan->temposalesman;
            $nota->nonota              = $this->getNextNotaNo($req->session()->get('subcabang'));
            $nota->tglproforma         = Carbon::now()->toDateString();
            $nota->printproforma       = 0;
            $nota->printnota           = 0;
            $nota->createdby           = strtoupper(auth()->user()->username);
            $nota->lastupdatedby       = strtoupper(auth()->user()->username);
            $nota->save();

            $totalnominal = 0;
            foreach ($notadata as $data) {
                $orderpenjualandetail = OrderPenjualanDetail::find($data['orderdetailid']);

                $notadetail = New NotaPenjualanDetail;
                $notadetail->notapenjualanid        = $nota->id;
                $notadetail->stockid                = $orderpenjualandetail->stockid;
                $notadetail->hrgsatuanbrutto        = $orderpenjualandetail->hrgsatuanbrutto;
                $notadetail->disc1                  = $orderpenjualandetail->disc1;
                $notadetail->disc2                  = $orderpenjualandetail->disc2;
                $notadetail->totaldiscount          = $orderpenjualandetail->hrgsatuanbrutto*($orderpenjualandetail->disc1/100);
                $notadetail->ppn                    = $orderpenjualandetail->ppn;
                $notadetail->hrgsatuannetto         = $orderpenjualandetail->hrgsatuannetto;
                $notadetail->orderpenjualandetailid = $orderpenjualandetail->id;
                $notadetail->qtynota                = $data['qty'];
                $notadetail->createdby              = strtoupper(auth()->user()->username);
                $notadetail->lastupdatedby          = strtoupper(auth()->user()->username);
                $notadetail->save();

                $totalnominal += $notadetail->hrgsatuannetto*$notadetail->qtynota;
            }

            $nota->totalnominal = $totalnominal;
            $nota->save();

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function getNextNotaNo($recordownerid){
        $next_no = '';
        $max_order = NotaPenjualan::select('id','nonota')->where('id', NotaPenjualan::where('recordownerid', $recordownerid)->max('id'))->first();
        if ($max_order == null) {
            $next_no = sprintf("%010d", 1);
        }elseif (strlen($max_order->nonota)<10) {
            $next_no = sprintf("%010d", 1);
        }elseif ($max_order->nonota == '9999999999') {
            $next_no = sprintf("%010d", 1);
        }else {
            $nomor = intval(ltrim($max_order->nonota,'0'));
            $next_no = sprintf("%010d", $nomor+1);
        }
        return $next_no;
    }

    public function submitTglTerimaNota(Request $req)
    {
        $notapenjualan = NotaPenjualan::find($req->notaid);
        $notapenjualan->tglnota = Carbon::parse($req->tglterima)->toDateString();  
        $notapenjualan->save();

        $kp = New KartuPiutang;
        $kp->recordownerid      = $req->session()->get('subcabang');
        $kp->notaid             = $notapenjualan->id;
        $kp->tokoid             = $notapenjualan->tokoid;
        $kp->nonota             = $notapenjualan->nonota;
        $kp->tglproforma        = Carbon::parse($notapenjualan->tglproforma);
        $kp->tglterima          = Carbon::parse($req->tglterima);
        $kp->tgljt              = Carbon::parse($notapenjualan->tglnota)->addDays($notapenjualan->tempokirim+$notapenjualan->temponota+$notapenjualan->temposalesman);
        $kp->nomnota            = $notapenjualan->totalnominal;
        $kp->karyawanidsalesman = $notapenjualan->karyawanidsalesman; 
        $kp->temponota          = $notapenjualan->temponota; 
        $kp->tempokirim         = $notapenjualan->tempokirim; 
        $kp->temposalesman      = $notapenjualan->temposalesman; 
        // $kp->uraian             = $notapenjualan->uraian; 
        $kp->tipetrans          = $notapenjualan->tipetransaksi;
        $kp->createdby          = strtoupper(auth()->user()->username);
        $kp->lastupdatedby      = strtoupper(auth()->user()->username);
        $kp->barcodenota        = Carbon::parse($notapenjualan->tglproforma)->format('Ym').$notapenjualan->nonota; 
        $kp->jeniskartupiutang  = 'Piutang Nota'; 
        $kp->save();

        return response()->json(['success'=>true]);
    }

    public function hapusTglTerimaNota($notaid)
    {
        $kartupiutang = KartuPiutang::where('notaid',$notaid);
        $kartupiutang->delete();

        $notapenjualan = NotaPenjualan::find($notaid);
        $notapenjualan->tglnota = null;
        $notapenjualan->save();
        return true;
    }

    public function changeTipeTransaksi(Request $req){
        $nota = NotaPenjualan::find($req->id);
        $temposalesman = $nota->toko->tokojw->jwsales;

        return response()->json([
            'success' => true,
            'temposalesman' => $temposalesman,
        ]);
    }

    public function cekKewenangan(Request $req)
    {
        $lastUserId = auth()->user()->id;
        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                $nota = NotaPenjualan::find($req->notaid);
                if($req->about == 'nota'){
                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                        'url'     => route('notapenjualan.cetaknota').'?id='.$req->notaid,
                    ]);
                }elseif ($req->about == 'proforma') {
                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                        'url'     => route('notapenjualan.cetakproforma').'?id='.$req->notaid,
                    ]);
                }elseif ($req->about == 'delete') {
                    $this->deleteNota($req->notaid);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif ($req->about == 'deletedetail') {
                    $this->deleteNotaDetail($req->notaid);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif ($req->about == 'hapustglterima') {
                    $this->hapusTglTerimaNota($req->notaid);

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
    
    public function nomorkoli($nokoli)
    {
        $nomor = explode(",", $nokoli);
        $nomor = array_values(array_sort($nomor, function($value){ return $value; }));

        $awal = null;
        $koli = "";
        $separator = ",";
        
        foreach ($nomor as $urut) {
            if($awal == null)
            {
                $koli .= $urut;
            }
            else
            {
                if(((int)$awal + 1) != (int)$urut || $urut == max($nomor))
                {
                    $koli .= $separator . $urut . ",";
                    $separator = ",";
                }
                else
                {
                    $separator = "-";
                }
            }
            $awal = $urut;
        }

        return $koli;
    }

    public function synchHeader(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $nota = NotaPenjualanMurni::select([
                0 => "pj.notapenjualanmurni.id",
                1 => "pj.notapenjualanmurni.recordownerid",
                2 => "pj.notapenjualanmurni.orderpenjualanid",
                3 => "pj.notapenjualanmurni.omsetsubcabangid",
                4 => "pj.notapenjualanmurni.pengirimsubcabangid",
                5 => "pj.notapenjualanmurni.nonota",
                6 => "pj.notapenjualanmurni.tglnota",
                7 => "pj.notapenjualanmurni.tglproforma",
                8 => "pj.notapenjualanmurni.tokoid",
                9 => "pj.notapenjualanmurni.karyawanidsalesman",
                10 => "pj.notapenjualanmurni.tglcheck",
                11 => "pj.notapenjualanmurni.tipetransaksi",
                12 => "pj.notapenjualanmurni.temponota",
                13 => "pj.notapenjualanmurni.tempokirim",
                14 => "pj.notapenjualanmurni.temposalesman",
                15 => "pj.notapenjualanmurni.karyawanidchecker1",
                16 => "pj.notapenjualanmurni.karyawanidchecker2",
                17 => "pj.notapenjualanmurni.printproforma",
                18 => "pj.notapenjualanmurni.printnota",
                19 => "pj.notapenjualanmurni.tglprintnota",
                20 => DB::raw("pj.notapenjualanmurni.tglprintproforma::date"),
                21 => "pj.notapenjualanmurni.totalnominal",
                22 => "pj.notapenjualanmurni.isarowid",
                23 => "pj.notapenjualanmurni.createdby",
                24 => "pj.notapenjualanmurni.lastupdatedby",
                25 => "mstr.toko.kodetoko",
                26 => "mstr.toko.namatoko",
                27 => "mstr.toko.alamat",
                28 => "mstr.toko.kota",
                29 => "cbomset.kodecabang as omsetcabang",
                30 => "cbkirim.kodecabang as kirimcabang",
                31 => "hr.karyawan.kodesales",
                32 => "pj.orderpenjualan.tipetransaksi",
                33 => "chk1.namakaryawan as checker1",
                34 => "chk2.namakaryawan as checker2",
            ])
            ->join("pj.orderpenjualan", "pj.notapenjualanmurni.orderpenjualanid", "=", "pj.orderpenjualan.id")
            ->join("mstr.toko", "pj.notapenjualanmurni.tokoid", "=", "mstr.toko.id")
            ->join("hr.karyawan", "pj.notapenjualanmurni.karyawanidsalesman", "=", "hr.karyawan.id")
            ->leftjoin("hr.karyawan as chk1", "pj.notapenjualanmurni.karyawanidchecker1", "=", "chk1.id")
            ->leftjoin("hr.karyawan as chk2", "pj.notapenjualanmurni.karyawanidchecker2", "=", "chk2.id")
            ->join("mstr.subcabang as subcbomset", "pj.notapenjualanmurni.omsetsubcabangid", "=", "subcbomset.id")
            ->join("mstr.cabang as cbomset", "subcbomset.cabangid", "=", "cbomset.id")
            ->join("mstr.subcabang as subcbkirim", "pj.notapenjualanmurni.omsetsubcabangid", "=", "subcbkirim.id")
            ->join("mstr.cabang as cbkirim", "subcbkirim.cabangid", "=", "cbkirim.id")
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

    public function synchDetail(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $details = NotaPenjualanDetail::select([
                0 => "pj.notapenjualandetail.id",
                1 => "pj.notapenjualandetail.notapenjualanid",
                2 => "pj.notapenjualandetail.stockid",
                3 => "pj.notapenjualandetail.qtynota",
                4 => "pj.notapenjualandetail.hrgsatuanbrutto",
                5 => "pj.notapenjualandetail.disc1",
                6 => "pj.notapenjualandetail.disc2",
                7 => "pj.notapenjualandetail.totaldiscount",
                8 => "pj.notapenjualandetail.ppn",
                9 => "pj.notapenjualandetail.hrgsatuannetto",
                10 => "pj.notapenjualandetail.stockgudang",
                11 => "pj.notapenjualandetail.orderpenjualandetailid",
                12 => "pj.notapenjualandetail.koreksipjparentid",
                13 => "pj.notapenjualandetail.newchildid",
                14 => "pj.notapenjualandetail.isarowid",
                15 => "pj.notapenjualandetail.createdby",
                17 => "pj.notapenjualandetail.lastupdatedby",
                19 => "pj.notapenjualandetail.kartupiutangdetailid",
                20 => "pj.notapenjualandetail.pot",
                21 => "mstr.stock.kodebarang",
                22 => "mstr.stock.namabarang",
                23 => "pj.notapenjualandetailnokoli.nokoli",
                24 => "pj.notapenjualandetailnokoli.keterangan",
            ])
            ->join("pj.notapenjualanmurni", "pj.notapenjualandetail.notapenjualanid", "=", "pj.notapenjualanmurni.id")
            ->join("mstr.stock", "pj.notapenjualandetail.stockid", "=", "mstr.stock.id")
            ->join("mstr.subcabang as subcbowner", "pj.notapenjualanmurni.recordownerid", "=", "subcbowner.id")
            ->leftjoin("pj.notapenjualandetailnokoli", "pj.notapenjualandetailnokoli.id", "=", "pj.notapenjualandetail.id")
            ->where(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.notapenjualanmurni.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.notapenjualanmurni.createdon::date"), "<=", $tglselesai);
            })
            ->orWhere(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.notapenjualandetail.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.notapenjualandetail.createdon::date"), "<=", $tglselesai);
            })
            ->where("subcbowner.kodesubcabang", $cabang)
            ->get();

        $data = [];
        foreach ($details as $key => $value) {
            $data[$key] = $value->toArray();
            $data[$key]["nokoli"] = self::nomorkoli($data[$key]["nokoli"]);
        }

        return json_encode($data);
    }

    public function synchKoreksi(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $details = KoreksiPenjualanDetail::select([
                0 => "pj.koreksipenjualandetail.id",
                1 => "pj.koreksipenjualandetail.notapenjualanid",
                2 => "pj.koreksipenjualandetail.stockid",
                3 => "pj.koreksipenjualandetail.qtynota",
                4 => "pj.koreksipenjualandetail.hrgsatuanbrutto",
                5 => "pj.koreksipenjualandetail.disc1",
                6 => "pj.koreksipenjualandetail.disc2",
                7 => "pj.koreksipenjualandetail.totaldiscount",
                8 => "pj.koreksipenjualandetail.ppn",
                9 => "pj.koreksipenjualandetail.hrgsatuannetto",
                10 => "pj.koreksipenjualandetail.stockgudang",
                11 => "pj.koreksipenjualandetail.orderpenjualandetailid",
                12 => "pj.koreksipenjualandetail.koreksipjparentid",
                13 => "pj.koreksipenjualandetail.newchildid",
                14 => "pj.koreksipenjualandetail.isarowid",
                15 => "pj.koreksipenjualandetail.createdby",
                17 => "pj.koreksipenjualandetail.lastupdatedby",
                19 => "pj.koreksipenjualandetail.kartupiutangdetailid",
                20 => "pj.koreksipenjualandetail.pot",
                21 => "pj.koreksipenjualandetail.notapenjualandetailid",
                22 => "pj.koreksipenjualandetail.hrgkoreksi",
                23 => "pj.koreksipenjualandetail.qtykoreksi",
                24 => "pj.notapenjualan.tglproforma",
                24 => "pj.notapenjualan.nonota",
                25 => "mstr.toko.kodetoko",
                26 => "mstr.stock.kodebarang",
                27 => "mstr.stock.namabarang",
            ])
            ->join("pj.notapenjualan", "pj.koreksipenjualandetail.notapenjualanid", "=", "pj.notapenjualan.id")
            ->join("mstr.stock", "pj.koreksipenjualandetail.stockid", "=", "mstr.stock.id")
            ->join("mstr.subcabang as subcbowner", "pj.notapenjualan.recordownerid", "=", "subcbowner.id")
            ->join("mstr.toko", "pj.notapenjualan.tokoid", "=", "mstr.toko.id")
            ->where(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.notapenjualan.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.notapenjualan.createdon::date"), "<=", $tglselesai);
            })
            ->orWhere(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.koreksipenjualandetail.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.koreksipenjualandetail.createdon::date"), "<=", $tglselesai);
            })
            ->where("subcbowner.kodesubcabang", $cabang)
            ->get();

        $data = [];
        foreach ($details as $key => $value) {
            $data[$key] = $value->toArray();
        }

        return json_encode($data);
    }
}
