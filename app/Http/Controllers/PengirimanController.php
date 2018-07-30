<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArmadaKirim;
use App\Models\Karyawan;
use App\Models\Pengiriman;
use App\Models\PengirimanDetail;
use App\Models\SuratJalan;

use Carbon\Carbon;
use DB;
use PDF;

class PengirimanController extends Controller
{
	protected $original_column = array(
        1 => "pj.pengiriman.tglkirim",
        2 => "pj.pengiriman.nokirim",
        3 => "hr.karyawan.namakaryawan",
        4 => "pj.pengiriman.tglkembali",
        5 => "mstr.armadakirim.namakendaraan",
    );

    public function index()
    {
    	return view('transaksi.pengiriman.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('pengiriman.index')) {
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
            0 => "pj.pengiriman.tglkirim",
	        1 => "pj.pengiriman.nokirim",
	        2 => "hr.karyawan.namakaryawan as sopir",
	        3 => "pj.pengiriman.tglkembali",
	        4 => "mstr.armadakirim.namakendaraan as armadakirim",
            5 => "pj.pengiriman.id",
            6 => "pj.pengiriman.armadakirimid",
        );
        for ($i=1; $i < 6; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }

        $pengiriman = Pengiriman::selectRaw(collect($columns)->implode(', '));
        $pengiriman->leftJoin('mstr.armadakirim', 'pj.pengiriman.armadakirimid', '=', 'mstr.armadakirim.id');
        $pengiriman->leftJoin('hr.karyawan', 'pj.pengiriman.karyawanidsopir', '=', 'hr.karyawan.id');
        $pengiriman->where("pj.pengiriman.recordownerid",$req->session()->get('subcabang'));
        $pengiriman->where("pj.pengiriman.tglkirim",'>=',Carbon::parse($req->tglmulai));
        $pengiriman->where("pj.pengiriman.tglkirim",'<=',Carbon::parse($req->tglselesai));
        $total_data = $pengiriman->count();
        if($empty_filter < 5){
            for ($i=1; $i < 6; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if(($index > 1 && $index < 4) || $index == 5){
                        if($req->custom_search[$i]['filter'] == '='){
                            $pengiriman->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $pengiriman->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                        $pengiriman->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $pengiriman->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $pengiriman->orderBy('pj.pengiriman.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $pengiriman->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $pengiriman->skip(0)->take($req->length);
        }else{
            $pengiriman->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($pengiriman->get() as $key => $kirim) {
			$kirim->tglkirim   = $kirim->tglkirim;
			$kirim->tglkembali = $kirim->tglkembali;
			$data[$key]        = $kirim->toArray();
            $data[$key]['DT_RowId'] = 'gv1_'.$kirim->id;
			if(Carbon::parse($kirim->tglkirim)->format('d-m-Y') == Carbon::now()->format('d-m-Y')){
				if($kirim->print == 0){
					if($kirim->tglkembali){
						$data[$key]['tambahpd'] = 'Tidak bisa tambah record Surat Jalan. Tanggal kembali sudah terisi. Hubungi Manager Anda.';
					}else{
						$data[$key]['tambahpd'] = 'tambah';
					}
				}else{
					$data[$key]['tambahpd'] = 'Tidak bisa tambah record Surat Jalan. Daftar kiriman sudah dicetak. Hubungi Manager Anda.';
				}
			}else{
				$data[$key]['tambahpd'] = 'Tidak bisa tambah record Surat Jalan. Tanggal server tidak sama dengan tanggal kirim. Hubungi Manager Anda.';
			}
			if($kirim->tglkembali){
				$armadakirimsama = Pengiriman::where('armadakirimid',$kirim->armadakirimid)->where('tglkirim','>',Carbon::parse($kirim->tglkirim))->get();
				if(!$armadakirimsama->isEmpty()){
					$data[$key]['updatep'] = 'Tidak bisa update kiriman. Armada kirim sudah berangkat tanggal '.$armadakirimsama->first()->tglkirim.' dengan nomor kirim '.$armadakirimsama->first()->nokirim.'. Hubungi manager anda.';
				}else{
					$data[$key]['updatep'] = 'update';
				}
			}else{
				$data[$key]['updatep'] = 'update';
			}
			if(!$kirim->details->isEmpty()){
				$data[$key]['hapusp'] = 'Tidak bisa hapus record. Sudah ada record di Pengiriman detail. Hapus detail terlebih dahulu.';
			}else{
				$data[$key]['hapusp'] = 'auth';
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
        if(!$req->user()->can('pengiriman.index')) {
            return response()->json([
                'pd' => [],
            ]);
        }

        // jika lolos, tampilkan data
		$p       = Pengiriman::find($req->id);
		$pd      = $p->details;
		$node_pd = array();

		if(Carbon::parse($p->tglkirim) == Carbon::now()){
			if($p->print == 0){
				if($p->tglkembali){
					$hapuspd = 'Tidak bisa hapus record Surat Jalan. Tanggal kembali sudah terisi. Hubungi Manager Anda.';
				}else{
					$hapuspd = 'auth';
				}
			}else{
				$hapuspd = 'Tidak bisa hapus record Surat Jalan. Daftar kiriman sudah dicetak. Hubungi Manager Anda.';
			}
		}else{
			$hapuspd = 'Tidak bisa hapus record Surat Jalan. Tanggal server tidak sama dengan tanggal kirim. Hubungi Manager Anda.';
		}

        foreach ($pd as $detail) {
            $temp_pd = [
                'DT_RowId'  => 'gv2_'.$detail->id,
				'nosj'      => $detail->sj->nosj,
				'tglsj'     => $detail->sj->tglsj,
				'toko'      => $detail->sj->toko->namatoko,
				'wilid'     => $detail->sj->toko->customwilayah,
				'totalkoli' => $detail->sj->totalkoli(),
				'id'        => $detail->id,
				'hapuspd'   => $hapuspd,
            ];
            array_push($node_pd, $temp_pd);
        }

        return response()->json([
            'pd' => $node_pd,
        ]);
    }

    public function getPengiriman(Request $req)
    {
    	$p = Pengiriman::find($req->id);

    	return response()->json([
			'tglkirim'      => $p->tglkirim,
			'nokirim'       => $p->nokirim,
			'tglkembali'    => $p->tglkembali,
			'sopir'         => $p->sopir->namakaryawan,
			'kernet'        => $p->kernet->namakaryawan,
			'armadakirim'   => $p->armadakirim->namakendaraan,
			'kmberangkat'   => $p->kmberangkat,
			'kmkembali'     => $p->kmkembali,
			'catatan'       => $p->catatan,
			'print'         => $p->print,
			'tglprint'      => $p->tglprint,
			'lastupdatedby' => $p->lastupdatedby,
			'lastupdatedon' => $p->lastupdatedon,
    	]);
    }

    public function getPengirimanDetail(Request $req)
    {
    	$pd = PengirimanDetail::find($req->id);

    	return response()->json([
			'nosj'              => $pd->sj->nosj,
			'tglsj'             => $pd->sj->tglsj,
			'toko'              => $pd->sj->toko->namatoko,
			'alamat'            => $pd->sj->toko->alamat,
			'kecamatan'         => $pd->sj->toko->kecamatan,
			'kota'              => $pd->sj->toko->kota,
			'wilid'             => $pd->sj->toko->customwilayah,
			'idtoko'            => $pd->sj->toko->id,
			'totalkoli'         => $pd->sj->totalkoli(),
			'keteranganpending' => $pd->keteranganpending,
			'lastupdatedby'     => $pd->lastupdatedby,
			'lastupdatedon'     => $pd->lastupdatedon,
    	]);
    }

    public function createPengiriman(Request $req)
    {
    	$armadabelumkembali = Pengiriman::where('armadakirimid',$req->armada)->where('tglkirim','<',Carbon::now())->where('tglkembali',null)->get();

    	if(!$armadabelumkembali->isEmpty()){
    		$pengiriman = $armadabelumkembali->first();
    		return response()->json([
	            'success' => false,
	            'error' => 'Tidak bisa simpan pengiriman. Armada kirim belum kembali pada tanggal kirim '.$pengiriman->tglkirim.' nomor kirim '.$pengiriman->nokirim.'. Hubungi manager anda',
	        ]);
    	}else{
	    	$p = Pengiriman::create([
				'recordownerid'    => $req->session()->get('subcabang'),
				'tglkirim'         => Carbon::now(),
				'nokirim'          => $this->getNextKirimNo($req->session()->get('subcabang')),
				'karyawanidsopir'  => $req->sopir,
				'karyawanidkernet' => $req->kernet,
                'armadakirimid'    => $req->armada,
				'kmberangkat'      => $req->kmberangkat,
				'catatan'          => strtoupper($req->catatan),
				'createdby'        => strtoupper(auth()->user()->username),
				'lastupdatedby'    => strtoupper(auth()->user()->username),
	        ]);

	    	return response()->json([
	            'success' => true,
	        ]);
    	}
    }

    public function createPengirimanDetail(Request $req)
    {
        $pd = PengirimanDetail::create([
            'pengirimanid'  => $req->pid,
            'suratjalanid'  => $req->sjid,
            'createdby'     => strtoupper(auth()->user()->username),
            'lastupdatedby' => strtoupper(auth()->user()->username),
        ]);

        $sj = SuratJalan::find($req->sjid);
        $sj->pengirimanid = $req->pid;
        $sj->save();

        return response()->json([
            'success' => true,
            'pid'     => $req->pid,
        ]);
    }

    public function updatePengiriman(Request $req)
    {
        $p = Pengiriman::find($req->id);
        $p->tglkembali = Carbon::parse($req->tglkembali);
        $p->kmkembali  = $req->kmkembali;
        $p->catatan    = strtoupper($req->catatan);
        $p->save();

        $armada           = ArmadaKirim::find($p->armadakirimid);
        $armada->kmtempuh = $p->kmkembali;
        $armada->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function deletePengiriman($pId)
    {
        $p = Pengiriman::find($pId);
        $p->delete();

        return true;
    }

    public function deletePengirimanDetail($pdId)
    {
        $pd = PengirimanDetail::find($pdId);
        $pd->delete();

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
                if ($req->about == 'deletep') {
                    $this->deletePengiriman($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }elseif($req->about == 'deletepd'){
                    $this->deletePengirimanDetail($req->id);

                    return response()->json([
                        'success' => true,
                        'tipe'    => $req->about,
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'error' => 'Anda tidak memiliki kewenangan untuk melakukan aksi ini. Hubungi Manager Anda.',
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'error' => 'Ada data yang salah pada username atau password Anda',
            ]);
        }
    }

    public function cetak(Request $req)
    {
        $config = [
            'mode'                 => '',
            'format'               => [215.9,325.12],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 30,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'P',
            'title'                => 'DAFTAR KIRIMAN',
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $p   = Pengiriman::find($req->id);
        if(!$p->tglprint){
          $p->tglprint = Carbon::now();
        }
        $p->print = ($p->print)+1;
        $p->save();

        $pdf = PDF::loadView('transaksi.pengiriman.pdf',[
            'p' => $p,
        ],[],$config);
        return $pdf->stream('P-'.$p->nokirim.'.pdf');
    }

    public function getNoKirim(Request $req){
        return response()->json([
            'nokirim' => $this->getNextKirimNo($req->session()->get('subcabang')),
        ]);
    }

    public function getNextKirimNo($recordownerid){
        $next_no = '';
        $max_order = Pengiriman::select('id','nokirim')->where('id', Pengiriman::where('recordownerid', $recordownerid)->max('id'))->first();
        if ($max_order == null) {
            $next_no = sprintf("%010d", 1);
        }elseif (strlen($max_order->nokirim)<10) {
            $next_no = sprintf("%010d", 1);
        }elseif ($max_order->nokirim == '9999999999') {
            $next_no = sprintf("%010d", 1);
        }else {
            $next_no = sprintf("%010d", ltrim($max_order->nokirim,'0')+1);
        }
        return $next_no;
    }

}
