<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCabang;
use App\Models\OrderPenjualan;
use App\Models\OrderPenjualanDetail;
use App\Models\NotaPenjualan;
use App\Models\NotaPenjualanDetail;
use App\Models\ReportLog;
use Carbon\Carbon;
use EXCEL;
use PDF;

class LaporanAccHargaDitolakController extends Controller
{
    public function index(Request $req)
    {
    	$subcabang = SubCabang::all();
    	return view('laporan.acchargaditolak.index',compact('subcabang'));
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
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'ACC HARGA DITOLAK');
        $columns = array(
            0 => 'mstr.toko.namatoko',
            1 => 'mstr.toko.alamat',
            2 => 'mstr.toko.kota',
            3 => 'pj.orderpenjualan.tglpickinglist',
            4 => 'pj.orderpenjualan.nopickinglist',
            5 => 'hr.karyawan.kodesales',
            6 => 'mstr.stock.namabarang',
            7 => 'pj.orderpenjualandetail.hrgsatuannetto as opjdnetto',
            8 => 'pj.orderpenjualandetail.qtyso',
            9 => 'pj.notapenjualan.nonota',
            10 => 'pj.notapenjualan.tglproforma',
            11 => 'pj.notapenjualandetail.hrgsatuannetto as npjdnetto',
            12 => 'pj.orderpenjualandetail.catatan',
            13 => 'pj.orderpenjualan.tokoid',
            14 => 'pj.orderpenjualandetail.stockid',
            15 => 'pj.orderpenjualan.statustoko'
        );
        $opj = OrderPenjualan::selectRaw(collect($columns)->implode(', '));
        $opj->leftJoin('mstr.toko', 'pj.orderpenjualan.tokoid', '=', 'mstr.toko.id');
        $opj->leftJoin('hr.karyawan', 'pj.orderpenjualan.karyawanidsalesman', '=', 'hr.karyawan.id');
        $opj->leftJoin('pj.orderpenjualandetail','pj.orderpenjualan.id','=','pj.orderpenjualandetail.orderpenjualanid');
        $opj->leftJoin('mstr.stock', 'pj.orderpenjualandetail.stockid', '=', 'mstr.stock.id');
        $opj->leftJoin('pj.notapenjualan','pj.orderpenjualan.id','=','pj.notapenjualan.orderpenjualanid');
        $opj->leftJoin('pj.notapenjualandetail','pj.orderpenjualandetail.id','=','pj.notapenjualandetail.orderpenjualandetailid');
        $opj->where('pj.orderpenjualan.omsetsubcabangid',$req->subcabang);
        $opj->where("pj.orderpenjualan.tglpickinglist",'>=',Carbon::parse($req->tglmulai));
        $opj->where("pj.orderpenjualan.tglpickinglist",'<=',Carbon::parse($req->tglselesai)->endOfDay());
        $opj->where('pj.orderpenjualandetail.qtysoacc','<=',0);
        $opj->where('pj.orderpenjualandetail.noacc11','=','');
        // $opj->where('pj.orderpenjualandetail.noacc11','=',null);
        $opj->orderBy('mstr.toko.namatoko','asc');
        $data = $opj->get();
        // dd($opj->toSql());
        foreach ($data as $key => $jual) {
            $jual->hargabmk          = $jual->hargabmkstock;
            $jual->hppa              = ($jual->hppaperstock) ? $jual->hppaperstock->nominalhppa : 0;
            $jual->totalhargajual    = $jual->opjdnetto*$jual->qtyso;
            $jual->hargajualterakhir = ($jual->npjdnetto) ? $jual->npjdnetto : 0;
        }

        $namasheet  = str_slug("Laporan ACC Harga Ditolak");
        $namafile   = $namasheet."-".uniqid();

        $tglmulai   = Carbon::parse($req->tglmulai)->format('d/m/Y');
        $tglselesai = Carbon::parse($req->tglselesai)->format('d/m/Y');
        $subcabang  = SubCabang::find($req->subcabang)->kodesubcabang;
        $username   = strtoupper(auth()->user()->username);
        Excel::create($namafile, function($excel) use ($tglmulai,$tglselesai,$subcabang,$data,$username,$namasheet) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use ($tglmulai,$tglselesai,$subcabang,$data,$username) {
                $sheet->setColumnFormat(array(
                    'I' => '#,##0',
                    'J' => '#,##0',
                    'K' => '#,##0',
                    'L' => '0',
                    'M' => '#,##0',
                    'P' => '#,##0',
                    'Q' => '@',
                ));
                $sheet->loadView('laporan.acchargaditolak.excel',array('tglmulai'=>$tglmulai,'tglselesai'=>$tglselesai,'subcabang'=>$subcabang,'datas'=>$data,'username'=>$username));
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

        $pdf = PDF::loadView('laporan.acchargaditolak.pdf',[
            'tglmulai'   => $tglmulai,
            'tglselesai' => $tglselesai,
            'subcabang'  => $subcabang,
            'datas'      => $data,
            'username'   => $username
        ],[],$config);

        $pdf->save(storage_path('excel/exports').'/'.$namafile.'.pdf');

        $urlExcel = asset('storage/excel/exports/'.$namafile.'.xls');
        $urlPDF = asset('storage/excel/exports/'.$namafile.'.pdf');

    	return view('laporan.acchargaditolak.preview',compact('data','urlExcel','urlPDF'));
    }

	// select mstr.toko.id,mstr.toko.namatoko,mstr.toko.alamat,mstr.toko.kota,
	// hr.karyawan.kodesales,
	// pj.orderpenjualan.tglpickinglist, pj.orderpenjualan.nopickinglist,
	// pj.orderpenjualandetail.hrgsatuannetto as opjdnetto,pj.orderpenjualandetail.qtyso,pj.orderpenjualandetail.catatan,
	// mstr.stock.namabarang,
	// pj.notapenjualan.nonota, pj.notapenjualan.tglproforma,
	// pj.notapenjualandetail.hrgsatuannetto as npjdnetto
	// from pj.orderpenjualan
	// left join mstr.toko on pj.orderpenjualan.tokoid = mstr.toko.id
	// left join hr.karyawan on pj.orderpenjualan.karyawanidsalesman = hr.karyawan.id
	// left join pj.orderpenjualandetail on pj.orderpenjualan.id = pj.orderpenjualandetail.orderpenjualanid
	// left join mstr.stock on pj.orderpenjualandetail.stockid = mstr.stock.id
	// left join pj.notapenjualan on pj.orderpenjualan.id = pj.notapenjualan.orderpenjualanid
	// left join pj.notapenjualandetail on pj.orderpenjualandetail.id = pj.notapenjualandetail.orderpenjualandetailid
	// where pj.orderpenjualan.omsetsubcabangid = 3
	// and pj.orderpenjualan.tglpickinglist >= '2016-10-08'
	// and pj.orderpenjualan.tglpickinglist <= '2016-10-08'
	// and pj.orderpenjualandetail.qtysoacc <= 0
	// and pj.orderpenjualandetail.noacc11 is null

	// $opj = OrderPenjualan::selectRaw(collect($columns)->implode(', '));
	// $opj->leftJoin('pj.orderpenjualandetail','pj.orderpenjualan.id','=','pj.orderpenjualandetail.orderpenjualanid');
	// $opj->leftJoin('pj.notapenjualan','pj.orderpenjualan.id','=','pj.notapenjualan.orderpenjualanid');
	// $opj->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid');
	// $opj->leftJoin('mstr.toko', 'pj.orderpenjualan.tokoid', '=', 'mstr.toko.id');
	// $opj->leftJoin('mstr.statustoko', 'pj.orderpenjualan.tokoid', '=', 'mstr.statustoko.tokoid');
	// $opj->leftJoin('mstr.stock', 'pj.orderpenjualandetail.stockid', '=', 'mstr.stock.id');
	// $opj->leftJoin('hr.karyawan', 'pj.orderpenjualan.karyawanidsalesman', '=', 'hr.karyawan.id');
	// $opj->where('pj.orderpenjualan.omsetsubcabangid',$req->subcabang);
	// $opj->where("pj.orderpenjualan.tglpickinglist",'>=',Carbon::parse($req->tglmulai));
	// $opj->where("pj.orderpenjualan.tglpickinglist",'<=',Carbon::parse($req->tglselesai)->endOfDay());
	// $opj->where('pj.orderpenjualandetail.qtysoacc','<=',0);
	// $opj->where('pj.orderpenjualandetail.noacc11','=',null);
}
