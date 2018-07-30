<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\dbStorage;
use Carbon\Carbon;
use EXCEL;
use DB;

class StorageController extends Controller
{

	public function index(Request $req) {
		return view('master.storage.index');
	}

	public function files(Request $req, $id) {
		$fls = dbStorage::where("key", $id)->first();
		if (count($fls) > 0 && Storage::disk('local')->exists($fls->path)) {
			return
			response(Storage::disk('local')->get($fls->path))
			->header("Content-Type", "application/octet-stream")
			->header("Content-Disposition", "attachment; filename=" . $fls->nama)
			->header("Content-Transfer-Encoding", "binary")
			->header("Connection", "Keep-Alive")
			->header("Content-Length", Storage::disk('local')->size($fls->path));

		} else return response('Not Found', 404);
	}

	// not yet finished
	public function upload(Request $req) {
		$dat = $req->getAttributes();
		$user = Auth()->User()->username;

		if (!$req->file('file')) {
			return response()->json([
				"result" => false,
				"msg" => "File not found"
			]);
		}

		try {
			$fd = dbStorage::where("id", $dat->id)->first();
			if (count($fd) > 0) {

				$fd->revision += 1;
				$fd->lastupdatedby = $user;
				$fd->lastupdatedon = date("Y-m-d H:i:s");

			} else {
				$fd = new dbStorage();
				$fd->nama = $req->nama;
				if ($req->key && $req->key != "!") $fd->key = $req->key;
				else {
					do {
						$fd->key = $this->strRandom(25);
					} while(dbStorage::where($fd->key)->exists());
				}
			}

			Storage::disk('local')->putFile($fd->path, $req->file("file"));


		} catch (\Exception $ex) {
			return response()->json([
				"result" => false,
				"msg" => $ex->getMessage()
			]);
		}
	}

}
