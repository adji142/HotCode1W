<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Numerator;
use App\Models\SubCabang;
use Carbon\Carbon;
use DB;

/* Excel */
use EXCEL;
use PDF;

/* models */
use App\Models\KelompokBarang;


class LaporanController extends Controller
{
    public function salesmanPenjualanhi()
    {
        return view('laporan.salesman.frmrptpenjualanhi');
    }

    public function salesmanPenjualanhireport(Request $req)
    {
        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();

        $periode = $req->tglmulai . " s.d. " . $req->tglselesai;

        $omsetsubcabangid = $req->omsetsubcabang;
        $pemgirimsubcabangid = $req->pengirimsubcabang;

        $harga = $req->harga;

        $data = DB::select(DB::raw("
            select * 
            from report.rsp_salesman_penjualanhi(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $omsetsubcabangid . "',
                '" . $pemgirimsubcabangid . "'
            ) order by tglnota"));

        $klmpkbrg = KelompokBarang::select([
                0 => "id",
                1 => "kode",
                2 => "keterangan",
            ])
            ->where("kode", "<>", "SB4")
            ->where("kode", "<>", "SB2")
            ->where("kode", "<>", "SE4")
            ->where("kode", "<>", "SE2")
            ->orderBy("keterangan")
            ->orderBy("kode")
            ->get()
            ->toArray();

        $loop = 0;


        //buat table di controller karena format table nya butuh pivot
        //di isa pake matrix
        //diphp diakal2in pake looping
        $result = "
            <table id='tableData' class='table table-bordered table-striped display mbuhsakarepku' width='100%' style='white-space: nowrap'>
                <thead>
        ";

        $header1 = "<tr>
                        <td width='5px'></td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Tanggal</td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Proforma</td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Salesman</td>
                        <td width='50px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Toko</td>
                        <td width='30px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Kota</td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Omzet</td>
        ";

        $header2 = "<tr>
                        <td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                    ";

        $header3 = $header2;

        $colspan = 0;


        //generate headernya
        for ($loop = 0; $loop < count($klmpkbrg); $loop++) {
            $currKode = $klmpkbrg[$loop]["kode"];
            $currKeterangan = $klmpkbrg[$loop]["keterangan"];
            
            for ($looper = $loop; $looper < count($klmpkbrg); $looper++)
            {
                $nextKeterangan = $klmpkbrg[$looper]["keterangan"];
                if($currKeterangan == $nextKeterangan)
                {
                    $colspan = $colspan + 2;
                    $klmpkbrg[$looper]["keterangan"] = "";
                }
            }

            if($currKeterangan != "")
            {
                $header1 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='" . $colspan . "' align='center' valign='center'>".$currKeterangan."</td>";
            }
            $header2 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='2' align='center' valign='center'>".$currKode."</td>";
            $header3 .= "<td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Pcs</td>
                        <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Nilai</td>";
            $colspan = 0;
        }

        $header1 .= "
            <td width='20px' rowspan='2' colspan='2' align='center' style='border: 1px solid #000000; font-weight: bold;'>Total</td>
        </tr>";
        $header2 .= "
            <td class='replace'></td>
            <td class='replace'></td>
        </tr>";
        $header3 .= "
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Pcs</td>
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Nilai</td>
        </tr>";

        $result .= $header1 . $header2 . $header3 . "
                </thead>
        ";
        
        $body = "
                <tbody>
        ";

        $totalQty = 0;
        $totalHrg = 0;
        $grandtotalQty = 0;
        $grandtotalHrg = 0;
        $grandtotalOmset = 0;

        //isi datanya
        foreach ($data as $key => $value) {
            $body .= "<tr>";
            $body .= "<td class='margin'></td>";
            $body .= "<td style='border: 1px solid #000000'>" . $value->tglnotastring . "</td>";
            $body .= "<td style='border: 1px solid #000000'>" . $value->nonota . "</td>";
            $body .= "<td style='border: 1px solid #000000'>" . $value->kodesales . "</td>";
            $body .= "<td style='border: 1px solid #000000'>" . $value->namatoko . "</td>";
            $body .= "<td style='border: 1px solid #000000'>" . $value->kota . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . (int)$value->omset . "</td>";

            foreach ($klmpkbrg as $klmpk) {
                if($klmpk["kode"] == $value->kpl)
                {
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . (int)$value->qty . "</td>";
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . ($harga == "hbeli" ? (int)$value->hpp : (int)$value->hppa) . "</td>";
                }
                else
                {
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . (int)0 . "</td>";
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . (int)0 . "</td>";
                }
            }

            $body .= "<td style='border: 1px solid #000000' align='right'>" . (int)$value->qty . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . ($harga == "hbeli" ? (int)$value->hpp : (int)$value->hppa) . "</td>";

            $body .= "</tr>";

            $grandtotalOmset += (int)$value->omset;
        }

        $footer = "<tfoot>
            <tr>
                <td class='margin'></td>
                <td style='border: 1px solid #000000' align='right' colspan='5'>Total : </td>
                <td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalOmset  . " </td>
        ";


        //total
        foreach ($klmpkbrg as $klmpk) {

            $totalQty = 0;
            $totalHrg = 0;

            foreach ($data as $key => $value) {
                if($klmpk["kode"] == $value->kpl)
                {
                    $totalQty += $value->qty;
                    $totalHrg += ($harga == "hbeli" ? (int)$value->hpp : (int)$value->hppa);
                }
            }
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalQty . "</td>";
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalHrg . "</td>";

            $grandtotalQty += $totalQty;
            $grandtotalHrg += $totalHrg;
        }

        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalQty . "</td>";
        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalHrg . "</td>";

        $result .= $body . "
                </tbody>" . $footer . "
            </table>
        ";

        // return $result;

        $namasheet = "Laporan Penjualan HI";
        $namafile = $namasheet . "_" . auth()->user()->id . "_" . date("YmdHis");

        $excels = Excel::create($namafile, function($excel) use ($periode, $result, $namasheet) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use ($periode, $result) {
                $sheet->loadView('laporan.salesman.rptpenjualanhi',
                    array(
                        "periode" => $periode,
                        "table" => $result,
                        )
                    );
            });
        });

        $excels->store('xls', storage_path('excel/exports'));

        $result = str_replace("<td class='replace'></td>", "", $result);

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
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

        $pdf = PDF::loadView('laporan.salesman.rptpenjualanhi',
                array(
                    "periode" => $periode,
                    "table" => $result,
                    ),[],$config);

        $pdf->save(storage_path('excel/exports') . "/" . $namafile .'.pdf');

        return view("laporan.tampil")
            ->with("laporan", "penjualanhi")
            ->with("periode", $periode)
            ->with("linkExcel", asset('storage/excel/exports/' . $namafile .'.xls'))
            ->with("linkPdf", asset('storage/excel/exports/' . $namafile .'.pdf'))
            ->with("table", $result);
    }

    public function salesmanPenjualanABE()
    {
        return view('laporan.salesman.frmrptpenjualanABE');
    }

    public function salesmanPenjualanABEreport(Request $req)
    {
        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();

        $periode = $req->tglmulai . " s.d. " . $req->tglselesai;

        $subcabangid = $req->subcabang;
        $cabangid = $req->cabang;
        $jenis = $req->jenis;
        $jenisString = "";

        if ($jenis == 1)
        {
            $jenisString = "Penjualan Bruto";
        }elseif ($jenis == 2) {
            $jenisString = "Koreksi Jual";
        }elseif ($jenis == 3) {
            $jenisString = "Retur Kotor";
        }elseif ($jenis == 4) {
            $jenisString = "Koreksi Retur";
        }


        $data = DB::select(DB::raw("
        select * 
        from report.rsp_salesman_abe_penjualan(
            '" . $fromdate . "',
            '" . $todate . "',
            '" . $cabangid . "',
            '" . $subcabangid . "',
            '" . $jenis . "'
        ) order by kodesales"));

        $dataArray = array();

        foreach ($data as $key => $value) {
            array_push($dataArray, (array)$value);
        }

        // Untuk mengambil data salesman siapa saja yang ada di laporan
        $salesman = array();
        foreach($dataArray as $value)
        {
            if ($value["kodesales"] <> null)
            {
                if(!in_array($value["kodesales"], $salesman))
                {
                    array_push($salesman, $value['kodesales']);
                }
            }
        }

        // Untuk mengambil kelompok barang yang akan dijadikan header
       $klmpkbrg = KelompokBarang::select([
                0 => "id",
                1 => "kode",
                2 => DB::raw("LEFT(keterangan,8) as keterangan"),
            ])
            ->orderBy("keterangan")
            ->orderBy("kode")
            ->get()
            ->toArray();

        $loop = 0;

        $result = "
            <table id='tableData' class='table table-bordered table-striped display mbuhsakarepku table-data' width='100%' style='white-space: nowrap'>
                <thead>
        ";

        $header1 = "<tr>
                        <td width='5px'></td>
                        <td width='30px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Kode Sales</td>
        ";

        $header2 = "<tr>
                        <td>
                        <td class='replace'></td>
                    ";

        $header3 = $header2;

        $colspan = 0;

        for ($loop = 0; $loop < count($klmpkbrg); $loop++) {
            $currKode = $klmpkbrg[$loop]["kode"];
            $currKeterangan = $klmpkbrg[$loop]["keterangan"];
            
            for ($looper = $loop; $looper < count($klmpkbrg); $looper++)
            {
                $nextKeterangan = $klmpkbrg[$looper]["keterangan"];
                if($currKeterangan == $nextKeterangan)
                {
                    $colspan = $colspan + 3;
                    $klmpkbrg[$looper]["keterangan"] = "";
                }
            }

            if($currKeterangan != "")
            {
                $header1 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='" . $colspan . "' align='center' valign='center'>".$currKeterangan."</td>";
            }
            $header2 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='3' align='center' valign='center'>".$currKode."</td>";
            $header3 .= "<td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Pcs</td>
                        <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Nilai</td>
                        <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>HPP</td>";
            $colspan = 0;
        }

        $header1 .= "
            <td width='20px' rowspan='2' colspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Total</td>
        </tr>";
        $header2 .= "
            <td class='replace'></td>
            <td class='replace'></td>
            <td class='replace'></td>
        </tr>";
        $header3 .= "
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Pcs</td>
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Nilai</td>
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>HPP</td>
        </tr>";

        $result .= $header1 . $header2 . $header3 . "
                </thead>
        ";
        
        $body = "
                <tbody>
        ";

        // Perulangan untuk membuat data kolom dan sales (koyo pivot)
        $dataresult = array();
        foreach ($salesman as $key => $value) {
            $totalQty = 0;
            $totalNet = 0;
            $totalHPP = 0;

            if ($value <> null)
            {
                //Dibuat array kosong dulu, biar bisa di isi data
                array_push($dataresult, array());
                $dataresult[$key]["Kodesales"] = $value;
            }

                //Perulangan, jika index header belum ada membuat header, jika sudah ada tidak buat baru, tapi insert data-nya saja
                foreach ($klmpkbrg as $klmpk) {
                    if(!array_key_exists($klmpk["kode"] . "Qty", $dataresult))
                    {
                        $dataresult[$key][$klmpk["kode"] . "Qty"] = 0; 
                        $dataresult[$key][$klmpk["kode"] . "Hrg"] = 0; 
                        $dataresult[$key][$klmpk["kode"] . "HPP"] = 0; 
                    }

                    foreach ($data as $kdata => $vdata) {
                        if ($value == $vdata->kodesales)
                        {
                            if($klmpk["kode"] == $vdata->klp) 
                            {
                                $dataresult[$key][$klmpk["kode"] . "Qty"] = (int)$vdata->qty;
                                $dataresult[$key][$klmpk["kode"] . "Hrg"] = (int)$vdata->net;
                                $dataresult[$key][$klmpk["kode"] . "HPP"] = (int)$vdata->hpp;

                                $totalQty = $totalQty + $vdata->qty;
                                $totalNet = $totalNet + $vdata->net;
                                $totalHPP = $totalHPP + $vdata->hpp;
                            }
                        }
                        
                    }
                }

                //Membuat Total Ke Kanan (kolom Total)
                if(!array_key_exists("TotalQty", $dataresult))
                    {
                        $dataresult[$key]["TotalQty"] = 0; 
                        $dataresult[$key]["TotalNet"] = 0; 
                        $dataresult[$key]["TotalHPP"] = 0; 
                    }

                $dataresult[$key]["TotalQty"] = $totalQty;
                $dataresult[$key]["TotalNet"] = $totalNet;
                $dataresult[$key]["TotalHPP"] = $totalHPP;
        }

        //Perulangan untuk menampilkan data
        foreach ($dataresult as $key => $value) {
            $body .= "<tr>";
            $body .= "<td class='margin'></td>";

            foreach ($value as $v) {
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . $v . "</td>";
            }

            $body .= "</tr>";
        }

        //Cetak footer
        $footer = "<tfoot>
            <tr>
                <td class='margin'></td>
                <td style='border: 1px solid #000000' align='right'>Total : </td>
        ";

        $grandtotalQty = 0;
        $grandtotalNet = 0;
        $grandtotalHPP = 0;
        foreach ($klmpkbrg as $klmpk) {

            $totalQty = 0;
            $totalNet = 0;
            $totalHPP = 0;

            foreach ($data as $key => $value) {
                if($klmpk["kode"] == $value->klp)
                {
                    $totalQty += $value->qty;
                    $totalNet += $value->net;
                    $totalHPP += $value->hpp;
                }
            }
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalQty . "</td>";
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalNet . "</td>";
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalHPP . "</td>";

            $grandtotalQty += $totalQty;
            $grandtotalNet += $totalNet;
            $grandtotalHPP += $totalHPP;
        }

        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalQty . "</td>";
        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalNet . "</td>";
        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalHPP . "</td>";

        $result .= $body . "
                </tbody> ". $footer ."
            </table>
        ";

        // return $result;

        $namasheet = "Laporan Penjualan ABE";
        $namafile = $namasheet . "_" . $jenisString . "_" . csrf_token();

        $excels = Excel::create($namafile, function($excel) use ($periode, $result, $namasheet, $jenisString) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use ($periode, $result, $jenisString) {
                $sheet->loadView('laporan.salesman.rptpenjualanABE',
                    array(
                        "periode" => $periode,
                        "table" => $result,
                        "jenis" => $jenisString,
                        )
                    );
            });
        });

        $excels->store('xls', storage_path('excel/exports'));

        $result = str_replace("<td class='replace'></td>", "", $result);

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
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

        $pdf = PDF::loadView('laporan.salesman.rptpenjualanABE',
                array(
                    "periode" => $periode,
                    "table" => $result,
                    "jenis" => $jenisString,
                    ),[],$config);

        $pdf->save(storage_path('excel/exports') . "/" . $namafile .'.pdf');

        return view("laporan.tampil")
            ->with("laporan", "penjualanabe")
            ->with("periode", $periode)
            ->with("linkExcel", asset('storage/excel/exports/' . $namafile .'.xls'))
            ->with("linkPdf", asset('storage/excel/exports/' . $namafile .'.pdf'))
            ->with("jenis", $jenisString)
            ->with("table", $result);
    }

    public function salesmanPenjualanABEreport_Netto(Request $req)
    {
        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();

        $periode = $req->tglmulai . " s.d. " . $req->tglselesai;

        $subcabangid = $req->subcabang;
        $cabangid = $req->cabang;
        $jenisString = "Penjualan Netto";

        $data = DB::select(DB::raw("
        select * 
        from report.rsp_salesman_abe_penjualan_netto(
            '" . $fromdate . "',
            '" . $todate . "',
            '" . $cabangid . "',
            '" . $subcabangid . "'
        ) order by kodesales"));

        $dataArray = array();

        foreach ($data as $key => $value) {
            array_push($dataArray, (array)$value);
        }

        // Untuk mengambil data salesman siapa saja yang ada di laporan
        $salesman = array();
        foreach($dataArray as $value)
        {
            if ($value["kodesales"] <> null)
            {
                if(!in_array($value["kodesales"], $salesman))
                {
                    array_push($salesman, $value['kodesales']);
                }
            }
        }

        // Untuk mengambil kelompok barang yang akan dijadikan header
       $klmpkbrg = KelompokBarang::select([
                0 => "id",
                1 => "kode",
                2 => DB::raw("LEFT(keterangan,8) as keterangan"),
            ])
            ->orderBy("keterangan")
            ->orderBy("kode")
            ->get()
            ->toArray();

        $loop = 0;

        $result = "
            <table id='tableData' class='table table-bordered table-striped display mbuhsakarepku table-data' width='100%' style='white-space: nowrap'>
                <thead>
        ";

        $header1 = "<tr>
                        <td width='5px'></td>
                        <td width='20px' rowspan='3' align='left' style='border: 1px solid #000000; font-weight: bold;'>Kode Sales</td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Outlet Aktif</td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>SKU</td>
                        <td width='20px' rowspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Nominal</td>
        ";

        $header2 = "<tr>
                        <td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                    ";

        $header3 = $header2;

        $colspan = 0;

        for ($loop = 0; $loop < count($klmpkbrg); $loop++) {
            $currKode = $klmpkbrg[$loop]["kode"];
            $currKeterangan = $klmpkbrg[$loop]["keterangan"];
            
            for ($looper = $loop; $looper < count($klmpkbrg); $looper++)
            {
                $nextKeterangan = $klmpkbrg[$looper]["keterangan"];
                if($currKeterangan == $nextKeterangan)
                {
                    $colspan = $colspan + 3;
                    $klmpkbrg[$looper]["keterangan"] = "";
                }
            }

            if($currKeterangan != "")
            {
                $header1 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='" . $colspan . "' align='center' valign='center'>".$currKeterangan."</td>";
            }
            $header2 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='3' align='center' valign='center'>".$currKode."</td>";
            $header3 .= "<td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Pcs</td>
                        <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Nilai</td>
                        <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>HPP</td>";
            $colspan = 0;
        }

        $header1 .= "
            <td width='20px' rowspan='2' colspan='3' align='center' style='border: 1px solid #000000; font-weight: bold;'>Total</td>
        </tr>";
        $header2 .= "
            <td class='replace'></td>
            <td class='replace'></td>
            <td class='replace'></td>
        </tr>";
        $header3 .= "
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Pcs</td>
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Nilai</td>
            <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>HPP</td>
        </tr>";

        $result .= $header1 . $header2 . $header3 . "
                </thead>
        ";
        
        $body = "
                <tbody>
        ";

        // Perulangan untuk membuat data kolom dan sales (koyo pivot)
        $dataresult = array();
        foreach ($salesman as $key => $value) {
            $totalQty = 0;
            $totalNet = 0;
            $totalHPP = 0;

            if ($value <> null)
            {
                //Dibuat array kosong dulu, biar bisa di isi data
                array_push($dataresult, array());
                $dataresult[$key]["Kodesales"] = $value; 
                $dataresult[$key]["oa"] = 0; 
                $dataresult[$key]["sku"] = 0; 
                $dataresult[$key]["nominal"] = 0; 
            }

                //Perulangan, jika index header belum ada membuat header, jika sudah ada tidak buat baru, tapi insert data-nya saja
                foreach ($klmpkbrg as $klmpk) {
                    if(!array_key_exists($klmpk["kode"] . "Qty", $dataresult))
                    {
                        $dataresult[$key][$klmpk["kode"] . "Qty"] = 0; 
                        $dataresult[$key][$klmpk["kode"] . "Hrg"] = 0; 
                        $dataresult[$key][$klmpk["kode"] . "HPP"] = 0; 
                    }

                    foreach ($data as $kdata => $vdata) {
                        if ($value == $vdata->kodesales)
                        {
                            $dataresult[$key]["oa"] = (int)$vdata->oa; 
                            $dataresult[$key]["sku"] = (int)$vdata->sku; 
                            $dataresult[$key]["nominal"] = (int)$vdata->nominal; 

                            if($klmpk["kode"] == $vdata->klp) 
                            {
                                $dataresult[$key][$klmpk["kode"] . "Qty"] = (int)$vdata->qty;
                                $dataresult[$key][$klmpk["kode"] . "Hrg"] = (int)$vdata->net;
                                $dataresult[$key][$klmpk["kode"] . "HPP"] = (int)$vdata->hpp;

                                $totalQty = $totalQty + $vdata->qty;
                                $totalNet = $totalNet + $vdata->net;
                                $totalHPP = $totalHPP + $vdata->hpp;
                            }
                        }
                        
                    }
                }

                //Membuat Total Ke Kanan (kolom Total)
                if(!array_key_exists("TotalQty", $dataresult))
                    {
                        $dataresult[$key]["TotalQty"] = 0; 
                        $dataresult[$key]["TotalNet"] = 0; 
                        $dataresult[$key]["TotalHPP"] = 0; 
                    }

                $dataresult[$key]["TotalQty"] = $totalQty;
                $dataresult[$key]["TotalNet"] = $totalNet;
                $dataresult[$key]["TotalHPP"] = $totalHPP;
        }

        //Perulangan untuk menampilkan data
        foreach ($dataresult as $key => $value) {
            $body .= "<tr>";
            $body .= "<td class='margin'></td>";

            foreach ($value as $v) {
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . $v . "</td>";
            }

            $body .= "</tr>";
        }

        //Cetak footer
        $footer = "<tfoot>
            <tr>
                <td class='margin'></td>
                <td style='border: 1px solid #000000' align='right' colspan='4'>Total : </td>
        ";

        $grandtotalQty = 0;
        $grandtotalNet = 0;
        $grandtotalHPP = 0;
        foreach ($klmpkbrg as $klmpk) {

            $totalQty = 0;
            $totalNet = 0;
            $totalHPP = 0;

            foreach ($data as $key => $value) {
                if($klmpk["kode"] == $value->klp)
                {
                    $totalQty += $value->qty;
                    $totalNet += $value->net;
                    $totalHPP += $value->hpp;
                }
            }
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalQty . "</td>";
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalNet . "</td>";
            $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$totalHPP . "</td>";

            $grandtotalQty += $totalQty;
            $grandtotalNet += $totalNet;
            $grandtotalHPP += $totalHPP;
        }

        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalQty . "</td>";
        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalNet . "</td>";
        $footer .= "<td style='border: 1px solid #000000' align='right'>" . (int)$grandtotalHPP . "</td>";

        $result .= $body . "
                </tbody> ". $footer ."
            </table>
        ";

        $namasheet = "Laporan Penjualan ABE";
        $namafile = $namasheet . "_" . $jenisString . "_" . csrf_token();

        $excels = Excel::create($namafile, function($excel) use ($periode, $result, $namasheet, $jenisString) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use ($periode, $result, $jenisString) {
                $sheet->loadView('laporan.salesman.rptpenjualanABE',
                    array(
                        "periode" => $periode,
                        "table" => $result,
                        "jenis" => $jenisString,
                        )
                    );
            });
        });

        $excels->store('xls', storage_path('excel/exports'));

        $result = str_replace("<td class='replace'></td>", "", $result);

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
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

        $pdf = PDF::loadView('laporan.salesman.rptpenjualanABE',
                array(
                    "periode" => $periode,
                    "table" => $result,
                    "jenis" => $jenisString,
                    ),[],$config);

        $pdf->save(storage_path('excel/exports') . "/" . $namafile .'.pdf');

        return view("laporan.tampil")
            ->with("laporan", "penjualanabe")
            ->with("periode", $periode)
            ->with("linkExcel", asset('storage/excel/exports/' . $namafile .'.xls'))
            ->with("linkPdf", asset('storage/excel/exports/' . $namafile .'.pdf'))
            ->with("jenis", $jenisString)
            ->with("table", $result);
    }

    public function salesmanRekapitulasipenjualan()
    {
        return view('laporan.salesman.frmrptrekapitulasipenjualan');
    }

    public function salesmanRekapitulasipenjualanreport(Request $req)
    {
        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();

        $periode = $req->tglmulai . " s.d. " . $req->tglselesai;

        $omsetsubcabangid = $req->omsetsubcabang;
        $pemgirimsubcabangid = $req->pengirimsubcabang;

        $klmpkbrg = KelompokBarang::select(DB::raw(
                "distinct keterangan"
            ))
            ->where("kode", "<>", "SB4")
            ->where("kode", "<>", "SB2")
            ->where("kode", "<>", "SE4")
            ->where("kode", "<>", "SE2")
            ->orderBy("keterangan")
            ->get()
            ->toArray();


        $dataPenjualan = DB::select(DB::raw("
            select * 
            from report.rsp_salesman_rekapitulasipenjualansales(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $omsetsubcabangid . "',
                '" . $pemgirimsubcabangid . "', 'penjualan'
            )"));

        $dataKoreksi = DB::select(DB::raw("
            select * 
            from report.rsp_salesman_rekapitulasipenjualansales(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $omsetsubcabangid . "',
                '" . $pemgirimsubcabangid . "', 'koreksijual'
            )"));

        $dataRetur = DB::select(DB::raw("
            select * 
            from report.rsp_salesman_rekapitulasipenjualansales(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $omsetsubcabangid . "',
                '" . $pemgirimsubcabangid . "', 'retur'
            )"));

        $dataKoreksiRetur = DB::select(DB::raw("
            select * 
            from report.rsp_salesman_rekapitulasipenjualansales(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $omsetsubcabangid . "',
                '" . $pemgirimsubcabangid . "', 'koreksiretur'
            )"));

        $dataNetto = DB::select(DB::raw("
            select * 
            from report.rsp_salesman_rekapitulasipenjualansales_netto(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $omsetsubcabangid . "',
                '" . $pemgirimsubcabangid . "'
            )"));

        $resultPenjualan = self::salesmanRekapitulasipenjualanreportBuilder($dataPenjualan, $klmpkbrg);
        $resultKoreksiJual = self::salesmanRekapitulasipenjualanreportBuilder($dataKoreksi, $klmpkbrg);
        $resultRetur = self::salesmanRekapitulasipenjualanreportBuilder($dataRetur, $klmpkbrg);
        $resultKoreksiRetur = self::salesmanRekapitulasipenjualanreportBuilder($dataKoreksiRetur, $klmpkbrg);
        $resultNetto = self::salesmanRekapitulasipenjualanreportBuilder($dataNetto, $klmpkbrg);

         // return $result;

        $namasheet = "RINCIAN PENJUALAN PER SALES";
        $namafile = $namasheet . "_" . auth()->user()->id . "_" . date("YmdHis");

        $excels = Excel::create($namafile, function($excel) use ($periode, $resultPenjualan, $resultRetur, $resultKoreksiJual, $resultKoreksiRetur, $resultNetto, $namasheet) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use ($periode, $resultPenjualan, $resultRetur, $resultKoreksiJual, $resultKoreksiRetur, $resultNetto, $namasheet) {
                $sheet->loadView('laporan.salesman.rptrekapitulasipenjualan',
                    array(
                        "periode" => $periode,
                        "tablePenjualan" => $resultPenjualan,
                        "tableKoreksiJual" => $resultKoreksiJual,
                        "tableRetur" => $resultRetur,
                        "tableKoreksiRetur" => $resultKoreksiRetur,
                        "tableNetto" => $resultNetto,
                        )
                    );
            });
        });

        $excels->store('xls', storage_path('excel/exports'));

        $resultPenjualan = str_replace("<td class='replace'></td>", "", $resultPenjualan);
        $resultKoreksiJual = str_replace("<td class='replace'></td>", "", $resultKoreksiJual);
        $resultRetur = str_replace("<td class='replace'></td>", "", $resultRetur);
        $resultKoreksiRetur = str_replace("<td class='replace'></td>", "", $resultKoreksiRetur);
        $resultNetto = str_replace("<td class='replace'></td>", "", $resultNetto);

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
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

        $pdf = PDF::loadView('laporan.salesman.rptrekapitulasipenjualan',
                array(
                        "periode" => $periode,
                        "tablePenjualan" => $resultPenjualan,
                        "tableKoreksiJual" => $resultKoreksiJual,
                        "tableRetur" => $resultRetur,
                        "tableKoreksiRetur" => $resultKoreksiRetur,
                        "tableNetto" => $resultNetto,
                    ),[],$config);

        $pdf->save(storage_path('excel/exports') . "/" . $namafile .'.pdf');

        return view("laporan.tampil")
            ->with("laporan", "rekapitulasisales")
            ->with("periode", $periode)
            ->with("linkExcel", asset('storage/excel/exports/' . $namafile .'.xls'))
            ->with("linkPdf", asset('storage/excel/exports/' . $namafile .'.pdf'))
            ->with("tablePenjualan", $resultPenjualan)
            ->with("tableRetur", $resultRetur)
            ->with("tableKoreksiJual", $resultKoreksiJual)
            ->with("tableKoreksiRetur", $resultKoreksiRetur)
            ->with("tableNetto", $resultNetto);
    }

    public function salesmanRekapitulasipenjualanreportBuilder($data, $klmpkbrg)
    {
        $head = "";
        $body = "";
        $foot = "";

        //buat ngedistinct sales
        $dataArray = array();

        foreach ($data as $key => $value) {
            array_push($dataArray, (array)$value);
        }

        // Untuk mengambil data salesman siapa saja yang ada di laporan
        $salesman = array();

        foreach($dataArray as $value)
        {
            if ($value["kodesales"] <> null || $value["kodesales"] == "")
            {
                if(!in_array($value["kodesales"], $salesman))
                {
                    array_push($salesman, $value['kodesales']);
                }
            }
        }

        //salesman nya di sorting
        $salesman = array_values(array_sort($salesman, function($value){
            return $value;
        }));

        // Perulangan untuk membuat data kolom dan sales (koyo pivot)
        $dataresult = array();
        $dataresultHPP = array();
        foreach ($salesman as $key => $value) {
            $totalOmset = (double)0;
            $totalHPP = (double)0;

            if ($value <> null)
            {
                //Dibuat array kosong dulu, biar bisa di isi data
                array_push($dataresult, array());
                $dataresult[$key]["Kodesales"] = $value;
                $dataresultHPP[$key]["Kodesales"] = $value;
            }

                //Perulangan, jika index header belum ada membuat header, jika sudah ada tidak buat baru, tapi insert data-nya saja
                foreach ($klmpkbrg as $klmpk) {
                    if(!array_key_exists($klmpk["keterangan"] . "Tunai", $dataresult))
                    {
                        $dataresult[$key][$klmpk["keterangan"] . "Tunai"] = 0; 
                        $dataresult[$key][$klmpk["keterangan"] . "Kredit"] = 0; 
                        $dataresultHPP[$key][$klmpk["keterangan"] . "Tunai"] = 0; 
                        $dataresultHPP[$key][$klmpk["keterangan"] . "Kredit"] = 0; 
                    }

                    foreach ($data as $kdata => $vdata) {
                        if ($value == $vdata->kodesales)
                        {
                            if($klmpk["keterangan"] == $vdata->klmpkbarang) 
                            {
                                $dataresult[$key][$klmpk["keterangan"] . "Tunai"] = (double)$vdata->tunai;
                                $dataresult[$key][$klmpk["keterangan"] . "Kredit"] = (double)$vdata->kredit;
                                $dataresultHPP[$key][$klmpk["keterangan"] . "Tunai"] = (double)$vdata->omsettunai;
                                $dataresultHPP[$key][$klmpk["keterangan"] . "Kredit"] = (double)$vdata->omsetkredit;

                                $totalOmset = $totalOmset + $vdata->tunai + $vdata->kredit;
                                $totalHPP = $totalHPP + $vdata->omsettunai + $vdata->omsetkredit;
                            }
                        }
                        
                    }
                }

                //Membuat Total Ke Kanan (kolom Total)
                if(!array_key_exists("TotalOmset", $dataresult))
                    {
                        $dataresult[$key]["totalomset"] = (double)0; 
                        $dataresult[$key]["totalhpp"] = (double)0; 
                        $dataresult[$key]["labakotor"] = (double)0; 
                        $dataresult[$key]["labapersen"] = (double)0; 
                    }
                
                $dataresult[$key]["totalomset"] = $totalOmset; 
                $dataresult[$key]["totalhpp"] = $totalHPP; 
                $dataresult[$key]["labakotor"] = $totalOmset - $totalHPP; 
                $dataresult[$key]["labapersen"] = ($totalOmset > 0 ? ($totalOmset - $totalHPP) / $totalOmset * 100 : 0) ; 

                $dataresultHPP[$key]["totalhpp"] = $totalHPP; 
        }

        //tampilin datanya sekaligus buat total buat bawah, ini yang susah :D
        $bottomSummary = array();
        $bottomSummary["Omzet"] = array();
        $bottomSummary["HPP"] = array();
        $bottomSummary["Laba Kotor"] = array();
        $bottomSummary["Laba (%)"] = array();
        foreach ($dataresult as $key => $value) {
            $body .= "<tr>";
            
            $body .= "<td class='margin'></td>";

            foreach ($value as $k => $v) {
                if($k == "Kodesales")
                    $body .= "<td style='border: 1px solid #000000'>" . $v . "</td>";
                else
                {
                    $body .= "<td style='border: 1px solid #000000' align='right'>" . sprintf('%0.2f', $v) . "</td>";
                    if(!array_key_exists($k, $bottomSummary["Omzet"]))
                        $bottomSummary["Omzet"][$k] = 0;
                    if(!array_key_exists($k, $bottomSummary["HPP"]))
                        $bottomSummary["HPP"][$k] = 0;
                    if(!array_key_exists($k, $bottomSummary["Laba Kotor"]))
                        $bottomSummary["Laba Kotor"][$k] = 0;
                    if(!array_key_exists($k, $bottomSummary["Laba (%)"]))
                        $bottomSummary["Laba (%)"][$k] = 0;

                    $bottomSummary["Omzet"][$k] = (double)$bottomSummary["Omzet"][$k] + (double)$v;

                    if($k != "totalomset" && $k != "labakotor" && $k != "labapersen")
                    {
                        $bottomSummary["HPP"][$k] = (double)$bottomSummary["HPP"][$k] + (double)$dataresultHPP[$key][$k];
                    }
                }
            }

            $body .= "</tr>";
        }

        //hitung laba dan persen footer
        foreach ($bottomSummary["Omzet"] as $key => $value) {
            $bottomSummary["Laba Kotor"][$key] = $bottomSummary["Omzet"][$key] - $bottomSummary["HPP"][$key];
            $bottomSummary["Laba (%)"][$key] = ($bottomSummary["Omzet"][$key] > 0 ? ($bottomSummary["Omzet"][$key] - $bottomSummary["HPP"][$key]) / $bottomSummary["Omzet"][$key] * 100 : 0);
        }


        //tampilkan footer
        foreach ($bottomSummary as $key => $value) {
            $foot .= "<tr>";
            $foot .= "<td class='margin'></td>";
            $foot .= "<td style='border: 1px solid #000000'>" . $key . "</td>";

            $banjelan = "<td style='border: 1px solid #000000'></td>";

            foreach ($bottomSummary[$key] as $k => $v) {

                if($k == "totalomset" ||  $k == "totalhpp" || $k == "labakotor" || $k == "labapersen")
                {
                    if($key == "Omzet" && $k == "totalomset") $foot .= "<td style='border: 1px solid #000000' align='right'>" . sprintf('%0.2f', $v) . "</td>" . $banjelan . $banjelan . $banjelan;
                    if($key == "HPP" && $k == "totalhpp") $foot .=  $banjelan . "<td style='border: 1px solid #000000' align='right'>" . sprintf('%0.2f', $v) . "</td>"  . $banjelan . $banjelan;
                    if($key == "Laba Kotor" && $k == "labakotor") $foot .= $banjelan  . $banjelan . "<td style='border: 1px solid #000000' align='right'>" . sprintf('%0.2f', $v) . "</td>"  . $banjelan;
                    if($key == "Laba (%)" && $k == "labapersen") $foot .= $banjelan . $banjelan . $banjelan . "<td style='border: 1px solid #000000' align='right'>" . sprintf('%0.2f', ($bottomSummary["Omzet"]["totalomset"] > 0 ? $bottomSummary["Laba Kotor"]["labakotor"] / $bottomSummary["Omzet"]["totalomset"] * 100 : 0)) . "</td>";
                }
                else
                    $foot .= "<td style='border: 1px solid #000000' align='right'>" . sprintf('%0.2f', $v) . "</td>";
            }

            $foot .= "</tr>";
        }

        //mulai buat table nya
        $result = "
            <table class='table table-bordered table-striped display mbuhsakarepku table-data' width='100%' style='white-space: nowrap'>
                <thead>
        ";

        $header1 = "<tr>
                        <td width='5px'></td>
                        <td width='30px' rowspan='2' align='center' style='border: 1px solid #000000; font-weight: bold;'>Kode Sales</td>
        ";

        $header2 = "<tr>
                        <td>
                        <td class='replace'></td>
                    ";

        $colspan = 0;

        for ($loop = 0; $loop < count($klmpkbrg); $loop++) {
            $currKeterangan = $klmpkbrg[$loop]["keterangan"];
            
            for ($looper = $loop; $looper < count($klmpkbrg); $looper++)
            {
                $nextKeterangan = $klmpkbrg[$looper]["keterangan"];
                if($currKeterangan == $nextKeterangan)
                {
                    $colspan = $colspan + 2;
                    $klmpkbrg[$looper]["keterangan"] = "";
                }
            }

            if($currKeterangan != "")
            {
                $header1 .= "<td style='border: 1px solid #000000; font-weight: bold;' colspan='" . $colspan . "' align='center' valign='center'>".$currKeterangan."</td>";
            }
            $header2 .= "<td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Tunai</td>
                        <td width='15px' style='border: 1px solid #000000; font-weight: bold;' align='center' valign='center'>Kredit</td>";
            $colspan = 0;
        }

        $header1 .= "
            <td width='20px' rowspan='2' align='center' style='border: 1px solid #000000; font-weight: bold;'>Omzet</td>
            <td width='20px' rowspan='2' align='center' style='border: 1px solid #000000; font-weight: bold;'>HPP</td>
            <td width='20px' rowspan='2' align='center' style='border: 1px solid #000000; font-weight: bold;'>Laba Kotor</td>
            <td width='20px' rowspan='2' align='center' style='border: 1px solid #000000; font-weight: bold;'>Laba (%)</td>
        </tr>";
        $header2 .= "
            <td class='replace'></td>
            <td class='replace'></td>
            <td class='replace'></td>
            <td class='replace'></td>
        </tr>";

        $result .= $header1 . $header2 . "
                </thead>
            <tbody>"
            . $body .
            "</tbody>
            <tfoot>"
            . $foot .
            "</tfoot>
        </table>
        ";

        return $result;
    }

    public function salesmanNotaJual()
    {
        return view('laporan.salesman.frmrptsalesmannotajual');
    }

    public function salesmanNotaJualreport(Request $req)
    {

        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();

        $periode = $req->tglmulai . " s.d. " . $req->tglselesai;

        $pengirimsubcabang = $req->pengirimsubcabang;
         if($pengirimsubcabang == null) 
        { 
            $pengirimsubcabang = 'null'; 
        }

        $namasales= $req->namasales; 
        $kodesales= $req->kodesales; 

        $salesid= $req->salesid; 
        if($salesid == null) 
        { 
            $salesid = 'null'; 
        }

        $wilid = $req->wilid;

        $omsetsubcabang=$req->session()->get('subcabang');


        $data = DB::select(DB::raw("
            select * 
            from report.rsp_Laporan_Salesman_NotaJual(
                '" . $fromdate . "',
                '" . $todate . "',
                 " . $salesid . ",
                '" . $wilid . "',
                " . $omsetsubcabang . ",
                " . $pengirimsubcabang . "
             );"));

        if($wilid=='' || $wilid == null)
        {
            $wilid='Semua';
        }

        $salesman='Semua';

        if($salesid != 'null')
        {

            $salesman=$kodesales." ".$namasales;
        }


        $result = "<table id='tableData' class='table table-bordered table-striped display mbuhsakarepku table-data' width='100%' style='white-space: nowrap'>
                <thead>
        ";

         $header1 = "<tr>
                        <td width='5px' rowspan='2'></td>
                        <td width='5px' rowspan='2' style='border: 1px solid #000000' align='center'>No</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>No Nota</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>Cab 2</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>Tgl Nota</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>Jth Tempo</td>
                        <td width='20px' rowspan='2' style='border: 1px solid #000000' align='center'>Salesman</td>
                        <td width='20px' rowspan='2' style='border: 1px solid #000000' align='center'>Nama Toko</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>Kota</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>WilID</td>
                        <td width='30px' colspan='3' style='border: 1px solid #000000' align='center'>Nota Jual Netto</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>HPPA</td>
                        <td width='10px' rowspan='2' style='border: 1px solid #000000' align='center'>Laba</td>
                    </tr>
                    <tr>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                        <td width='10px' style='border: 1px solid #000000' align='center'>Tunai</td>
                        <td width='10px' style='border: 1px solid #000000' align='center'>Kredit</td>
                        <td width='10px' style='border: 1px solid #000000' align='center'>Jumlah</td>
                        <td class='replace'></td>
                        <td class='replace'></td>
                    </tr>

        ";


        $result .= $header1 . " </thead> ";
        
        $body = "
                <tbody>
        ";

        $no=0;
        $totaltunai=0;
        $totalkredit=0;
        $totalnominal=0;
        $totalhppa=0;
        $totallaba=0;

        foreach ($data as $key => $value) {
            $no++;
            $body .= "<tr>";
            $body .= "<td class='margin'></td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . $no . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='center'>" . $value->nosuratjalan . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='center'>" . $value->kodecabang . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='center'>" . date_format(date_create($value->tglsuratjalan), 'j-M-Y') . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . $value->jkw . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='left'>" . $value->namakaryawan . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='left'>" . $value->namatoko . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='left'>" . $value->kota . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='center'>" . $value->wilid . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . number_format($value->jualtunai) . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . number_format($value->jualkredit) . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . number_format(($value->jualtunai + $value->jualkredit)) . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . number_format($value->jualhppa) . "</td>";
            $body .= "<td style='border: 1px solid #000000' align='right'>" . number_format((($value->jualtunai + $value->jualkredit) - $value->jualhppa)) . "</td>";

            $body .= "</tr>";

            $totaltunai +=$value->jualtunai;
            $totalkredit +=$value->jualkredit;
            $totalnominal +=$value->jualtunai + $value->jualkredit;
            $totalhppa +=$value->jualhppa;
            $totallaba +=($value->jualtunai + $value->jualkredit)-$value->jualhppa;

        }

        $body .= "</tbody>";

        $footer = "<tfoot>
         <tr>
                <td class='margin'></td>
                <td style='border: 1px solid #000000' align='left' colspan='9'>Total : </td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totaltunai) . " </td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totalkredit) . " </td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totalnominal)  . " </td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totalhppa ) . " </td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totallaba) . " </td>
            </tr>
        ";


        if(count($data) >0)
        {
            $footer.="
            <tr>
                <td class='margin'></td>
                <td style='border: 1px solid #000000' align='left' colspan='9'>Persentase : </td>

                <td style='border: 1px solid #000000' align='right'>" . number_format($totaltunai/$totalnominal*100, 2). " %</td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totalkredit/$totalnominal*100, 2). " %</td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totalnominal/$totalnominal*100, 2). " %</td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totalhppa/$totalnominal*100, 2). " %</td>
                <td style='border: 1px solid #000000' align='right'>" . number_format($totallaba/$totalnominal*100, 2). " %</td>

            </tr>
            ";
        }

        $footer .=" </tfoot> ";
       
        $result .= $body . "". $footer . "
            </table>
        ";

        //return $result;

        $namasheet = "Laporan Salesman Nota Jual";
        $namafile = $namasheet . "_".$omsetsubcabang."_". auth()->user()->id . "_" . date('YmdHis');

        // $excels = Excel::create($namafile, function($excel) use ($periode, $result, $namasheet, $salesman, $wilid) {
        //     $excel->setTitle($namasheet);
        //     $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
        //     $excel->setmanager(strtoupper(auth()->user()->username));
        //     $excel->setsubject($namasheet);
        //     $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
        //     $excel->setDescription($namasheet);
        //     $excel->sheet($namasheet, function($sheet) use ($periode, $result, $salesman, $wilid) {
        //         $sheet->loadView('laporan.salesman.rptsalesmannotajual',
        //             array(
        //                 "periode" => $periode,
        //                 "table" => $result,
        //                 "salesman" => $salesman,
        //                 "wilid" => $wilid,
        //                 )
        //             );
        //     });
        // });

        // $excels->store('xls', storage_path('excel/exports'));

        $result = str_replace("<td class='replace'></td>", "", $result);

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
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

        // $pdf = PDF::loadView('laporan.salesman.rptsalesmannotajual',
        //         array(
        //             "periode" => $periode,
        //             "table" => $result,
        //             "salesman" => $salesman,
        //             "wilid" => $wilid,
        //             ),[],$config);

        // $pdf->save(storage_path('excel/exports') . "/" . $namafile .'.pdf');


        return view("laporan.tampil")
            ->with("laporan", "salesmannotajual")
            ->with("periode", $periode)
            ->with("linkExcel", asset('storage/excel/exports/' . $namafile .'.xls'))
            ->with("linkPdf", asset('storage/excel/exports/' . $namafile .'.pdf'))
            ->with("salesman", $salesman)
            ->with("wilid", $wilid)
            ->with("table", $result);
    }

    public function show(Request $req)
    {
        // $fileReport="testa".date_format(date_create(date("Y-m-d H:i:s")), "YmdHis").".xls";
        // // header("Content-type: application/vnd.ms-excel");
        // // header("Content-Disposition: attachment; filename=$fileReport");
        // header("Content-Type: application/pdf"); //check this is the proper header for pdf
        // header("Content-Disposition: attachment; filename='some.pdf';");

        $xlsObject = new PHPExcel;

        $xlsWriter = PHPExcel_IOFactory::createWriter($xlsObject, 'Excel5');
        ob_start();
        $xlsWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        );
    }


    public static function hapus()
    {
        $token = csrf_token();
        $filePath = "storage/excel/exports";
        $files = File::allFiles($filePath);

        foreach ($files as $key => $file) {
            // if(strpos($file->getFilename(), $token) !== false)
            // {
            //     unlink($filePath . "/" . $file->getFilename());
            // }
            $temp = explode("_", $file->getFilename());
            if(count($temp) > 1)
            {
                if(auth()->user()->id == $temp[1])
                {
                    unlink($filePath . "/" . $file->getFilename());
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function index()
    {
        return view('laporan.salesman.frmrpt_perbandingansodonotabo');
    }

    public function indexsodonota()
    {
        return view('laporan.salesman.frmrpt_perbandingansodonotabo');
    }

    public function sodonota(Request $req)
    {
        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();
        $jenis = $req->jenis;
        $kelompokbarang = $req->kelompokbarang;
        $recordownerid = $req->session()->get('subcabang');
        $user = Auth::user()->name;

        if($jenis == 'sales')
        {
            $data = DB::select(DB::raw("
                select * 
                from report.rsp_salsesman_perbandingansodonotabo_sales(
                    '" . $fromdate . "',
                    '" . $todate . "',
                    '" . $kelompokbarang . "',
                     " . $recordownerid . "
                )"));
        }
        else
        {
            $data = DB::select(DB::raw("
                select * 
                from report.rsp_salsesman_perbandingansodonotabo_toko(
                    '" . $fromdate . "',
                    '" . $todate . "',
                    '" . $kelompokbarang . "',
                     " . $recordownerid . "
                ) order by namatoko"));
        }
        

        $sum_nominalso=0; 
        $sum_nominaldo=0; 
        $sum_nominalnota=0; 
        $sum_nominaltolak11=0; 
        $sum_nominaldosunatdr11=0; 
        $sum_rpaccpiutang=0; 
        $sum_totaltolakpiutang=0; 
        $sum_nominalbomurni=0; 
        $sum_nominaldoblmadanota=0; 
        $sum_nominaldoblmadanotasamasekali=0; 
        $sum_tolakfull=0;
        $sum_doblmdiutakatikpiutang=0; 
        $sum_nominaldoblmterpenuhifulladanotanya=0; 
        $sum_nominalpenambahannota=0;
        $sum_nominalblmaccpiutang=0; 
        $sum_nominaldopareto=0; 
        $sum_prs_dopareto=0; 
        $sum_nominaldodisc=0; 
        $sum_prs_dodisc=0;
        $sum_nominalnotapareto=0;
        $sum_nominalnotadisc=0;
        $sum_rpjualnetto=0;
        $sum_rpretur=0;
        
        foreach ($data as $item) {
            $sum_nominalso                              += $item->nominalso; 
            $sum_nominaldo                              += $item->nominaldo; 
            $sum_nominalnota                            += $item->nominalnota; 
            $sum_nominaltolak11                         += $item->nominaltolak11; 
            $sum_nominaldosunatdr11                     += $item->nominaldosunatdr11; 
            $sum_rpaccpiutang                           += $item->rpaccpiutang; 
            $sum_totaltolakpiutang                      += $item->totaltolakpiutang; 
            $sum_nominalbomurni                         += $item->nominalbomurni; 
            $sum_nominaldoblmadanota                    += $item->nominaldoblmadanota; 
            $sum_nominaldoblmadanotasamasekali          += $item->nominaldoblmadanotasamasekali; 
            $sum_tolakfull                              += $item->tolakfull;
            $sum_doblmdiutakatikpiutang                 += $item->doblmdiutakatikpiutang; 
            $sum_nominaldoblmterpenuhifulladanotanya    += $item->nominaldoblmterpenuhifulladanotanya; 
            $sum_nominalpenambahannota                  += $item->nominalpenambahannota;
            $sum_nominalblmaccpiutang                   += $item->nominalblmaccpiutang; 
            $sum_nominaldopareto                        += $item->nominaldopareto; 
            $sum_prs_dopareto                           += $item->prs_dopareto; 
            $sum_nominaldodisc                          += $item->nominaldodisc; 
            $sum_prs_dodisc                             += $item->prs_dodisc;
            $sum_nominalnotapareto                      += $item->nominalnotapareto;
            $sum_nominalnotadisc                        += $item->nominalnotadisc;
            $sum_rpjualnetto                            += $item->rpjualnetto;
            $sum_rpretur                                += $item->rpretur;
        }


        $total = array(
            'nominalso'                             => $sum_nominalso, 
            'nominaldo'                             => $sum_nominaldo, 
            'nominalnota'                           => $sum_nominalnota, 
            'nominaltolak11'                        => $sum_nominaltolak11, 
            'nominaldosunatdr11'                    => $sum_nominaldosunatdr11, 
            'rpaccpiutang'                          => $sum_rpaccpiutang, 
            'totaltolakpiutang'                     => $sum_totaltolakpiutang, 
            'nominalbomurni'                        => $sum_nominalbomurni, 
            'nominaldoblmadanota'                   => $sum_nominaldoblmadanota, 
            'nominaldoblmadanotasamasekali'         => $sum_nominaldoblmadanotasamasekali, 
            'tolakfull'                             => $sum_tolakfull,
            'doblmdiutakatikpiutang'                => $sum_doblmdiutakatikpiutang, 
            'nominaldoblmterpenuhifulladanotanya'   => $sum_nominaldoblmterpenuhifulladanotanya, 
            'nominalpenambahannota'                 => $sum_nominalpenambahannota,
            'nominalblmaccpiutang'                  => $sum_nominalblmaccpiutang, 
            'nominaldopareto'                       => $sum_nominaldopareto, 
            'prs_dopareto'                          => $sum_prs_dopareto, 
            'nominaldodisc'                         => $sum_nominaldodisc, 
            'prs_dodisc'                            => $sum_prs_dodisc,
            'nominalnotapareto'                     => $sum_nominalnotapareto,
            'nominalnotadisc'                       => $sum_nominalnotadisc,
            'rpjualnetto'                           => $sum_rpjualnetto,
            'rpretur'                               => $sum_rpretur
        );

        if($jenis == 'sales')   
        {
            return view("laporan.salesman.rptperbandingansodonotabo_sales")
                ->with("table", $data)
                ->with("total", $total)
                ->with("periode", $req->tglmulai . " s/d " .$req->tglselesai)
                ->with("kelompok", $kelompokbarang)
                ->with("user",$user)
                ;   
        }
        else
        {
            return view("laporan.salesman.rptperbandingansodonotabo_toko")
                ->with("table", $data)
                ->with("total", $total)
                ->with("periode", $req->tglmulai . " s/d " .$req->tglselesai)
                ->with("kelompok", $kelompokbarang)
                ->with("user", $user)
                ;           
        }
    }

    public function sodonotadetail(Request $req)
    {
        $fromdate = Carbon::parse($req->tglmulai)->toDateString();
        $todate = Carbon::parse($req->tglselesai)->toDateString();
        $jenis = $req->jenis;
        $kelompokbarang = $req->kelompokbarang;
        $recordownerid = $req->session()->get('subcabang');
        $kodegudang = SubCabang::find($recordownerid)->kodesubcabang;
        $user = Auth::user()->name;

        $data = DB::select(DB::raw("
            select * 
            from report.rsp_salsesman_perbandingansodonotabo_detail(
                '" . $fromdate . "',
                '" . $todate . "',
                '" . $kelompokbarang . "',
                 " . $recordownerid . "
            )"));

        $sum_qtybomurni=0;
        $sum_rpbomurni=0;   
        $sum_qtysoacc=0; 
        $sum_rpsoacc=0;   
        $sum_notaqtytotal=0; 
        $sum_qtystock=0;
        $sum_rpdoblmproses=0;  
        $sum_notahrgsubtotal=0;

        foreach ($data as $item) {
            $sum_qtybomurni     += $item->qtybomurni;
            $sum_rpbomurni      += $item->rpbomurni;
            $sum_qtysoacc       += $item->qtysoacc;
            $sum_rpsoacc        += $item->rpsoacc;
            $sum_notaqtytotal   += $item->notaqtytotal;
            $sum_qtystock       += $item->qtystock;
            $sum_rpdoblmproses  += $item->rpdoblmproses;
            $sum_notahrgsubtotal+= $item->notahrgsubtotal;
        }


        $total = array(
            'qtybomurni'        => $sum_qtybomurni, 
            'rpbomurni'         => $sum_rpbomurni, 
            'qtysoacc'          => $sum_qtysoacc, 
            'rpsoacc'           => $sum_rpsoacc, 
            'notaqtytotal'      => $sum_notaqtytotal, 
            'qtystock'          => $sum_qtystock, 
            'rpdoblmproses'     => $sum_rpdoblmproses, 
            'notahrgsubtotal'   => $sum_notahrgsubtotal
        );

        return view("laporan.salesman.rptperbandingansodonotabo_detail")
            ->with("table", $data)
            ->with("total", $total)
            ->with("periode", $req->tglmulai . " s/d " .$req->tglselesai)
            ->with("kelompok", $kelompokbarang)
            ->with("kodegudang", $kodegudang)
            ->with("user", $user)
           ;            
    }

    public function indexpenjualanperitem()
    {
        return view('laporan.barang.frmrpt_penjualanperitem');
    }

    public function penjualanperitem(Request $req)
    {
        $fromdate = Carbon::parse($req->fromdate)->toDateString();
        $todate = Carbon::parse($req->todate)->toDateString();

        $salesid                = $req->salesid; 
        if($salesid == null) 
        { 
            $salesid = 'null' ; 
        }
        $kota                   = $req->kota;
        $kelompokbarang         = $req->kelompokbarang;
        if($kelompokbarang == "") 
        { 
            $kelompokbarang = 'null' ; 
        }

        $jenislaporan           = $req->jenislaporan;
        $katpenjualan           = $req->katpenjualan;
        if($katpenjualan == null) 
        { 
            $katpenjualan = 'null' ; 
        }

        if($req->tokopareto == 'tokoall') 
        { 
            $tokopareto = 0 ; 
        }
        else
        {
            $tokopareto = 1 ; 
        }
        
        $tokoid                 = $req->tokoid;
        if($tokoid == null) 
        { 
            $tokoid = 'null' ; 
        }
        $kodebarang             = $req->kodebarang;
        
        $omsetsubcabangid       = $req->omsetsubcabangid;
        if($omsetsubcabangid == "") 
        { 
            $omsetsubcabangid = 'null' ; 
        }
        else
        {
            $omsetsubcabangid = intval($omsetsubcabangid); 
        }

        $namabarang = $req->namabarang;
        if($namabarang != NULL) 
        { 
            $namabarang = "'".$namabarang."'" ; 
        }

        $namatoko               = $req->namatoko;
        if($namatoko != NULL) 
        { 
            $namatoko = "'".$namatoko."'" ; 
        }

        $rekap = $req->rekap ; 

        $recordownerid          = $req->session()->get('subcabang');
        $user                   = Auth::user()->name;
        // dd($tokopareto);
        if($tokopareto==1 &&  $rekap=='rekap')
        {
            $data = DB::select(DB::raw("
                select * 
                from report.rsp_barang_penjualanbarang_peritemrekappareto(
                    '" . $fromdate . "',
                    '" . $todate . "',
                     " . $recordownerid . ",
                     " . $omsetsubcabangid. ",
                    '" . $kodebarang . "',
                    '" . $kota . "',
                     " . $tokoid . ",
                     " . $salesid . ",
                     " . $kelompokbarang . ",
                    '" . $namatoko . "',
                    '" . $namabarang . "',
                     " . $katpenjualan . ",
                    '" . $tokopareto . "',
                    '" . $jenislaporan . "'
                )"));

                $sum_omsetfb2=0;
                $sum_omsetfb4=0;
                $sum_omsetfe2=0;
                $sum_omsetfe4=0;

                foreach ($data as $item) {
                    $sum_omsetfb2                       += $item->omsetfb2; 
                    $sum_omsetfb4                       += $item->omsetfb4; 
                    $sum_omsetfe2                       += $item->omsetfe2; 
                    $sum_omsetfe4                       += $item->omsetfe4; 
                }


                $total = array(
                    'omsetfb2'                          => $sum_omsetfb2, 
                    'omsetfb4'                          => $sum_omsetfb4, 
                    'omsetfe2'                          => $sum_omsetfe2, 
                    'omsetfe4'                          => $sum_omsetfe4 
                );

                return view("laporan.barang.rptpenjualanperitem_pareto")
                    ->with("table", $data)
                    ->with("total", $total)
                    ->with("periode", $req->fromdate . " s/d " .$req->todate)
                    ->with("user",$user)
                    ->with("jenis", $jenislaporan )
                    ;


        }
        else
        {
            if($jenislaporan == 'brutto')
            {
                $data = DB::select(DB::raw("
                    select * 
                    from report.rsp_barang_penjualanbarang_peritem_brutto(
                        '" . $fromdate . "',
                        '" . $todate . "',
                         " . $recordownerid . ",
                         " . $omsetsubcabangid. ",
                        '" . $kodebarang . "',
                        '" . $kota . "',
                         " . $tokoid . ",
                         " . $salesid . ",
                         " . $kelompokbarang . ",
                        '" . $namatoko . "',
                        '" . $namabarang . "',
                         " . $katpenjualan . ",
                        '" . $tokopareto . "'
                    )"));
            }
            else
            {
                $data = DB::select(DB::raw("
                    select * 
                    from report.rsp_barang_penjualanbarang_peritem_netto(
                        '" . $fromdate . "',
                        '" . $todate . "',
                         " . $recordownerid . ",
                         " . $omsetsubcabangid. ",
                        '" . $kodebarang . "',
                        '" . $kota . "',
                         " . $tokoid . ",
                         " . $salesid . ",
                         " . $kelompokbarang . ",
                        '" . $namatoko . "',
                        '" . $namabarang . "',
                         " . $katpenjualan . ",
                        '" . $tokopareto . "'
                    )"));
            }
            
            $sum_jumlahbruto=0;
            $sum_hrgjual=0;
            $sum_hppa=0;
            $sum_rpnet=0;
            $sum_quantity=0;
            
            foreach ($data as $item) {
                $sum_jumlahbruto                        += $item->jumlahbruto; 
                $sum_hrgjual                            += $item->hrgjual; 
                $sum_hppa                               += $item->hppa; 
                $sum_rpnet                              += $item->rpnet; 
                $sum_quantity                           += $item->quantity; 
            }


            $total = array(
                'jumlahbruto'                           => $sum_jumlahbruto, 
                'hrgjual'                               => $sum_hrgjual, 
                'hppa'                                  => $sum_hppa, 
                'rpnet'                                 => $sum_rpnet, 
                'quantity'                              => $sum_quantity
            );

            $namasheet = "Penjualan Per Item";
            $namafile = $namasheet . "_" . auth()->user()->id . "_" . date("YmdHis");
            $periode = $req->fromdate . " s/d " .$req->todate;

            $excels = Excel::create($namafile, function($excel) use ($data, $total, $periode, $user, $jenislaporan, $namasheet) {
                $excel->setTitle($namasheet);
                $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
                $excel->setmanager(strtoupper(auth()->user()->username));
                $excel->setsubject($namasheet);
                $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
                $excel->setDescription($namasheet);
                $excel->sheet($namasheet, function($sheet) use ($data, $total, $periode, $user, $jenislaporan) {
                    $sheet->loadView('laporan.barang.rptpenjualanperitem_excel',
                        array(
                            "table" => $data,
                            "total" => $total,
                            "periode" => $periode,
                            "user" => $user,
                            "jenis" => $jenislaporan,
                            )
                        );
                });
            });

            $excels->store('xls', storage_path('excel/exports'));

            // $config = [
            //     'mode'                 => '',
            //     'format'               => 'A4-L',
            //     'default_font_size'    => '9',
            //     'default_font'         => 'sans-serif',
            //     'margin_left'          => 8,
            //     'margin_right'         => 8,
            //     'margin_top'           => 20,
            //     'margin_bottom'        => 8,
            //     'margin_header'        => 0,
            //     'margin_footer'        => 5,
            //     'orientation'          => 'L',
            //     'title'                => "SAS",
            //     'author'               => '',
            //     'watermark'            => '',
            //     'watermark_font'       => 'sans-serif',
            //     'display_mode'         => 'fullpage',
            //     'watermark_text_alpha' => 0.2,
            //     'show_watermark_image' => true,
            //     'show_watermark'       => true,
            // ];

            // $pdf = PDF::loadView('laporan.barang.rptpenjualanperitem_excel',
            //         array(
            //             "table" => $data,
            //             "total" => $total,
            //             "periode" => $periode,
            //             "user" => $user,
            //             "jenis" => $jenislaporan,
            //             ),[],$config);

            // $pdf->save(storage_path('excel/exports') . "/" . $namafile .'.pdf');

            return view("laporan.barang.rptpenjualanperitem")
                ->with("table", $data)
                ->with("total", $total)
                ->with("periode", $req->fromdate . " s/d " .$req->todate)
                ->with("user",$user)
                ->with("jenis", $jenislaporan)
                ->with("linkExcel", asset('storage/excel/exports/' . $namafile .'.xls'))
                ->with("linkPdf", asset('storage/excel/exports/' . $namafile .'.pdf'))
                ;
        }
    }
}
