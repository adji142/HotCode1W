<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomreportGroup;

class LaporanCustomGroupController extends Controller
{
    public function index(Request $req)
    {
        return view('laporan.custom.group_index');
    }

    public function data(Request $req)
    {
        if(!$req->user()->can('laporan.customgroup.index')) {
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
            1 => "nama",
            2 => "id",
        ];

        if($req->custom_search) {
            foreach($req->custom_search as $search){
                if(empty($search['text'])){
                    $empty_filter++;
                }
            }
        }

        // Models
        $modelObj   = CustomreportGroup::select($columns);
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

        if(array_key_exists($req->order[0]['column'], $columns)){
            $modelObj->orderByRaw($columns[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get() as $k => $val) {
            $data[$k] = $val->toArray();

            $action = null;
            if($req->user()->can('laporan.customgroup.hapus')) {
            $action .= '<form class="formDelete" action="'.route('laporan.customgroup.hapus',$val->id).'" method="post">';
            }else{
            $action .= '<form class="formDelete" action="#" method="post">';
            }
            $action .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
            if($req->user()->can('laporan.customgroup.ubah')) {
                $action .= "<a href='".route('laporan.customgroup.ubah',$val->id)."' class='btn btn-xs btn-primary no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Ubah Group'><i class=\"fa fa-pencil\"></i></a>";
            }else{
            $action .= '<a href="#" class="btn btn-xs btn-warning no-margin-action" onclick="this.blur(); swal(\'Ups!\', \'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.\',\'error\'); return false;" data-toggle=\'tooltip\' data-placement=\'bottom\' title=\'Ubah Group\'><i class="fa fa-pencil"></i></a>';
            }
            if($req->user()->can('laporan.customgroup.hapus')) {
            $action .= '<button type="submit" class="btn btn-xs btn-danger no-margin-action" data-toggle=\'tooltip\' data-placement=\'bottom\' title=\'Hapus Group\'><i class=\'fa fa-trash\'></i></button>';
            }else{
            $action .= '<a href="#" class="btn btn-xs btn-danger no-margin-action" onclick="this.blur(); swal(\'Ups!\', \'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.\',\'error\'); return false;" data-toggle=\'tooltip\' data-placement=\'bottom\' title=\'Hapus Group\'><i class="fa fa-trash"></i></a>';
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
        $group = CustomreportGroup::find($id);
        return view('laporan.custom.group_form',['group'=>$group]);
    }

    public function save(Request $req,$id=null)
    {
        $cek = CustomreportGroup::where('nama','=',$req->nama)->count();

        // If exist, return
        if($cek == true) {
            $desc = "Group Laporan sudah ada!";
            
            if($id) {
                $group = CustomreportGroup::find($id);
                if($group->nama != $group->nama){
                    return response()->json(['status'=>false,'desc'=>$desc,'redirect'=>null]);
                }
            }else{
                return response()->json(['status'=>false,'desc'=>$desc,'redirect'=>null]);
            }
        }

        // Create Report
        if($id) {
            $group = CustomreportGroup::find($id);
            $desc =  'Laporan Custom : '.$group->nama.' Berhasil Diperbarui';
        }else {
            $group = new CustomreportGroup;
            $desc =  'Laporan Custom Berhasil Dibuat';
        }

        $group->nama  = $req->nama;
        $group->save();

        return response()->json(['status'=>true,'desc'=>$desc,'redirect'=>route('laporan.customgroup.index')]);
    }

    public function delete($id)
    {
        $group = CustomreportGroup::find($id);
        $group->delete();

        return json_encode('success');
    }
}
