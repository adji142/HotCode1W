<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCabang;
use App\Models\User;

class UserAccessController extends Controller
{
    public function index()
    {
    	$subcabang = SubCabang::orderBy('id','asc')->get();
    	return view('access.index', ['subcabang'=>$subcabang]);
    }

    public function ubah($id)
    {
      	$subcabang 		= SubCabang::find($id);
      	$user 			= User::get();
      	return view('access.form', compact('subcabang','user'));
    }

    public function saveUser(Request $req)
    {
    	$user 		= User::find($req->user);
    	$subcabang 	= SubCabang::find($req->subcabang);
    	if(!$user->hasAkses($subcabang->id)){
    		$user->assignAkses($subcabang->id);
            $status = 'success';
    		$message = $user->name.' diberikan akses ke subcabang '.$subcabang->kodesubcabang.' '.$subcabang->namasubcabang;
    	}else{
    		$status = 'danger';
    		$message = $user->name.' sudah memiliki akses ke subcabang '.$subcabang->kodesubcabang.' '.$subcabang->namasubcabang;
    	}
    	return redirect()->route('access.ubah', $subcabang->id)->with('message', ['status'=>$status,'desc'=>$message]);
    }

    public function deleteUser(Request $req, $subcabangid, $id)
    {
    	$user 		= User::find($id);
    	$subcabang 	= SubCabang::find($subcabangid);
    	$user->revokeAkses($subcabangid);
		$message = 'Akses '.$user->name.' dicabut dari subcabang '.$subcabang->kodesubcabang.' '.$subcabang->namasubcabang;
        $req->session()->flash('message',['status'=>'success','desc'=>$message]);
    	return response()->json([
    		'redirect' => route('access.ubah', $subcabangid),
		]);
    }
}
