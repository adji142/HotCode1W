<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\APIMasterController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\TokoUpdateHistory;
use App\Models\TosFormToko;
use App\Models\LoginToken;
use App\Models\AppSetting;
use App\Models\SubCabang;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use EXCEL;
use DB;

class TOSController extends APIMasterController //Controller
{

	// automatic reload when new version was released
	protected $ver = 20;

	protected $odat = [
	  'grp' => [
	    [
	      'step' => 1,
	      'name' => 'identity',
	      'title' => 'Identitas',
	      'list' => [
	      	'inputor',
	      	'verified',
	      	'submission_date',
	      	'kode_gudang',
	      	'kode_cabang',
	      	'kode_toko',
	      	'idwil',
	      	'first_name',
	      	'last_name',
	      	'tempat_lahir',
	      	'tanggal_lahir',
	      	'no_ktp'
	      ]
	    ],
	    [
	      'step' => 2,
	      'name' => 'address',
	      'title' => 'Alamat',
	      'list' => [
	      	'alamat',
	      	'provinsi',
	      	'kabupaten_kota',
	      	'kecamatan',
	      	'zip_code',
	      	'country',
	      	'no_hp',
	      	'wa_bb',
	      	'email',
	      	'facebook_twitter'
	      ]
	    ],
	    [
	      'step' => 3,
	      'name' => 'outletinfo',
	      'title' => 'Informasi Outlet',
	      'list' => [
	      	'nama_outlet',
	      	'jenis_toko',
	      	'alamat_dua',
	      	'provinsi_dua',
	      	'kabupaten_kota_dua',
	      	'kecamatan_dua',
	      	'zip_code_dua',
	      	'country_dua',
	      	'tahun_berdiri',
	      	'no_telp_outlet',
	      	'no_fax_outlet',
	      	'status_kepemilikan',
	      	'bentuk_badan_usaha',
	      	'npwp',
	      	'toko_retail',
	      	'jumlah_toko'
	      ]
	    ],
	    [
	      'step' => 4,
	      'name' => 'outletdetail',
	      'title' => 'Detail Outlet',
	      'list' => [
	      	'gudang',
	      	'luas_gudang',
	      	'armada_logistik',
	      	'jumlah_mobil',
	      	'jumlah_motor',
	      	'jumlah_sales',
	      	'wilayah_pemasaran',
	      	'plafon_rp',
	      	'top',
	      	'jenis_pembayaran',
	      	'nama_bank',
	      	'no_rekening_bank',
	      	'no_rekening_bg_ch',
	      	'jaminan_kredit'
	      ]
	    ],
	    [
	      'step' => 5,
	      'name' => 'optionaldata',
	      'title' => 'Data Pendukung',
	      'list' => [
	      	'scan_ktp',
	      	'foto_toko',
	      	'verifikasi_foto_ktp',
	      	'verifikasi_foto_toko',
	      	'keluhan',
	      	'nama_saya',
	      	'keterangan_lain_lain',
	      	'lokasi',
	      	'detected_location'
	      ]
	    ],
	    [
	      'step' => 6,
	      'name' => 'other',
	      'title' => 'Lain-Lain',
	      'list' => '*'
	    ]
	  ],
	  'def' => [
	    'submission_date' => NULL,
	    'kode_gudang' => NULL,
	    'kode_cabang' => NULL,
	    'kode_toko' => NULL,
	    'idwil' => NULL,
	    'first_name' => NULL,
	    'last_name' => NULL,
	    'tempat_lahir' => NULL,
	    'tanggal_lahir' => NULL,
	    'no_ktp' => NULL,
	    'alamat' => NULL,
	    'provinsi' => NULL,
	    'kabupaten_kota' => NULL,
	    'kecamatan' => NULL,
	    'zip_code' => NULL,
	    'country' => NULL,
	    'no_hp' => NULL,
	    'wa_bb' => NULL,
	    'email' => NULL,
	    'facebook_twitter' => NULL,
	    'nama_outlet' => NULL,
	    'jenis_toko' => NULL,
	    'alamat_dua' => NULL,
	    'provinsi_dua' => NULL,
	    'kabupaten_kota_dua' => NULL,
	    'kecamatan_dua' => NULL,
	    'zip_code_dua' => NULL,
	    'country_dua' => NULL,
	    'tahun_berdiri' => NULL,
	    'no_telp_outlet' => NULL,
	    'no_fax_outlet' => NULL,
	    'status_kepemilikan' => NULL,
	    'bentuk_badan_usaha' => NULL,
	    'npwp' => NULL,
	    'toko_retail' => NULL,
	    'jumlah_toko' => NULL,
	    'gudang' => NULL,
	    'luas_gudang' => NULL,
	    'armada_logistik' => NULL,
	    'jumlah_mobil' => NULL,
	    'jumlah_motor' => NULL,
	    'jumlah_sales' => NULL,
	    'wilayah_pemasaran' => NULL,
	    'plafon_rp' => NULL,
	    'top' => NULL,
	    'jenis_pembayaran' => NULL,
	    'nama_bank' => NULL,
	    'no_rekening_bank' => NULL,
	    'no_rekening_bg_ch' => NULL,
	    'jaminan_kredit' => NULL,
	    'verifikasi_foto_ktp' => false,
	    'scan_ktp' => NULL,
	    'verifikasi_foto_toko' => false,
	    'foto_toko' => NULL,
	    'keluhan' => NULL,
	    'nama_saya' => NULL,
	    'keterangan_lain_lain' => NULL,
	    'lokasi' => NULL,
	    'detected_location' => NULL,
	    'order' => NULL,
	    'created_at' => NULL
	  ],
	  'mst' => [
	    'status_kepemilikan' => true,
	    'bentuk_badan_usaha' => true,
	    'detected_location' => false,
	    'jenis_pembayaran' => true,
	    'jaminan_kredit' => true,
	    'nama_outlet' => true,
	    'toko_retail' => true,
	    'jenis_toko' => true,
	    'scan_ktp' => true,
	    'lokasi' => false,
	    'gudang' => true,
	    'top' => true
	  ],
	  'edt' => [
	    'detected_location' => false,
	    'submission_date' => false,
	    'kode_gudang' => false,
	    'lokasi' => false
	  ],
	  'ign' => [
	    'verifikasi_foto_toko' => false,
	    'detected_location' => false,
	    'updated_at' => false,
	    'created_at' => false,
	    'verified' => false,
	    'inputor' => false,
	    'lokasi' => false,
	    'loaded' => false,
	    'order' => false,
	    'key' => false,
	    'ref' => false,
	    'id' => false
	  ],
	  'typ' => [
	    'verifikasi_foto_ktp' => 'bool::success',
	    'verifikasi_foto_toko' => 'bool::success',
	    'jenis_toko' => 'list::["AGEN", "GROSIR", "RETAIL"]',
	    'bentuk_badan_usaha' => 'list::["UD", "PT", "CV", "Belum Ada"]',
	    'status_kepemilikan' => 'list::["MILIK SENDIRI", "MILIK ORANG TUA", "SEWA / KONTRAK"]',
	    'jaminan_kredit' => 'list::["BANK GUARANTIE", "SERTIFIKAT", "BPKB", "TIDAK ADA"]',
	    'jenis_pembayaran' => 'list::["TUNAI", "TRANSFER", "BG / CH", "TIDAK ADA"]',
	    'top' => 'list::["30 HARI", "60 HARI", "90 HARI", "120 HARI", "150 HARI"]',
	    'armada_logistik' => 'list::["MOBIL BOX / PICK UP", "MOTOR"]',
	    'toko_retail' => 'list::["ADA", "TIDAK ADA"]',
	    'gudang' => 'list::["ADA", "TIDAK ADA"]',
	    'country_dua' => 'list::["INDONESIA", "TIMOR TIMUR", "PHILIPINES", "MALAYSIA", "BRUNEI"]',
	    'provinsi_dua' => 'list::!provinsi::kabupaten_kota_dua|kecamatan_dua',
	    'kabupaten_kota_dua' => 'list::!kabupaten::provinsi_dua|kecamatan_dua',
	    'kecamatan_dua' => 'list::!kecamatan::provinsi_dua|kabupaten_kota_dua',
	    'country' => 'list::["INDONESIA", "TIMOR TIMUR", "PHILIPINES", "MALAYSIA", "BRUNEI"]',
	    'provinsi' => 'list::!provinsi::kabupaten_kota|kecamatan',
	    'kabupaten_kota' => 'list::!kabupaten::provinsi|kecamatan',
	    'kecamatan' => 'list::!kecamatan::provinsi|kabupaten_kota',
	    'submission_date' => 'str::date::99/99/9999 99:99::now',
	    'tanggal_lahir' => 'str::date::99/99/9999',
	    'no_rekening_bg_ch' => 'str::numeric',
	    'no_rekening_bank' => 'str::numeric',
	    'tahun_berdiri' => 'str::int::year',
	    'no_telp_outlet' => 'str::numeric',
	    'zip_code_dua' => 'str::numeric',
	    'keterangan_lain_lain' => 'str',
	    'zip_code' => 'str::numeric',
	    'jumlah_mobil' => 'str::int',
	    'jumlah_motor' => 'str::int',
	    'jumlah_sales' => 'str::int',
	    'jumlah_toko' => 'str::int',
	    'plafon_rp' => 'str::int',
	    'no_hp' => 'str::numeric',
	    'email' => 'str::email',
	    'foto_toko' => 'img',
	    'scan_ktp' => 'img'
	  ],
	  'tbs' => [
	    'nama_outlet' => [ 'title' => 'Nama' ],
	    'kode_toko' => [ 'title' => 'Kode Toko' ],
	  	'kode_gudang' => [ 'title' => 'Kode Gudang' ],
	  	'kode_cabang' => [ 'title' => 'Kode Cabang' ],
	    'bentuk_badan_usaha' => [ 'title' => 'Tipe' ],
	    'npwp' => [ 'title' => 'NPWP' ],
	    'tahun_berdiri' => [ 'title' => 'Berdiri' ],
	    'alamat' => [ 'title' => 'Alamat' ],
	    'verified' => [ 'title' => 'Verified' ],
	    'created_at' => [ 'title' => 'Created At' ],
	    'updated_at' => [ 'title' => 'Updated At' ]
	  ]
	];

	protected $toscdirect = [
		"key" => "key",
		"inputor" => "@inputor",
		"submissiondate" => "@submission_date",
		"kodegudang" => "kode_gudang",
		"kodecabang" => "kode_cabang",
		"kodetoko" => "kode_toko",
		"wilid" => "idwil",
		"firstname" => "first_name",
		"lastname" => "last_name",
		"tempatlahir" => "tempat_lahir",
		"tgllahir" => "tgl_lahir",
		"alamat" => "alamat",
		"kecamatan" => "kecamatan",
		"kabupaten" => "kabupaten_kota",
		"provinsi" => "provinsi",
		"kodepos" => "zip_code",
		"country" => "country",
		"nohp" => "no_hp",
		"wa_bb" => "wa_bb",
		"email" => "email",
		"facebook_twitter" => "facebook_twitter",
		"namaoutlet" => "nama_outlet",
		"alamat2" => "alamat_dua",
		"kecamatan2" => "kecamatan_dua",
		"kabupaten2" => "kabupaten_kota_dua",
		"provinsi2" => "provinsi_dua",
		"kodepos2" => "zip_code_dua",
		"country2" => "country_dua",
		"tahunberdiri" => "tahun_berdiri",
		"notelpoutlet" => "no_telp_outlet",
		"nofaxoutlet" => "no_fax_outlet",
		"statuskepemilikan" => "status_kepemilikan",
		"badanusaha" => "bentuk_badan_usaha",
		"jenistoko" => "jenis_toko",
		"gudang" => "gudang",
		"npwp" => "npwp",
		"tokoretail" => "toko_retail",
		"jumlahtoko" => "jumlah_toko",
		"luasgudang" => "luas_gudang",
		"jumlahmobil" => "jumlah_mobil",
		"jumlahmotor" => "jumlah_motor",
		"jumlahsales" => "jumlah_sales",
		"jenispembayaran" => "jenis_pembayaran",
		"norekeningbank" => "no_rekening_bank",
		"norekeningbgch" => "no_rekening_bg_ch",
		"jaminankredit" => "jaminan_kredit",
		"geolocation" => "geolocation",
		"namabank" => "nama_bank",
		"scanktp" => "@scan_ktp",
		"top" => "top",
		"noktp" => "no_ktp",
		"lokasi" => "lokasi",
		"fototoko" => "@foto_toko",
		"namasaya" => "nama_saya",
		"verifiednik" => "verifikasi_foto_ktp",
		"verifiedtoko" => "verifikasi_foto_toko",
		"keteranganlainnya" => "keterangan_lain_lain",
		"firstdate" => "@created_at",
		"createdon" => "!now",
		"createdby" => "!user",
		"lastupdatedon" => "@updated_at",
		"lastupdatedby" => "!user"
	];

	protected $settings = [
		'tokenInterval' => "P1M",
		'tokenName' => "Token",
		'colors' => [
			"#16a085",
			"#27ae60",
			"#2980b9",
			"#8e44ad",
			"#2c3e50",
			"#f39c12",
			"#c0392b"
		]
	];

	// __construct ver APIMasterController
	// don't overrides __construct
	protected function __startup() {
		$this->apis = [
			'login' => [
				'mode' => ['post'],
				'params' => [
					'username',
					'password'
				],
				'output' => [
					'defaultJSON' => [
						"Result" => false
					],
					'errorJSON' => [
						"Result" => false,
						"Msg" => "!%message%"
					]
				],
				'fn' => function (&$out, $req, $args, $config) {
					if (Auth::attempt([
						'username' => $args['params']['username'],
						'password' => $args['params']['password']
					])) {
						if ($this->itCan("tos.config,android")) {
							$ltk = new LoginToken();

							$as = AppSetting::where("keyid", "TOSAPIExp")->first();
							if (count($as) > 0) $this->settings['tokenInterval'] = $as->value;

							$exp = new \DateTime();
							$exp = $exp->add(new \DateInterval($this->settings['tokenInterval']));
							$ltk->expired = $exp->format("Y-m-d H:i:s");

							$ltk->time = date("Y-m-d H:i:s");
							$ltk->contoken = $this->strRandom(50);
							$ltk->token = $this->strRandom(150);
							$ltk->userid = Auth()->User()->id;
							$ltk->api = "ANDROID.TOSFORM";
							$ltk->save();

							$out['Inputor'] = strtoupper(trim(Auth()->User()->username));
							$out['Expired'] = ((int)$exp->getTimestamp() * 1000);
							$out['ContinueToken'] = $ltk->contoken;
							$out['Token'] = $ltk->token;
							$out['Result'] = true;
							Auth::logout();

						} else {
							Auth::logout();
							throw new \Exception("Anda tidak memiliki ijin akses");
						}

					} else throw new \Exception("Username atau password tidak cocok atau belum terdaftar");
				}
			],
			'token' => [
				'mode' => ['post'],
				'params' => [
					'continueToken'
				],
				'output' => [
					'defaultJSON' => [
						"Result" => false
					],
					'errorJSON' => [
						"Result" => false,
						"Msg" => "!%message%"
					]
				],
				'fn' => function (&$out, $req, $args, $config) {
					$logt = LoginToken::where([
						["token", "=", $req->header($this->settings['tokenName'])],
						["contoken", "=", $args['params']['continueToken']],
						["api", "=", "ANDROID.TOSFORM"]
					])->first();

					if (count($logt) > 0) {
						if ($logt->User->can("tos.config,android")) {
							$as = AppSetting::where("keyid", "TOSAPIExp")->first();
							if (count($as) > 0) $this->settings['tokenInterval'] = $as->value;

							$exp = new \DateTime();
							$exp = $exp->add(new \DateInterval($this->settings['tokenInterval']));
							$logt->expired = $exp->format("Y-m-d H:i:s");

							$logt->contoken = $this->strRandom(50);
							$logt->token = $this->strRandom(150);
							$logt->save();

							$out['Result'] = true;
							$out['NewToken'] = $logt->token;
							$out['NewContinueToken'] = $logt->contoken;
							$out['Expired'] = ((int)$exp->getTimestamp() * 1000);
							$out['Inputor'] = strtoupper(trim($logt->User->username));

						} else throw new \Exception("Tidak memiliki ijin akses");
					} else throw new \Exception("Token not valid");
				}
			],
			'lists' => [
				'mode' => ['post'],
				'params' => [ ],
				'output' => [
					'defaultJSON' => [
						"Result" => false,
						"Value" => []
					],
					'errorJSON' => [
						"Result" => false,
						"Msg" => "!%message%"
					]
				],
				'fn' => function (&$out, $req, $args, $config) {
					$logt = $this->checkLoginToken(
						$req->header($this->settings['tokenName']),
						"ANDROID.TOSFORM",
						"tos.config,android"
					);
					if ($logt !== false) {
						$ic = [];
						$cols = [];
						$revc = $this->revToscDirect();
						foreach ($this->odat['tbs'] as $k => $v) {
							if ($k == "verified") continue;
							$cols[$revc[$k]] = $k;
							$ic[] = $revc[$k];
						}

						if (!in_array("id", $ic)) $ic[] = "id";
						if (!in_array("key", $ic)) $ic[] = "key";
						if (!in_array("inputor", $ic)) $ic[] = "inputor";
						if (!in_array("scanktp", $ic)) $ic[] = "CASE WHEN (COALESCE(scanktp, '') = '') THEN 0 ELSE 1 END AS scanktp";
						if (!in_array("verifiednik", $ic)) $ic[] = "verifiednik";
						if (!in_array("kodetoko", $ic)) $ic[] = "kodetoko";
						if (!in_array("noktp", $ic)) $ic[] = "noktp";

						$q = TosFormToko::where("inputor", strtoupper(trim($logt->User->username)));
						if (isset($args['params']['from'])) $q->where([[ DB::raw('firstdate'), '>=', (new \DateTime($args['params']['from']))->format("Y-m-d") ]]);
						if (isset($args['params']['to'])) $q->where([[ DB::raw('firstdate'), '<=', (new \DateTime($args['params']['to']))->format("Y-m-d") ]]);
						$q->selectRaw(join($ic, ", "));
						$q = $q->get();

						$daz = [];
						foreach ($q as $d) {
							$nda = [];
							foreach ($cols as $k => $v) {
								switch ($v) {
									case "updated_at": case "created_at":
										$nda[$v] = ((int)(new \DateTime($d->$k))->getTimestamp() * 1000);
										break;
									default:
										$nda[$v] = $d->$k;
										break;
								}
							}
							$nda['verified'] = ($d->kodetoko && $d->noktp && $d->scanktp && $d->verifiednik ? true : false);
							$nda['key'] = ($d->key ? (is_numeric($d->key) ? null : $d->key) : null);
							$nda['id'] = $d->id;
							$daz[] = $nda;
						}
						$out['Result'] = true;
						$out['Value'] = $daz;

					} else throw new \Exception("Anda tidak memiliki ijin akses");
				}
			],
			'checkin' => [
				'mode' => ['post'],
				'params' => [
					'ids'
				],
				'output' => [
					'defaultJSON' => [
						"Result" => false,
						"Value" => []
					],
					'errorJSON' => [
						"Result" => false,
						"Msg" => "!%message%"
					]
				],
				'fn' => function (&$out, $req, $args, $config) {
					$logt = $this->checkLoginToken(
						$req->header($this->settings['tokenName']),
						"ANDROID.TOSFORM",
						"tos.config,android"
					);
					if ($logt !== false) {
						if (gettype($args['params']['ids']) != "array") $args['params']['ids'] = [$args['params']['ids']];

						$revc = $this->revToscDirect();
						$q = TosFormToko::where("inputor", strtoupper(trim($logt->User->username)));
						$q->whereIn("id", $args['params']['ids']);
						$q = $q->get();

						$daz = [];
						foreach ($q as $d) {
							$nda = [];
							foreach ($revc as $k => $v) {
								switch ($k) {
									case "updated_at": case "created_at":
										$nda[$k] = ((int)(new \DateTime($d->$v))->getTimestamp() * 1000);
										break;
									default:
										$nda[$k] = $d->$v;
										break;
								}
							}
							$dat['verified'] = ($d->kodetoko && $d->noktp && $d->scanktp && $d->verifiednik ? true : false);
							$nda['key'] = ($d->key ? (is_numeric($d->key) ? null : $d->key) : null);
							$nda['id'] = $d->id;
							$daz[$d->id] = $nda;
						}
						$out['Result'] = true;
						$out['Value'] = $daz;

					} else throw new \Exception("Anda tidak memiliki ijin akses");
				}
			],
			'checkout' => [
				'mode' => ['post'],
				'params' => [
					'data'
				],
				'output' => [
					'defaultJSON' => [
						"Result" => false,
						"Value" => []
					],
					'errorJSON' => [
						"Result" => false,
						"Msg" => "!%message%"
					]
				],
				'fn' => function (&$out, $req, $args, $config) {
					$logt = $this->checkLoginToken(
						$req->header($this->settings['tokenName']),
						"ANDROID.TOSFORM",
						"tos.config,android"
					);
					if ($logt !== false) {
						$datas = $args['params']['data'];
						if (gettype($datas) == "array") {
							$keys = array_keys($datas);
							if (gettype($datas[$keys[0]]) != "array") {
								$datas = [ $datas ];
							}

						} else throw new \Exception("Parameter not valid");

						$revc = $this->revToscDirect();
						foreach ($datas as $key => $data) {
							$tfm = null;
							try {
								$mode = 0;
								if (isset($data['id'])) {
									$tfm = TosFormToko::where([
										["inputor", "=", $logt->User->username],
										["id", "=", $data['id']]
									])->first();
								}

								if (count($tfm) <= 0) {
									if (isset($data['kode_toko']) && TosFormToko::where("kodetoko", $data['kode_toko'])->exists())
										throw new \Exception("Kode toko " . $data['kode_toko'] . " sudah dipakai");
									/*
									if (isset($data['no_ktp']) && TosFormToko::where("noktp", $data['no_ktp'])->exists())
										throw new \Exception("No KTP " . $data['no_ktp'] . " sudah dipakai");
									*/

									$tfm = new TosFormToko();
									$mode = 1;

								} else {
									if (isset($data['kode_toko']) && TosFormToko::where([
										["kodetoko", "=", $data['kode_toko']],
										["id", "!=", $tfm->id]
									])->exists()) throw new \Exception("Kode toko " . $data['kode_toko'] . " sudah dipakai");
									/*
									if (isset($data['no_ktp']) && TosFormToko::where([
										["noktp", "=", $data['no_ktp']],
										["id", "!=", $tfm->id]
									])->exists()) throw new \Exception("No KTP " . $data['no_ktp'] . " sudah dipakai");
									*/
								}

								$allow = false;
								switch ($mode) {
									case 0:
										$allow = $logt->User->can("tos.config,update");
										if (!$allow) $allow = $logt->User->can("tos.config,pull");
										break;
									case 1:
										$allow = $logt->User->can("tos.config,new");
										if (!$allow) $allow = $logt->User->can("tos.config,pull");
										break;
								}
								if (!$allow) throw new \Exception("Anda tidak memiliki ijin akses");

								foreach ($this->toscdirect as $k => $v) {
									$val = (isset($data[$v]) ? $data[$v] : null);
									if ($v == "key") $val = ($val ? (is_numeric($val) ? null : $val) : null);
									switch ($v[0]) {
										case "!":
											if ($mode == 0 && ($k == 'createdon' || $k == "createdby")) continue;
											switch (substr($v, 1)) {
												case "now": $val = date("Y-m-d H:i:s"); break;
												case "user": $val = $logt->User->username; break;
											}
											break;
										case "@":
											$val = (isset($data[substr($v, 1)]) ? $data[substr($v, 1)] : null);
											switch (substr($v, 1)) {
												case "inputor":
													if (!$val) $val = $logt->User->username;
													$val = strtoupper($val);
													break;

												case "submission_date":
													$tmp = explode(" ", $val);
													$val = ($mode == 1 ? (date("Y-m-d H:i") . ":00") : null);
													if (count($tmp) >= 2) {
														$tmp2 = explode("/", $tmp[0]);
														$tmp3 = explode(":", $tmp[1]);
														if (count($tmp2) >= 3 && count($tmp3) >= 2) {
															$val = $tmp2[2] . "-" . $tmp2[1] . "-" . $tmp2[0] . " " . $tmp3[0] . ":" . $tmp3[1] . ":00";
														}
													}
													break;

												case "created_at":
													$tmp = floatval($val);
													$val = ($mode == 1 ? date("Y-m-d H:i:s") : null);
													if ($tmp > 0) $val = date("Y-m-d H:i:s", ($tmp / 1000));
													break;

												case "updated_at":
													$tmp = floatval($val);
													$val = date("Y-m-d H:i:s");
													if ($tmp > 0) $val = date("Y-m-d H:i:s", ($tmp / 1000));
													break;

												case "scan_ktp": case "foto_toko":
													if ($val) {
														if (strlen($val) <= 500 && base64_decode($val, true) === false) {
															try {
																$tmp = file_get_contents($val);
																$val = base64_encode($tmp);

															} catch (\Exception $ex) { $val = null; }
														}

													} else $val = null;
													break;
											}
											break;
									}
									if ($val !== null) $tfm->$k = $val;
								}

								$tfm->inputor = $logt->User->username;
								$tfm->save();
								$out['Value'][$key] = [
									"ID" => $tfm->id,
									"Status" => true
								];

							} catch (\Exception $ex) {
								$out['Value'][$key] = [
									"Status" => false,
									"Msg" => $ex->getMessage()
								];
							}
						}
						$out['Result'] = true;

					} else throw new \Exception("Anda tidak memiliki ijin akses");
				}
			]
		];
	}

	private function checkLoginToken($token, $api, $slug) {
		$logt = LoginToken::where([
			["token", "=", $token],
			["api", "=", $api],
			["expired", ">=", date("Y-m-d H:i:s")]

		])->first();

		if (count($logt) > 0) {
			$usr = $logt->User;
			if (count($usr) > 0 && $usr->can($slug)) return $logt;
			return false;
		} else return false;
	}

	private function resolveFileName($nm) {
		$lst = [
			":", ";", "\"", "'", "\\", "*", "[", "]", "|", "=", ",", "#", "!"
		];
		foreach ($lst as $l) $nm = str_replace($l, "", $nm);
		return $nm;
	}

	private function itCan($slug) {
		return (
			Auth()->User()->can($slug) ? true : false
		);
	}

	private function revToscDirect() {
		$cols = [];
		foreach ($this->toscdirect as $k => $v) {
			switch ($v[0]) {
				case "!": continue;
				case "@":
					$cols[substr($v, 1)] = $k;
					break;
				default:
					$cols[$v] = $k;
					break;
			}
		}
		return $cols;
	}

	public function index(Request $req) {
		if ($this->itCan("tos.index")) {
			$dat = [
				"kodecabang" => 0,
				"kodegudang" => "",
				"version" => $this->ver,
				"fineToken" => $this->strRandom(25),
				"color" => $this->settings["colors"][rand(0, (count($this->settings["colors"]) - 1))]
			];
			if (is_numeric($dat["fineToken"][0])) $dat["fineToken"] = "x" . $dat["fineToken"];

			if (session("subcabang")) {
				$sc = SubCabang::where("id", session("subcabang"))->first();
				if (count($sc) > 0) {
					$dat['kodegudang'] = $sc->kodesubcabang;

					$sc = $sc->cabang;
					if (count($sc) > 0) $dat['kodecabang'] = $sc->kodecabang;
				}
			}
			$gmKey = env("DATACOLLECTOR_GMAPS_KEY", "");
			return view('master.tosform.index', compact("gmKey", "dat"));

		} else return response(null, 403);
	}

	public function fbauth(Request $req) {
		if ($this->itCan("tos.fbauth")) {
			$auth = [
				'apiKey' => env("FIREBASE_DATACOLLECTOR_KEY", ""),
				'authDomain' => env("FIREBASE_DATACOLLECTOR_PROJECT", "") . ".firebaseapp.com",
				'databaseURL' => env("FIREBASE_DATACOLLECTOR_DB", ""),
				'storageBucket' => env("FIREBASE_DATACOLLECTOR_BUCKET", "")
			];
			return base64_encode(json_encode($auth));

		} else return response(null, 403);
	}

	public function config(Request $req, $name) {
		$dat = [];
		switch ($name) {
			case "odat":
				$dat = $this->odat;
				if (!$this->itCan("tos.config,verify")) {
					$dat["ign"]['verifikasi_foto_ktp'] = false;
				}
				break;

			case "inputor":
				$lst = TosFormToko::selectRaw("distinct inputor")->get();
				foreach ($lst as $l) $dat[] = strtoupper($l->inputor);
				break;

			case "index":
				$ic = [];
				$cols = [];
				$revc = $this->revToscDirect();
				foreach ($this->odat['tbs'] as $k => $v) {
					if ($k == "verified") continue;
					$cols[$revc[$k]] = $k;
					$ic[] = $revc[$k];
				}

				if (!in_array("id", $ic)) $ic[] = "id";
				if (!in_array("key", $ic)) $ic[] = "key";
				if (!in_array("inputor", $ic)) $ic[] = "inputor";
				if (!in_array("scanktp", $ic)) $ic[] = "CASE WHEN (COALESCE(scanktp, '') = '') THEN 0 ELSE 1 END AS scanktp";
				if (!in_array("verifiednik", $ic)) $ic[] = "verifiednik";
				if (!in_array("kodetoko", $ic)) $ic[] = "kodetoko";
				if (!in_array("noktp", $ic)) $ic[] = "noktp";

				$daz = [];
				$lst = TosFormToko::selectRaw(join($ic, ", "))->get();
				foreach ($lst as $d) {
					$d->inputor = strtoupper($d->inputor);
					if (!isset($daz[$d->inputor])) $daz[$d->inputor] = [];

					$nda = [];
					foreach ($cols as $k => $v) {
						switch ($v) {
							case "updated_at": case "created_at":
								$nda[$v] = ((new \DateTime($d->$k))->getTimestamp() * 1000);
								break;
							default:
								$nda[$v] = $d->$k;
								break;
						}
					}
					$nda['id'] = $d->id;
					$nda['verified'] = (trim($d->kodetoko) && trim($d->noktp) && $d->scanktp && $d->verifiednik ? true : false);
					$daz[$d->inputor][(empty($d->key) ? $d->id : $d->key)] = $nda;
				}
				$dat['result'] = true;
				$dat['data'] = $daz;
				break;

			case "data":
				$arg = $req->all();

				$allow = [ 'inputor' ];
				$allow = $this->checkFields($arg, $allow);
				if (count($allow) > 0) {
					return response()->json([
						"result" => false,
						"msg" => "Parameter " . join($this->listFields($allow), ", ") . " is not setted"
					]);
				}

				$q = TosFormToko::where("inputor", $arg['inputor']);
				if (isset($arg['id'])) $q->where([["id", "=", $arg['id']]]);
				else if (isset($arg['key'])) $q->where([["key", "=", $arg['key']]]);
				if (isset($arg['from'])) $q->where([["lastupdatedon", ">=", $arg['from']]]);
				if (isset($arg['to'])) $q->where([["lastupdatedon", "<=", $arg['to']]]);

				$revc = $this->revToscDirect();
				$daz = $q->get();
				if (count($daz) == 1) {
					foreach ($this->odat['def'] as $k => $v) {
						if (isset($revc[$k])) {
							$k2 = $revc[$k];
							switch ($k) {
								case "verifikasi_foto_ktp": case "verifikasi_foto_toko":
									$dat['data'][$k] = ($daz[0]->$k2 ? true : false);
									break;
								default:
									$dat['data'][$k] = $daz[0]->$k2;
									break;
							}
						}
					}
					$dat['data']['verified'] = ($daz[0]->kodetoko && $daz[0]->noktp && $daz[0]->scanktp && $daz[0]->verifiednik ? true : false);
					$dat['data']['key'] = ($daz[0]->key ? (is_numeric($daz[0]->key) ? null : $daz[0]->key) : null);
					$dat['data']['inputor'] = $daz[0]->inputor;
					$nda['data']['id'] = $daz[0]->id;
				}

				$dat['result'] = true;
				break;

			case "set":
				ini_set("max_execution_time", (60 * 60 * 3)); // 3 hours
				ini_set("memory_limit", "2048M"); // 2 gigabytes

				$dat = [
					'result' => false,
					'msg' => "Tidak memiliki akses"
				];
				if (
					$this->itCan("tos.config,new") ||
					$this->itCan("tos.config,update") ||
					$this->itCan("tos.config,pull")
				) {
					try {
						$data = $req->data;
						$cols = $this->odat['def'];
						if (gettype($data) != "string") {
							return response()->json([
								'result' => false,
								'msg' => "Data not valid"
							]);
						}
						else $data = json_decode($data, true);

						$mode = 0;
						$sdat = null;
						if (isset($data['key']) && $data['key'] == "!") $sdat = null;
						else if (isset($data['id'])) $sdat = TosFormToko::where("id", "=", $data['id']);
						else if (isset($data['key'])) $sdat = TosFormToko::where("key", "=", $data['key']);
						if (count($sdat) > 0 && isset($data['inputor'])) $sdat = $sdat->where("inputor", "=", $data['inputor']);
						if (count($sdat) > 0) $sdat = $sdat->first();

						if (count($sdat) <= 0) {
							if (isset($data['kode_toko']) && TosFormToko::where([
								["kodetoko", "=", $data['kode_toko']],
								["kodegudang", "=", $data['kode_gudang']]
							])->exists()) {
								return response()->json([
									"result" => false,
									"msg" => "Kode toko " . $data['kode_toko'] . " sudah dipakai"
								]);
							}

							$sdat = new TosFormToko();
							$mode = 1;

						} else {
							if (isset($data['kode_toko']) && TosFormToko::where([
								["kodetoko", "=", $data['kode_toko']],
								["kodegudang", "=", $data['kode_gudang']],
								["id", "!=", $sdat->id]
							])->exists()) {
								return response()->json([
									"result" => false,
									"msg" => "Kode toko " . $data['kode_toko'] . " sudah dipakai"
								]);
							}
						}

						$allow = false;
						switch ($mode) {
							case 0:
								$allow = $this->itCan("tos.config,update");
								if (!$allow) $allow = $this->itCan("tos.config,pull");
								break;
							case 1:
								$allow = $this->itCan("tos.config,new");
								if (!$allow) $allow = $this->itCan("tos.config,pull");
								break;
						}
						if (!$allow) {
							return response()->json([
								"result" => false,
								"msg" => "Anda tidak memiliki akses"
							]);
						}

						foreach ($this->toscdirect as $k => $v) {
							$val = (isset($data[$v]) ? $data[$v] : null);
							if ($v == "key") $val = ($val ? (is_numeric($val) ? null : $val) : null);
							switch ($v[0]) {
								case "!":
									if ($mode == 0 && ($k == 'createdon' || $k == "createdby")) continue;
									switch (substr($v, 1)) {
										case "now": $val = date("Y-m-d H:i:s"); break;
										case "user": $val = Auth()->User()->username; break;
									}
									break;
								case "@":
									$val = (isset($data[substr($v, 1)]) ? $data[substr($v, 1)] : null);
									switch (substr($v, 1)) {
										case "inputor":
											if (!$val) $val = Auth()->User()->username;
											$val = strtoupper($val);
											break;

										case "submission_date":
											$tmp = explode(" ", $val);
											$val = ($mode == 1 ? (date("Y-m-d H:i") . ":00") : null);
											if (count($tmp) >= 2) {
												$tmp2 = explode("/", $tmp[0]);
												$tmp3 = explode(":", $tmp[1]);
												if (count($tmp2) >= 3 && count($tmp3) >= 2) {
													$val = $tmp2[2] . "-" . $tmp2[1] . "-" . $tmp2[0] . " " . $tmp3[0] . ":" . $tmp3[1] . ":00";
												}
											}
											break;

										case "created_at":
											$tmp = floatval($val);
											$val = ($mode == 1 ? date("Y-m-d H:i:s") : null);
											if ($tmp > 0) $val = date("Y-m-d H:i:s", ($tmp / 1000));
											break;

										case "updated_at":
											$tmp = floatval($val);
											$val = date("Y-m-d H:i:s");
											if ($tmp > 0) $val = date("Y-m-d H:i:s", ($tmp / 1000));
											break;

										case "scan_ktp": case "foto_toko":
											if ($val) {
												if (strlen($val) <= 500 && base64_decode($val, true) === false) {
													try {
														$tmp = file_get_contents($val);
														$val = base64_encode($tmp);

													} catch (\Exception $ex) { $val = null; }
												}

											} else $val = null;
											break;
									}
									break;
							}
							if ($val !== null) $sdat->$k = $val;
						}
						if ($sdat->key == "!") $sdat->key = null;
						$sdat->save();

						$dat = [
							"result" => true,
							"id" => $sdat->id
						];

					} catch (\Exception $ex) {
						$dat = [
							"result" => false,
							"msg" => $ex->getMessage()
						];
						// sorry message output limited, because my laptop crashed :(
						if (strlen($dat['msg']) > 255) $dat['msg'] = substr($dat['msg'], 0, 255) . "...";
					}
				}
				break;

			case "check":
				$dat = [
					'result' => false,
					'msg' => "Tidak memiliki akses"
				];
				if ($this->itCan('tos.config,fbodelete')) {
					$arg = $req->all();

					$allow = [ 'datas' ];
					$allow = $this->checkFields($arg, $allow);
					if (count($allow) > 0) {
						return response()->json([
							"result" => false,
							"msg" => "Parameter " . join($this->listFields($allow), ", ") . " is not setted"
						]);
					}

					$dat = [
						'result' => false,
						'data' => []
					];
					foreach ($arg["datas"] as $k => $v) {
						foreach ($v as $v2) {
							if (TosFormToko::where([
								["inputor", "=", $k],
								["key", "=", $v2]
							])->exists()) {
								if (!isset($dat["data"][$k])) $dat["data"][$k] = [];
								$dat["data"][$k][] = $v2;
							}
						}
					}
					$dat['result'] = true;
				}
				break;

			case "datetime":
				$now = new \DateTime();
				$dat['def'] = $now->format("Y-m-d H:i:s");
				$dat['ind'] = $now->format("d/m/Y H:i:s");
				$dat['ver'] = $this->ver;
				break;

			case "synch":
				$res = [ "result" => false ];
				$hdat = null;
				if ($this->itCan("tos.config,synch")) {
					try {
						$data = $req->data;
						$allow = [
							'kode_toko',
							'verifikasi_foto_ktp',
							'scan_ktp',
							'no_ktp'
						];
						$allow = $this->checkFields($data, $allow);
						if (count($allow) > 0) throw new \Exception("Memperbarui tidak valid: " . join($this->listFields($allow), ", "));

						$tk = Toko::where(DB::raw("trim(kodetoko)"), trim($data['kode_toko']))->first();
						if (count($tk) <= 0) throw new \Exception("Kode toko " . $data['kode_toko'] . " tidak di temukan");
						if ($tk->tosupdate != null) throw new \Exception("Toko sudah di synch, tidak dapat di synch kembali");

						if ($data['kode_toko'] && $data['verifikasi_foto_ktp'] && $data['scan_ktp'] && $data['no_ktp']) {
							$tkh = new TokoUpdateHistory();
							$attr = $tk->getAttributes();
							foreach ($attr as $tr => $td) {
								switch ($tr) {
									case 'id': case 'tosupdate':
										// do nothing
										break;
										
									case 'kodetoko':
										$tkh->$tr = trim($td);
										break;

									default: $tkh->$tr = $td; break;
								}
							}
							$tkh->obsoleteby  = auth()->user()->username;
							$tkh->tglobsolete = date("Y-m-d H:i:s");
							$tkh->save();

							$chg = [
								"pemilik" => "!pemilik",
								"tempatlahir" => "!tempat_lahir",
								"tgllahir" => "!tgl_lahir",
								"alamatrumah" => "!alamat",
								"hp" => "no_hp",
								"email" => "!email",
								"namatoko" => "!nama_outlet",
								"alamat" => "!alamat_dua",
								"kodepos" => "zip_code_dua",
								"thnberdiri" => "tahun_berdiri",
								"telp" => "no_telp_outlet",
								"fax" => "no_fax_outlet",
								"bangunan" => "!status_kepemilikan",
								"bentukbadanusaha" => "!bentuk_badan_usaha",
								"nonpwp" => "npwp",
								"jmlcabang" => "jumlah_toko",
								"no_toko" => "jumlah_toko",
								"luasgudang" => "luas_gudang",
								"armadamobil" => "jumlah_mobil",
								"armadamotor" => "jumlah_motor",
								"jmlsales" => "jumlah_sales",
								"wilayahpemasaran" => "!wilayah_pemasaran",
								"namabank" => "!nama_bank",
								"norekening" => "no_rekening_bank",
								"norekbgch" => "no_rekening_bg_ch",
								"customwilayah" => "idwil",
								"no_ktp" => "no_ktp",
								"imagektp" => "!foto_toko",
								"imagetoko" => "!scan_ktp",
								"lastupdatedby" => "!nama_saya",
								"lastupdatedon" => "!now"
							];

							$hdat = $tkh;
							foreach ($chg as $k => $v) {
								$var = (isset($data[$v]) ? $data[$v] : null);
								if ($v[0] == "!") {
									switch (substr($v, 1)) {
										case "now":	$var = date("Y-m-d H:i:s"); break;
										case "pemilik": $var = strtoupper($data["first_name"] . " " . $data["last_name"]); break;
										case "foto_toko": case "scan_ktp":
											$v = substr($v, 1);
											if ($data[$v]) {
												if (strlen($data[$v]) <= 500) {
													$tmp = base64_decode($data[$v], true);
													if ($tmp === false) $data[$v] = file_get_contents($data[$v]);
													else $data[$v] = $tmp;

												} else $data[$v] = base64_decode($data[$v]);

												$key = ($v == "foto_toko" ? "toko" : "ktp") . ".";
												$key .= $this->resolveFileName($tk->kodetoko . "_" . $tk->id . "_tos") . ".jpg";

												Storage::disk("local")->put("tos/" . $key, $data[$v]);
												$var = $tk->id . "/" . ($v == "foto_toko" ? "toko" : "ktp");
											}
										break;
										default:
											$v = substr($v, 1);
											if (isset($data[$v])) {
												$var = $data[$v];
												switch ($v) {
													case "tanggal_lahir":
														$var = explode("/", $var);
														if (count($var) >= 3) {
															$var = array_shift(explode(" ", $var[2])) . "-" . $var[1] . "-" . $var[0];
															$var = (new \DateTime($var))->format("Y-m-d");
														}
														else $var = null;
														break;
													case "tempat_lahir": case "alamat": case "nama_outlet": case "alamat_dua":
													case "status_kepemilikan": case "bentuk_badan_usaha": case "wilayah_pemasaran":
													case "nama_bank": case "nama_saya":
														$var = strtoupper($var);
														break;
													case "email":
														$var = strtolower($var);
														break;
												}
											}
											break;
									}
								}

								if ($var !== null && strlen(trim((string)$var)) > 0) {
									if (gettype($var) == "string") $var = trim($var);
									$tk->$k = $var;
								}
							}
							$tk->tosupdate = date("Y-m-d H:i:s");
							$tk->verifiednik = true;
							$tk->save();
						}

						$res['result'] = true;
						return response()->json($res);

					} catch (\Exception $ex) {
						if ($hdat !== null) $hdat->delete();

						$res['result'] = false;
						$res['msg'] = $ex->getMessage();
						return response()->json($res);
					}
				}
				break;

			case "regions":
				$provs = Provinsi::all();
				$kabs = Kabupaten::select(["id", "provinsiid", "namakota"])->get()->toArray();
				$kecs = Kecamatan::select(["id", "kabkotaid", "namakecamatan"])->get()->toArray();
				foreach ($provs as $p) {
					$dat2 = [];
					foreach ($kabs as $k) {
						if ($k['provinsiid'] != $p->id) continue;

						$dat3 = [];
						foreach ($kecs as $e) {
							if ($e['kabkotaid'] != $k['id']) continue;
							$dat3[] = strtoupper($e['namakecamatan']);
						}

						$kabkota = $k['namakota'];
						if (strlen($kabkota) > 4 && strtolower(substr($kabkota, 0, 4)) == "kab.") $kabkota = "KABUPATEN" . substr($kabkota, 4);
						$dat2[strtoupper($kabkota)] = $dat3;
					}
					$dat[strtoupper($p->namaprovinsi)] = $dat2;
				}
				break;

			case "excel":
				$dat = [
					'result' => false,
					'msg' => "Tidak memiliki akses"
				];
				if ($this->itCan('tos.config,excel')) {
					ini_set("max_execution_time", (60 * 60 * 3)); // 3 hours
					ini_set("memory_limit", "2048M"); // 2 gigabytes

					$arg = $req->all();

					$allow = ["from", "to", "pt", "type"];
					$allow = $this->checkFields($arg, $allow);
					if (count($allow) > 0) {
						return response()->json([
							'result' => false,
							'msg' => "Parameter not valid, " . join($this->listFields($allow), ", ")
						]);
					}

					$ps = [];
					$realn = [];
					foreach ($this->toscdirect as $k => $v) {
						switch ($v[0]) {
							case "!": continue;
							case "@":
								switch (substr($v, 1)) {
									case "foto_toko": case "scan_ktp":
										$ps[] = "CASE WHEN (COALESCE(" . $k . ", '') != '') THEN 'YA' ELSE 'TIDAK' END AS " . $k;
										break;
									default:
										$ps[] = $k;
										break;
								}
								$realn[$k] = substr($v, 1);
								break;

							default:
								switch ($v) {
									case "verifikasi_foto_ktp": case "verifikasi_foto_toko":
										$ps[] = "CASE WHEN (" . $k . " = 1::BIT) THEN 'YA' ELSE 'TIDAK' END AS " . $k;
										break;
									default:
										$ps[] = $k;
										break;
								}
								$realn[$k] = $v;
								break;
						}
					}

					$tosf = TosFormToko::where([
						[DB::raw("lastupdatedon::date"), ">=", (new \DateTime($arg['from']))->format("Y-m-d")],
						[DB::raw("lastupdatedon::date"), "<=", (new \DateTime($arg['to']))->format("Y-m-d")]
					]);
					switch ($arg['pt']) {
						case "SEMUA": default:
							// do nothing
							break;

						case "SASA":
							$tosf->whereRaw("
								(kodecabang is not null or kodegudang is not null) and
								(kodecabang = '28' or kodecabang is null) and
								(left(kodegudang, 2) = '28' or kodegudang is null)
							");
							break;

						case "SAP":
							$tosf->whereRaw("
								(kodecabang is not null or kodegudang is not null) and
								(kodecabang != '28' or kodecabang is null) and
								(left(kodegudang, 2) != '28' or kodegudang is null)
							");
							break;
					}

					if (isset($arg['inputor'])) $tosf->where("inputor", strtoupper($arg['inputor']));
					$tosf = $tosf->selectRaw(join($ps, ", "))->get();

					$data = [];
					foreach ($tosf as $d) {
						$dx = $d->getAttributes();
						if (!isset($data[$dx['inputor']])) $data[$dx['inputor']] = [];

						$nda = [];
						foreach ($realn as $k => $v) {
							if (isset($dx[$k])) $nda[$v] = $dx[$k];
							else $nda[$v] = null;
						}
						$data[$dx['inputor']][] = $nda;
					}

					$i = 1;
					$dats = [];
					foreach ($data as $k => $d) {
						foreach ($d as $k2 => $d2) {
							$d2["verified"] = (
								strlen(strval($d2['kode_toko'])) > 0 &&
								strlen(strval($d2['no_ktp'])) > 0 &&
								($d2['verifikasi_foto_ktp'] == "YA") &&
								($d2['scan_ktp'] == "YA")
								? 'SUDAH' : 'BELUM'
							);
							$d2["inputor"] = $k;
							$d2["no"] = $i;
							$dats[] = $d2;
							$i++;
						}
					}

					$cols = $this->odat['def'];
					$uicols = [];
					foreach ($cols as $ck => $cv) {
						$nm = "";
						$ns = explode("_", $ck);
						foreach ($ns as $n) {
							if ($nm != "") $nm .= " ";
							$nm .= ucfirst($n);
						}
						$uicols[$ck] = $nm;
					}

					$ns  = str_slug("TOS Form");
					$nf  = $ns . "-" . uniqid();

					$pars = [
						'PT' => $arg['pt'],
						'Tanggal' => date("d/m/Y H:i:s")
					];

					Excel::create($nf, function($excel) use ($pars, $dats, $cols, $uicols, $ns) {
						$excel->setTitle($ns);
						$excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
						$excel->setmanager(strtoupper(auth()->user()->username));
						$excel->setsubject($ns);
						$excel->setlastModifiedBy(strtoupper(auth()->user()->username));
						$excel->setDescription($ns);
						$excel->sheet($ns, function($sheet) use ($pars, $dats, $cols, $uicols) {
							$sheet->loadView(
								'master.tosform.excel',
								array(
									'pars'   => $pars,
									'dats'   => $dats,
									'cols'   => $cols,
									'uicols' => $uicols
								)
							);
						});

					})->store('xls', storage_path('excel/exports'));

					$dat = [
						'result' => true,
						'url' => asset('storage/excel/exports/' . $nf . '.xls')
					];
				}
				break;
		}
		return response()->json($dat);
	}

	public function image(Request $req, $id, $sub) {
		switch (strtolower($sub)) {
			case "ktp":
			case "foto":
				$tko = Toko::where('id', $id)->first();
				if (count($tko) <= 0) $tko = Toko::where('kodetoko', $id)->first();

				if (count($tko) > 0) {
					$key = 'tos/' . strtolower($sub) . "." . $this->resolveFileName($tko->kodetoko . "_" . $tko->id . "_tos") . ".jpg";
					if (Storage::disk('local')->exists($key)) {
						return response(Storage::disk('local')->get($key))
							   ->header('Content-Type', 'image/jpeg');
					}
				}
				break;
		}
		return response('Not found', 404);
	}

}
