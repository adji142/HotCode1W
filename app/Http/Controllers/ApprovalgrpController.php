<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalModule;
use App\Models\Role;

class ApprovalgrpController extends Controller
{
    public function index()
    {
        $roleObj = Role::all()->where('groupapps','TRADING');
        return view('approvalgrp.index',['roleObj'=>$roleObj]);
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('approvalgrp.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        // jika lolos, tampilkan data
        $filter_count = 0;
        $empty_filter = 0;
        $columns = [
            1 => "secure.approvalmgmtmodule.modulename",
            2 => "secure.approvalmgmtmodule.emailsubject",
            3 => "secure.approvalmgmtmodule.emailbody",
            4 => "secure.approvalmgmtmodule.keterangan",
        ];

        foreach($req->custom_search as $search){
            if(empty($search['text'])){
                $empty_filter++;
            }
        }

        // Models
        $modelObj   = ApprovalModule::select("*");
        $total_data = $modelObj->count();

        if($empty_filter){
            foreach($req->custom_search as $i=>$search){
                if($search['text'] != ''){
                    if($i == 2 || $i == 3){
                        if($search['filter'] == '='){
                            $modelObj->where($columns[$i],'ilike','%'.$search['text'].'%');
                        }else{
                            $modelObj->where($columns[$i],'not ilike','%'.$search['text'].'%');
                        }
                    }else{
                        $modelObj->where($columns[$i],$search['filter'],$search['text']);
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
            $modelObj->orderBy('secure.approvalmgmtmodule.lastupdatedon','desc');
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
            $val->createdon     = $val->createdon;
            $val->lastupdatedon = $val->lastupdatedon;
            $data[$k] = $val->toArray();
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;
            $data[$k]['action'] = "<div class='btn btn-xs btn-success skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah Role - F1' onclick='tambahrole(this)'><i class='fa fa-plus'></i></div>";
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
        if(!$req->user()->can('approvalgrp.index')) {
            return response()->json(['node' => []]);
        }

        // jika lolos, tampilkan data
        $approval = ApprovalModule::find($req->id);
        $roles = $approval->roles;

        $node = array();
        foreach ($roles as $k => $val) {
            $temp = $val->toArray();
            $temp['action'] = "<div class='btn btn-xs btn-danger skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus Roles - Del' onclick='hapus(this)'><i class='fa fa-trash'></i></div>";
            $temp['DT_RowId'] = 'gv2_'.$val->id;
            array_push($node, $temp);
        }

        return response()->json(['node'=>$node]);
    }

    public function getHeader(Request $req)
    {
        $approval = ApprovalModule::find($req->id);
        $approval->lastupdatedon = $approval->lastupdatedon;
        $approval->createdon     = $approval->createdon;
        $data  = $approval->toArray();
        return response()->json($data);
    }

    public function simpanDetail(Request $req)
    {
        $approval = ApprovalModule::find($req->approvalmoduleid);
        if ($req->tambah_role) {
            $approval->roles()->syncWithoutDetaching([$req->tambah_role]);
        }elseif ($req->hapus_role) {
            $approval->roles()->detach($req->hapus_role);
        }

         return response()->json(['success' => true]);
    }
}
