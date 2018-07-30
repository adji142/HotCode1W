<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\StockOpnameHistory;
use App\Models\Barang;
use App\Models\NotaPenjualan;
use App\Models\ReturPenjualan;
use App\Models\NotaPembelian;
use App\Models\ReturPembelian;
use App\Models\GudangSementara;
use App\Models\AntarGudang;
use App\Models\Mutasi;
use App\Models\SubCabang;
use App\Models\PenanggungJawabArea;
use EXCEL;
use PDF;
use DB;

class StockOpnameController extends Controller
{
	protected $original_column_header = array(
        // 1 => "mstr.stock.id",
        1 => "mstr.stock.namabarang",
        2 => "mstr.stock.kodebarang",
        3 => "mstr.stock.satuan",
        4 => "mstr.stock.statusaktif",
        5 => "mstr.stock.area1",
    );

	protected $original_column_detail = array(
        1 => "stk.stockopnamehistory.tglrencana",
        2 => "stk.stockopnamehistory.tglstop",
        3 => "stk.stockopnamehistory.qtyawal",
        4 => "stk.stockopnamehistory.qtybaik",
        5 => "stk.stockopnamehistory.qtyrusak",
        6 => "stk.stockopnamehistory.qtygs",
        7 => "stk.stockopnamehistory.qtyaggit",
        8 => "stk.stockopnamehistory.qtystop",
        10 => "stk.stockopnamehistory.tglclosing",
    );

    public function index()
    {
    	return view('transaksi.stockopname.index');
    }

    public function getDataHeader(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('opname.index')) {
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
            0 => "mstr.stock.id",
  	        1 => "mstr.stock.namabarang",
  	        2 => "mstr.stock.kodebarang",
  	        3 => "mstr.stock.satuan",
  	        4 => "mstr.stock.statusaktif",
            5 => "mstr.stock.area1",
        );
        for ($i=1; $i < 7; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        // $stop = StockOpnameHistory::selectRaw(collect($columns)->implode(', '));
        // $stop->leftJoin('mstr.stock', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id');
        // $stop->where("stk.stockopnamehistory.recordownerid",$req->session()->get('subcabang'));
        // $stop->where("stk.stockopnamehistory.tglrencana",'>=',Carbon::parse($req->tglmulai));
        // $stop->where("stk.stockopnamehistory.tglrencana",'<=',Carbon::parse($req->tglselesai));
        $stop = Barang::selectRaw(collect($columns)->implode(', '));
        $stop->where("statusaktif",'=',"TRUE");

        $total_data = $stop->count();
        if($empty_filter < 6){
            for ($i=1; $i < 7; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    // if(($index > 1 && $index < 5) || $index == 6){
                    if($index < 5 || $index == 6){
                        if($req->custom_search[$i]['filter'] == '='){
                            $stop->where($this->original_column_header[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $stop->where($this->original_column_header[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }elseif($index == 5){
                        if($req->custom_search[$i]['filter'] == '='){
                            $stop->where($this->original_column_header[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                        }else{
                            $stop->where($this->original_column_header[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                        }
                    }else{
                        $stop->where($this->original_column_header[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $stop->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $stop->orderBy('stk.stockopnamehistory.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column_header)){
                $stop->orderByRaw($this->original_column_header[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $stop->skip(0)->take($req->length);
        }else{
            $stop->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($stop->get() as $key => $stkop) {
      			$stkop->tglrencana = $stkop->tglrencana;
      			$stkop->tglstop    = $stkop->tglstop;
      			$data[$key]        = $stkop->toArray();
            $data[$key]['DT_RowId'] = 'gv1_'.$stkop->id;
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
        if(!$req->user()->can('opname.index')) {
            return response()->json([
              'soh' => [],
            ]);
        }

        // jika lolos, tampilkan data
        $soh = StockOpnameHistory::where('stockid',$req->id)
               ->where("recordownerid",$req->session()->get('subcabang'))
               ->where("tglrencana",'>=',Carbon::parse($req->session()->get('tglmulai')))
               ->where("tglrencana",'<=',Carbon::parse($req->session()->get('tglselesai')));
        $node_soh = array();

        foreach ($soh->get() as $stkop) {
            if($stkop->tglclosing){
                $hapus   = 'Tidak bisa hapus record. STOP barang '.$stkop->barang->namabarang.' kode barang '.$stkop->barang->kodebarang.' sudah di closing. Hubungi manager anda.';
                $update  = 'Tidak bisa update record. Data sudah di closing. Hubungi manager anda.';
                $closing = 'Tidak bisa closing lagi.';
            }else{
                if($stkop->karyawanidpenghitung){
                    $hapus = 'auth';
                }else{
                    $hapus = 'hapus';
                }
                $update  = 'update';
                $closing = 'closing';
            }
            $barang  = $stkop->barang->namabarang;

            $temp_soh = [
                'tglrencana' => $stkop->tglrencana,
                'tglstop'    => $stkop->tglstop,
                'qtyawal'    => $stkop->qtyawal,
                'qtybaik'    => $stkop->qtybaik,
                'qtyrusak'   => $stkop->qtyrusak,
                'qtygs'      => $stkop->qtygs,
                'qtyaggit'   => $stkop->qtyaggit,
                'qtystop'    => $stkop->qtystop,
                'qtyselisih' => $stkop->qtystop-$stkop->qtyawal,
                'tglclosing' => $stkop->tglclosing,
                'id'         => $stkop->id,
                'hapus'      => $hapus,
                'update'     => $update,
                'closing'    => $closing,
                'barang'     => $barang,
                'DT_RowId'   => 'gv2_'.$stkop->id,
            ];
            array_push($node_soh, $temp_soh);
        }

        return response()->json([
            'soh' => $node_soh,
        ]);
    }

   //  public function getDataDetail(Request $req)
   //  {
   //  	$req->session()->put('tglmulai', $req->tglmulai);
   //      $req->session()->put('tglselesai', $req->tglselesai);

   //      $filter_count = 0;
   //      $empty_filter = 0;
   //      $columns = array(
   //          0 => "stk.stockopnamehistory.tglrencana",
	  //       1 => "stk.stockopnamehistory.tglstop",
	  //       2 => "stk.stockopnamehistory.qtyawal",
	  //       3 => "stk.stockopnamehistory.qtybaik",
	  //       4 => "stk.stockopnamehistory.qtyrusak",
   //          5 => "stk.stockopnamehistory.qtygs",
   //          6 => "stk.stockopnamehistory.qtyaggit",
   //          7 => "stk.stockopnamehistory.qtystop",
   //          8 => "stk.stockopnamehistory.tglclosing",
   //          9 => "stk.stockopnamehistory.id",
   //          10 => "stk.stockopnamehistory.stockid",
   //          11 => "mstr.stock.namabarang",
   //      );
   //      for ($i=1; $i < 11; $i++) {
   //          if($req->custom_search[$i]['text'] == ''){
   //              $empty_filter++;
   //          }
   //      }
   //      $stop = StockOpnameHistory::selectRaw(collect($columns)->implode(', '));
   //      $stop->where("stk.stockopnamehistory.recordownerid",$req->session()->get('subcabang'))->where("stk.stockopnamehistory.tglrencana",'>=',Carbon::parse($req->tglmulai))->where("stk.stockopnamehistory.tglrencana",'<=',Carbon::parse($req->tglselesai));
   //      $stop->leftJoin('mstr.stock', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id');
   //      $total_data = $stop->count();
   //      if($empty_filter < 10){
   //          for ($i=1; $i < 11; $i++) {
   //              if($req->custom_search[$i]['text'] != ''){
   //                  $index = $i;
   //                  // searching
   //                  $filter_count++;
   //              }
   //          }
   //      }
   //      if($filter_count > 0){
   //          $filtered_data = $stop->count();
   //      }else{
   //          $filtered_data = $total_data;
   //      }
   //      if($req->tipe_edit){
   //          $stop->orderBy('stk.stockopnamehistory.lastupdatedon','desc');
   //      }else{
   //          if(array_key_exists($req->order[0]['column'], $this->original_column_detail)){
   //              $stop->orderByRaw($this->original_column_detail[$req->order[0]['column']].' '.$req->order[0]['dir']);
   //          }
   //      }
   //      if($req->start > $filtered_data){
   //          $stop->skip(0)->take($req->length);
   //      }else{
   //          $stop->skip($req->start)->take($req->length);
   //      }

   //      $data = array();
   //      foreach ($stop->get() as $key => $stkop) {
			// $stkop->tglrencana = $stkop->tglrencana;
			// $stkop->tglstop    = $stkop->tglstop;
   //          $stkop->qtyselisih = $stkop->qtystop-$stkop->qtyawal;
			// $data[$key]        = $stkop->toArray();
			// if($stkop->tglclosing){
			// 	$data[$key]['hapus']   = 'Tidak bisa hapus record. STOP barang '.$stkop->barang->namabarang.' kode barang '.$stkop->barang->kodebarang.' sudah di closing. Hubungi manager anda.';
			// 	$data[$key]['update']  = 'Tidak bisa update record. Data sudah di closing. Hubungi manager anda.';
   //              $data[$key]['closing'] = 'Tidak bisa closing lagi.';
			// 	$data[$key]['barang'] = '';
			// }else{
			// 	if($stkop->karyawanidpenghitung){
			// 		$data[$key]['hapus'] = 'auth';
			// 	}else{
			// 		$data[$key]['hapus'] = 'hapus';
			// 	}
			// 	$data[$key]['update']  = 'update';
   //              $data[$key]['closing'] = 'closing';
			// 	$data[$key]['barang'] = $stkop->namabarang;
			// }
   //      }

   //      return response()->json([
   //          'draw'              => $req->draw,
   //          'recordsTotal'      => $total_data,
   //          'recordsFiltered'   => $filtered_data,
   //          'data'              => $data,
   //      ]);
   //  }

    public function getStop(Request $req)
    {
    	$stop = StockOpnameHistory::find($req->id);

    	return response()->json([
            'sohid'                 => $stop->id,
            'stockid'               => $stop->stockid,
            'namabarang'            => $stop->barang->namabarang,
            'kodebarang'            => $stop->barang->kodebarang,
            'satuan'                => $stop->barang->satuan,
            'statusaktif'           => $stop->barang->statusaktif,
            'tglrencana'            => $stop->tglrencana,
            'tglstop'               => $stop->tglstop,
            'qtyawal'               => ($stop->qtyawal) ? $stop->qtyawal : 0,
            'qtybaik'               => ($stop->qtybaik) ? $stop->qtybaik : 0,
            'qtyrusak'              => ($stop->qtyrusak) ? $stop->qtyrusak : 0,
            'qtygs'                 => ($stop->qtygs) ? $stop->qtygs : 0,
            'qtyaggit'              => ($stop->qtyaggit) ? $stop->qtyaggit : 0,
            'qtystop'               => ($stop->qtystop) ? $stop->qtystop : 0,
            'qtyselisih'            => $stop->qtystop-$stop->qtyawal,
            'penghitung'            => ($stop->penghitung) ? $stop->penghitung->namakaryawan : '',
            'karyawanidpenghitung'  => $stop->karyawanidpenghitung,
            'pemeriksa'             => ($stop->pemeriksa) ? $stop->pemeriksa->namakaryawan : '',
            'karyawanidpemeriksa'   => $stop->karyawanidpemeriksa,
            'keteranganrencana'     => $stop->keteranganrencana,
            'keteranganhasilhitung' => $stop->keteranganhasilhitung,
            'lastupdatedby'         => $stop->lastupdatedby,
            'lastupdatedon'         => $stop->lastupdatedon,
    	]);
    }

    public function getStock(Request $req)
    {
        $barang = Barang::find($req->id);

        return response()->json([
            'namabarang'    => $barang->namabarang,
            'kodebarang'    => $barang->kodebarang,
            'rak1'          => $barang->area1,
            'rak2'          => $barang->area2,
            'rak3'          => $barang->area3,
            'statusaktif'   => $barang->statusaktifmutate,
        ]);
    }

    public function updateStop(Request $req)
    {
        $stop = StockOpnameHistory::find($req->id);
        $stop->tglstop  = Carbon::parse($req->tglstop)->toDateTimeString();
        $stop->qtybaik  = $req->qtybaik;
        $stop->qtyrusak = $req->qtyrusak;
        $stop->qtystop  = $req->qtybaik+$req->qtyrusak+$stop->qtygs+$stop->qtyaggit;
        $stop->karyawanidpenghitung  = $req->penghitungid;
        $stop->karyawanidpemeriksa   = $req->pemeriksaid;
        $stop->keteranganhasilhitung = $req->keteranganhasilhitung;
        $stop->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function closingStop(Request $req)
    {
        $stop             = StockOpnameHistory::find($req->id);
        $stop->tglclosing = Carbon::now();
        $stop->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function rencanaStop(Request $req, $tipe)
    {
      // ACCU MOBIL N-200 YUASA
        $data       = $req->all();
        $status     = '';
        $tipe       = 3;
        // $barang     = Barang::select('*');
        $sohclosing = StockOpnameHistory::select(['mstr.stock.id','mstr.stock.namabarang','mstr.stock.kodebarang','stk.stockopnamehistory.tglrencana','stk.stockopnamehistory.keteranganrencana'])
                    ->leftJoin('mstr.stock', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id')
                    ->where('stk.stockopnamehistory.tglclosing',null);
        if($req->namabarang){
            $sohclosing->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
        }

        if($sohclosing->count() > 0){
            $status = 'GAGAL INSERT KARENA ADA STOP SEBELUMNYA BELUM CLOSING';
            $tipe   = 0;
            $sohclosing->orderBy('mstr.stock.area1','asc');
            $sohclosing->orderBy('mstr.stock.area2','asc');
            $sohclosing->orderBy('mstr.stock.area3','asc');
            $sohclosing->orderBy('mstr.stock.namabarang','asc');
            $barang = $sohclosing->get();
        }else{
            $npj     = NotaPenjualan::select(['pj.notapenjualan.id','pj.notapenjualan.tglnota','pj.notapenjualandetail.stockid','mstr.stock.namabarang'])
                     ->where('pj.notapenjualan.tglnota',Carbon::parse($req->tgl))
                     ->leftJoin('pj.notapenjualandetail', 'pj.notapenjualan.id', '=', 'pj.notapenjualandetail.notapenjualanid')
                     ->leftJoin('mstr.stock', 'pj.notapenjualandetail.stockid', '=', 'mstr.stock.id');
            $rpj     = ReturPenjualan::select(['pj.returpenjualan.id','pj.returpenjualan.tglnotaretur','pj.returpenjualandetail.stockid','mstr.stock.namabarang'])
                     ->where('pj.returpenjualan.tglnotaretur',Carbon::parse($req->tgl))
                     ->leftJoin('pj.returpenjualandetail', 'pj.returpenjualan.id', '=', 'pj.returpenjualandetail.returpenjualanid')
                     ->leftJoin('mstr.stock', 'pj.returpenjualandetail.stockid', '=', 'mstr.stock.id');
            $npb     = NotaPembelian::select(['pb.notapembelian.id','pb.notapembelian.tglterima','pb.notapembeliandetail.stockid','mstr.stock.namabarang'])
                     ->where('pb.notapembelian.tglterima',Carbon::parse($req->tgl))
                     ->leftJoin('pb.notapembeliandetail', 'pb.notapembelian.id', '=', 'pb.notapembeliandetail.notapembelianid')
                     ->leftJoin('mstr.stock', 'pb.notapembeliandetail.stockid', '=', 'mstr.stock.id');
            $rpb     = ReturPembelian::select(['pb.returpembelian.id','pb.returpembelian.tglprb','pb.returpembeliandetail.stockid','mstr.stock.namabarang'])
                     ->where('pb.returpembelian.tglprb',Carbon::parse($req->tgl))
                     ->leftJoin('pb.returpembeliandetail', 'pb.returpembelian.id', '=', 'pb.returpembeliandetail.returpembelianid')
                     ->leftJoin('mstr.stock', 'pb.returpembeliandetail.stockid', '=', 'mstr.stock.id');
            $gstrans = GudangSementara::select(['stk.gudangsementara.id','stk.gudangsementara.tgltransaksi','stk.gudangsementaradetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.gudangsementara.tgltransaksi',Carbon::parse($req->tgl))
                     ->leftJoin('stk.gudangsementaradetail', 'stk.gudangsementara.id', '=', 'stk.gudangsementaradetail.gudangsementaraid')
                     ->leftJoin('mstr.stock', 'stk.gudangsementaradetail.stockid', '=', 'mstr.stock.id');
            $gslink  = GudangSementara::select(['stk.gudangsementara.id','stk.gudangsementara.tgllink','stk.gudangsementaradetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.gudangsementara.tgllink',Carbon::parse($req->tgl))
                     ->leftJoin('stk.gudangsementaradetail', 'stk.gudangsementara.id', '=', 'stk.gudangsementaradetail.gudangsementaraid')
                     ->leftJoin('mstr.stock', 'stk.gudangsementaradetail.stockid', '=', 'mstr.stock.id');
            $agkirim = AntarGudang::select(['stk.antargudang.id','stk.antargudang.tglkirim','stk.antargudangdetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.antargudang.tglkirim',Carbon::parse($req->tgl))
                     ->leftJoin('stk.antargudangdetail', 'stk.antargudang.id', '=', 'stk.antargudangdetail.antargudangid')
                     ->leftJoin('mstr.stock', 'stk.antargudangdetail.stockid', '=', 'mstr.stock.id');
            $agtrima = AntarGudang::select(['stk.antargudang.id','stk.antargudang.tglterima','stk.antargudangdetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.antargudang.tglterima',Carbon::parse($req->tgl))
                     ->leftJoin('stk.antargudangdetail', 'stk.antargudang.id', '=', 'stk.antargudangdetail.antargudangid')
                     ->leftJoin('mstr.stock', 'stk.antargudangdetail.stockid', '=', 'mstr.stock.id');
            $mt      = Mutasi::select(['stk.mutasi.id','stk.mutasi.tglmutasi','stk.mutasidetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.mutasi.tglmutasi',Carbon::parse($req->tgl))
                     ->leftJoin('stk.mutasidetail', 'stk.mutasi.id', '=', 'stk.mutasidetail.mutasiid')
                     ->leftJoin('mstr.stock', 'stk.mutasidetail.stockid', '=', 'mstr.stock.id');
            if($req->namabarang){
                $npj->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $rpj->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $npb->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $rpb->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $gstrans->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $gslink->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $agkirim->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $agtrima->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                $mt->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
            }
            if($npj->count() > 0 || $rpj->count() > 0 || $npb->count() > 0 || $rpb->count() > 0 || $gstrans->count() > 0 || $gslink->count() > 0 || $agkirim->count() > 0 || $agtrima->count() > 0 || $mt->count() > 0){
                $status = 'GAGAL INSERT KARENA SUDAH ADA TRANSAKSI LAINNYA DI TGL. RENCANA';
                $tipe   = 1;
                $barang = collect([
                    'npj'     => $npj->get(),
                    'rpj'     => $rpj->get(),
                    'npb'     => $npb->get(),
                    'rpb'     => $rpb->get(),
                    'gstrans' => $gstrans->get(),
                    'gslink'  => $gslink->get(),
                    'agkirim' => $agkirim->get(),
                    'agtrima' => $agtrima->get(),
                    'mt'      => $mt->get(),
                ]);
            }else{
                $sohsama = StockOpnameHistory::select(['mstr.stock.id','mstr.stock.namabarang','mstr.stock.kodebarang','stk.stockopnamehistory.tglrencana','stk.stockopnamehistory.keteranganrencana'])->leftJoin('mstr.stock', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id')->where('tglrencana',Carbon::parse($req->tgl));
                if($req->namabarang){
                    $sohsama->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                }
                if($sohsama->count() > 0){
                    $status = 'GAGAL INSERT KARENA SUDAH ADA RENCANA STOP DITANGGAL YANG SAMA';
                    $tipe   = 2;
                    $sohsama->orderBy('mstr.stock.area1','asc');
                    $sohsama->orderBy('mstr.stock.area2','asc');
                    $sohsama->orderBy('mstr.stock.area3','asc');
                    $sohsama->orderBy('mstr.stock.namabarang','asc');
                    $barang = $sohsama->get();
                }else{
                    $status = 'BERHASIL INSERT';
                    if($req->namabarang){
                        $barang->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
                    }
                    if($req->kodebarang){
                        $barang->where('kodebarang','ilike','%'.$req->kodebarang.'%');
                    }
                    if($req->rak1){
                        $barang->where('area1','ilike','%'.$req->rak1.'%');
                    }
                    if($req->rak2){
                        $barang->where('area2','ilike','%'.$req->rak2.'%');
                    }
                    if($req->rak3){
                        $barang->where('area3','ilike','%'.$req->rak3.'%');
                    }
                    if($req->statusaktif < 2){
                        $barang->where('statusaktif',$req->statusaktif);
                    }
                    if($req->penanggungjawabrak){
                        $query = PenanggungJawabArea::find($req->penanggungjawabrak)->kodearea;
                        $barang->where(function ($q) use($query){
                            $q->orWhereRaw("left(area1, 1) = '".$query."'")
                              ->orWhereRaw("left(area2, 1) = '".$query."'")
                              ->orWhereRaw("left(area3, 1) = '".$query."'");
                        });
                    }
                    $barang->orderBy('mstr.stock.area1','asc');
                    $barang->orderBy('mstr.stock.area2','asc');
                    $barang->orderBy('mstr.stock.area3','asc');
                    $barang->orderBy('mstr.stock.namabarang','asc');
                    $barang = $barang->get();

                    foreach ($barang as $brg) {
                        $now         = Carbon::now()->format('Ymd');
                        $idsubcabang = $req->session()->get('subcabang');
                        $query       = DB::select("select qtygit,gudangsementara from report.rsp_stokperperiode('".$now."', '".$now."', ".$brg->id.", ".$idsubcabang.")");
                        $aggit       = $query[0]->qtygit;
                        $gs          = $query[0]->gudangsementara;
                        $stockakhir  = app('App\Http\Controllers\MasterController')->getQtyStockGudang($now,$brg->id,$idsubcabang);
                        StockOpnameHistory::create([
                            'recordownerid'         => $req->session()->get('subcabang'),
                            'stockid'               => $brg->id,
                            'tglstop'               => Carbon::parse($req->tgl),
                            'qtyawal'               => $stockakhir['total'],
                            'qtybaik'               => 0,
                            'qtyrusak'              => 0,
                            'qtygs'                 => $gs,
                            'qtyaggit'              => $aggit,
                            'qtystop'               => 0,
                            'keteranganrencana'     => $req->ket,
                            'keteranganhasilhitung' => '',
                            'syncflag'              => 0,
                            'createdby'             => strtoupper(auth()->user()->username),
                            'lastupdatedby'         => strtoupper(auth()->user()->username),
                            'tglrencana'            => Carbon::parse($req->tgl),
                        ]);
                    }
                }
            }
        }

        if($status) {
          $kodegudang = SubCabang::find($req->session()->get('subcabang'))->kodesubcabang;
          $file       = str_slug("Laporan Rencana STOP")."-".uniqid();
          Excel::create($file, function($excel) use ($barang,$data,$status,$tipe,$kodegudang) {
              $excel->setTitle('Laporan Rencana STOP');
              $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
              $excel->setmanager(strtoupper(auth()->user()->username));
              $excel->setsubject('Laporan Rencana STOP');
              $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
              $excel->setDescription('Laporan Rencana STOP');
              $excel->sheet('LaporanRencanaSTOP', function($sheet) use ($barang,$data,$status,$tipe,$kodegudang) {
                  $sheet->loadView('transaksi.stockopname.laporanrencana',array('barang'=>$barang,'req'=>$data,'status'=>$status,'tipe'=>$tipe,'gudang'=>$kodegudang));
              });
          })->store('xls', storage_path('excel/exports'));

          return response()->json([
            'success' => true,
            'url' => asset('storage/excel/exports').'/'.$file.'.xls',
          ]);
        }else{
          return response()->json([
            'success' => false,
            'url' => null,
          ]);
        }
    }

    public function rencanaStopPerBarang(Request $req)
    {
        $sohclosing = StockOpnameHistory::select(['mstr.stock.id','mstr.stock.namabarang','mstr.stock.kodebarang','stk.stockopnamehistory.tglrencana','stk.stockopnamehistory.keteranganrencana'])
                    ->leftJoin('mstr.stock', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id')
                    ->where('mstr.stock.id',$req->id)
                    ->where('stk.stockopnamehistory.tglclosing',null);
        if($sohclosing->count() > 0){
            return response()->json([
                'success' => false,
                'error'   => 'Tidak bisa tambahan record rencana STOP. Rencana STOP terakhir masih dalam proses hitung. Pastikan record sebelumnya sudah diclosing sebelum membuat recana STOP yang baru. Hubungi manager anda',
            ]);
        }else{
            $npj     = NotaPenjualan::select(['pj.notapenjualan.id','pj.notapenjualan.tglnota','pj.notapenjualandetail.stockid','mstr.stock.namabarang'])
                     ->where('pj.notapenjualan.tglnota',Carbon::parse($req->tgl))
                     ->where('pj.notapenjualandetail.stockid',$req->id)
                     ->leftJoin('pj.notapenjualandetail', 'pj.notapenjualan.id', '=', 'pj.notapenjualandetail.notapenjualanid');
            $rpj     = ReturPenjualan::select(['pj.returpenjualan.id','pj.returpenjualan.tglnotaretur','pj.returpenjualandetail.stockid','mstr.stock.namabarang'])
                     ->where('pj.returpenjualan.tglnotaretur',Carbon::parse($req->tgl))
                     ->where('pj.returpenjualandetail.stockid',$req->id)
                     ->leftJoin('pj.returpenjualandetail', 'pj.returpenjualan.id', '=', 'pj.returpenjualandetail.returpenjualanid');
            $npb     = NotaPembelian::select(['pb.notapembelian.id','pb.notapembelian.tglterima','pb.notapembeliandetail.stockid','mstr.stock.namabarang'])
                     ->where('pb.notapembelian.tglterima',Carbon::parse($req->tgl))
                     ->where('pb.notapembeliandetail.stockid',$req->id)
                     ->leftJoin('pb.notapembeliandetail', 'pb.notapembelian.id', '=', 'pb.notapembeliandetail.notapembelianid');
            $rpb     = ReturPembelian::select(['pb.returpembelian.id','pb.returpembelian.tglprb','pb.returpembeliandetail.stockid','mstr.stock.namabarang'])
                     ->where('pb.returpembelian.tglprb',Carbon::parse($req->tgl))
                     ->where('pb.returpembeliandetail.stockid',$req->id)
                     ->leftJoin('pb.returpembeliandetail', 'pb.returpembelian.id', '=', 'pb.returpembeliandetail.returpembelianid');
            $gstrans = GudangSementara::select(['stk.gudangsementara.id','stk.gudangsementara.tgltransaksi','stk.gudangsementaradetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.gudangsementara.tgltransaksi',Carbon::parse($req->tgl))
                     ->where('stk.gudangsementaradetail.stockid',$req->id)
                     ->leftJoin('stk.gudangsementaradetail', 'stk.gudangsementara.id', '=', 'stk.gudangsementaradetail.gudangsementaraid');
            $gslink  = GudangSementara::select(['stk.gudangsementara.id','stk.gudangsementara.tgllink','stk.gudangsementaradetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.gudangsementara.tgllink',Carbon::parse($req->tgl))
                     ->where('stk.gudangsementaradetail.stockid',$req->id)
                     ->leftJoin('stk.gudangsementaradetail', 'stk.gudangsementara.id', '=', 'stk.gudangsementaradetail.gudangsementaraid');
            $agkirim = AntarGudang::select(['stk.antargudang.id','stk.antargudang.tglkirim','stk.antargudangdetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.antargudang.tglkirim',Carbon::parse($req->tgl))
                     ->where('stk.antargudangdetail.stockid',$req->id)
                     ->leftJoin('stk.antargudangdetail', 'stk.antargudang.id', '=', 'stk.antargudangdetail.antargudangid');
            $agtrima = AntarGudang::select(['stk.antargudang.id','stk.antargudang.tglterima','stk.antargudangdetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.antargudang.tglterima',Carbon::parse($req->tgl))
                     ->where('stk.antargudangdetail.stockid',$req->id)
                     ->leftJoin('stk.antargudangdetail', 'stk.antargudang.id', '=', 'stk.antargudangdetail.antargudangid');
            $mt      = Mutasi::select(['stk.mutasi.id','stk.mutasi.tglmutasi','stk.mutasidetail.stockid','mstr.stock.namabarang'])
                     ->where('stk.mutasi.tglmutasi',Carbon::parse($req->tgl))
                     ->where('stk.mutasidetail.stockid',$req->id)
                     ->leftJoin('stk.mutasidetail', 'stk.mutasi.id', '=', 'stk.mutasidetail.mutasiid');
            if($npj->count() > 0 || $rpj->count() > 0 || $npb->count() > 0 || $rpb->count() > 0 || $gstrans->count() > 0 || $gslink->count() > 0 || $agkirim->count() > 0 || $agtrima->count() > 0 || $mt->count() > 0){
                return response()->json([
                    'success' => false,
                    'error'   => 'Tidak bisa buat rencana STOP. Barang xxx kode barang xxx sudah tercatat di transaksi xxx tglxxx dd-mm-yyyy',
                ]);
            }else{
                $sohsama = StockOpnameHistory::select(['mstr.stock.id','mstr.stock.namabarang','mstr.stock.kodebarang','stk.stockopnamehistory.tglrencana','stk.stockopnamehistory.keteranganrencana'])
                         ->leftJoin('mstr.stock', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id')
                         ->where('mstr.stock.id',$req->id)
                         ->where('stk.stockopnamehistory.tglrencana',Carbon::parse($req->tgl));
                if($sohsama->count() > 0){
                    return response()->json([
                        'success' => false,
                        'error'   => 'Tidak bisa tambah record Rencana STOP dengan tanggal yang sama. Hubungi manager anda.',
                    ]);
                }else{
                    $now         = Carbon::now()->format('Ymd');
                    $idsubcabang = $req->session()->get('subcabang');
                    $query       = DB::select("select qtygit,gudangsementara from report.rsp_stokperperiode('".$now."', '".$now."', ".$req->id.", ".$idsubcabang.")");
                    $aggit       = $query[0]->qtygit;
                    $gs          = $query[0]->gudangsementara;
                    $stockakhir  = app('App\Http\Controllers\MasterController')->getQtyStockGudang($now,$req->id,$idsubcabang);
                    $stkop = StockOpnameHistory::create([
                        'recordownerid'         => $req->session()->get('subcabang'),
                        'stockid'               => $req->id,
                        'tglstop'               => Carbon::parse($req->tgl),
                        'qtyawal'               => $stockakhir['total'],
                        'qtybaik'               => 0,
                        'qtyrusak'              => 0,
                        'qtygs'                 => $gs,
                        'qtyaggit'              => $aggit,
                        'qtystop'               => 0,
                        'keteranganrencana'     => $req->keteranganrencana,
                        'keteranganhasilhitung' => '',
                        'syncflag'              => 0,
                        'createdby'             => strtoupper(auth()->user()->username),
                        'lastupdatedby'         => strtoupper(auth()->user()->username),
                        'tglrencana'            => Carbon::parse($req->tgl),
                    ]);

                    return response()->json([
                        'success' => true,
                        'id'      => $stkop->id,
                    ]);
                }
            }
        }
    }

    public function cetakCfs(Request $req)
    {
        $data = $req->all();
        $barang = Barang::select(
          'stk.stockopnamehistory.stockid',
          'stk.stockopnamehistory.qtybaik',
          'stk.stockopnamehistory.qtyrusak',
          'mstr.stock.area1',
          'mstr.stock.area2',
          'mstr.stock.area3',
          'mstr.stock.namabarang'
        )->leftJoin('stk.stockopnamehistory', 'stk.stockopnamehistory.stockid', '=', 'mstr.stock.id');
        if($req->tipestop == 'rencana'){
            $barang->where('stk.stockopnamehistory.tglrencana',Carbon::parse($req->tgl));
        }else{
            $barang->where('stk.stockopnamehistory.tglclosing',Carbon::parse($req->tgl));
        }
        if($req->namabarang){
            $barang->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
        }
        if($req->kodebarang){
            $barang->where('mstr.stock.kodebarang','ilike','%'.$req->kodebarang.'%');
        }
        if($req->rak1){
            $barang->where('mstr.stock.area1','ilike','%'.$req->rak1.'%');
        }
        if($req->rak2){
            $barang->where('mstr.stock.area2','ilike','%'.$req->rak2.'%');
        }
        if($req->rak3){
            $barang->where('mstr.stock.area3','ilike','%'.$req->rak3.'%');
        }
        if($req->statusaktif < 2){
            $barang->where('mstr.stock.statusaktif',$req->statusaktif);
        }
        if($req->penanggungjawabrak){
            $query = PenanggungJawabArea::find($req->penanggungjawabrak)->kodearea;
            $barang->where(function ($q) use($query){
                $q->orWhereRaw("left(area1, 1) = '".$query."'")
                  ->orWhereRaw("left(area2, 1) = '".$query."'")
                  ->orWhereRaw("left(area3, 1) = '".$query."'");
            });
        }
        $barang->orderBy('mstr.stock.area1','asc');
        $barang->orderBy('mstr.stock.area2','asc');
        $barang->orderBy('mstr.stock.area3','asc');
        $barang->orderBy('mstr.stock.namabarang','asc');
        $barang = $barang->get();

        $config = [
            'mode'                 => '',
            'format'               => [162.56,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'Form STOP Harian/ Tahunan',
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $pdf = PDF::loadView('transaksi.stockopname.cfs',[
            'barang' => $barang,
            'tglrencana' => $req->tgl,
        ],[],$config);
        return $pdf->stream('CFS'.$req->tgl.'.pdf');
    }

    public function cetakDfs(Request $req)
    {
        $soh = StockOpnameHistory::find($req->id);
        if($soh) {
          $soh->print++;
          $soh->save();
        }
        $config = [
            'mode'                 => '',
            'format'               => [162.56,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'Form STOP Harian/ Tahunan',
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];
        $pdf = PDF::loadView('transaksi.stockopname.dfs',[
            'soh' => $soh,
            'tglrencana' => Carbon::now()->format('d-m-Y'),
        ],[],$config);
        return $pdf->stream('DFS'.$req->tgl.'.pdf');
    }

    public function analisaClosingStop(Request $req)
    {
        $data = $req->all();
        $barang = Barang::select('*')->leftJoin('stk.stockopnamehistory', 'mstr.stock.id', '=', 'stk.stockopnamehistory.stockid');
        if($req->tipestop == 'rencana'){
            $barang->where('stk.stockopnamehistory.tglrencana',$req->tgl);
        }else{
            $barang->where('stk.stockopnamehistory.tglclosing',$req->tgl);
        }
        if($req->namabarang){
            $barang->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
        }
        if($req->kodebarang){
            $barang->where('mstr.stock.kodebarang','ilike','%'.$req->kodebarang.'%');
        }
        if($req->rak1){
            $barang->where('mstr.stock.area1','ilike','%'.$req->rak1.'%');
        }
        if($req->rak2){
            $barang->where('mstr.stock.area2','ilike','%'.$req->rak2.'%');
        }
        if($req->rak3){
            $barang->where('mstr.stock.area3','ilike','%'.$req->rak3.'%');
        }
        if($req->statusaktif < 2){
            $barang->where('mstr.stock.statusaktif',$req->statusaktif);
        }
        if($req->penanggungjawabrak){
            $query = PenanggungJawabArea::find($req->penanggungjawabrak)->kodearea;
            $barang->where(function ($q) use($query){
                $q->orWhereRaw("left(area1, 1) = '".$query."'")
                  ->orWhereRaw("left(area2, 1) = '".$query."'")
                  ->orWhereRaw("left(area3, 1) = '".$query."'");
            });
        }
        $barang = $barang->get();

        Excel::create('Laporan Analisa STOP', function($excel) use ($barang,$data) {
            $excel->setTitle('Laporan Analisa STOP');
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject('Laporan Analisa STOP');
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription('Laporan Analisa STOP');
            $excel->sheet('LaporanAnalisaSTOP', function($sheet) use ($barang,$data) {
                $sheet->loadView('transaksi.stockopname.laporananalisaclosing',array('barang'=>$barang,'req'=>$data));
            });
        })->store('xls', storage_path('excel/exports'));

        return response()->json([
          'success' => true,
          'url' => asset('storage/excel/exports').'/Laporan Analisa STOP.xls',
        ]);
    }

    public function closeAllBarang(Request $req)
    {
        // $barang = Barang::select('*','stk.stockopnamehistory.id as stkid')->leftJoin('stk.stockopnamehistory', 'mstr.stock.id', '=', 'stk.stockopnamehistory.stockid');
        $barang = Barang::select('stk.stockopnamehistory.id as stkid')->leftJoin('stk.stockopnamehistory', 'mstr.stock.id', '=', 'stk.stockopnamehistory.stockid');
        // if($req->tgl){
        //   $barang->where('stk.stockopnamehistory.tglrencana',Carbon::parse($req->tgl));
        // }
        if($req->namabarang){
            $barang->whereRaw("replace(mstr.stock.namabarang,' ','') ilike '%".preg_replace('/\s+/', '', $req->namabarang)."%'");
        }
        if($req->kodebarang){
            $barang->where('mstr.stock.kodebarang','ilike','%'.$req->kodebarang.'%');
        }
        if($req->rak1){
            $barang->where('mstr.stock.area1','ilike','%'.$req->rak1.'%');
        }
        if($req->rak2){
            $barang->where('mstr.stock.area2','ilike','%'.$req->rak2.'%');
        }
        if($req->rak3){
            $barang->where('mstr.stock.area3','ilike','%'.$req->rak3.'%');
        }
        if($req->statusaktif < 2){
            $barang->where('mstr.stock.statusaktif',$req->statusaktif);
        }
        if($req->penanggungjawabrak){
            $query = PenanggungJawabArea::find($req->penanggungjawabrak)->kodearea;
            $barang->where(function ($q) use($query){
                $q->orWhereRaw("left(area1, 1) = '".$query."'")
                  ->orWhereRaw("left(area2, 1) = '".$query."'")
                  ->orWhereRaw("left(area3, 1) = '".$query."'");
            });
        }

        $now         = Carbon::now()->format('Ymd');
        $idsubcabang = $req->session()->get('subcabang');

        $barang = $barang->get();
        foreach ($barang as $key=>$brg) {
            $soh = StockOpnameHistory::find($brg->stkid);
            if(!$soh->tglclosing){
              $soh->tglclosing = Carbon::now();
              if(!$soh->karyawanidpenghitung){
                $query         = DB::select("select qtygit,gudangsementara from report.rsp_stokperperiode('".$now."', '".$now."', ".$soh->stockid.", ".$idsubcabang.")");
                $aggit         = $query[0]->qtygit;
                $gs            = $query[0]->gudangsementara;
                $qtystop       = $aggit+$gs;
                $soh->qtybaik  = 0;
                $soh->qtyrusak = 0;
                $soh->qtystop  = $qtystop;
              }
              $soh->save();
            }

        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteStop($id)
    {
        $soh = StockOpnameHistory::find($id);
        $soh->delete();

        return true;
    }

    public function cekKewenangan(Request $req)
    {
        $lastUserId = auth()->user()->id;
        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                if ($req->about == 'stopcab') {
                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif ($req->about == 'deletesoh') {
                    $this->deleteStop($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'error'   => 'Anda tidak memiliki kewenangan untuk melakukan aksi ini. Hubungi Manager Anda.',
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'error'   => 'Ada data yang salah pada username atau password Anda',
        ]);
    }
}
