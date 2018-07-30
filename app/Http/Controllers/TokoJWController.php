<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Toko;
use App\Models\Tokojw;
use App\Models\StatusToko;
use Carbon\Carbon;
use DB;

class TokoJWController extends Controller
{
    public function index()
    {
        return view('master.tokojw.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('tokojw.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        $filter_count = 0;
        $empty_filter = 0;

        // Models
        $columns = [
            1 => "mstr.toko.namatoko",
            2 => "mstr.toko.id",
            3 => "mstr.toko.customwilayah",
            4 => "mstr.toko.alamat",
            5 => "mstr.toko.kota",
            6 => "mstr.toko.daerah",
          ];

        $modelObj = Toko::leftJoin('mstr.tokoaktifpasif', 'mstr.toko.id', '=', 'mstr.tokoaktifpasif.tokoid')->where('mstr.tokoaktifpasif.statusaktif', 't');

        $total_data = $modelObj->count();

        if($empty_filter < 6){
            for ($i=1; $i < 7; $i++) {
              if ($req->custom_search[$i]['text']!='') {
                $index = $i;
                if($index == 2 ){
                    $modelObj->where($columns[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                }else{
                    if($req->custom_search[$i]['filter'] == '='){
                    $modelObj->where(DB::raw($columns[$index] . '::VARCHAR'),'ilike','%'.$req->custom_search[$i]['text'].'%');
                    }else{
                    $modelObj->where(DB::raw($columns[$index] . '::VARCHAR'),'not ilike','%'.$req->custom_search[$i]['text'].'%');
                    }
                  
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

        $modelObj->orderBy('mstr.toko.namatoko','asc')->orderBy('mstr.toko.alamat','asc');

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get($columns) as $k => $val) {
            $data[$k] = $val->toArray();
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;
        }

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function getDataJW(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('tokojw.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        $filter_count = 0;
        $empty_filter = 0;
        $columns = [
            0 => "mstr.tokojw.id",
            1 => "mstr.tokojw.tglaktif",
            2 => "mstr.tokojw.jwkredit",
            3 => "mstr.tokojw.jwkirim",
            4 => "mstr.tokojw.jwsales",
            5 => "mstr.tokojw.catatan",
            6 => "mstr.tokojw.tokoid",
        ];

        // Models
        $modelObj = Tokojw::leftJoin('mstr.toko', 'mstr.toko.id', '=', 'mstr.tokojw.tokoid')
                    ->leftJoin('mstr.tokoaktifpasif', 'mstr.toko.id', '=', 'mstr.tokoaktifpasif.tokoid')
                    ->where('mstr.toko.id', $req->tokoid);
        $total_data = $modelObj->count();
        
        if($filter_count > 0){
            $filtered_data = $stop->count();
        }else{
            $filtered_data = $total_data;
        }

        $modelObj->orderBy('mstr.tokojw.tglaktif','desc');

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get($columns) as $k => $val) {
            $data[$k] = $val->toArray();
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;
        }

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function getDataStatus(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('tokojw.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        $filter_count = 0;
        $empty_filter = 0;
        $columns = [
            0 => "mstr.statustoko.id",
            1 => "mstr.statustoko.tglaktif",
            2 => "mstr.statustoko.status",
            3 => "mstr.statustoko.keterangan",
        ];

        // Models
        $modelObj = StatusToko::leftJoin('mstr.toko', 'mstr.toko.id', '=', 'mstr.statustoko.tokoid')
                    ->leftJoin('mstr.tokoaktifpasif', 'mstr.toko.id', '=', 'mstr.tokoaktifpasif.tokoid')
                    ->where('mstr.toko.id', $req->tokoid);
        $total_data = $modelObj->count();
        
        if($filter_count > 0){
            $filtered_data = $stop->count();
        }else{
            $filtered_data = $total_data;
        }

        $modelObj->orderBy('mstr.statustoko.tglaktif','desc');

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get($columns) as $k => $val) {
            $data[$k] = $val->toArray();
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;
        }

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function tambah(){
        return view('master.tokojw.form');
    }

    public function simpan(Request $req){
        if($req->index == "tokojw"){
            if($req->id){
                $update = Tokojw::where('id',$req->id)->first();
                $update->tglaktif   = Carbon::parse($req->tglaktif);
                $update->jwkredit   = $req->jwkredit;
                
                if($req->jwkirim == ""){
                    $update->jwkirim               = 0;
                } else{
                    $update->jwkirim               = $req->jwkirim;
                }

                if($req->jwsales == ""){
                    $update->jwsales               = 0;
                } else{
                    $update->jwsales               = $req->jwsales;
                }

                $update->catatan    = $req->catatan;
                $update->lastupdatedby = auth()->user()->username;
                $update->lastupdatedon = date('Y-m-d H:i:s');
                $update->update();
            } else{
                $tj = new Tokojw;
                $tj->tokoid         = $req->tokoid;
                $tj->tglaktif       = Carbon::parse($req->tglaktif);
                $tj->omsetmax       = 0;
                $tj->jwkredit       = $req->jwkredit;

                if($req->jwkirim == ""){
                    $tj->jwkirim               = 0;
                } else{
                    $tj->jwkirim               = $req->jwkirim;
                }

                if($req->jwsales == ""){
                    $tj->jwsales               = 0;
                } else{
                    $tj->jwsales               = $req->jwsales;
                }

                $tj->catatan        = $req->catatan;
                $tj->recordownerid  = -99;
                $tj->createdby      = auth()->user()->username;
                $tj->lastupdatedby  = auth()->user()->username;
                $tj->save();
            }    
        } else{
            if($req->id){
                $update = StatusToko::where('id',$req->id)->first();
                $update->tglaktif       = Carbon::parse($req->tglaktifstatus);
                $update->status         = $req->status;
                $update->keterangan     = $req->keterangan;
                $update->lastupdatedby  = auth()->user()->username;
                $update->lastupdatedon  = date('Y-m-d H:i:s');
                $update->update();
            } else{
                $ts = new StatusToko;
                $ts->tokoid         = $req->tokoid;
                $ts->tglaktif       = Carbon::parse($req->tglaktifstatus);
                $ts->status         = $req->status;
                $ts->keterangan     = $req->keterangan;
                $ts->createdby      = auth()->user()->username;
                $ts->lastupdatedby  = auth()->user()->username;
                $ts->save();
            }
        }
        return response()->json(['success' => true]);
    }

    public function cektglaktif(Request $req){
        if($req->index == "jw"){
            $cek = Tokojw::where('tokoid',$req->tokoid)->where('tglaktif',$req->tglaktif)->pluck('tglaktif');
        } else{
            $cek = StatusToko::where('tokoid',$req->tokoid)->where('tglaktif',$req->tglaktif)->pluck('tglaktif');
        }
        return json_encode($cek);
    }
}
