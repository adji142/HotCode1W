<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutasi;
use App\Models\MutasiDetail;
use Carbon\Carbon;

class MutasiController extends Controller
{
	protected $original_column = array(
        0 => "stk.mutasi.tglmutasi",
        1 => "stk.mutasi.nomutasi",
        2 => "stk.mutasi.keterangan",
        3 => "stk.mutasi.tipemutasi",
        4 => "stk.mutasi.id",
    );

    public function index()
    {
    	return view('transaksi.mutasi.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('mutasi.index')) {
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
            0 => "tglmutasi",
	        1 => "nomutasi",
	        2 => "keterangan",
	        3 => "tipemutasi",
	        4 => "id",
        );
        for ($i=0; $i < 4; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        $mt = Mutasi::selectRaw(collect($columns)->implode(', '));
        $mt->where("stk.mutasi.recordownerid",$req->session()->get('subcabang'))->where("stk.mutasi.tglmutasi",'>=',Carbon::parse($req->tglmulai))->where("stk.mutasi.tglmutasi",'<=',Carbon::parse($req->tglselesai));
        $total_data = $mt->count();
        if($empty_filter < 5){
            for ($i=0; $i < 4; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if(($index > 0 && $index < 4)){
                        if($req->custom_search[$i]['filter'] == '='){
                            $mt->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $mt->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                        $mt->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $mt->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $mt->orderBy('stk.mutasi.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $mt->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $mt->skip(0)->take($req->length);
        }else{
            $mt->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($mt->get() as $key => $mutasi) {
			$data[$key] = $mutasi->toArray();
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
        if(!$req->user()->can('mutasi.index')) {
            return response()->json([
                'mtd' => [],
            ]);
        }

        // jika lolos, tampilkan data
		$mt       = Mutasi::find($req->id);
		$mtd      = $mt->details;
		$node_mtd = array();

		foreach ($mtd as $detail) {
            $temp_mtd = [
				'namabarang' => $detail->barang->namabarang,
				'kodebarang' => $detail->barang->kodebarang,
				'sat'        => $detail->barang->satuan,
				'qtymutasi'  => $detail->qtymutasi,
				'id'         => $detail->id,
            ];
            array_push($node_mtd, $temp_mtd);
        }

		return response()->json([
			'mtd' => $node_mtd,
		]);
    }
}
