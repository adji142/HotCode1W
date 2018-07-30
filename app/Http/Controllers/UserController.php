<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(){
      return view('user.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('user.index')) {
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
            1 => "secure.users.username",
            2 => "secure.users.name",
            3 => "hr.karyawan.namakaryawan",
            4 => "secure.users.active",
            5 => "secure.users.updated_at",
            6 => "secure.users.created_at",
            7 => "secure.users.id",
        ];

        // Models
        $modelObj = User::leftJoin('hr.karyawan', 'secure.users.karyawanid', '=', 'hr.karyawan.id');
        $total_data = $modelObj->count();
        $modelObj->orderBy('secure.users.username');
        if($filter_count > 0){
            $filtered_data = $stop->count();
        }else{
            $filtered_data = $total_data;
        }

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get($columns) as $k => $val) {
            $data[$k] = $val->toArray();
            $data[$k]['active'] = ($val->active) ? "Ya" : "Tidak";
            $data[$k]['updated_at'] = ($val->updated_at) ? Carbon::parse($val->updated_at)->format("d-m-Y") : (($val->created_at) ? Carbon::parse($val->created_at)->format("d-m-Y") : "");

            $action = '';
            if($req->user()->can('user.ubah')){
              $action .= "<a href='".route('user.ubah',$val->id)."' class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Ubah User - F2'><i class='fa fa-pencil'></i></a>";
            }else{
              $action .= "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Ubah User - F2' onclick=\"this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;\"><i class='fa fa-pencil'></i></div>";
            }

            if($req->user()->can('user.hapus')){
              $action .= "<div class='btn btn-xs btn-danger no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Hapus User - Del' onclick='hapus(this)'><i class='fa fa-trash'></i></div>";
            }else{
              $action .= "<div class='btn btn-xs btn-danger no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Hapus User - Del' onclick=\"this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;\"><i class='fa fa-trash'></i></div>";
            }
            $data[$k]['action']   = $action;
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
      return view('user.form');
    }

    public function ubah($id){
      $user = User::find($id);
      return view('user.form', compact('user'));
    }

    public function simpan(Request $request, $id=null){
      // return $request->all();
      $data = [
        'username'   => $request->username,
        'karyawanid' => ($request->karyawanid) ? $request->karyawanid : null,
        'name'       => $request->name,
        'active'     => $request->active? true:false,
      ];

      // Check if user exist
      $checkuser = User::where('username','=',$request->username)->count();

      if ($id) {
        $user = User::find($id);
        if($user->username != $request->username && $checkuser){
          $status = 'Username Sudah Digunakan!';

          return redirect()->route('user.ubah')->with('status', $status);
        }else{
          $data +=['updatedby' => $request->user()->username];
          $user->fill($data)->save();

          return redirect()->route('user.index');
        }
      }elseif(!$checkuser){
        if($request->password != $request->password2){
          $status = 'Harap masukkan password yang sama!';

          return redirect()->route('user.tambah')->with('status', $status)->withInput();
        }
        $data += [
          'password'  => bcrypt($request->password),
          'createdby' => $request->user()->username
        ];
        User::create($data);

        return redirect()->route('user.index');
      }

      $status = 'Username Sudah Digunakan!';
      return redirect()->route('user.tambah')->with('status', $status);
    }

    public function hapus(Request $req){
      if($req->id) {
        $user = User::find($req->id);
        $user->delete();
        return json_encode(true);
      }else{
        return json_encode(false);
      }
    }

    public function changePassword()
    {
      return view('user.password');
    }

    public function changePasswordSave(Request $req)
    {
      $rules = [
          'now_password' => 'required',
          'password'     => 'min:5|confirmed|different:now_password',
          'password_confirmation' => 'required_with:password|min:5',
      ];

      // Validate
      $this->validate($req,$rules);

      // Update
      if(Hash::check($req->now_password,$req->user()->password)) {
        $user = User::find($req->user()->id);
        $user->password = bcrypt($req->password);
        $user->save();

        return redirect()->back()->with('status',['success','Password anda telah diperbarui!']);
      }else{
        return redirect()->back()->with('status',['danger','Password salah.']);
      }
    }
}
