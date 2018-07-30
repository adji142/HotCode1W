<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaPenjualan;
use Carbon\Carbon;
use App\Models\SubCabang;
use App\Models\ReportLog;
use EXCEL;
use PDF;

class LaporanPengirimanGudangController extends Controller
{
    public function index(Request $req)
    {
    	return view('laporan.pengirimangudang.index');
    }

    public function insert_reportlog($recordownerid,$userid,$reportname)
    {
        $reportlog = ReportLog::create([
            'recordownerid' => $recordownerid,
            'userid'        => $userid,
            'reportname'    => $reportname,
            'createdon'     => Carbon::now(),
        ]);
    }

    public function cetak(Request $req)
    {
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PENGIRIMAN GUDANG');
    	$columns = array(
    		0 => 'mstr.toko.namatoko',
    		1 => 'mstr.toko.kota',
    		2 => 'mstr.toko.customwilayah',
    		3 => 'pj.notapenjualan.nonota',
    		4 => 'hr.karyawan.namakaryawan',
    		5 => 'mstr.expedisi.namaexpedisi',
    		6 => 'pj.notapenjualan.tipetransaksi',
    		7 => 'pj.notapenjualan.totalnominal'
    	);
    	$npj = NotaPenjualan::selectRaw(collect($columns)->implode(', '));
    	$npj->leftJoin('pj.orderpenjualan','pj.notapenjualan.orderpenjualanid','=','pj.orderpenjualan.id');
    	$npj->leftJoin('mstr.toko', 'pj.notapenjualan.tokoid', '=', 'mstr.toko.id');
    	$npj->leftJoin('mstr.expedisi', 'pj.orderpenjualan.expedisiid', '=', 'mstr.expedisi.id');
    	$npj->leftJoin('hr.karyawan', 'pj.orderpenjualan.karyawanidsalesman', '=', 'hr.karyawan.id');
    	// $npj->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid');
    	$npj->where("pj.notapenjualan.tglproforma",'=',Carbon::parse($req->tgl));
    	// $npj->groupBy(["mstr.toko.namatoko","mstr.toko.kota","mstr.toko.customwilayah","pj.notapenjualan.nonota","hr.karyawan.namakaryawan","mstr.expedisi.namaexpedisi","pj.notapenjualan.tipetransaksi"]);
    	$data = $npj->get();
        $total = array(
            'totaltunai' => $data->sum(function ($jual){
                if(substr($jual->tipetransaksi,0,1) == "T"){
                    return $jual->totalnominal;
                }else{
                    return 0;
                }
            }),
            'totalkredit' => $data->sum(function ($jual){
                if(substr($jual->tipetransaksi,0,1) == "K"){
                    return $jual->totalnominal;
                }else{
                    return 0;
                }
            })
        );

        $tgl       = Carbon::parse($req->tgl)->format('d/m/Y');
        $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username  = strtoupper(auth()->user()->username);
        Excel::create('Laporan Pengiriman Gudang', function($excel) use ($tgl,$subcabang,$data,$username,$total) {
            $excel->setTitle('Laporan Pengiriman Gudang');
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject('Laporan Pengiriman Gudang');
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription('Laporan Pengiriman Gudang');
            $excel->sheet('LaporanPengirimanGudang', function($sheet) use ($tgl,$subcabang,$data,$username,$total) {
                $sheet->setColumnFormat(array(
                    'E' => '@',
                    'H' => '#,##0',
                    'I' => '#,##0',
                ));
                $sheet->loadView('laporan.pengirimangudang.excel',array('tgl'=>$tgl,'subcabang'=>$subcabang,'datas'=>$data,'username'=>$username,'total'=>$total));
            });
        })->store('xls', storage_path('excel/exports'));

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 30,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => "SAS",
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $pdf = PDF::loadView('laporan.pengirimangudang.pdf',[
            'tgl'       => $tgl,
            'subcabang' => $subcabang,
            'datas'     => $data,
            'username'  => $username,
            'total'     => $total
        ],[],$config);

        $pdf->save(storage_path('excel/exports') . '/Laporan Pengiriman Gudang.pdf');

        $urlExcel = asset('storage/excel/exports/Laporan Pengiriman Gudang.xls');
        $urlPDF = asset('storage/excel/exports/Laporan Pengiriman Gudang.pdf');

    	return view('laporan.pengirimangudang.preview',compact('data','total','urlExcel','urlPDF'));
    }
}
