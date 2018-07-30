<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NotaPembelian;
// use App\Models\NotaPembelianMurni;
use App\Models\NotaPembelianDetail;
use App\Models\ReturPembelianDetail;
// use App\Models\KoreksiNotaPembelian;
use App\Models\SubCabang;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Numerator;
use Carbon\Carbon;
use DB;

class NotaPembelianController extends Controller
{
    protected $original_column = array(
        1 => "pb.notapembelian.tglnota",
        2 => "pb.notapembelian.nonota",
        3 => "mstr.supplier.nama",
        4 => "pb.notapembelian.tglterima",
        5 => "sum(pb.notapembeliandetail.qtynota*pb.notapembeliandetail.hrgsatuannetto)",
    );

    public function index()
    {
        $tipe = null;
    	return view('transaksi.notapembelian.index',compact('tipe'));
    }

    public function barangDiterimaGudang()
    {
        $tipe = 'gudang';
        return view('transaksi.notapembelian.index',compact('tipe'));
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapembelian.index')) {
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
            0 => "pb.notapembelian.tglnota",
            1 => "pb.notapembelian.nonota",
            2 => "mstr.supplier.nama as suppliernama",
            3 => "pb.notapembelian.tglterima",
            4 => "sum(pb.notapembeliandetail.qtynota*pb.notapembeliandetail.hrgsatuannetto) as hargatotal",
            5 => "pb.notapembelian.id",
            6 => "pb.notapembelian.staffidpemeriksa11",
            7 => "pb.notapembelian.expedisiid",
            8 => "hr.karyawan.namakaryawan as karyawannama",
            9 => "pb.notapembelian.keterangan",
            10 => "pb.notapembelian.lastupdatedby",
            11 => "pb.notapembelian.lastupdatedon",
            12 => "count(*) over() as totalrecord"
        );
        for ($i=1; $i < 6; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        $notapembelian = NotaPembelian::selectRaw(collect($columns)->implode(', '));
        $notapembelian->leftJoin('pb.notapembeliandetail', 'pb.notapembelian.id', '=', 'pb.notapembeliandetail.notapembelianid');
        $notapembelian->leftJoin('mstr.supplier', 'pb.notapembelian.supplierid', '=', 'mstr.supplier.id');
        $notapembelian->leftJoin('hr.karyawan', 'pb.notapembelian.staffidpemeriksa00', '=', 'hr.karyawan.id');
        $notapembelian->where("pb.notapembelian.recordownerid",$req->session()->get('subcabang'))->where("pb.notapembelian.tglnota",'>=',Carbon::parse($req->tglmulai))->where("pb.notapembelian.tglnota",'<=',Carbon::parse($req->tglselesai));
        $notapembelian->groupBy('pb.notapembelian.id','pb.notapembelian.tglnota','pb.notapembelian.nonota','mstr.supplier.nama','pb.notapembelian.tglterima','hr.karyawan.namakaryawan');
        $total_data = $notapembelian->value('totalrecord');
        if($empty_filter < 5){
            for ($i=1; $i < 6; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index == 2 || $index == 3){
                        if($req->custom_search[$i]['filter'] == '='){
                            $notapembelian->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $notapembelian->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }elseif($index == 5){
                        $notapembelian->having(DB::raw($this->original_column[$index]),$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }else{
                        $notapembelian->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($req->tipe){
            $notapembelian->where('pb.notapembelian.tglterima','!=',null);
        }
        if($filter_count > 0){
            $filtered_data = $notapembelian->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $notapembelian->orderBy('pb.notapembelian.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $notapembelian->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $notapembelian->skip(0)->take($req->length);
        }else{
            $notapembelian->skip($req->start)->take($req->length);
        }
        $data = array();
        foreach ($notapembelian->get() as $key => $beli) {
            $beli->lastupdatedon = $beli->lastupdatedon;
            $beli->tglnota       = $beli->tglnota;
            $beli->tglterima     = $beli->tglterima;
            $data[$key]          = $beli->toArray();
            $data[$key]['DT_RowId'] = 'gv1_'.$beli->id;

            if($beli->tglterima != null){
                if(Carbon::parse($beli->tglterima)->toDateString() == Carbon::now()->toDateString()){
                    $data[$key]['edit'] = 'auth';
                }else{
                    $data[$key]['edit'] = 'Tanggal server tidak sama dengan tanggal terima. Tidak bisa edit data. Hubungi manager anda.';
                }
            }else{
                $data[$key]['edit'] = 'edit';
            }
            foreach ($beli->details as $belidetail) {
                if($belidetail->newchildid){
                    $data[$key]['edit'] = 'unedit';
                }
            }

            $data[$key]['action'] = "";
            if($data[$key]['edit'] != 'unedit'){
                $data[$key]['action'] = "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Update - F2' onclick='update(this)' data-message='".$data[$key]['edit']."' data-tipe='header'><i class='fa fa-pencil'></i></div>";
            }

            $data[$key]['hargatotal'] = number_format($beli->hargatotal,0,',','.');
        }
        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }

    public function getDataDetail(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapembelian.index')) {
           return response()->json(['node' => []]);
        }

        // jika lolos, tampilkan data
        $nota = NotaPembelian::find($req->id);
        if($nota->tglterima != null){
            $tglterima = true;
            if(Carbon::parse($nota->tglterima)->toDateString() == Carbon::now()->toDateString()){
                $tglterimasama = true;
            }else{
                $tglterimasama = false;
            }
        }else{
            $tglterima = false;
        }
        $nota_details = $nota->details;
        $node = array();
        foreach ($nota_details as $detail) {
            $barang = $detail->barang;
            if($tglterima) {
                //checker edit
                if($tglterimasama) {
                    if($detail->returdetail->isEmpty()) {
                        $edit = 'edit';
                    }else{
                        $edit = 'Tidak bisa edit record. Data nota pembelian detail sudah tercatat di riwayat retur pembelian detail. Hubungi Manager Anda.';
                    }
                }else{
                    $edit = 'Tidak bisa edit record. Tanggal server tidak sama dengan Tanggal terima nota pembelian. Hubungi Manager Anda.';
                }
                //checker koreksi
                if($detail->qtyterima == 0) {
                    $koreksi = 'Tidak bisa koreksi barang dengan nilai Qty. Terima = 0. Tidak ada yang bisa dikoreksi. Hubungi Manager anda.';
                }else{
                    if($detail->returdetail->isEmpty()) {
                        $koreksi = 'koreksi';
                    }else{
                        $koreksi = 'Tidak koreksi record. Data nota pembelian detail sudah tercatat di riwayat retur pembelian detail. Hubungi Manager Anda.';
                    }
                }
            }else{
                $edit = 'Tidak bisa edit record. Tanggal terima nota pembelian harus diisi dahulu. Hubungi manager anda';
                $koreksi = 'Koreksi hanya bisa dilakukan jika barang sudah diterima. Hubungi Manager anda.';
            }
            if($detail->newchildid){
                $edit = 'unedit';
                $koreksi = 'unkoreksi';
            }
            if($detail->koreksipbparentid){
                $edit = 'unedit';
            }

            // Action Pindah Ke Backend
            if($edit == 'unedit' && $koreksi == 'unkoreksi'){
                $action = null;
            }else if($edit == 'unedit' && $detail->qtyterima < 0){
                $action = null;
            }else if($edit == 'unedit'){
                $action = '<div class="btn btn-info btn-xs no-margin-action skeyF6" onclick="koreksi(this)" data-message="'.$koreksi.'" data-toggle="tooltip" data-placement="bottom" title="Koreksi - F6"><i class="fa fa-edit"></i></div>';
            }else if($detail->qtyterima < 0){
                $action = '<div class="btn btn-warning btn-xs no-margin-action skeyF2" onclick="update(this)" data-message="'.$edit.'" data-tipe="detail" data-toggle="tooltip" data-placement="bottom" title="Edit - F2"><i class="fa fa-pencil"></i></div>';
            }else{
                $action = '<div class="btn btn-warning btn-xs no-margin-action skeyF2" onclick="update(this)" data-message="'.$edit.'" data-tipe="detail" data-toggle="tooltip" data-placement="bottom" title="Edit - F2"><i class="fa fa-pencil"></i></div>';
                $action .= '<div class="btn btn-info btn-xs no-margin-action skeyF6" onclick="koreksi(this)" data-message="'.$koreksi.'" data-toggle="tooltip" data-placement="bottom" title="Koreksi - F6"><i class="fa fa-edit"></i></div>';
            }

            $temp = [
                'DT_RowId'       => 'gv2_'.$detail->id,
                'action'         => $action,
                'barang'         => $barang->namabarang,
                'satuan'         => $barang->satuan,
                'qtynota'        => $detail->qtynota,
                'qtyterima'      => $detail->qtyterima,
                'hrgsatuannetto' => number_format($detail->hrgsatuannetto,2,',','.'),
                'hrgtotalnetto'  => number_format($detail->qtynota*$detail->hrgsatuannetto,0,',','.'),
                // 6 => [
                //     'edit'      => $edit,
                //     'koreksi'   => $koreksi
                // ],
                'id'                => $detail->id,
                'koreksipbparentid' => $detail->koreksipbparentid,
                'newchildid'        => $detail->newchildid,
            ];
            array_push($node, $temp);
        }
        return response()->json([
            'node' => $node,
        ]);
    }

    public function getDetail(Request $req)
    {
        $detail = NotaPembelianDetail::find($req->id);
        $nota = $detail->nota;
        if($nota->staff != null){
            $staff = $nota->staff->namakaryawan;
            $staff_id = $nota->staff->id;
        }else{
            $staff = '';
            $staff_id = '';
        }

        if($nota->pusat != null){
            $pusat = $nota->pusat->name;
        }else{
            $pusat = '';
        }

        // $hrgdisc1 = ($detail->qtynota*$detail->hrgsatuannetto)-($detail->hrgsatuannetto*$detail->disc1);
        // $hrgdisc2 = $hrgdisc1-($detail->hrgsatuannetto*$detail->disc2);
        $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto * $detail->qtynota;
        $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;

        return response()->json([
            'notaid'            => $nota->id,
            'notadetailid'      => $detail->id,
            'tglnota'           => $nota->tglnota,
            'nonota'            => $nota->nonota,
            'tglterima'         => $nota->tglterima,
            'supplier'          => $nota->supplier->nama,
            'pemeriksa00'       => $staff,
            'pemeriksa00id'     => $staff_id,
            'keterangan'        => $nota->keterangan,
            'pemeriksa11'       => $pusat,
            'namabarang'        => $detail->barang->namabarang,
            'satuan'            => $detail->barang->satuan,
            'qtynota'           => $detail->qtynota,
            'qtyterima'         => $detail->qtyterima,
            'hrgsatuanbrutto'   => $detail->hrgsatuanbrutto,
            'disc1'             => $detail->disc1,
            'hrgdisc1'          => $hrgdisc1,
            'disc2'             => $detail->disc2,
            'hrgdisc2'          => $hrgdisc2,
            'ppn'               => $detail->ppn,
            'hrgsatuannetto'    => $detail->hrgsatuannetto,
            'keteranganbarang'  => $detail->keterangan,
            'lastupdatedby'     => $detail->lastupdatedby,
            'lastupdatedon'     => $detail->lastupdatedon,
        ]);
    }

    public function simpan(Request $req)
    {
        $nota = NotaPembelian::find($req->data['notaid']);
        $nota->staffidpemeriksa00   = $req->data['pemeriksa00id'];
        $nota->keterangan           = strtoupper($req->data['keterangan']);
        $nota->tglterima            = Carbon::parse($req->data['tglterima']);
        $nota->save();

        if($nota->staff != null){
            $staff = $nota->staff->namakaryawan;
        }else{
            $staff = '';
        }

        $notadetails = $nota->details;
        $node = array();
        foreach ($notadetails as $detail) {
            $temp = [
                0 => $detail->barang->namabarang,
                1 => $detail->barang->satuan,
                2 => $detail->qtynota,
                3 => $detail->qtyterima,
            ];
            array_push($node, $temp);
        }

        return response()->json([
            'id'            => $nota->id,
            'tglnota'       => $nota->tglnota,
            'nonota'        => $nota->nonota,
            'tglterima'     => $nota->tglterima,
            'supplier'      => $nota->supplier->nama,
            'pemeriksa00'   => $staff,
            'node'          => $node,
        ]);
    }

    public function simpanKoreksi(Request $req)
    {
        $numerator = Numerator::find(Numerator::where('doc','KOREKSI NOTA PEMBELIAN')->value('id'));
        $depan     = $numerator->depan;
        $lebar     = $numerator->lebar-strlen($numerator->depan);
        $nomor     = sprintf("%0".$lebar."d", $numerator->nomor);
        
        $notalama = NotaPembelian::find($req->data['nota']);
        $nota                     = new NotaPembelian;
        // $nota->id              = $nota->nextstatementid;
        $nota->recordownerid      = $notalama->recordownerid;
        $nota->tglnota            = Carbon::now()->toDateString();
        $nota->nonota             = $depan.$nomor;
        $nota->supplierid         = $notalama->supplierid;
        $nota->tglterima          = Carbon::parse($req->data['tglterima'])->toDateString();
        $nota->expedisiid         = $notalama->expedisiid;
        $nota->staffidpemeriksa00 = $req->data['pemeriksa00'];
        $nota->keterangan         = strtoupper($req->data['keterangan'].' | '.$req->data['catatan']);
        $nota->staffidpemeriksa11 = $notalama->staffidpemeriksa11;
        $nota->createdby          = auth()->user()->name;
        $nota->lastupdatedby      = auth()->user()->name;
        $nota->save();
        $numerator->nomor++;
        $numerator->save();

        $detaillama = NotaPembelianDetail::find($req->data['notadetail']);
        $detail_minus                    = new NotaPembelianDetail;
        // $detail_minus->id             = $detail_minus->nextstatementid;
        $detail_minus->notapembelianid   = $nota->id;
        $detail_minus->stockid           = $detaillama->stockid;
        $detail_minus->qtynota           = 0;
        $detail_minus->qtyterima         = $detaillama->qtyterima*-1;
        $detail_minus->hrgsatuanbrutto   = $detaillama->hrgsatuanbrutto;
        $detail_minus->disc1             = $detaillama->disc1;
        $detail_minus->disc2             = $detaillama->disc2;
        $detail_minus->ppn               = $detaillama->ppn;
        $detail_minus->hrgsatuannetto    = $detaillama->hrgsatuannetto;
        $detail_minus->keterangan        = strtoupper($req->data['keterangan'].' | '.$req->data['catatan']);
        $detail_minus->koreksipbparentid = $detaillama->id;
        $detail_minus->createdby         = auth()->user()->name;
        $detail_minus->lastupdatedby     = auth()->user()->name;
        $detail_minus->save();


        $detail = new NotaPembelianDetail;
        // $detail->id                      = $detail->nextstatementid;
        $detail->notapembelianid   = $nota->id;
        $detail->stockid           = $detaillama->stockid;
        $detail->qtynota           = 0;
        $detail->qtyterima         = $req->data['qtyterima'];
        $detail->hrgsatuanbrutto   = $detaillama->hrgsatuanbrutto;
        $detail->disc1             = $detaillama->disc1;
        $detail->disc2             = $detaillama->disc2;
        $detail->ppn               = $detaillama->ppn;
        $detail->hrgsatuannetto    = $detaillama->hrgsatuannetto;
        $detail->keterangan        = strtoupper($req->data['keteranganbarang']);
        $detail->koreksipbparentid = $detaillama->id;
        $detail->createdby         = auth()->user()->name;
        $detail->lastupdatedby     = auth()->user()->name;
        $detail->save();

        $detaillama->newchildid = $detail->id;
        $detaillama->save();

        return response()->json(['success'=>true]);
    }

     public function simpanAPIKoreksi(Request $req)
    {
        $kodesubcabang              = $req->kodesubcabang;
        $tglnotaawal                = $req->tglnotaawal;
        $nonotaawal                 = $req->nonotaawal;
        $nonotakoreksi              = $req->nonotakoreksi;
        $keteranganheader           = $req->keteranganheader;
        $kodebarang                 = $req->kodebarang;
        $qtynotasetelahkoreksi      = $req->qtynotasetelahkoreksi;
        $hargasatuansetelahkoreksi  = $req->hargasatuansetelahkoreksi;
        $keterangandetail           = $req->keterangandetail;
        
        $subcabang = SubCabang::where('kodesubcabang',$kodesubcabang)->first();
        if($subcabang){
            $ceknotalama = NotaPembelian::where('recordownerid',$subcabang->id)->where('nonota',$nonotaawal)->whereDate('tglnota',$tglnotaawal)->first();
            if ($ceknotalama) {
                  // insert record ke notapembelian header dengan nonota K0012345 , tglnota & tglterima = getdate , selebihnya replicate yg ada di nota aaslinya 
                 //    keterangan = 'testing 123'
                    $nota                     = new NotaPembelian;
                    $nota->recordownerid      = $ceknotalama->recordownerid;
                    $nota->tglnota            = Carbon::now()->toDateString();
                    $nota->nonota             = $nonotakoreksi;
                    $nota->supplierid         = $ceknotalama->supplierid;
                    $nota->tglterima          = Carbon::now()->toDateString();
                    $nota->expedisiid         = $ceknotalama->expedisiid;
                    $nota->staffidpemeriksa00 = $ceknotalama->staffidpemeriksa00;
                    $nota->keterangan         = strtoupper($keteranganheader);
                    $nota->staffidpemeriksa11 = $ceknotalama->staffidpemeriksa11;
                    $nota->createdby          = strtoupper('stok1101');
                    $nota->lastupdatedby      = strtoupper('stok1101');
                    $nota->save();
                    // 3. insert record ke notapembelian detail ( 2 record ) 
                    // 3.1 record ke 1 -> insert sama persis yg ada di notapembelian detail aslinya , namun qtynya di kali -1 
                    // 3.2 record ke 2 -> insert notapembeliandetail dg kolom  lookup stockid yg FB412345678 , Qty = 100 , hrg - 7800 , keterangan = testing 98765 
                    $barang    = Barang::where('kodebarang',$kodebarang)->first();
                    if($barang){
                        $cekdetaillama = NotaPembelianDetail::where('stockid',$barang->id)->where('notapembelianid',$ceknotalama->id)->first();
                        if ($cekdetaillama) {
                            // 3.1 record ke 1 -> insert sama persis yg ada di notapembelian detail aslinya , namun qtynya di kali -1 
                            $detail_minus                    = new NotaPembelianDetail;
                            $detail_minus->notapembelianid   = $nota->id;
                            $detail_minus->stockid           = $cekdetaillama->stockid;
                            $detail_minus->qtynota           = $cekdetaillama->qtyterima*-1;
                            $detail_minus->qtyterima         = $cekdetaillama->qtyterima*-1;
                            $detail_minus->hrgsatuanbrutto   = $cekdetaillama->hrgsatuanbrutto;
                            $detail_minus->disc1             = $cekdetaillama->disc1;
                            $detail_minus->disc2             = $cekdetaillama->disc2;
                            $detail_minus->ppn               = $cekdetaillama->ppn;
                            $detail_minus->hrgsatuannetto    = $cekdetaillama->hrgsatuannetto;
                            $detail_minus->keterangan        = strtoupper($cekdetaillama->keterangan);
                            $detail_minus->koreksipbparentid = $cekdetaillama->id;
                            $detail_minus->createdby         = strtoupper('stok1101');
                            $detail_minus->lastupdatedby     = strtoupper('stok1101');
                            $detail_minus->save();
                            // 3.2 record ke 2 -> insert notapembeliandetail dg kolom  lookup stockid yg FB412345678 , Qty = 100 , hrg - 7800 , keterangan = testing 98765 
                            $detailbaru = new NotaPembelianDetail;
                            $detailbaru->notapembelianid   = $nota->id;
                            $detailbaru->stockid           = $barang->id;
                            $detailbaru->qtynota           = $qtynotasetelahkoreksi;
                            $detailbaru->qtyterima         = $qtynotasetelahkoreksi;
                            $detailbaru->hrgsatuanbrutto   = $hargasatuansetelahkoreksi;
                            $detailbaru->disc1             = $cekdetaillama->disc1;
                            $detailbaru->disc2             = $cekdetaillama->disc2;
                            $detailbaru->ppn               = $cekdetaillama->ppn;
                            $detailbaru->hrgsatuannetto    = $hargasatuansetelahkoreksi;
                            $detailbaru->keterangan        = strtoupper($keterangandetail);
                            $detailbaru->koreksipbparentid = 0;
                            $detailbaru->createdby         = strtoupper('stok1101');
                            $detailbaru->lastupdatedby     = strtoupper('stok1101');
                            $detailbaru->save();

                            $cekdetaillama->newchildid     = $detailbaru->id;
                            $cekdetaillama->save();
                            return response()->json([
                                'success'=>true, 
                                'idnotalama' => $ceknotalama->id,
                                'idnotabaru' => $nota->id,
                                'detailminus'=> $detail_minus->id,
                                'detailbaru' => $detailbaru->id,
                                'message'    =>'Berhasil ditambahkan'
                            ]);
                        }
                    }
            }else{
                return response()->json([
                    'success'=>false, 
                    'message'=>'Nota Pembelian tidak ditemukan.'
                ]);
            }
        }else{
            return response()->json([
                    'success'=>false, 
                    'message'=>'Kode SubCabang tidak ada.'
            ]);
        }
    }

    public function simpanDetail(Request $req)
    {
        if($req->tipe == 'detail'){
            $notaid = '';
            $index = '';
            $detail = NotaPembelianDetail::find($req->notaid);
            $detail->qtyterima = $req->data[0];
            $detail->save();
        }else{
            $notaid = $req->notaid;
            $index = '';
            $nota = NotaPembelian::find($req->notaid);
            foreach ($nota->details as $key => $detail) {
                $detail->qtyterima = $req->data[$key];
                $detail->save();
            }
        }

        return response()->json(['success'=>true,'index'=>$index, 'notaid'=>$notaid]);
    }

    public function cekKewenangan(Request $req)
    {
        $lastUserId = auth()->user()->id;  
        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                $nota = NotaPembelian::find($req->notaid);
                return response()->json([
                    'success'               => true,
                    'id'                    => $nota->id,
                    'suppliernama'          => $nota->supplier->nama,
                    'tglnota'               => $nota->tglnota,
                    'nonota'                => $nota->nonota,
                    'tglterima'             => $nota->tglterima,
                    'staffidpemeriksa11'    => $nota->staffidpemeriksa11
                ]);
            }
        }
        return response()->json([
            'success' => false,
        ]);
    }

    public function findData($data, $id){
        return collect($data)->flatten()->search($id);
    }

    public function getFrom11(Request $req)
    {
        $curuser = "synch11";
        $result = array(
            "result" => false
        );
        $data = array();
        $trans = false;

        try {
            $data = json_decode($req->data, true);
            
            // debug
            // return response()->json($data);

            // check minimal data
            $allow = array("RowID", "CabangID", "SupplierID", "NoOrder", "NoNota", "TglNota", "Keterangan", "Details", "User");
            foreach($data as $k => $v) {
                $i = array_search($k, $allow);
                if($i !== false) {
                    unset($allow[$i]);
                }
            }
            if(count($allow) > 0) {
                throw new \Exception("Data request not completed,\nMissing data: " . join($allow, ", "));
            }

            /*
             * json format
             * {
             *    header.data, ...
             *    [details]: [{detail.data}, ...]
             * }
             */
            $itCreate = false;
            $curuser = $data["User"] + "_" + $curuser;
            if (strlen(trim(strval($data["NoNota"]))) <= 0) throw new \Exception("Data NoNota cannot be null");

            $dat = NotaPembelian::where("nonota", $data["NoNota"])->first();
            if(count($dat) <= 0) {
                $dat = new NotaPembelian();
                $dat->isarowid = $data["RowID"];
                $dat->createdby = $curuser;
                $itCreate = true;
            }

            $rdid = SubCabang::where("kodesubcabang", $data["CabangID"])->first();
            $spid = Supplier::where(DB::raw("trim(kode)"), trim($data["SupplierID"]))->first();

            // reduce fail query
            if(count($rdid) <= 0 || count($spid) <= 0) {
                throw new \Exception("Cannot find subcabang and/or supplier\nID: " . $data["RowID"]);

            } else {
                $rdid = $rdid->id;
                $spid = $spid->id;

                $dat->recordownerid = $rdid;
                $dat->supplierid    = $spid;
                $dat->noorder       = $data["NoOrder"];
                $dat->nonota        = $data["NoNota"];
                $dat->tglnota       = $data["TglNota"];
                $dat->keterangan    = $data["Keterangan"];
                $dat->lastupdatedby = $curuser;

                $details = array();
                foreach($data["Details"] as $ddata) {
                    // check minimal data
                    $allow = array("RowID", "KodeBarang", "QtyKirim", "HrgJual", "Keterangan");
                    foreach($ddata as $k => $v) {
                        $i = array_search($k, $allow);
                        if($i !== false) {
                            unset($allow[$i]);
                        }
                    }
                    if(count($allow) > 0) {
                        throw new \Exception("Data detail request not completed\nMissing data: " . join($allow, ", "));
                    }

                    // update when exist or insert when not
                    $dat2 = NotaPembelianDetail::where("isarowid", $ddata["RowID"])->first();
                    if(count($dat2) <= 0) {
                        $dat2 = new NotaPembelianDetail();
                        $dat2->isarowid = $ddata["RowID"];
                        $dat2->createdby = $curuser;
                    }

                    $skid = Barang::where("kodebarang", $ddata["KodeBarang"])->first();

                    if(count($skid) <= 0) {
                        throw new \Exception("Cannot find kodebarang\nID: " . $ddata["RowID"]);
                        break;

                    } else {
                        $skid = $skid->id;
                    }
    
                    $dat2->stockid = $skid;
                    $dat2->qtynota = $ddata["QtyKirim"];
                    $dat2->hrgsatuanbrutto = $ddata["HrgJual"];
                    $dat2->hrgsatuannetto = $ddata["HrgJual"];
                    $dat2->disc1 = 0;
                    $dat2->disc2 = 0;
                    $dat2->ppn = 0;
                    $dat2->keterangan = $ddata["Keterangan"];
                    $dat2->lastupdatedby = $curuser;
                    if(!$itCreate) $dat2->notapembelianid = $dat->id;

                    $details[$ddata["RowID"]] = $dat2;
                }

                // sensitive query
                $trans = true;
                DB::beginTransaction();

                if($dat->save()) {
                    foreach($details as $k => $v) {
                        // cover when it new record
                        if($itCreate) $v->notapembelianid = $dat->id;
                        if(!$v->save()) {
                            throw new \Exception("Cannot insert/update detail\nID: " . $k);
                            break;
                        }
                    }

                } else {
                    throw new \Exception("Cannot insert/update header\nID: " .$data["RowID"]);
                }

                DB::commit();
                $result["result"] = true;
            }

        } catch(\Exception $ex) {
            if($trans) { // rollback when fail
                DB::rollback();
            }

            $result["msg"] = $ex->getMessage();
            $result["line"] = $ex->getLine();
            $result["result"] = false;
        }
        
        return response()->json($result);
    }
}
