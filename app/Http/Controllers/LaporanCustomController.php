<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use XLSXWriter;

use App\Models\SubCabang;
use App\Models\Reportcustom;
use App\Models\CustomreportGroup;

// Traits
use App\Http\Traits\ReportTraits;

class LaporanCustomController extends Controller
{
    use ReportTraits;

    private $tipe_field = [
        0 => 'GENERAL / TEXT',
        1 => 'INTEGER',
        2 => 'NOMINAL / DECIMAL',
        3 => 'PERCENT / %',
        4 => 'DATETIME / d-m-Y h:i:s',
        5 => 'DATE / d-m-Y',
        6 => 'TIME / h:i:s',
    ];

    private $header_format = ['GENERAL'];
    private $header_style  = [
        'font-style' =>'bold',
        'fill'   =>'#dbe5f1',
        'halign' =>'center',
        'border' =>'left,right,top,bottom',
    ];
    private $data_style = [
        'border' =>'left,right,top,bottom',
    ];

    public function index(Request $req)
    {
        $userid = $req->user()->id;
        $group  = DB::select(DB::raw("
            SELECT DISTINCT(rcrg.id) id, rcrg.nama FROM secure.users su
            JOIN secure.roleuser sru ON sru.user_id = su.id
            JOIN secure.customreportgrouprole scr ON scr.role_id = sru.role_id
            JOIN report.customreportgroup rcrg ON scr.customreportgroup_id = rcrg.id
            JOIN report.customreport rcr ON rcr.customreportgroupid = rcrg.id
            WHERE su.id = $userid
            ORDER BY rcrg.nama
        "));

        $report = Reportcustom::select('id','nama','customreportgroupid')->where('aktif',1)->orderBy('nama')->get();
        return view('laporan.custom.index',['group'=>$group,'report'=>$report]);
    }

    public function parameter(Request $req, $id)
    {
        $userid = $req->user()->id;
        // $report = Reportcustom::select('param')->find($id);
        $report = DB::select(DB::raw("
            SELECT DISTINCT(rcr.id) id, rcr.param FROM secure.users su
            JOIN secure.roleuser sru ON sru.user_id = su.id
            JOIN secure.customreportgrouprole scr ON scr.role_id = sru.role_id
            JOIN report.customreportgroup rcrg ON scr.customreportgroup_id = rcrg.id
            JOIN report.customreport rcr ON rcr.customreportgroupid = rcrg.id
            WHERE su.id = $userid AND rcr.id = $id AND rcr.aktif = TRUE
        "));

        $parameter  = json_decode($report[0]->param);

        $result = "";
        $loop = 1;
        if($parameter){
            foreach($parameter as $param) {
                $result .= '<div class="item form-group">';
                $result .= '    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="param_'.$loop.'">'.$param->ketparam.'</label>';
                if($param->tipeparam == 4) {
                $result .= '    <div class="col-md-1 col-sm-1 col-xs-12">';
                $result .= '        <input type="text" id="param_'.$loop.'" name="param_'.$loop.'" class="form-control inputmask" data-inputmask="\'mask\': \'d-m-y h:i:s\'">';
                }elseif($param->tipeparam == 5) {
                $result .= '    <div class="col-md-1 col-sm-1 col-xs-12">';
                $result .= '        <input type="text" id="param_'.$loop.'" name="param_'.$loop.'" class="form-control inputmask" data-inputmask="\'mask\': \'d-m-y\'">';
                }elseif($param->tipeparam == 6) {
                $result .= '    <div class="col-md-1 col-sm-1 col-xs-12">';
                $result .= '        <input type="text" id="param_'.$loop.'" name="param_'.$loop.'" class="form-control inputmask" data-inputmask="\'mask\': \'h:i\'">';
                }elseif($param->tipeparam == 1 || $param->tipeparam == 2 || $param->tipeparam == 3) {
                $result .= '    <div class="col-md-1 col-sm-1 col-xs-12">';
                $result .= '        <input type="number" id="param_'.$loop.'" name="param_'.$loop.'" class="form-control">';
                }else{
                $result .= '    <div class="col-md-3 col-sm-3 col-xs-12">';
                $result .= '        <input type="text" id="param_'.$loop.'" name="param_'.$loop.'" class="form-control">';
                }
                $result .= '    </div>';
                $result .= '</div>';

                $loop++;
            }
        }

        return response()->json($result);
    }

    public function preview(Request $req)
    {
        $report = Reportcustom::find($req->laporan);
        if($report) {
            $report->field = json_decode($report->field);
            $report->param = json_decode($report->param);
        }

        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,strtoupper($report->nama));

        $get = $req->all();
        return view('laporan.custom.preview',['report'=>$report,'get'=>$get]);
    }

    public function previewData(Request $req)
    {
        $limit  = $req->length;
        $offset = $req->start;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->previewQuery($req,$limit,$offset,true);

        return response()->json([
          'draw'            => $req->draw,
          'recordsTotal'    => $data['total'],
          'recordsFiltered' => $data['total'],
          'data'            => $data['result'],
        ]);
    }

    public function previewExcel(Request $req)
    {
        $report = Reportcustom::select(['nama','field'])->find($req->laporan);
        $field  = json_decode($report->field);
        $limit  = $req->length;
        $offset = $req->start;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->previewQuery($req);
        $count = count($data);

        // Xlsx Var
        $subcabang = SubCabang::find($req->session()->get('subcabang'))->kodesubcabang;
        $sheet     = str_slug($report->nama.' '.$subcabang);
        $file      = $sheet."-".uniqid().".xlsx";
        $folder    = storage_path('excel/exports');
        $username  = strtoupper(auth()->user()->username);
        $tanggal   = Carbon::now()->format('d/m/Y');

        $header_data = array_column($field,'ketfield');
        $data_format = array_column($field,'tipefield');
        foreach($data_format as $k=>$v){
            if($v == 2){
                $data_format[$k] = '#,##0';
            }elseif($v == 3){
                $data_format[$k] = '0.00%';
            }else{
                $data_format[$k] = 'GENERAL';
            }
        }

        // Init Xlsx
        $writer = new XLSXWriter();
        $writer->setAuthor($username);
        $writer->setTempDir($folder);

        // Write Sheet Header
        $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true]);
        $writer->writeSheetRow($sheet,[strtoupper($report->nama)],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,['']);

        // Write Table Header
        $writer->writeSheetRow($sheet,$header_data,$this->header_style);

        // Write Table Data
        if($data) {
            foreach ($data as $row) {
                foreach ($field as $f) {
                    $rows_value[$f->namafield] = $row->{$f->namafield};
                }
                $writer->writeSheetRow($sheet,$rows_value,$this->data_style,$data_format);
            }
        }

        // Footer
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    private function previewQuery($req,$limit=0,$offset=0,$wtotal=false)
    {
        // Get
        $subcabang = $req->session()->get('subcabang');
        $laporan   = $req->laporan;
        $userid    = $req->user()->id;

        // Get Laporan
        // $report = Reportcustom::find($laporan);
        $report = DB::select(DB::raw("
            SELECT DISTINCT(rcr.id) id, rcr.nama, rcr.query, rcr.field, rcr.param FROM secure.users su
            JOIN secure.roleuser sru ON sru.user_id = su.id
            JOIN secure.customreportgrouprole scr ON scr.role_id = sru.role_id
            JOIN report.customreportgroup rcrg ON scr.customreportgroup_id = rcrg.id
            JOIN report.customreport rcr ON rcr.customreportgroupid = rcrg.id
            WHERE su.id = $userid AND rcr.id = $laporan AND rcr.aktif = TRUE
        "));

        if(count($report)) {
            $report = $report[0];
        }else{
            if($wtotal){
                return ['total'=>0,'result'=>[]];
            }else{
                return [];
            }
        }

        $field  = json_decode($report->field);
        $param  = json_decode($report->param);

        // Parameter
        $query = $report->query;
        $query = str_replace(";","",$query);
        $query = str_replace("{recordownerid}",$subcabang,$query);

        $n = 1;
        foreach($param as $par) {
            if($par->tipeparam == 4) {
                $param_{$n} = Carbon::parse($req->get('param_'.$n))->toDateString();
            }else{
                $param_{$n} = $req->get('param_'.$n);
            }

            $query = str_replace("{param_".$n."}",$param_{$n},$query);
            $n++;
        }

        // Field
        $field_select = implode(', ',array_column($field, 'namafield'));

        // Test dulu apakah query normal?
        $try = true;
        try {
            $checkquery  = DB::connection('pgsql_report')->select(DB::raw("SELECT COUNT(1) as total FROM ($query) temptable"));
        } catch (\Exception $e) {
            $try = false;
        }

        if($try) {
            // Jika butuh paginasi
            if($wtotal){
                $total  = DB::connection('pgsql_report')->select(DB::raw("SELECT COUNT(1) as total FROM ($query) temptable"));
                $result = DB::connection('pgsql_report')->select(DB::raw("SELECT $field_select FROM ($query) temptable LIMIT $limit OFFSET $offset"));

                foreach($result as $res) {
                    foreach($field as $f){
                        if($f->tipefield == 4) {
                            $res->{$f->namafield} = Carbon::parse($res->{$f->namafield})->format('d-m-Y h:i:s');
                        }elseif($f->tipefield == 5) {
                            $res->{$f->namafield} = Carbon::parse($res->{$f->namafield})->format('d-m-Y');
                        }elseif($f->tipefield == 6) {
                            $res->{$f->namafield} = Carbon::parse($res->{$f->namafield})->format('h:i');
                        }elseif($f->tipefield == 2) {
                            $res->{$f->namafield} = $res->{$f->namafield} ? number_format($res->{$f->namafield},0,",",".") : 0;
                        }
                    }
                }

                return ['total'=>$total[0]->total,'result'=>$result];
            }else{
                $result = DB::connection('pgsql_report')->select(DB::raw($query));

                foreach($result as $res) {
                    foreach($field as $f){
                        if($f->tipefield == 4) {
                            $res->{$f->namafield} = Carbon::parse($res->{$f->namafield})->format('d-m-Y h:i:s');
                        }elseif($f->tipefield == 5) {
                            $res->{$f->namafield} = Carbon::parse($res->{$f->namafield})->format('d-m-Y');
                        }elseif($f->tipefield == 6) {
                            $res->{$f->namafield} = Carbon::parse($res->{$f->namafield})->format('h:i');
                        }elseif($f->tipefield == 3) {
                            $res->{$f->namafield} = ($res->{$f->namafield}) ? $res->{$f->namafield}/100 : 0;
                        }
                    }
                }

                return $result;
           }
        }else{
            if($wtotal){
                return ['total'=>0,'result'=>[]];
            }else{
                return [];
            }
        }
    }

    public function list(Request $req)
    {
        return view('laporan.custom.list');
    }

    public function data(Request $req)
    {
        if(!$req->user()->can('laporan.custom.list')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        $filter_count = 0;
        $empty_filter = 0;
        $columns_real = [
            1 => "report.customreport.nama",
            2 => "report.customreportgroup.nama as namagroup",
            3 => "query",
            4 => "field",
            5 => "lastupdatedby",
            6 => "lastupdatedon",
            7 => "report.customreport.id",
        ];

        $columns = [
            1 => "report.customreport.nama",
            2 => "report.customreportgroup.nama",
            3 => "query",
            4 => "field",
            5 => "lastupdatedby",
            6 => "lastupdatedon",
            7 => "report.customreport.id",
        ];

        if($req->custom_search) {
            foreach($req->custom_search as $search){
                if(empty($search['text'])){
                    $empty_filter++;
                }
            }
        }

        // Models
        $modelObj   = Reportcustom::select($columns_real)->join('report.customreportgroup','report.customreportgroup.id','report.customreport.customreportgroupid');
        $total_data = $modelObj->count();

        if($empty_filter){
            foreach($req->custom_search as $i=>$search){
                if($search['text'] != ''){
                    if($i == 2 || $i == 3){
                        if($search['filter'] == '='){
                            $modelObj->where($columns_real[$i],'ilike','%'.$search['text'].'%');
                        }else{
                            $modelObj->where($columns_real[$i],'not ilike','%'.$search['text'].'%');
                        }
                    }else{
                        $modelObj->where($columns_real[$i],$search['filter'],$search['text']);
                    }
                    $filter_count++;
                }
            }
        }

        if($filter_count > 0){
            $filtered_data = $modelObj->count();
        }else{
            $filtered_data = $total_data;
        }

        if($req->tipe_edit){
            $modelObj->orderBy('report.reportcustom.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $columns)){
                $modelObj->orderByRaw($columns[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get() as $k => $val) {
            $action = null;

            $data[$k] = $val->toArray();
            $data[$k]['lastupdatedon'] = $val->lastupdatedon->format('d-m-Y');
            $data[$k]['field']      = implode(', ',array_column(json_decode($val->field), 'ketfield'));

            if($req->user()->can('laporan.custom.hapus')) {
            $action .= '<form class="formDelete" action="'.route('laporan.custom.hapus',$val->id).'" method="post">';
            }else{
            $action .= '<form class="formDelete" action="#" method="post">';
            }
            $action .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
            if($req->user()->can('laporan.custom.ubah')) {
                $action .= "<a href='".route('laporan.custom.ubah',$val->id)."' class='btn btn-xs btn-primary no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Ubah Query'>Ubah Query</a>";
            }else{
            $action .= '<a href="#" class="btn btn-xs btn-warning no-margin-action" onclick="this.blur(); swal(\'Ups!\', \'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.\',\'error\'); return false;" data-toggle=\'tooltip\' data-placement=\'bottom\' title=\'Ubah Query\'>Ubah Query</a>';
            }
            if($req->user()->can('laporan.custom.hapus')) {
            $action .= '<button type="submit" class="btn btn-xs btn-danger no-margin-action" data-toggle=\'tooltip\' data-placement=\'bottom\' title=\'Hapus Laporan\'><i class=\'fa fa-trash\'></i></button>';
            }else{
            $action .= '<a href="#" class="btn btn-xs btn-danger no-margin-action" onclick="this.blur(); swal(\'Ups!\', \'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.\',\'error\'); return false;" data-toggle=\'tooltip\' data-placement=\'bottom\' title=\'Hapus Laporan\'><i class="fa fa-trash"></i></a>';
            }
            $action .= '</form>';



            $data[$k]['action']   = $action;
            $data[$k]['DT_RowId'] = 'row_'.$val->id;
        }

        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }

    public function form($id=null)
    {
        $group  = CustomreportGroup::select('id','nama')->orderBy('nama')->get();
        $report = Reportcustom::find($id);
        if($report) {
            $report->field = json_decode($report->field);
            $report->param = json_decode($report->param);
        }

        return view('laporan.custom.form',['group'=>$group,'report'=>$report,'tipe_field'=>$this->tipe_field]);
    }

    public function save(Request $req,$id=null)
    {
        // Prepare
        $aktif = ($req->aktif) ? 1 : 0;
        $nama  = $req->nama;
        $query = $req->isi_query;
        $customreportgroupid = $req->customreportgroupid;
        $field = [];
        $param = [];

        foreach ($req->ketfield as $fkey=>$fval) {
            $field[] = ['ketfield'=>$fval,'tipefield'=>$req->tipefield[$fkey],'namafield'=>$req->namafield[$fkey]];
        }
        foreach ($req->ketparam as $pkey=>$pval) {
            $param[] = ['ketparam'=>$pval,'tipeparam'=>$req->tipeparam[$pkey]];
        }

        $field = json_encode($field);
        $param = json_encode($param);

        // Cek Malicious Query
        $cek = false;
        if(preg_match('(insert|update|delete|alter|drop|set|truncate)', strtolower($query)) === 1){
            $cek = true;
        }
        if(preg_match('(insert|update|delete|alter|drop|set|truncate)', strtolower($field)) === 1){
            $cek = true;
        }
        if(preg_match('(insert|update|delete|alter|drop|set|truncate)', strtolower($param)) === 1){
            $cek = true;
        }

        // If exist, return
        if($cek == true) {
            $desc = "Query / Field / Parameter tidak sesuai dengan standar yang diijinkan sistem!";
            if($id) {
                return response()->json(['status'=>false,'desc'=>$desc,'redirect'=>null]);
            }else{
                return response()->json(['status'=>false,'desc'=>$desc,'redirect'=>null]);
            }
        }

        // Create Report
        if($id) {
            $report = Reportcustom::find($id);
            $desc =  'Laporan Custom : '.$report->nama.' Berhasil Diperbarui';
        }else {
            $report = new Reportcustom;
            $desc =  'Laporan Custom Berhasil Dibuat';
        }

        $report->customreportgroupid  = $customreportgroupid;
        $report->nama  = $nama;
        $report->query = $query;
        $report->param = $param;
        $report->field = $field;
        $report->aktif = 1;
        $report->createdby     = strtoupper($req->user()->username);
        $report->lastupdatedby = strtoupper($req->user()->username);
        $report->save();

        return response()->json(['status'=>true,'desc'=>$desc,'redirect'=>route('laporan.custom.list')]);
    }

    public function delete($id)
    {
        $report = Reportcustom::find($id);
        $report->delete();

        return json_encode('success');
    }
}
