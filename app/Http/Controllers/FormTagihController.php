<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;

use App\Models\KartuPiutang;

class FormTagihController extends Controller
{
    public function index(Request $req)
    {
        return view('transaksi.ftagih.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('ftagih.index')) {
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
        $tglmulai   = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();

        $filter_count = 0;
        $columns = array(
            "ptg.kartupiutang.tgljt",
            "ptg.kartupiutang.tipetrans",
            "ptg.kartupiutang.temponota",
            "pj.notapenjualan.nonota",
            "hr.karyawan.kodesales",
            "mstr.toko.namatoko",
            "mstr.toko.customwilayah",
            "mstr.toko.alamat",
            "mstr.toko.kota",
            "ptg.kartupiutang.nomnota",
        );
        $filter_columns = $columns;

        $kartupiutang = KartuPiutang::select($columns);
        $kartupiutang->join('pj.notapenjualan', 'ptg.kartupiutang.notaid', '=', 'pj.notapenjualan.id');
        $kartupiutang->join('hr.karyawan', 'ptg.kartupiutang.karyawanidsalesman', '=', 'hr.karyawan.id');
        $kartupiutang->join('mstr.toko', 'ptg.kartupiutang.tokoid', '=', 'mstr.toko.id');
        $kartupiutang->where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $kartupiutang->whereRaw("DATE(ptg.kartupiutang.tgljt) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
        if($req->tokoid) {
            $kartupiutang->where("ptg.kartupiutang.tokoid",$req->tokoid);
        }
        $kartupiutang->where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $total_data = $kartupiutang->count();

        if($req->filter) {
            foreach($req->filter as $index=>$filter) {
                foreach($filter as $fil) {
                    if($fil['text'] != ''){
                        if($index == 1 || $index == 2 || $index == 4 || $index == 5 || $index == 6 || $index == 7 || $index == 8){
                            if($fil['filter'] == '='){
                                $kartupiutang->where($filter_columns[$index],'ilike','%'.$fil['text'].'%');
                            }else{
                                $kartupiutang->where($filter_columns[$index],'not ilike','%'.$fil['text'].'%');
                            }
                        }elseif($index == 0){
                            $kartupiutang->where($filter_columns[$index],$fil['filter'],Carbon::parse($fil['text'])->toDateString());
                        }else{
                            $kartupiutang->where($filter_columns[$index],$fil['filter'],$fil['text']);
                        }
                        $filter_count++;
                    }
                }
            }
        }

        if($filter_count > 0){
            $filtered_data = $kartupiutang->count();
            $kartupiutang->skip($req->start)->take($req->length);
        }else{
            $filtered_data = $total_data;
            $kartupiutang->skip($req->start)->take($req->length);
        }

        if(array_key_exists($req->order[0]['column'], $filter_columns)){
            $kartupiutang->orderByRaw($filter_columns[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }

        // Data
        $data = [];
        foreach ($kartupiutang->get() as $k => $val) {
            $val->tgljt   = ($val->tgljt) ? Carbon::parse($val->tgljt)->format('d-m-Y') : '';
            // $val->nominal = number_format($val->nominal,2,',','.');
            $data[$k] = $val->toArray();
        }

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function cek(Request $req)
    {
        $tglmulai   = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();

        $toko = KartuPiutang::selectRaw("COUNT(mstr.toko.id) as cek");
        $toko->join('mstr.toko', 'ptg.kartupiutang.tokoid', '=', 'mstr.toko.id');
        $toko->where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $toko->whereRaw("DATE(ptg.kartupiutang.tgljt) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
        if($req->tokoid) {
            $toko->where("ptg.kartupiutang.tokoid",$req->tokoid);
        }
        if($req->all == 0) {
            $toko->where("ptg.kartupiutang.printftagih","<=",0);
        }
        $toko->orderBy("mstr.toko.namatoko");
        $toko->groupBy("mstr.toko.id");
        $toko = $toko->first();

        return ($toko) ? $toko->cek : 0;
    }

    public function print(Request $req)
    {
        $config = [
            'mode'                  => '',
            'format'                => [152.4,107.95],
            'default_font_size'     => '7',
            'default_font'          => 'sans-serif',
            'margin_left'           => 8,
            'margin_right'          => 8,
            'margin_top'            => 30,
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

        $tglmulai   = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();

        $toko = KartuPiutang::select("mstr.toko.id","mstr.toko.namatoko","mstr.toko.customwilayah","mstr.toko.alamat","mstr.toko.kota","mstr.toko.telp");
        $toko->join('mstr.toko', 'ptg.kartupiutang.tokoid', '=', 'mstr.toko.id');
        $toko->where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $toko->whereRaw("DATE(ptg.kartupiutang.tgljt) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
        if($req->tokoid) {
            $toko->where("ptg.kartupiutang.tokoid",$req->tokoid);
        }
        if($req->all == 0) {
            $toko->where("ptg.kartupiutang.printftagih","<=",0);
        }
        $toko->orderBy("mstr.toko.namatoko");
        $toko->groupBy("mstr.toko.id");
        $toko = $toko->get();

        $kartupiutang = KartuPiutang::selectRaw("ptg.kartupiutang.tokoid, ptg.kartupiutang.tipetrans, ptg.kartupiutang.tglterima, ptg.kartupiutang.temponota, ptg.kartupiutang.tgljt, pj.notapenjualan.nonota, pj.notapenjualan.totalnominal, hr.karyawan.kodesales, COALESCE(SUM(ptg.kartupiutangdetail.nomtrans),0) as titipan");
        $kartupiutang->join('pj.notapenjualan', 'ptg.kartupiutang.notaid', '=', 'pj.notapenjualan.id');
        $kartupiutang->leftJoin('ptg.kartupiutangdetail', 'ptg.kartupiutang.id', '=', 'ptg.kartupiutangdetail.kartupiutangid');
        $kartupiutang->join('hr.karyawan', 'ptg.kartupiutang.karyawanidsalesman', '=', 'hr.karyawan.id');
        $kartupiutang->join('mstr.toko', 'ptg.kartupiutang.tokoid', '=', 'mstr.toko.id');
        $kartupiutang->where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $kartupiutang->whereRaw("DATE(ptg.kartupiutang.tgljt) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
        if($req->tokoid) {
            $kartupiutang->where("ptg.kartupiutang.tokoid",$req->tokoid);
        }
        if($req->all == 0) {
            $kartupiutang->where("ptg.kartupiutang.printftagih","<=",0);
        }
        $kartupiutang->orderBy("ptg.kartupiutang.tgljt");
        $kartupiutang->groupBy("ptg.kartupiutang.tokoid", "ptg.kartupiutang.tipetrans", "ptg.kartupiutang.tglterima", "ptg.kartupiutang.temponota", "ptg.kartupiutang.tgljt", "pj.notapenjualan.nonota", "pj.notapenjualan.totalnominal", "hr.karyawan.kodesales");
        $kartupiutang = $kartupiutang->get();

        foreach ($kartupiutang as $k => $val) {
            $val->tglterima = ($val->tglterima) ? Carbon::parse($val->tglterima)->format('d-m-Y') : '';
            $val->tgljt     = ($val->tgljt) ? Carbon::parse($val->tgljt)->format('d-m-Y') : '';
        }

        // Update printftagih
        $printftagih = KartuPiutang::where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $printftagih->whereRaw("DATE(ptg.kartupiutang.tgljt) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
        if($req->tokoid) {
            $printftagih->where("ptg.kartupiutang.tokoid",$req->tokoid);
        }
        $printftagih->where("ptg.kartupiutang.recordownerid",$req->session()->get('subcabang'));
        $printftagih->update(['printftagih' => DB::raw('printftagih+1')]);

        $pdf   = PDF::loadView('transaksi.ftagih.cetak',[
            'toko'         => $toko,
            'kartupiutang' => $kartupiutang,
            'user'         => strtoupper(auth()->user()->username),
            ],[],$config);
        return $pdf->stream('FTAGIH-'.$req->tglmulai.'-'.$req->selesai.'.pdf');
    }
}
