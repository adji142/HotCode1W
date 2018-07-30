<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

use App\Models\ApprovalModule;
use App\Models\ApprovalManagement;
use App\Models\ApprovalManagementDetail;
use App\Models\Role;
use App\Models\AppSetting;


class ApprovalmgmtController extends Controller
{
    private $tipestatus = ['PENGAJUAN','APPROVED','REJECTED'];
    public function index(Request $req)
    {
        $tipemodul = ApprovalModule::join('secure.approvalgroup','secure.approvalgroup.approvalmoduleid','=','secure.approvalmgmtmodule.id');
        $tipemodul->join('secure.roleuser','secure.roleuser.role_id','=','secure.approvalgroup.rolesid');
        $tipemodul->where('secure.roleuser.user_id','=',$req->user()->id);

        return view('approvalmgmt.index',['tipestatus'=>$this->tipestatus,'tipemodul'=>$tipemodul->get()]);
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('approvalmgmt.index')) {
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
        $req->session()->put('tipestatus', $req->tipestatus);
        $req->session()->put('tipemodul', $req->tipemodul);
        $tglmulai   = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();

        $filter_count = 0;
        $empty_filter = 0;
        $columns = [
            1 => "secure.approvalmgmt.createdon",
            2 => "secure.approvalmgmtmodule.modulename",
            3 => "mstr.subcabang.namasubcabang",
            4 => "detail.status",
        ];

        $columns_alias = [
            "secure.approvalmgmt.id",
            "secure.approvalmgmtmodule.modulename",
            "mstr.subcabang.namasubcabang",
            "detail.status",
            // "secure.approvalmgmt.tgltransaksi",
            "secure.approvalmgmt.createdby",
            "secure.approvalmgmt.createdon",
        ];

        foreach($req->custom_search as $search){
            if(empty($search['text'])){
                $empty_filter++;
            }
        }

        // Models
        // $modelObj = ApprovalManagement::select("*");
        $modelObj = ApprovalManagement::leftJoin('mstr.subcabang', 'secure.approvalmgmt.recordownerid', '=', 'mstr.subcabang.id');
        $modelObj->join('secure.approvalmgmtmodule', 'secure.approvalmgmtmodule.id', '=', 'secure.approvalmgmt.moduleid');
        $modelObj->join('secure.approvalgroup','secure.approvalgroup.approvalmoduleid','=','secure.approvalmgmtmodule.id');
        $modelObj->join('secure.roleuser','secure.roleuser.role_id','=','secure.approvalgroup.rolesid');        
        $modelObj->join('secure.approvalmgmtdetail as detail', function ($join) {
            $join->on('detail.approvalmgmtid', '=', 'secure.approvalmgmt.id');
            $join->on('detail.createdon', '=',DB::raw('(select max(createdon) from secure.approvalmgmtdetail where approvalmgmtid = detail.approvalmgmtid)'));
        });
        $modelObj->where("secure.approvalmgmt.recordownerid",$req->session()->get('subcabang'));
        $modelObj->where('secure.roleuser.user_id','=',$req->user()->id);
        if($req->tipestatus) {
        $modelObj->where("detail.status",$req->tipestatus);
        }
        if($req->tipemodul) {
        $modelObj->where("secure.approvalmgmtmodule.modulename",$req->tipemodul);
        }
        $modelObj->whereRaw("DATE(secure.approvalmgmt.createdon) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
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
            $modelObj->orderBy('secure.approvalmgmt.lastupdatedon','desc');
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
        foreach ($modelObj->get($columns_alias) as $k => $val) {
            $val->createdon     = $val->createdon;
            $val->lastupdatedon = $val->lastupdatedon;
            // $val->tgltransaksi  = $val->tgltransaksi;
            $data[$k] = $val->toArray();
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;

            $action = "";
            if($val->status == 'PENGAJUAN') {
                $action .= "<div class='btn btn-xs btn-success skeyF1' onclick='ubahstatusacc(this,".$val->id.")'>ACC - F1</div>";
                $action .= "<div class='btn btn-xs btn-danger skeyF2' onclick='ubahstatustolak(this,".$val->id.")'>TOLAK - F2</div>";
            }else{
                $action .= "<button class='btn btn-xs btn-success' disabled>ACC</button>";
                $action .= "<button class='btn btn-xs btn-danger' disabled>TOLAK</button>";
            }
            $action .= "<div class='btn btn-xs btn-primary skeyF3' onclick='detail(this,".$val->id.")'>PENGAJUAN - F3</div>";
            $data[$k]['action'] = $action;
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
        if(!$req->user()->can('approvalmgmt.index')) {
            return response()->json(['node' => []]);
        }

        // jika lolos, tampilkan data
        $approval = ApprovalManagement::find($req->id);
        $details = $approval->details;

        $node = array();
        foreach ($details as $k => $val) {
            $temp = $val->toArray();
            array_push($node, $temp);
        }

        return response()->json(['node'=>$node]);
    }

    public function getHeader(Request $req)
    {
        $approval = ApprovalManagement::select(["id","moduleid","keterangan","closed","createdby","createdon","lastupdatedby","lastupdatedon"])->find($req->id);
        $data  = $approval->toArray();

        $data['modulename']    = $approval->modules->modulename;
        // $data['tgltransaksi']  = $approval->tgltransaksi;
        $data['lastupdatedon'] = $approval->lastupdatedon;
        $data['createdon']     = $approval->createdon;
        return response()->json($data);
    }

    public function getHeaderData(Request $req)
    {
        $approval = ApprovalManagement::find($req->id);
        $data["datareportingheader"]  = json_decode($approval->datareportingheader);
        $data["datareportingdetail"]  = json_decode($approval->datareportingdetail);
        return response()->json($data);
    }

    public function simpanStatus(Request $req){
        $approval = ApprovalManagement::find($req->id);
        $approval->lastupdatedby = strtoupper($req->user()->username);
        $approval->save();

        $approvaldetail = new ApprovalManagementDetail;
        $approvaldetail->approvalmgmtid = $req->id;
        if($req->tipe == "acc") {
            $approvaldetail->status = "APPROVED";
        }elseif($req->tipe == "tolak") {
            $approvaldetail->status = "REJECTED";
        }else{
            $approvaldetail->status = "PENGAJUAN";
        }
        if($req->keterangan) {
            $approvaldetail->keterangan = strtoupper($req->keterangan);
        }
        $approvaldetail->username       = strtoupper($req->user()->username);
        $approvaldetail->createdby      = strtoupper($req->user()->username);
        $approvaldetail->lastupdatedby  = strtoupper($req->user()->username);
        $approvaldetail->save();


        return response()->json(['success' => true]);
    }

    public function email(Request $req)
    {
        $id = decrypt($req->id);
        $modulename = $req->modulename;

        if($req->tipe == "acc" || ($req->tipe == "tolak" && ($req->keterangan != "" || $req->keterangan != null)))
        {
            $jmlTanggapan = ApprovalManagementDetail::where("approvalmgmtid", $id)
                ->where(function($query){
                    $query->where("status", "APPROVED")
                        ->orWhere("status", "REJECTED");
                })
                ->count();

            if($jmlTanggapan == 0)
            {

                $approval = ApprovalManagement::find($id);
                $approval->lastupdatedby = $req->username;
                $approval->save();

                $approvaldetail = new ApprovalManagementDetail;
                $approvaldetail->approvalmgmtid = $id;
                if($req->tipe == "acc") {
                    $approvaldetail->status = "APPROVED";
                }elseif($req->tipe == "tolak") {
                    $approvaldetail->status = "REJECTED";
                }else{
                    $approvaldetail->status = "PENGAJUAN";
                }
                if($req->keterangan) {
                    $approvaldetail->keterangan = $req->keterangan;
                }
                $approvaldetail->username       = $req->username;
                $approvaldetail->createdby      = $req->username;
                $approvaldetail->lastupdatedby  = $req->username;
                $approvaldetail->save();
            }
        }

        $header = ApprovalManagement::find($id);
        $details = $header->details;

        $cannotApprove = 0;
        $status = "-";

        $approvallist = ApprovalModule::join('secure.approvalgroup','secure.approvalgroup.approvalmoduleid','=','secure.approvalmgmtmodule.id');
        $approvallist->join('secure.roleuser','secure.roleuser.role_id','=','secure.approvalgroup.rolesid');
        $approvallist->join('secure.users','secure.roleuser.user_id','=','secure.users.id');
        $approvallist->where('modulename',$modulename);
        $approvallist = $approvallist->get(['email','name','username'])->toArray();

        //CA username yang diperoleh dari email, merupakan username yang termasuk dalam role approval
        $user = array_search($req->username, array_column($approvallist, 'username'));

        if($user === false)
        {
            $cannotApprove += 1;
        }

        //CA pengajuan sudah di respon atau belum 
        foreach ($details as $detail) {
            if($detail->status == "APPROVED" || $detail->status == "REJECTED")
            {
                $status = $detail->status;
                $cannotApprove += 1;
                break;
            }
        }

        return view("approvalmgmt.email-response")
            ->with('title', $header->keterangan)
            ->with('approvalid', $id)
            ->with('approvalstatus', $status)
            ->with('header', json_decode($header->datareportingheader)->header)
            ->with('headerdata', json_decode($header->datareportingdetail)->header)
            ->with('detail', json_decode($header->datareportingheader)->detail)
            ->with('detaildata', json_decode($header->datareportingdetail)->detail)
            ->with('tglajuan', $header->createdon)
            ->with('username', $req->username)
            ->with('disablebutton', $cannotApprove);
    }
}
