<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Stock;
use App\Models\StandarStock;
use EXCEL;
use PDF;
use Session;
use DB;

class StandarStockController extends Controller
{
	public function index()
    {
    	return view('transaksi.standarstok.index');
    }

    public function getData(Request $req){
    	// $req->session()->put('bulan', $req->bulan);
     //    $req->session()->put('tahun', $req->tahun);
        Session::set('bulan', $req->bulan);
        Session::set('tahun', '2017');
        $filter_count = 0;
        $empty_filter = 0;
        $tglaktif = '';
        if($req->input('bulan') != ''){
          $tglaktif = $req->input('tahun').'-'.$req->input('bulan').'-01';
        }

        $columns = array(
            0 => "mstr.stock.kodebarang",
            1 => "mstr.stock.namabarang",
            2 => "stk.standarstock.rataratajual",
            3 => "mstr.stock.catatan",
            4 => "stk.standarstock.minindex",
            5 => "stk.standarstock.maxindex",
            6 => "stk.standarstock.keterangan",
            7 => "stk.standarstock.recordownerid",
            8 => "mstr.stock.id",
            9 => "stk.standarstock.tglaktif"
      );

      $standar = Stock::selectRaw(collect($columns)->implode(', '))
      			->join('stk.standarstock', 'mstr.stock.id','=','stk.standarstock.stockid')
            ->where('mstr.stock.statusaktif', 't');
            if($tglaktif != '') {
              $standar->whereRaw("stk.standarstock.tglaktif = '".$tglaktif."'");
            }else{
              $standar->whereRaw('stk.standarstock.id = (SELECT MAX(k."id") FROM stk.standarstock k where k.stockid = "stk"."standarstock"."stockid")');
            }
      // dd($standar->toSql());
      $total_data = $standar->count();
      if($empty_filter < 6){
        for ($i=1; $i < 7; $i++) {
        	if(isset($req->custom_search[$i])){
        		if ($req->custom_search[$i]['text']!='') {
		            $index = $i;
		            if($index == 3 || $index == 4 || $index == 5){
		              if($req->custom_search[$i]['filter'] == '='){
		                $standar->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
		              }else{
		                $standar->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
		              }
		            }else{
		              $standar->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
		            }
		            $filter_count++;
		          }
        	}
        }
      }
      if($filter_count > 0){
        $filtered_data = $standar->count();
        $standar->skip($req->start)->take($req->length);
      }else{
        $filtered_data = $total_data;
        $standar->skip($req->start)->take($req->length);
      }

      // dd($standar->get());
      $data = $standar->orderBy('mstr.stock.namabarang')
              ->get();

      return response()->json([
        'draw'            => $req->draw,
        'recordsTotal'    => $total_data,
        'recordsFiltered' => $filtered_data,
        'data'            => $data,
        'bulan'           => $req->bulan,
        'tahun'           => $req->tahun,
        ]);
    }

    public function checkData(Request $req){
      $date = $req->tgl;
      $standar = StandarStock::where('stk.standarstock.tglaktif', $date);
      $total_data = $standar->count();
      if($total_data > 0){
         return response()->json([
        'jumlah'  => $total_data,
        'status'  => 'false'    
        ]);
      }else{
        return response()->json([
        'jumlah'  => $total_data,
        'status'  => 'true'    
        ]);
      }

    }

    public function insertData(Request $req) {
      ini_set("max_execution_time", (1200)); // 3 hours
      $awal = date('Ym');
      $input = $awal.'01';
      $date = strtotime($input .' -6 months');
      $akhir = date('Ym', $date);
      $taktif = date('Y-m-01');
      DB::beginTransaction();
      try
            {

                $strSQL = "WITH carmap AS (
                          SELECT
                                          Cast('".$taktif."' as date) as tglaktif,
                                          :recordownerid as recordownerid,
                                          ".$req->minindex." as minindex,
                                          ".$req->maxindex." as maxindex,
                                          '".strtoupper(auth()->user()->username)."' as user,
                                          stk.id AS stockid,
                                          stk.kodebarang,
                                          stk.namabarang,
                                          CASE WHEN COALESCE(rpp.qtyhisjual,0) > 0 THEN COALESCE(rpp.qtyhisjual,0) / 6 ELSE 0 END AS qtyhisjual,
                                          COALESCE(rpp.rpj,0) rpj,
                                          COALESCE(rpp.pj,0) pj,
                                          COALESCE(rpp.kpj,0) kpj,
                                          COALESCE(rpp.krpj,0) krpj
                                      FROM mstr.stock stk 
                                      LEFT JOIN LATERAL
                                      (
                                          SELECT
                                              SUM(rpp.qty) AS qtyhisjual,
                                              SUM (CASE WHEN rpp.jenis = '2' THEN rpp.subtotalnettojual ELSE 0 END) AS rpj,
                                              SUM (CASE WHEN rpp.jenis = '1' THEN rpp.subtotalnettojual ELSE 0 END) AS pj,
                                              SUM (CASE WHEN rpp.jenis = '3' THEN rpp.subtotalnettojual ELSE 0 END) AS kpj,
                                              SUM (CASE WHEN rpp.jenis = '4' THEN rpp.subtotalnettojual ELSE 0 END) AS krpj
                                          FROM batch.rekappenjualanperitemmonthly rpp
                                          WHERE rpp.recordownerid = :recordownerid
                                          AND rpp.periode BETWEEN :awal AND :akhir
                                          and rpp.stockid = stk.id 
                                      ) AS rpp ON TRUE

                                      WHERE stk.statusaktif = 't'
                          )
                  INSERT INTO stk.standarstock (\"recordownerid\",\"stockid\",\"tglaktif\",\"rataratajual\",\"minindex\",\"keterangan\",\"maxindex\",\"createdby\",\"lastupdatedby\", \"createdon\", \"lastupdatedon\")
                  SELECT  cm.recordownerid
                          ,cm.stockid
                          ,cm.tglaktif
                          ,cm.qtyhisjual
                          ,cm.minindex
                          , CONCAT ( 'PJ =', cm.pj, ', RPJ =', cm.rpj, ', KPJ = ',cm.kpj , ', KRPJ =', cm.krpj )
                          ,cm.maxindex
                          ,cm.user
                          ,cm.user
                          , CURRENT_TIMESTAMP
                          , CURRENT_TIMESTAMP
                  FROM carmap cm";
                
                

                $query = DB::select($strSQL, array(
                    ':awal' => $akhir,
                    ':akhir' => $awal,
                    ':recordownerid' => $req->session()->get('subcabang')          
                ));
                
                DB::commit();
                 return response()->json([
                  // 'data'  => $data,
                  'status'  => 'true'    
                  ]);
            }catch (Exception $ex){
                DB::rollback();
                return response()->json([
                  'status'  => 'false'    
                  ]);
            }
    }

    public function laporan(Request $req){
      return view('transaksi.standarstok.laporan');
    }

    public function laporanStandarStock(Request $req){
      $tglaktif = date('Y-m-01');
        if($req->input('bulan') != ''){
          $tglaktif = $req->input('tahun').'-'.$req->input('bulan').'-01';
        }

        $columns = array(
            0 => "mstr.stock.kodebarang",
            1 => "mstr.stock.namabarang",
            2 => "stk.standarstock.rataratajual",
            3 => "mstr.stock.catatan",
            4 => "stk.standarstock.minindex",
            5 => "stk.standarstock.maxindex",
            6 => "stk.standarstock.keterangan",
            7 => "stk.standarstock.recordownerid",
            8 => "mstr.stock.id",
            9 => "stk.standarstock.tglaktif"
      );

      $standar = Stock::selectRaw(collect($columns)->implode(', '))
            ->join('stk.standarstock', 'mstr.stock.id','=','stk.standarstock.stockid')
            ->where('mstr.stock.statusaktif', 't');
            if($tglaktif != '') {
              $standar->whereRaw("stk.standarstock.tglaktif = '".$tglaktif."'");
            }else{
              $standar->whereRaw('stk.standarstock.id = (SELECT MAX(k."id") FROM stk.standarstock k where k.stockid = "stk"."standarstock"."stockid")');
            }
      $data = $standar->get();
      $data = json_decode(json_encode($data), true);
      //  Excel::create('Filename', function($excel) use ($data){

      //     $excel->sheet('Sheetname', function($sheet) use ($data) {
      //         // dd($data);
      //         $sheet->fromArray($data);

      //     });

      // })->download('xls');

      header("Content-type: application/vnd-ms-excel");
      header("Content-Disposition: attachment; filename=laporan_standarstock.xls");
      ?>
      <center>
          <h3>LAPORAN STDS</h3>
          <h2>KODE CABANG: 3</h2>
          <h2>TGL. AKTIF: 2017-01-01</h2>
      </center>
          
      <table border="1" cellpadding="5">
        <tr>
          <th>Kd. Barang</th>
          <th>Nama barang</th>
          <th>Rata2 jual</th>
          <th>Min. Index</th>
          <th>Max. Index</th>
          <th>Stock Min</th>
          <th>Stock Max</th>
          <th>Keterangan</th>
        </tr>
        <?php
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        foreach ($data as $key => $value){ // Ambil semua data dari hasil eksekusi $sql
          echo "<tr>";
          echo "<td>".$value['kodebarang']."</td>";
          echo "<td>".$value['namabarang']."</td>";
          echo "<td>".$value['rataratajual']."</td>";
          echo "<td>".$value['minindex']."</td>";
          echo "<td>".$value['maxindex']."</td>";
          echo "<td>".$value['rataratajual'] * $value['minindex']. "</td>";
          echo "<td>".$value['rataratajual'] * $value['maxindex']. "</td>";
          echo "<td>".$value['keterangan']. "</td>";
          echo "</tr>";
          
          $no++; // Tambah 1 setiap kali looping
        }
        ?>
      </table>
  <?php
      exit();
    }

}