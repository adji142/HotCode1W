<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\StockScrab;
use App\Models\StockScrabDetail;
use App\Models\SubCabang;
use App\Models\Karyawan;
use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\MutasiDetail;
use App\Models\ApprovalManagement;
use App\Models\ApprovalManagementDetail;
use App\Models\ApprovalModule;
use EXCEL;
use PDF;
use DB;

class StockScrabController extends Controller
{
    protected $original_column = array(
      1 => "stk.gudangsementara.tgltransaksi",
      2 => "stk.gudangsementara.notransaksi",
      3 => "k.namakaryawan",
      4 => "detail00.status",
      5 => "detail11.status",
      6 => "stk.gudangsementara.tgllink"
    );

    public function index()
    {
     return view('transaksi.stockscrab.index');
    }

    public function getDataHeader(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('scrab.index')) {
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
        $filter_count = 0;
        $empty_filter = 0;

        $columns = array(
            0 => "stk.gudangsementara.tgltransaksi",
            1 => "stk.gudangsementara.notransaksi",
            2 => "k.namakaryawan as namakaryawan",
            3 => "detail00.status as status",
            4 => "detail11.status as status11",
            5 => "stk.gudangsementara.tgllink as tgllink",
            6 => "stk.gudangsementara.tgldocument",
            7 => "stk.gudangsementara.id",
            8 => "stk.gudangsementara.tglajuan",
            9 => "stk.gudangsementara.tglajuanstok11",
            10=> "p.namakaryawan as namapemeriksa",
            11=> "detail00.createdon as tglstatus00",
            12=> "detail00.keterangan as keterangan00",
            13=> "detail11.createdon as tglstatus11",
            14=> "detail11.keterangan as keterangan11",
            15=> "acc.namakaryawan as namakaryawanacc",
            16=> "stk.gudangsementara.lastupdatedby",
            17=> "stk.gudangsementara.lastupdatedon",
           
      );
      for ($i=1; $i < 7; $i++) {
        if(empty($req->custom_search[$i]['text'])){
          $empty_filter++;
        }
      }
      $scrab = StockScrab::selectRaw(collect($columns)->implode(', '))->leftJoin('hr.karyawan as k', 'stk.gudangsementara.karyawanidstock', '=', 'k.id')
      ->leftJoin('hr.karyawan as p', 'stk.gudangsementara.karyawanidpemeriksa', '=', 'p.id')
      ->leftJoin('hr.karyawan as acc', 'stk.gudangsementara.karyawanid11acc', '=', 'acc.id');
      $scrab->leftJoin('secure.approvalmgmtdetail as detail00', function ($leftJoin) {
        $leftJoin->on('detail00.approvalmgmtid', '=', 'stk.gudangsementara.approvalmgmtid00');
        $leftJoin->on('detail00.createdon', '=',DB::raw('(select max(createdon) from secure.approvalmgmtdetail where approvalmgmtid = detail00.approvalmgmtid)'));
      });
      $scrab->leftJoin('secure.approvalmgmtdetail as detail11', function ($leftJoin) {
        $leftJoin->on('detail11.approvalmgmtid', '=', 'stk.gudangsementara.approvalmgmtid11');
        $leftJoin->on('detail11.createdon', '=',DB::raw('(select max(createdon) from secure.approvalmgmtdetail where approvalmgmtid = detail11.approvalmgmtid)'));
      });
      $scrab->where("stk.gudangsementara.recordownerid",$req->session()->get('subcabang'))->where("stk.gudangsementara.tgltransaksi",'>=',Carbon::parse($req->tglmulai))->where("stk.gudangsementara.tgltransaksi",'<=',Carbon::parse($req->tglselesai));
     
      // $dataaa= $scrab->toSql();
      $total_data = $scrab->count();
      if($empty_filter < 6){
        for ($i=1; $i < 7; $i++) {
          if ($req->custom_search[$i]['text']!='') {
            $index = $i;
            if($index == 3 || $index == 4 || $index == 5){
              if($req->custom_search[$i]['filter'] == '='){
                $scrab->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
              }else{
                $scrab->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
              }
            }else{
              $scrab->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
            }
            $filter_count++;
          }
        }
      }
      if($filter_count > 0){
        $filtered_data = $scrab->count();
        $scrab->skip($req->start)->take($req->length);
      }else{
        $filtered_data = $total_data;
        $scrab->skip($req->start)->take($req->length);
      }
      if ($req->tipe_edit) {
        $scrab->orderBy('stk.gudangsementara.lastupdatedon','desc');
      }else {
        if(array_key_exists($req->order[0]['column'], $this->original_column)){
          $scrab->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }
      }
      $data = array();
      foreach ($scrab->get() as $key => $sc) {
        $sc->tgltransaksi   = $sc->tgltransaksi;
        $sc->tglajuan       = $sc->tglajuan;
        $sc->tglajuanstok11 = $sc->tglajuanstok11;
        $sc->tgllink        = $sc->tgllink;
        $sc->lastupdatedon  = $sc->lastupdatedon;
        $sc->createdon      = $sc->createdon;
        $data[$key]         = $sc->toArray();
        $data[$key]['DT_RowId'] = 'gv1_'.$sc->id;

        if(Carbon::parse($sc->tgltransaksi)->toDateString() == Carbon::now()->toDateString()){
            $data[$key]['add'] = 'add';
        }else {
          $data[$key]['add'] = 'Tidak bisa tambah data. Tanggal server tidak sama dengan Tanggal Transaksi. Hubungi Manager Anda.';
        }
       
        $jumlahdetails = count(StockScrab::find($sc->id)->details);
        if($sc->tglajuan == null && $jumlahdetails > 0){
          $data[$key]['ajuanadm'] = 'ajuanadm';
        }elseif($jumlahdetails == 0){
          $data[$key]['ajuanadm'] = 'Data tidak bisa diajukan ke Ka.Adm karena record di Scrab detail kosong. Harap isi terlebih dahulu.';
        }else{
          $data[$key]['ajuanadm'] = 'Data sudah diajukan ke Ka.Adm';
        }

        
        if($sc->status  == 'APPROVED' &&  $sc->tglajuanstok11 == null){
          $data[$key]['ajuanstok'] = 'ajuanstok';
        }
        elseif($sc->status != 'APPROVED'){
            $data[$key]['ajuanstok'] = 'Data tidak bisa diajukan ke stok 11 karena status 00 tidak sama dengan APPROVED';
        }
        elseif($sc->tglajuanstok11 != null){
          $data[$key]['ajuanstok'] = 'Data sudah diajukan ke stok 11';
        }

        if($sc->status11 == 'APPROVED' && $sc->tgllink == null){
          $data[$key]['ajuanmutasi'] = 'ajuanmutasi';
        }
        elseif( $sc->tgllink != null){
           $data[$key]['ajuanmutasi'] = 'Data sudah diajukan ke mutasi';
        }
        else{
           $data[$key]['ajuanmutasi'] = 'Data tidak dapat diajukan ke mutasi karena status 11 tidak sama dengan APPROVED';
        }

        if ($jumlahdetails > 0) {
          $data[$key]['delete'] = 'Tidak bisa hapus record. Sudah ada record di Scrab Detail. Hapus detail terlebih dahulu.';
        }else {
          $data[$key]['delete'] = 'auth';
        }
      }

      return response()->json([
        'draw'            => $req->draw,
        'recordsTotal'    => $total_data,
        'recordsFiltered' => $filtered_data,
        'data'            => $data,
        ]);
    }

    public function getDataDetail(Request $req)
    {
      // gunakan permission dari indexnya aja
      if(!$req->user()->can('scrab.index')) {
        return response()->json([
          'node' => [],
        ]);
      }

      // jika lolos, tampilkan data
      $scrab = StockScrab::find($req->id);
      if(Carbon::parse($scrab->tgltransaksi)->toDateString() == Carbon::now()->toDateString()){
         if ($scrab->tglajuan != null) {
          $delete = 'Tidak bisa hapus record. Tanggal Ka. Adm sudah terisi. Hubungi Manager anda.';
        }else {
          $delete = 'auth';
        } 
      }else {
        $delete = 'Tidak bisa delete data. Tanggal server tidak sama dengan Tanggal Transaksi. Hubungi Manager Anda.';
      }

      $scrab_details = $scrab->details;
      $node = array();
      foreach ($scrab_details as $detail) {
        $barang = $detail->barang;

        $temp = [
          0 => $barang->namabarang,
          1 => $barang->satuan,
          2 => $detail->qtyfinal,
          3 => $detail->keterangan,
          4 => $detail->id,
          5 => $delete,
          6 => $detail->gudangsementaraid,
          'DT_RowId' => 'gv2_'.$detail->id,
        ];
        array_push($node, $temp);
      }
      return response()->json([
        'node' => $node,
      ]);
    }
    public function getScrab(Request $req)
    {
       $scrab = StockScrab::find($req->id);
       return response()->json([
        'scrabid'          => $scrab->id,
        'tgltransaksi'     => $scrab->tgltransaksi,
        'notransaksi'      => $scrab->notransaksi,
        'stafstock'        => $scrab->karyawanidstock==null? null:$scrab->stafstock->namakaryawan,
        'pemeriksa'        => $scrab->karyawanidpemeriksa==null? null:$scrab->pemeriksa->namakaryawan,
        'pemeriksaacc'     => $scrab->karyawanid11acc==null? null:$scrab->pemeriksaacc->namakaryawan,
        'tglajuan'         => $scrab->tglajuan,
        'tglajuanstok11'   => $scrab->tglajuanstok11,
        'tgllink'          => $scrab->tgllink,
        'lastupdatedby'    => $scrab->lastupdatedby,
        'lastupdatedon'    => $scrab->lastupdatedon,
      ]);
    }
    public function getDetail(Request $req)
    {
      $detail = StockScrabDetail::find($req->id);
      return response()->json([
        'scrabdetail'      => $detail->id,
        'barangid'         => $detail->stockid,
        'kodebarang'       => $detail->barang->kodebarang,
        'namabarang'       => $detail->barang->namabarang,
        'satuan'           => $detail->barang->satuan,
        'qtyfinal'         => $detail->qtyfinal,
        'keterangan'       => $detail->keterangan,
        'lastupdatedby'    => $detail->lastupdatedby,
        'lastupdatedon'    => $detail->lastupdatedon,
      ]);
    }
     public function tambah(Request $request)
    {
      if ($request->session()->get('subcabang')) {
        return view('transaksi.stockscrab.form');
      }else {
        return redirect()->route('scrab.index');
      }
    }
    public function simpan(Request $request)
    {
      $data = [
        'recordownerid'         => $request->session()->get('subcabang'),
        'tgltransaksi'          => date('Y-m-d'),
        'notransaksi'           => $this->getNextTransaksiNo($request->subcabang),
        'karyawanidstock'       => $request->karyawanid,
        'karyawanidpemeriksa'   => $request->pemeriksaid,
        'keterangan'            => strtoupper($request->keterangan),
        'createdby'             => strtoupper($request->user()->username),
        'lastupdatedby'         => strtoupper($request->user()->username)
      ];
      $scrab = StockScrab::create($data);
      return response()->json(['success'=>true, 'scrabid'=>$scrab->id, 'notransaksi'=>$scrab->notransaksi, 'tgltransaksi'=>date('d-m-Y',strtotime($scrab->tgltransaksi))]);
    }
     public function simpanDetail(Request $request)
    {
      $detail = [
        'gudangsementaraid'=> $request->scrabid,
        'stockid'          => $request->stockid,
        'qtyfinal'         => $request->qty,
        'keterangan'       => strtoupper($request->keterangandetail),
        'createdby'        => strtoupper($request->user()->username),
        'lastupdatedby'    => strtoupper($request->user()->username)
      ];
      StockScrabDetail::create($detail);
      return response()->json(['success'=>true]);
    }

    public function getNextTransaksiNo($recordownerid){
      $next_no = '';
      $max_pj = StockScrab::where('id', StockScrab::where('recordownerid', $recordownerid)->max('id'))->first();
      if ($max_pj==null) {
        $next_no = '0000000001';
      }elseif (strlen($max_pj->notransaksi)<10) {
        $next_no = '0000000001';
      }elseif ($max_pj->notransaksi == '9999999999') {
        $next_no = '0000000001';
      }else {
        $next_no = substr('0000000000', 0, 10-strlen($max_pj->notransaksi+1)).($max_pj->notransaksi+1);
      }
      return $next_no;
    }

     public function kewenanganScrab(Request $req)
    {
      $lastUserId = auth()->user()->id;
      if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
      {
        if(auth()->user()->can($req->permission) && $req->permission == 'scrab.hapus')
        {
          auth()->loginUsingId($lastUserId);
          if ($req->tipe == 'header') {
            $scrab = StockScrab::find($req->scrabid);
            $scrab->delete();
          }else {
            $detail = StockScrabDetail::find($req->scrabid);
            $detail->delete();
          }
          return response()->json(['success' => true]);
        }
      }
      return response()->json(['success' => false]);
    }
     public function ajuanAdm(Request $request){
        $modulename = 'SCRAB01';
        $data   = $request->data;
        $id     = $data['id'];
        $scrab  = StockScrab::find($id);
        // $mod    = ApprovalModule::where('modulename',$modulename)->first(['id']);
        $mod    = ApprovalModule::where('modulename',$modulename)->first();
        // if(count($mod) > 0){
        //     $module= $mod->id;
        // }
        // else
        // {
        //     $module=0;
        // }
        $scrabdata[] = [
          'Tgl. Transaksi'=> $scrab->tgltransaksi,
          'No. Transaksi' => $scrab->notransaksi,
          'Staff Stock'   => ($scrab->stafstock) ? $scrab->stafstock->namakaryawan : $scrab->karyawanidstock,
        ];

        foreach ($scrab->details as $detail) {
          $scrabdetaildata[] = [
            'Kode Barang' => $detail->barang->kodebarang,
            'Nama Barang' => $detail->barang->namabarang,
            'Satuan'      => $detail->barang->satuan,
            'Qty. Final'  => $detail->qtyfinal,
            'keterangan'  => $detail->keterangan,
          ];
        }

        $header = ["header"=>"Data Scrab","detail"=>"Daftar Scrab Detail"];
        $detail = ["header"=>$scrabdata,"detail"=>$scrabdetaildata];


        $headers = [
          'recordownerid'        => $scrab->recordownerid,
          'moduleid'             => $mod->id,
          'tgltransaksi'         => date('Y-m-d'),
          'keterangan'           => 'PENGAJUAN SCRAB KE KA.ADM 00',
          'datareportingheader'  => json_encode($header),
          'datareportingdetail'  => json_encode($detail),
          'createdby'            => strtoupper($request->user()->username),
          'lastupdatedby'        => strtoupper($request->user()->username),
        ];
        $approval=ApprovalManagement::create($headers);

        $details = [
            'approvalmgmtid'     => $approval->id,
            'status'             => 'PENGAJUAN',
            'username'           => strtoupper($request->user()->username),
            'keterangan'         => 'PENGAJUAN SCRAB KE KA.ADM 00',
            'createdby'          => strtoupper($request->user()->username),
            'lastupdatedby'      => strtoupper($request->user()->username),
        ];
        ApprovalManagementDetail::create($details);

        $scrab->tglajuan         = date('Y-m-d H:i:s');
        $scrab->approvalmgmtid00 = $approval->id;
        $scrab->save();

        // Bikin Daftar Email
        $email_list = ApprovalModule::join('secure.approvalgroup','secure.approvalgroup.approvalmoduleid','=','secure.approvalmgmtmodule.id');
        $email_list->join('secure.roleuser','secure.roleuser.role_id','=','secure.approvalgroup.rolesid');
        $email_list->join('secure.users','secure.roleuser.user_id','=','secure.users.id');
        $email_list->where('modulename',$modulename);
        $email_to = $email_list->get(['email','name']);

        // Isi Email
        $email_title = $mod->emailsubject;
        $email_body  = $mod->emailbody;

        // Isi Detil Data
        $html = '<br/><br/>';
        foreach($header as $k=>$h) {
          // Get Field
          $field = array_keys($detail[$k][0]);
          $html .= '<h4>'.$h.'</h4>';
          $html .= '<div class="table-responsive">';
          $html .= '    <table id="tabel'.$k.'" class="table table-bordered table-striped" width="100%" cellspacing="0">';
          $html .= '        <thead>';
          $html .= '            <tr>';
          foreach ($field as $f) {
            $html .= '<th>'.$f.'</th>';
          }
          $html .= '            </tr>';
          $html .= '        </thead>';
          $html .= '        <tbody>';
          foreach($detail[$k] as $d) {
          $html .= '            <tr>';
            foreach ($field as $f) {
              $html .= '<td>'.$d[$f].'</td>';
            }
          $html .= '            </tr>';
          };
          $html .= '        </tbody>';
          $html .= '    </table>';
          $html .= '</div>';
        }
        $email_body = str_replace("#DETILDATA", $html, $email_body);

        // Kirim Email
        foreach($email_to as $email) {
          if($email->email) {
            // Sesuaikan Nama User
            $email_receipt = $email->email;
            $email_body    = str_replace("#NAMAUSER", $email->name,$email_body);

            Mail::queue('approvalmgmt.email',['email_title'=>$email_title,'email_body'=>$email_body], function ($message) use ($email_title, $email_receipt)
            {
              $message->from('sistem@sas.com', 'Sistem SAS');
              $message->to($email_receipt);
              $message->subject($email_title);
            });
          }
        }
         return response()->json([
          'success' => true,
          'message' => 'Scrab tanggal '.date('d-m-Y',strtotime($scrab->tgltransaksi)).' , no transaksi '.$scrab->notransaksi.' berhasil diajuan ke ka.Adm pada tanggal '.date('d-m-Y').'.Silahkan lihat kolom status di gridView Scrab untuk mengetahui progresnya dan konfirmasi ke Ka.Adm supaya cepat diproses.',
        ]);
    }
    public function ajuanStok(Request $request){
        $modulename = 'SCRAB02';
        $data   = $request->data;
        $id     = $data['id'];
        $scrab  = StockScrab::find($id);
        // $mod    = ApprovalModule::where('modulename',$modulename)->first(['id']);
        $mod    = ApprovalModule::where('modulename',$modulename)->first();
        // if(count($mod) > 0){
        //     $module= $mod->id;
        // }
        // else
        // {
        //     $module= 0;
        // }
        $scrabdata[] = [
          'Tgl. Transaksi'=> $scrab->tgltransaksi,
          'No. Transaksi' => $scrab->notransaksi,
          'Staff Stock'   => $scrab->stafstock->namakaryawan,
        ];

        foreach ($scrab->details as $detail) {
          $scrabdetaildata[] = [
            'Kode Barang'     => $detail->barang->kodebarang,
            'Nama Barang'     => $detail->barang->namabarang,
            'Satuan'          => $detail->barang->satuan,
            'Qty. Final'      => $detail->qtyfinal,
            'keterangan'      => $detail->keterangan,
          ];
        }

        $header = ["header"=>"Data Scrab","detail"=>"Daftar Barang Scrab Detail"];
        $detail = ["header"=>$scrabdata,"detail"=>$scrabdetaildata];

        

        $headers = [
          'recordownerid'        => $scrab->recordownerid,
          'moduleid'             => $mod->id,
          'tgltransaksi'         => date('Y-m-d'),
          'keterangan'           => 'PENGAJUAN SCRAB KE STOK 11',
          'datareportingheader'  => json_encode($header),
          'datareportingdetail'  => json_encode($detail),
          'createdby'            => strtoupper($request->user()->username),
          'lastupdatedby'        => strtoupper($request->user()->username),
        ];
        $approval=ApprovalManagement::create($headers);
        $details = [
            'approvalmgmtid'   => $approval->id,
            'status'           => 'PENGAJUAN',
            'username'         => strtoupper($request->user()->username),
            'keterangan'       => 'PENGAJUAN SCRAB KE STOK 11',
            'createdby'        => strtoupper($request->user()->username),
            'lastupdatedby'    => strtoupper($request->user()->username),
          ];
        ApprovalManagementDetail::create($details);

        $scrab  = StockScrab::find($id);
        $scrab->tglajuanstok11   = date('Y-m-d H:i:s');
        $scrab->approvalmgmtid11 = $approval->id;
        $scrab->save();

        // Bikin Daftar Email
        $email_list = ApprovalModule::join('secure.approvalgroup','secure.approvalgroup.approvalmoduleid','=','secure.approvalmgmtmodule.id');
        $email_list->join('secure.roleuser','secure.roleuser.role_id','=','secure.approvalgroup.rolesid');
        $email_list->join('secure.users','secure.roleuser.user_id','=','secure.users.id');
        $email_list->where('modulename',$modulename);
        $email_to = $email_list->get(['email','name']);

        // Isi Email
        $email_title = $mod->emailsubject;
        $email_body  = $mod->emailbody;

        // Isi Detil Data
        $html = '<br/><br/>';
        foreach($header as $k=>$h) {
          // Get Field
          $field = array_keys($detail[$k][0]);
          $html .= '<h4>'.$h.'</h4>';
          $html .= '<div class="table-responsive">';
          $html .= '    <table id="tabel'.$k.'" class="table table-bordered table-striped" width="100%" cellspacing="0">';
          $html .= '        <thead>';
          $html .= '            <tr>';
          foreach ($field as $f) {
            $html .= '<th>'.$f.'</th>';
          }
          $html .= '            </tr>';
          $html .= '        </thead>';
          $html .= '        <tbody>';
          foreach($detail[$k] as $d) {
          $html .= '            <tr>';
            foreach ($field as $f) {
              $html .= '<td>'.$d[$f].'</td>';
            }
          $html .= '            </tr>';
          };
          $html .= '        </tbody>';
          $html .= '    </table>';
          $html .= '</div>';
        }
        $email_body = str_replace("#DETILDATA", $html, $email_body);

        // Kirim Email
        foreach($email_to as $email) {
          if($email->email) {
            // Sesuaikan Nama User
            $email_receipt = $email->email;
            $email_body    = str_replace("#NAMAUSER", $email->name,$email_body);

            Mail::queue('approvalmgmt.email',['email_title'=>$email_title,'email_body'=>$email_body], function ($message) use ($email_title, $email_receipt)
            {
              $message->from('sistem@sas.com', 'Sistem SAS');
              $message->to($email_receipt);
              $message->subject($email_title);
            });
          }
        }
        return response()->json([
          'success' => true,
          'message' => 'Scrab tanggal '.date('d-m-Y',strtotime($scrab->tgltransaksi)).' , no transaksi '.$scrab->notransaksi.' berhasil diajuan ke 11 Stok pada tanggal '.date('d-m-Y').'.Silahkan lihat kolom status di gridView Scrab untuk mengetahui progresnya dan konfirmasi ke 11 Stok supaya cepat diproses.',
        ]);
    }
    public function ajuanMutasi(Request $request){
        $data       = $request->data;
        $id         = $data['id'];
        $scrab      = StockScrab::find($id);
        $datamutasi = Mutasi::all();
        $nomutasi   = count($datamutasi) + 1;
        $header = [
          'recordownerid'        => $scrab->recordownerid,
          'tglmutasi'            => date('Y-m-d'),
          'nomutasi'             => $nomutasi,
          'keterangan'           => 'SCRAB DENGAN TANGGAL TRANSAKSI '.date('d-m-Y',strtotime($scrab->tgltransaksi)).', NO TRANSAKSI '.$scrab->notransaksi.'',
          'tipemutasi'           => 'T',
          'createdby'            => strtoupper($request->user()->username),
          'lastupdatedby'        => strtoupper($request->user()->username),
        ];
        $mutasi=Mutasi::create($header);
        foreach ($scrab->details as $value) {
          $detail = [
            'mutasiid'         => $mutasi->id,
            'stockid'          => $value->stockid,
            'qtymutasi'        => $value->qtyfinal,
            'createdby'        => strtoupper($request->user()->username),
            'lastupdatedby'    => strtoupper($request->user()->username),
          ];
          MutasiDetail::create($detail);
        }
        $scrab->tgllink   = date('Y-m-d H:i:s');
        $scrab->save();
         return response()->json([
          'success' => true,
          'message' => 'Scrab mutasi tanggal '.date('d-m-Y',strtotime($scrab->tgltransaksi)).' , no transaksi   '.$scrab->notransaksi.' berhasil diajuan pada tanggal '.date('d-m-Y').'.',
        ]);
    }

}
