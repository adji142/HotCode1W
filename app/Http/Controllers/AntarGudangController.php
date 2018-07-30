<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;
use Mail;
use App\Models\Karyawan;
use App\Models\SubCabang;
use App\Models\AntarGudang;
use App\Models\AntarGudangDetail;

class AntarGudangController extends Controller
{
	// protected $original_column = array(
 //        1 => "stk.antargudang.norqag",
 //        2 => "stk.antargudang.darisubcabangid",
 //        3 => "stk.antargudang.tglnotaag",
 //        4 => "stk.antargudang.karyawanidpengirim",
 //        5 => "stk.antargudang.kesubcabangid",
 //        6 => "stk.antargudang.tglterima",
 //        7 => "stk.antargudang.karyawanidpenerima",
 //    );

    protected $original_column = array(
        1 => "stk.antargudang.norqag",
        2 => "darisubcabang.kodesubcabang",
        3 => "stk.antargudang.createdon",
        4 => "stk.antargudang.tglnotaag",
        5 => "karyawanpengirim.namakaryawan",
        6 => "kesubcabang.kodesubcabang",
        7 => "stk.antargudang.tglterima",
        8 => "karyawanpenerima.namakaryawan",
    );

    public function index(Request $req)
    {
    	$checkers = Karyawan::where('recordownerid',$req->session()->get('subcabang'))->where('kodechecker','!=',null)->get();
    	return view('transaksi.antargudang.index',compact('checkers'));
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('antargudang.index')) {
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
            0 => "stk.antargudang.norqag",
	        1 => "darisubcabang.kodesubcabang as darisubcabangnama",
	        2 => "stk.antargudang.createdon",
	        3 => "stk.antargudang.tglnotaag",
	        4 => "karyawanpengirim.namakaryawan as namapengirim",
	        5 => "kesubcabang.kodesubcabang as kesubcabangnama",
            6 => "stk.antargudang.tglterima",
            7 => "karyawanpenerima.namakaryawan as namapenerima",
            8 => "stk.antargudang.id",
            9 => "stk.antargudang.syncflag",
            10 => "stk.antargudang.subcabangidpenerima",
            11 => "stk.antargudang.subcabangidpengirim",
        );
        for ($i=1; $i < 8; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        $ags = AntarGudang::selectRaw(collect($columns)->implode(', '));
        $ags->leftJoin('mstr.subcabang as darisubcabang','stk.antargudang.subcabangidpengirim','=','darisubcabang.id');
        $ags->leftJoin('mstr.subcabang as kesubcabang','stk.antargudang.subcabangidpenerima','=','kesubcabang.id');
        $ags->leftJoin('hr.karyawan as karyawanpengirim','stk.antargudang.karyawanidpengirim','=','karyawanpengirim.id');
        $ags->leftJoin('hr.karyawan as karyawanpenerima','stk.antargudang.karyawanidpenerima','=','karyawanpenerima.id');
        $ags->where("stk.antargudang.recordownerid",$req->session()->get('subcabang'))->where("stk.antargudang.createdon",'>=',Carbon::parse($req->tglmulai))->where("stk.antargudang.createdon",'<=',Carbon::parse($req->tglselesai)->endOfDay());
        $total_data = $ags->count();
        if($empty_filter < 7){
            for ($i=1; $i < 8; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index > 1){
                        if($req->custom_search[$i]['filter'] == '='){
                            $ags->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $ags->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                    	$ags->where($this->original_column_header[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $ags->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $ags->orderBy('stk.antargudang.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $ags->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $ags->skip(0)->take($req->length);
        }else{
            $ags->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($ags->get() as $key => $ag) {
			$ag->tglnotaag  = $ag->tglnotaag;
			$ag->tglterima = $ag->tglterima;
			$data[$key]    = $ag->toArray();
			if($ag->syncflag == 0){
				if($req->session()->get('subcabang') == $ag->subcabangidpenerima){
					$data[$key]['editag'] = 'editag';
				}else{
					$data[$key]['editag'] = 'Hanya bisa diupdate di Gudang Penerima';
				}
				if($ag->details->count() > 0){
					$data[$key]['sync'] = 'confirmsyncpengirim';
				}else{
					$data[$key]['sync'] = 'Tidak ada detail, isi detail lebih dahulu';
				}
				$data[$key]['deleteag']  = 'delete';
				$data[$key]['cetakpil']  = 'Cetak PIL hanya bisa dilakukan di gudang pengirim dan belum dikirimkan ke Gudang Penerima';
				$data[$key]['cetaknota'] = 'Cetak Nota hanya bisa dilakukan di subcabang pengirim sebelum di synch kembali ke subcabang penerima';
				$data[$key]['tambahagd'] = 'tambah';
			}elseif($ag->syncflag == 1){
				if($req->session()->get('subcabang') == $ag->subcabangidpengirim){
					$data[$key]['editag']    = 'editpengirim';
					$data[$key]['cetakpil']  = 'cetakpil';
					$data[$key]['cetaknota'] = 'cetaknota';
					if($ag->tglprintnotaag){
						$data[$key]['sync']  = 'confirmsyncpenerima';
					}else{
						$data[$key]['sync']  = 'Belum ada dokumen yang tercetak, mohon cetak dokumen terlebih dahulu';
					}
				}else{
					$data[$key]['editag']    = 'Hanya bisa diupdate di Subcabang Pengirim';
					$data[$key]['cetakpil']  = 'Cetak PIL hanya bisa dilakukan di gudang pengirim dan belum dikirimkan ke Gudang Penerima';
					$data[$key]['cetaknota'] = 'Cetak Nota hanya bisa dilakukan di subcabang pengirim sebelum di synch kembali ke subcabang penerima';
					$data[$key]['sync']      = 'AG masih diproses oleh gudang *subcabangpengirim, tidak perlu synch ulang.';
				}
				$data[$key]['deleteag']  = 'Tidak bisa DELETE, AG sudah diproses oleh Gudang pengirim.';
				$data[$key]['tambahagd'] = 'Tidak bisa tambah data, AG sudah terproses';
			}elseif($ag->syncflag == 2){
				if($req->session()->get('subcabang') == $ag->subcabangidpenerima){
					if($ag->tglterima){
						$data[$key]['sync'] = 'confirmsyncpengirim2';
					}else{
						$data[$key]['sync'] = 'AG belum diterima, tidak bisa synch';
					}
					$data[$key]['editag'] = 'editpenerima';
				}else{
					$data[$key]['editag'] = 'Hanya bisa diupdate di Gudang Penerima';
					$data[$key]['sync']   = 'AG sudah synch ke gudang *subcabangpenerima, tidak perlu synch ulang';
				}
				$data[$key]['deleteag']  = 'Tidak bisa DELETE, AG sudah diproses oleh Gudang pengirim.';
				$data[$key]['cetakpil']  = 'Cetak PIL hanya bisa dilakukan di gudang pengirim dan belum dikirimkan ke Gudang Penerima';
				$data[$key]['cetaknota'] = 'Cetak Nota hanya bisa dilakukan di subcabang pengirim sebelum di synch kembali ke subcabang penerima';
				$data[$key]['tambahagd'] = 'Tidak bisa tambah data, AG sudah terproses';
			}else{
				$data[$key]['editag']    = 'AG sudah tidak dapat di update lagi';
				$data[$key]['deleteag']  = 'Tidak bisa DELETE, AG sudah diproses oleh Gudang pengirim.';
				$data[$key]['cetakpil']  = 'Cetak PIL hanya bisa dilakukan di gudang pengirim dan belum dikirimkan ke Gudang Penerima';
				$data[$key]['cetaknota'] = 'Cetak Nota hanya bisa dilakukan di subcabang pengirim sebelum di synch kembali ke subcabang penerima';
				$data[$key]['sync']      = 'AG terima telah dikonfirmasi ke *subcabangpengirim, tidak perlu synch ulang';
				$data[$key]['tambahagd'] = 'Tidak bisa tambah data, AG sudah terproses';
			}
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
        if(!$req->user()->can('antargudang.index')) {
            return response()->json([
                'agd' => [],
            ]);
        }

        // jika lolos, tampilkan data
		$ag       = AntarGudang::find($req->id);
		$agd      = $ag->details;
		$node_agd = array();

		if($ag->syncflag == 0){
			$deleteagd = 'delete';
			$editagd   = 'edit';
		}elseif($ag->syncflag == 1){
			if($req->session()->get('subcabang') == $ag->subcabangidpengirim){
				if($ag->karyawanidpengirim){
					$editagd = 'editpengirim'; 
				}else{
					$editagd = 'Karyawan pengirim belum terisi, mohon isi terlebih dahulu';
				}
			}else{
				$editagd = 'AG sudah diproses, tidak bisa update';
			}
			$deleteagd = 'Tidak bisa hapus data, AG sudah diproses';
		}elseif($ag->syncflag == 2){
			if($req->session()->get('subcabang') == $ag->subcabangidpenerima){
				$editagd = 'Subcabang Penerima hanya updatetanggal terima di header';
			}else{
				$editagd = 'AG sudah di synch hanya bisa di update dari subcabang penerima';
			}
			$deleteagd = 'Tidak bisa hapus data, AG sudah diproses';
		}else{
			$editagd   = 'AGD sudah diproses, tidak bisa update';
			$deleteagd = 'Tidak bisa hapus data, AG sudah diproses';
		}

        foreach ($agd as $detail) {
            $temp_agd = [
				'namabarang' => $detail->barang->namabarang,
				'kodebarang' => $detail->barang->kodebarang,
				'qtynotaag'   => $detail->qtynotaag,
				'qtyterima'  => $detail->qtyterima,
				'id'         => $detail->id,
				'edit'       => $editagd,
				'delete'     => $deleteagd,
            ];
            array_push($node_agd, $temp_agd);
        }

        return response()->json([
            'agd' => $node_agd,
        ]);
    }

    public function getAg(Request $req)
    {
    	$ag = AntarGudang::find($req->id);

    	return response()->json([
			'id'                         => $ag->id,
			'norqag'                       => $ag->norqag,
			'darisubcabangid'            => $ag->darisubcabang->kodesubcabang,
			'tglnotaag'                   => $ag->tglnotaag,
			'karyawanidpengirim'         => $ag->karyawanpengirim,
			'kesubcabangid'              => $ag->kesubcabang->kodesubcabang,
			'tglterima'                  => $ag->tglterima,
			'karyawanidpenerima'         => $ag->karyawanpenerima,
			'karyawanidchecker1pengirim' => $ag->checker1pengirim,
			'karyawanidchecker2pengirim' => $ag->checker2pengirim,
			'karyawanidchecker1penerima' => $ag->checker1penerima,
			'karyawanidchecker2penerima' => $ag->checker2penerima,
			'tglprintnotaag'                 => $ag->tglprintnotaag,
			'catatan'                    => $ag->catatan,
			'syncflag'                   => $ag->syncflagmutate,
			'createdby'                  => $ag->createdby,
			'createdon'                  => $ag->createdon,
			'lastupdatedby'              => $ag->lastupdatedby,
			'lastupdatedon'              => $ag->lastupdatedon,
			'idpengirim'                 => $ag->karyawanidpengirim,
			'idpenerima'                 => $ag->karyawanidpenerima,
			'idchecker1pengirim'         => $ag->karyawanidchecker1pengirim,
			'idchecker2pengirim'         => $ag->karyawanidchecker2pengirim,
			'idchecker1penerima'         => $ag->karyawanidchecker1penerima,
			'idchecker2penerima'         => $ag->karyawanidchecker2penerima,
		]);
    }

    public function getAgD(Request $req)
    {
    	$agd = AntarGudangDetail::find($req->id);

    	return response()->json([
			'id'            => $agd->id,
			'stockid'       => $agd->stockid,
			'namabarang'    => $agd->barang->namabarang,
			'kodebarang'    => $agd->barang->kodebarang,
			'qtyrq'         => $agd->qtyrq,
			'qtynotaag'      => $agd->qtynotaag,
			'qtyterima'     => $agd->qtyterima,
			'catatan'       => $agd->catatan,
			'createdby'     => $agd->createdby,
			'createdon'     => $agd->createdon,
			'lastupdatedby' => $agd->lastupdatedby,
			'lastupdatedon' => $agd->lastupdatedon,
		]);
    }

    public function getSubCabang(Request $req){
    	$login = Subcabang::find($req->session()->get('subcabang'));
		$subcabang = SubCabang::select('id','kodesubcabang','namasubcabang')
				   ->where('kodesubcabang', '!=', $login->kodesubcabang)
				   ->where(function ($query) use ($req) {
				   		$query->whereRaw("left(kodesubcabang,2) ilike '%".$req->katakunci."%'")
				   			  ->orWhere('namasubcabang', 'ilike', '%'.$req->katakunci.'%');
				   })
				   ->orderBy('kodesubcabang','asc')
				   ->get();
		return response()->json([
			'subcabang' => $subcabang,
		]);
    }

    public function getNextnorqag($recordownerid){
        $next_no = '';
        $max_order = AntarGudang::select('id','norqag')->where('id', AntarGudang::where('recordownerid', $recordownerid)->max('id'))->first();
        if ($max_order == null) {
            $next_no = sprintf("%010d", 1);
        }elseif (strlen($max_order->norqag)<10) {
            $next_no = sprintf("%010d", 1);
        }elseif ($max_order->norqag == '9999999999') {
            $next_no = sprintf("%010d", 1);
        }else {
            $next_no = sprintf("%010d", ltrim($max_order->norqag,'0')+1);
        }
        return $next_no;
    }

    public function syncAntarGudang(Request $req)
    {
		$ag       = AntarGudang::find($req->id);
		$syncflag = $ag->syncflag;
		if($syncflag == 0){
			$agd = $ag->details;

	    	$duplicateAg = AntarGudang::create([
				'recordownerid'       => $ag->subcabangidpengirim,
				'norqag'                => $ag->norqag,
				'subcabangidpengirim' => $ag->subcabangidpengirim,
				'subcabangidpenerima' => $ag->subcabangidpenerima,
				'catatan'             => $ag->catatan,
				'syncflag'            => 1,
				'coupleid'            => $ag->id,
				'createdby'           => strtoupper(auth()->user()->username),
				'lastupdatedby'       => strtoupper(auth()->user()->username),
			]);
			$ag->syncflag = 1;
			$ag->coupleid = $duplicateAg->id;
			$ag->save();

			foreach ($agd as $detail) {
				$duplicateAgd = AntarGudangDetail::create([
					'antargudangid' => $duplicateAg->id,
					'stockid'       => $detail->stockid,
					'qtyrq'         => $detail->qtyrq,
					'catatan'       => strtoupper($detail->catatan),
					'createdby'     => strtoupper(auth()->user()->username),
					'lastupdatedby' => strtoupper(auth()->user()->username),
				]);
			}

			//email
			$email_title   = 'AG REQUEST '.$ag->kesubcabang->kodesubcabang.' KE '.$ag->darisubcabang->kodesubcabang.' '.$ag->norqag;
			$email_receipt = $req->email;
			Mail::send('transaksi.antargudang.email',[
				'ag'          => $duplicateAg,
				'user'        => strtoupper(auth()->user()->username),
			], function ($message) use ($email_title, $email_receipt)
            {
            	$message->from('sistem@sas.com', 'Sistem SAS');
            	$message->to($email_receipt);
            	$message->subject($email_title);
            });
		}elseif($syncflag == 1) {
			$ag->syncflag = 2;
			$ag->save();

            $duplicateAg                                = AntarGudang::find($ag->coupleid);
            $duplicateAg->tglnotaag                     = $ag->tglnotaag;
            // $duplicateAg->karyawanidpengirim         = $this->getSyncKaryawan($duplicateAg->recordownerid,$ag->karyawanidpengirim);
            // $duplicateAg->karyawanidchecker1pengirim = $this->getSyncKaryawan($duplicateAg->recordownerid,$ag->karyawanidchecker1pengirim);
            // $duplicateAg->karyawanidchecker2pengirim = $this->getSyncKaryawan($duplicateAg->recordownerid,$ag->karyawanidchecker2pengirim);
            $duplicateAg->karyawanidpengirim            = $ag->karyawanidpengirim;
            $duplicateAg->karyawanidchecker1pengirim    = $ag->karyawanidchecker1pengirim;
            $duplicateAg->karyawanidchecker2pengirim    = $ag->karyawanidchecker2pengirim;
            $duplicateAg->tglprintnotaag                = $ag->tglprintnotaag;
            $duplicateAg->syncflag                      = 2;
			$duplicateAg->save();

			$duplicateAgd = $duplicateAg->details;
			foreach ($duplicateAgd as $key => $detail) {
				$detail->qtynotaag = $ag->details[$key]->qtynotaag;
				$detail->save();
			}
		}elseif($syncflag == 2) {
			$ag->syncflag = 3;
			$ag->save();

			$duplicateAg                             = AntarGudang::find($ag->coupleid);
			$duplicateAg->tglterima                  = $ag->tglterima;
			$duplicateAg->karyawanidpenerima         = $ag->karyawanidpenerima;
			$duplicateAg->karyawanidchecker1penerima = $ag->karyawanidchecker1penerima;
			$duplicateAg->karyawanidchecker2penerima = $ag->karyawanidchecker2penerima;
			$duplicateAg->syncflag                   = 3;
			$duplicateAg->save();

			$duplicateAgd = $duplicateAg->details;
			foreach ($duplicateAgd as $key => $detail) {
				$detail->qtyterima = $ag->details[$key]->qtyterima;
				$detail->save();
			}
		}

		return response()->json([
			'success' => true,
		]);
    }

    public function createAntarGudang(Request $req)
    {
    	$recordownerid = $req->session()->get('subcabang');
    	$ag = AntarGudang::create([
			'recordownerid'       => $recordownerid,
			'norqag'                => $this->getNextnorqag($recordownerid),
			'subcabangidpengirim' => $req->darigudang,
			'subcabangidpenerima' => $recordownerid,
			'syncflag'            => 0,
			'createdby'           => strtoupper(auth()->user()->username),
			'lastupdatedby'       => strtoupper(auth()->user()->username),
		]);

		return response()->json([
			'success' => true,
		]);
    }

    public function createAntarGudangDetail(Request $req)
    {
    	$agd = AntarGudangDetail::create([
			'antargudangid' => $req->agId,
			'stockid'       => $req->stockId,
			'qtyrq'         => $req->qtyrq,
			'catatan'       => strtoupper($req->catatan),
			'createdby'     => strtoupper(auth()->user()->username),
			'lastupdatedby' => strtoupper(auth()->user()->username),
		]);

		return response()->json([
			'success' => true,
		]);
    }

    public function updateAntarGudang(Request $req)
    {
		$ag       = AntarGudang::find($req->agId);
		$syncflag = $ag->syncflag;
		if($syncflag == 0){
			$ag->catatan = strtoupper($req->catatan);
		}elseif($syncflag == 1){
			if($req->pengirimId && $req->checkerpengirim1 && $req->checkerpengirim2){
				$ag->tglnotaag                   = $req->tglnotaag;
				$ag->karyawanidpengirim         = $req->pengirimId;
				$ag->karyawanidchecker1pengirim = $req->checkerpengirim1;
				$ag->karyawanidchecker2pengirim = $req->checkerpengirim2;
			}else{
				return response()->json([
					'success' => false,
					'error' => 'Pengirim dan checker pengirim harus diisi',
				]);
			}
		}elseif($syncflag == 2){
			if($req->penerimaId && $req->checkerpenerima1 && $req->checkerpenerima2){
				$ag->tglterima                  = $req->tglterima;
				$ag->karyawanidpenerima         = $req->penerimaId;
				$ag->karyawanidchecker1penerima = $req->checkerpenerima1;
				$ag->karyawanidchecker2penerima = $req->checkerpenerima2;

				foreach ($ag->details as $detail) {
					$detail->qtyterima = $detail->qtynotaag;
					$detail->save();
				}
			}else{
				return response()->json([
					'success' => false,
					'error' => 'Penerima dan checker penerima harus diisi',
				]);
			}
		}
		$ag->save();

		return response()->json([
			'success' => true,
		]);
    }

    public function updateAntarGudangDetail(Request $req)
    {
		$agd      = AntarGudangDetail::find($req->agdId);
		$syncflag = $agd->ag->syncflag;
		if($syncflag == 0){
			$agd->stockid  = $req->stockId;
			$agd->qtyrq    = $req->qtyrq;
			$agd->catatan  = $req->catatan;
		}elseif($syncflag == 1){
			//cek getstockrekap
			$now           = Carbon::now()->format('Ymd');
			$query         = DB::select("select stokgudang from mstr.getrspstokperperiode('".$now."', '".$now."', ".$agd->stockid.", ".$agd->ag->recordownerid.")");
			$stokgudang    = $query[0]->stokgudang;
			if($req->qtynotaag > $stokgudang){
				return response()->json([
					'success' => false,
					'error'   => 'Stok gudang = '.$stokgudang.', Tidak boleh kirim barang melebihi stock gudang',
				]);
			}else{
				$agd->qtynotaag = $req->qtynotaag;
			}
		}
    	$agd->save();

    	return response()->json([
    		'success' => true,
		]);
    }

    public function cetakPil(Request $req)
    {
    	$ag = AntarGudang::find($req->id);
        $config = [
            'mode'                 => '',
            'format'               => [139.7,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 25,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'PiL AG',
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];
        $pdf = PDF::loadView('transaksi.antargudang.pil',[
			'ag'          => $ag,
			'nilai_cetak' => 2,
        ],[],$config);
        return $pdf->stream('AG-PIL-'.$ag->id.'.pdf');
    }

    public function cetakNota(Request $req)
    {
		$ag             = AntarGudang::find($req->id);
		$ag->tglprintnotaag = Carbon::now();
    	$ag->save();
        $config = [
            'mode'                 => '',
            'format'               => [139.7,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 25,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'Nota AG',
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];
        $pdf = PDF::loadView('transaksi.antargudang.nota',[
			'ag'          => $ag,
			'nilai_cetak' => 2,
        ],[],$config);
        return $pdf->stream('AG-NOTA-'.$ag->id.'.pdf');
    }

    public function deleteAntarGudang(Request $req)
    {
    	$ag = AntarGudang::find($req->id);
    	foreach ($ag->details as $agd) {
    		$agd->delete();
    	}
    	$ag->delete();

    	return response()->json([
    		'success' => true,
		]);
    }

    public function deleteAntarGudangDetail(Request $req)
    {
    	$agd = AntarGudangDetail::find($req->id);
    	$agd->delete();

    	return response()->json([
    		'success' => true,
		]);
    }

    public function getSyncKaryawan($recordownerid,$karyawanid)
    {
    	$duplicateKaryawan = Karyawan::find($karyawanid);
    	$karyawan = Karyawan::where('recordownerid',$recordownerid)->where('namakaryawan',$duplicateKaryawan->namakaryawan)->where('nikhrd',$duplicateKaryawan->nikhrd)->value('id');
    	return $karyawan;
    }
}
