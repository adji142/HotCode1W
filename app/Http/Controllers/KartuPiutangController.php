<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\APIMasterController;
use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\Toko;
use App\Models\Salesman;
use App\Models\ApprovalManagement;
use App\Models\Bgc;
use App\Models\KartuPiutang;
use App\Models\KartuPiutangDetail;
use App\Models\KPKonsol;
use App\Models\KPDetailKonsol;
use App\Models\KPSynchHistory;
use App\Models\KPRekap;
use App\Models\SisaPlafondToko;
use App\Models\Perusahaan;
use Carbon\Carbon;
use DB;

class KartuPiutangController extends APIMasterController
{
	protected $apiName  = "kartupiutang";

	protected function __startup() {
		$this->apis = [
			'konsol' => [
				'mode' => ['get', 'post'],
				'params' => [
					//'data'
				],
				'output' => [
					'defaultJSON' => [
						"Result" => false,
						"Mystic" => [],
						"Lists" => [],
						"Count" => 0
					],
					'errorJSON' => [
						"Result" => false,
						"Msg" => "!%message%",
						//"Trace" => "!%trace%"
					]
				],
				'fn' => function (&$out, $req, $args, $config) {
					if (array_key_exists("subd", $args['dslug'])) {
						$from = trim(@$args['dslug']['subd']);
						$data = @$args['params']['data'];
						$force = @$args['params']['force'];

						$lst = KPSynchHistory::where([
							[DB::raw("tanggal::date"), "=", date("Y-m-d")],
							["recordowner", "=", (@$from[0] == "!" ? substr($from, 1) : $from)]
						])->first();

						if (!$force) {
							if (@$from[0] == "!") return ['Result' => (count($lst) <= 0)];
							else if (count($lst) > 0) {
								$out['Result'] = true;
								return $out;
							}
						}

						try {
							switch (gettype($data)) {
								case 'string':
									$data = json_decode($data, true);
									break;

								case 'array':
									// do nothing
									break;

								default:
									throw new \Exception();
							}

						} catch (\Exception $ex) { throw new \Exception("Data must be JSON"); }

						$allow = $this->checkFields($data, ['KP', 'KPDetail']);
						if (count($allow) > 0) {
							$msg = $this->jsonSet($config['messages']['paramsNotValid'], [
								'list' => join($this->listFields($allow), ', ')
							]);
							throw new \Exception($msg);
						}

						$cols = [
							'KP' => [
								"isarowid" => "RowID",
								"kpid" => "KPID",
								"kodetoko" => "KodeToko",
								"kodesales" => "KodeSales",
								"tgltransaksi" => "TglTransaksi",
								"tgllink" => "TglLink",
								"notransaksi" => "NoTransaksi",
								"status" => "Status",
								"jangkawaktu" => "JangkaWaktu",
								"tgljatuhtempo" => "TglJT",
								"uraian" => "Uraian",
								"cicil" => "Cicil",
								"transactiontype" => "TransactionType",
								"synchflag" => "SynchFlag",
								"harikirim" => "HariKirim",
								"harisales" => "HariSales",
								"keterangantagih" => "KetTagih",
								"tgllunas" => "TglLunas",
								"wiserid" => "WiserID",
								"initperusahaan" => "InitPerusahaan",
								"initcabang" => "InitCabang",
								"createdby" => "CreatedBy",
								"createdon" => "CreatedTime",
								"lastupdatedby" => "LastUpdatedBy",
								"lastupdatedon" => "LastUpdatedTime"
							],
							'KPDetail' => [
								"isarowid" => "RowID",
								"isaheaderid" => "HRowID",
								"recordid" => "RecordID",
								"kpid" => "KPID",
								"tgltransaksi" => "TglTransaksi",
								"kodetransaksi" => "KodeTransaksi",
								"debet" => "Debet",
								"kredit" => "Kredit",
								"tgljtgiro" => "TglJTGiro",
								"uraian" => "Uraian",
								"syncflag" => "SyncFlag",
								"nobuktikasmasuk" => "NoBuktiKasMasuk",
								"nogiro" => "NoGiro",
								"bank" => "Bank",
								"noacc" => "NoACC",
								"isclosed" => "IsClosed",
								"flagmerge" => "FlagMerge",
								"isagiroid" => "ISAGiroID",
								"isagtid" => "ISAGTID",
								"wiserid" => "WiserID",
								"tglcair" => "TglCair",
								"createdby" => "CreatedBy",
								"createdon" => "CreatedTime",
								"lastupdatedby" => "LastUpdatedBy",
								"lastupdatedon" => "LastUpdatedTime"
							],
							'PlafondToko' => [
								"kodetoko" => "KodeToko",
								"tokoid" => "TokoID",
								"tanggal" => "Tanggal",
								"initperusahaan" => "InitPerusahaan",
								"initcabang" => "InitCabang",
								"kodegudang" => "KodeGudang",
								"saldoplafond" => "SaldoPlafond"
							]
						];
						$tmpd = [];

						$tmpd['rekapPlafond'] = (SisaPlafondToko::where(DB::raw("tanggal::date"), date('Y-m-d'))->count() <= 0);
						$tmpd['mysticDat'] = [];

						foreach ($data as $k => $v) {
							if (gettype($v) == 'array') {
								foreach ($v as $k2 => $v2) {
									$dat = null;
									switch ($k) {
										case 'KP':
											if (count($this->checkFields($v2, $cols[$k])) <= 0) {
												$dat = KPKonsol::where([
													['isarowid', '=', $v2["RowID"]],
													['recordownerid', '=', $from]
												]); //->first();

												if ($force) {
													if ($dat->count() <= 0) $dat = null;
													else $dat = true;
												} else $dat = $dat->first();

												if ($dat !== true) {
													if (count($dat) <= 0) {
														$dat = new KPKonsol();
														$dat->createdon = date("Y-m-d H:i:s");
													}
													$dat->recordownerid = $from;
													$dat->tag = $v2['RowID'];
												}

											} else {
												$out['Mystic'][] = [
													'id' => @$v2["RowID"],
													'par' => "Header",
													'msg' => 'Fields not completed'
												];
												continue;
											}
											break;

										case 'KPDetail':
											if (count($this->checkFields($v2, $cols[$k])) <= 0) {
												$dat = KPDetailKonsol::where([
													['isarowid', '=', $v2["RowID"]],
													['recordownerid', '=', $from]
												]); //->first();

												if ($force) {
													if ($dat->count() <= 0) $dat = null;
													else $dat = true;
												} else $dat = $dat->first();

												if ($dat !== true) {
													if (count($dat) <= 0) {
														$dat = new KPDetailKonsol();
														$dat->createdon = date("Y-m-d H:i:s");
													}
													$dat->recordownerid = $from;
													$dat->tag = $v2['RowID'];
												}

											} else {
												$out['Mystic'][] = [
													'id' => @$v2["RowID"],
													'par' => "Detail",
													'msg' => 'Fields not completed'
												];
												continue;
											}										
											break;

										case 'PlafondToko':
											if (count($this->checkFields($v2, $cols[$k])) <= 0) {
												if ($tmpd['rekapPlafond']) {
													$dat = new SisaPlafondToko();
													$dat->tanggal = date('Y-m-d H:i:s');
													$dat->kodegudang = $from;

													if (!isset($tmpd['perusahaan'])) $tmpd['perusahaan'] = [];
													if (!isset($tmpd['perusahaan'][$v2['InitCabang']])) {
														$tmp = Cabang::where("kodecabang", $v2['InitCabang'])->first();
														if (count($tmp) > 0) {
															$tmp = Perusahaan::where("id", $tmp->perusahaanid)->first();
															$tmp = (count($tmp) > 0 ? $tmp : false);

														} else $tmp = false;
														$tmpd['perusahaan'][$v2['InitCabang']] = $tmp;
													}

													$perid = $tmpd['perusahaan'][$v2['InitCabang']];
													if ($perid === false) {
														if (!isset($tmpd['mysticDat']['c' . $v2['InitCabang'] . 'p' . $v2['InitPerusahaan']])) {
															$tmpd['mysticDat']['c' . $v2['InitCabang'] . 'p' . $v2['InitPerusahaan']] = true;
															$out['Mystic'][] = [
																'par' => "Plafond",
																'msg' => 'Perusahaan not found: ' . $v2['InitPerusahaan'] . ' | cabang: ' . $v2['InitCabang']
															];
														}
														continue;

													} else $dat->perusahaanid = $perid->id;
													$dat->tag = $v2['KodeToko'];

												} else $dat = true;

											} else {
												$out['Mystic'][] = [
													'id'  => @$v2['KodeToko'],
													'par' => "Plafond",
													'msg' => 'Fields not completed'
												];
												continue;
											}										
											break;											
									}

									if ($dat !== null && $dat !== true) {
										foreach ($cols[$k] as $k3 => $v3) {
											$skip = false;
											switch ($k) {
												case 'KP': case 'KPDetail':
													switch ($k3) {
														case 'createdon':
															$skip = true;
															break;
													}
													break;
												case 'PlafondToko':
													switch ($k3) {
														case 'tanggal': case 'kodegudang': case 'initcabang':
															$skip = true;
															break;
													}
													break;
											}
											if (!$skip) $dat->$k3 = @$v2[$v3];
										}

										try {
											$dat->save();
											$out['Count'] += 1;
											$out['Lists'][$dat->tag] = true;

										} catch (\Exception $ex) {
											/*$out['Mystic'][] = [
												'id' => @$v2['RowID'],
												'msg' => $ex->getMessage()
											];*/
											$out['Mystic'][] = [
												'tb' => $dat->table,
												'msg' => $ex->getMessage()
											];
											$out['Lists'][$dat->tag] = false;
										}

									} else if ($dat !== true) $out['Mystic'][] = [ 'id' => @$v2['RowID'], 'msg' => 'Unknown record' ];
								}
							}
						}

						// Rekap must by cronjob
						// if (KPRekap::where(DB::raw("tanggal::date"), date('Y-m-d'))->count() <= 0) DB::select("SELECT konsol.rsp_rekap_kartupiutang()");
						$out['Result'] = ($out['Count'] > 0 ? true : (count($out['Mystic']) <= 0));

						if (count($lst) <= 0) $lst = new KPSynchHistory();

						$lst->recordowner = $from;
						$lst->tanggal = date("Y-m-d H:i:s");
						$lst->status = $out['Result'];
						$lst->msg = ($out['Result'] ? "" : (count($out['Mystic']) > 0 ? json_encode($out["Mystic"]) : $out['Msg']));
						$lst->save();

					} else throw new \Exception("Something when wrong");
				}
			]
		];
	}

	private function numPad($val, $len) {
		$val = strval($val);
		$len = intval($len);
		if (strlen($val) < $len) {
			$tmp1 = '';
			for ($i = strlen($val); $i < $len; $i++) $tmp1 .= '0';
			$val = $tmp1 . $val;
		}
		return $val;
	}

	public function apiGetKartuPiutang(Request $req) {
		$result = array(
			"result" => false,
			"msg" => "Tidak ada data",
			"data" => array(),
			"length" => 0
		);

		try {
			$rows = array();
			if ($req->fromdate && $req->todate) {
				$rows = KartuPiutang::where(array(
					array(DB::raw("createdon::date"), ">=", (new \DateTime($req->fromdate))->format("Y-m-d")),
					array(DB::raw("createdon::date"), "<=", (new \DateTime($req->todate))->format("Y-m-d"))
				))->get();
			}

		} catch (\Exception $ex) {
			$result["msg"] = $ex->getMessage();
			$result["line"] = $ex->getLine();
		}

		if (count($rows) > 0) {
			try {
				foreach ($rows as $row) {
					$xdat = array();
					$rowx = $row->getAttributes();

					$toko  = Toko::where("id", $row->tokoid)->first();
					$sales = Salesman::where("id", $row->karyawanidsalesman)->first();
					$rows2 = KartuPiutangDetail::where("kartupiutangid", $row->id)->get();
					if (count($toko) <= 0 || count($sales) <= 0) {
						// skip when record not valid
						// and report it on mystic
						if (!isset($result["mystic"])) $result["mystic"] = array();
						$result["mystic"][] = array(
							'Type' => "Header",
							'HID' => $row->id,
							'Toko' => count($toko) == 1,
							'Sales' => count($sales) == 1
						);
						continue;
					}

					// header
					$tmp = array();
					try {
						$tmp = array(
							"KodeToko" => $toko->kodetoko,
							"KodeSales" => $sales->kodesales,
							"TglTransaksi" => $row->tglterima,
							"TglLink" => (new \DateTime($rowx["createdon"]))->format("Y-m-d H:i:s"), // $row->createdon,
							"NoTransaksi" => $row->nonota,
							"JangkaWaktu" => $row->temponota,
							"TglJatuhTempo" => $row->tgljt,
							"Uraian" => $row->uraian,
							"TransactionType" => $row->tipetrans,
							"HariKirim" => $row->tempokirim,
							"HariSales" => $row->temposalesman,
							"LastUpdatedBy" => $row->lastupdatedby,
							"LastUpdatedTime" => (new \DateTime($rowx["lastupdatedon"]))->format("Y-m-d H:i:s"), // $row->lastupdatedon,
							"TglKomitmenPKP" => $row->tglklakanjt,
							"KeteranganKomitmenPKP" => $row->ketklakanjt,
							"TglKomitmenKaAdm" => $row->tglklsudahjt,
							"KeteranganKomitmenKaAdm" => $row->ketklsudahjt,
							"TanggalACCTT" => null,
							"NoACCTT" => null,
							"TanggalPrr" => null,
							"wiserid" => $row->id,

							"Details" => array()
						);

					} catch (\Exception $ex) {
						// report it on mystic
						if (!isset($result["mystic"])) $result["mystic"] = array();
						$result["mystic"][] = array(
							'Type' => "Header",
							'HID' => $row->id,
							'Msg' => $ex->getMessage(),
							'Line' => $ex->getLine()
						);
						continue;
					}

					$str = strval($tmp["TransactionType"]);
					if (strlen($str) <= 0 || (@$str[0] !== 'K' && @$str[0] !== 'T')) {
						// skip when record not valid
						// and report it on mystic
						if (!isset($result["mystic"])) $result["mystic"] = array();
						$result["mystic"][] = array(
							'Type' => "Header",
							'HID' => $row->id,
							'TransactionType' => $str
						);
						continue;
					}

					$tmp["Details"][$row->id] = array(
						"TglTransaksi" => $tmp["TglTransaksi"],
						"KodeTransaksi" => $str[0] === "K" ? "PJK" : ($str[0] === "T" ? "PJT" : $str),
						"Debet" => abs($row->nomnota),
						"Kredit" => 0,
						"TglJTGiro" => null,
						"Uraian" => $tmp["Uraian"],
						"NoGiro" => null,
						"LastUpdatedBy" => $tmp["LastUpdatedBy"],
						"LastUpdatedTime" => $tmp["LastUpdatedTime"],
						"wiserid" => $tmp["wiserid"]
					);

					$secmgmt1 = ApprovalManagement::where("id", $row->approvalmgmtidtt)->first();
					if (count($secmgmt1) > 0) {
						$secmgmt1 = $secmgmt1->getAttributes();

						$tmp2 = $secmgmt1->id;
						$tmp3 = new \DateTime($secmgmt1["createdon"]);
						$secmgmt1 = ApprovalManagement::where(array(
							array(DB::raw("createdon"), ">=", $tmp3->format("Y-m-d")),
							array(DB::raw("createdon"), "<=", $tmp3->format("Y-m-d"))
						))->orderBy("id", "asc")->get();

						$tmp4 = 1;
						foreach ($secmgmt1 as $tmp5) {
							if ($tmp5->id == $tmp2) break;
							else $tmp4 += 1;
						}

						$tmp["TanggalACCTT"] = $tmp3->format("Y-m-d H:i:s");
						$tmp["NoACCTT"] = $tmp3->format("Ymd") . $this->numPad($tmp4, 4);
					}

					$secmgmt2 = ApprovalManagement::where("id", $row->approvalmgmtidprr)->first();
					if (count($secmgmt2) > 0) {
						$secmgmt2 = $secmgmt2->getAttributes();
						$tmp["TanggalPrr"] = $secmgmt2["createdon"];
					}

					$ttlnom = floatval($row->nomnota);
					foreach ($rows2 as $row2) {
						$rowx2 = $row2->getAttributes();

						$bgc = Bgc::where("id", $row2->bgcid)->first();
						if (count($bgc) <= 0) {
							// skip when record not valid
							// and report it on mystic
							if (!isset($result["mystic"])) $result["mystic"] = array();
							$result["mystic"][] = array(
								'Type' => "Detail",
								'HID' => $row->id,
								'ID' => $row2->id,
								'Bgc' => count($bgc) == 1
							);
							continue;
						}

						$tmp["Details"][$rows2->id] = array(
							"TglTransaksi" => $row2->tgltrans,
							"KodeTransaksi" => $row2->kodetrans,
							"Debet" => $row2->nomtrans < 0 ? abs($row2->nomtrans) : 0,
							"Kredit" => $row2->nomtrans > 0 ? abs($row2->nomtrans) : 0,
							"TglJTGiro" => $bgc->tgljtbgc,
							"Uraian" => $row2->uraian,
							"NoGiro" => $bgc->nobgc,
							"LastUpdatedBy" => $row2->lastupdatedby,
							"LastUpdatedTime" => (new \DateTime($rowx2["lastupdatedon"]))->format("Y-m-d H:i:s"), // $row2->lastupdatedon,
							"wiserid" => $row2->id
						);

						$ttlnom -= floatval($row2->nomtrans);
					}

					$tmp["Status"] = $ttlnom != 0 ? "OPEN" : "CLOSE";
					if (count($tmp["Details"]) > 0) {
						$result["Data"][$row->id] = $tmp;
						$result["Length"] += 1;
						$result["Msg"] = "";
					}
				}
				$result["Result"] = true;

			} catch (\Exception $ex) {
				$result["Msg"] = $ex->getMessage();
				$result["Line"] = $ex->getLine();
				$result["Result"] = false;
			}
		}

		return response()->json($result);
	}
}
