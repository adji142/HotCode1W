<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\LaporanMasterController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Numerator;
use App\Models\SubCabang;
use App\Models\Stock;
use Carbon\Carbon;
use EXCEL;
use PDF;
use DB;

class StockLaporanMasterController extends LaporanMasterController
{
    protected $rpname  = "stock";

    protected function __startup()
    {
        // the dynamic functions
    	$this->tbfunc = [
	        'kartustock'        => 'report.rsp_kartustock',
	        'stockakhirperiode' => function (&$out, &$req, &$tmp) {
                if ($tmp['groupid'] !== null) return 'report.rsp_stokperperiodebygroup';
                else if ($tmp['stockid'] !== null) return 'report.rsp_stokperperiode';
                else return 'report.rsp_stokperperiodeallbarang2';
	        }
    	];

        // the dynamic index data
        $this->idxfunc = [
            'kartustock' => function (&$res, $req, $dname, $info) {
                $res = [ 'subcabang' => SubCabang::all() ];
            },
            'stockakhirperiode' => function (&$res, $req, $dname, $info) {
                $res = [ 'subcabang' => SubCabang::all() ];
            }
        ];

        // the dynamic query model
        $this->tbxsys = [
            /*'stockakhirperiode' => function ($arg, $cbx) {
                $param = [$arg['tmp']['tglawal'], $arg['tmp']['tglakhir'], 0, $arg['tmp']['cabangid']];
                Stock::whereRaw("LEFT(kodebarang, 2) IN ('FB', 'FE')" . ($arg['tmp']['status'] == 0 ? " AND statusaktif = 'f'" : ($arg['tmp']['status'] == 1 ? " AND statusaktif = 't'" : "")))
                ->chunk(50, function ($dtbl) use (&$arg, &$cbx, $param) {
                    $xrecs = [];
                    foreach ($dtbl as $d) {
                        $param[2] = $d->id;
                        $str = '';
                        foreach ($param as $p) {
                            $vo = $p;
                            if ($str != '') $str .= ', ';
                            switch (strtolower(gettype($vo))) {
                                case 'null': $str .= 'null'; break;
                                case 'double': case 'integer': $str .= strval($vo); break;
                                case 'boolean': $str .= $vo ? '1' : '0'; break;
                                case 'string':
                                    $vo = str_replace('\\', '\\\\', $vo);
                                    $vo = str_replace('\'', '\\\'', $vo);
                                    $str .= '\'' . $vo . '\'';
                                    break;
                                default: die('Many arguments not valid');
                            }
                        }
                        $xrecs[] = DB::table(DB::raw('report.rsp_stokperperiode(' . $str . ')'))->first();
                    }
                    $cbx($xrecs);

                    // flush memory
                    $xrecs = null;
                    $dtbl = null;
                });
            }*/
        ];

        // the dynamic collumns
    	$this->tbcols = [
	        'kartustock'        => true,
	        'stockakhirperiode' => true
    	];

        // the dynamic parameters
    	$this->tbparam = [
        	'kartustock'        => ['tglawal', 'tglakhir', 'stockid', 'cabangid'],
	        'stockakhirperiode' => function (&$out, &$req, &$tmp) {
	        	$crow = ['tglawal', 'tglakhir', 'stockid', 'cabangid', 'groupid', 'status'];
                if ($tmp['groupid'] !== null) {
                    unset($crow[array_search('stockid', $crow)]);

                } else if ($tmp['stockid'] !== null) {
                    unset($crow[array_search('groupid', $crow)]);
                    unset($crow[array_search('status', $crow)]);

                } else {
                    unset($crow[array_search('stockid', $crow)]);
                    unset($crow[array_search('groupid', $crow)]);
                }
	        	return $crow;
	        }
    	];

        // the dynamic constants
        $this->tbconst = [
            'kartustock' => [
                '_' => function (&$out, &$req, &$tmp) {
                    if (empty(session('subcabang'))) die("Pilih subcabang dahulu");

                    if (!isset($tmp['custom'])) $tmp['custom'] = [];
                    $tmp['custom']['username'] = auth()->user()->username;
                    $tmp['custom']['kodeCabang'] = SubCabang::where('id', isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang'))->first()->kodesubcabang;

                    if (!isset($tmp['tglawal']) && !isset($tmp['tglakhir'])) {
                        if (isset($tmp['bln']) && isset($tmp['thn'])) {
                            $dt = new \DateTime(intval($tmp['thn']) . '-' . intval($tmp['bln']) . '-01');
                            $tmp['tglawal'] = $dt->format('Y-m-d');

                            $dt = $dt->add(new \DateInterval('P1M'))->sub(new \DateInterval('P1D'));
                            $tmp['tglakhir'] = $dt->format('Y-m-d');

                        } else die('Request not valid, no date range');
                    }
                    $tmp['custom']['tglAwal']  = (new \DateTime($tmp['tglawal']))->format('d-m-Y');
                    $tmp['custom']['tglAkhir'] = (new \DateTime($tmp['tglakhir']))->format('d-m-Y');
                    $tmp['custom']['namaBrg']  = Stock::where('id', $tmp['stockid'])->first()->namabarang;
                    return false;
                },
                'stockid' => function (&$out, &$req, &$tmp) {
                    $d = null;
                    if (isset($tmp['stockid'])) $d = Stock::where('id', $tmp['stockid'])->first();
                    else if (isset($tmp['kodebarang'])) $d = Stock::where('kodebarang', $tmp['kodebarang'])->first();
                    if ($d !== null) if (count($d) > 0) return $d->id;
                },
                'cabangid' => function (&$out, &$req, &$tmp) {
                    return isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang');
                }
            ],
            'stockakhirperiode' => [
            	'_' => function (&$out, &$req, &$tmp) {
                    if (empty(session('subcabang'))) die("Pilih subcabang dahulu");

                    if (!isset($tmp['custom'])) $tmp['custom'] = [];
                    $tmp['custom']['username'] = auth()->user()->username;
                    $tmp['custom']['kodeCabang'] = SubCabang::where('id', isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang'))->first()->kodesubcabang;

                    if (!isset($tmp['tglawal']) && !isset($tmp['tglakhir'])) {
                        if (isset($tmp['bln']) && isset($tmp['thn'])) {
                            $dt = new \DateTime(intval($tmp['thn']) . '-' . intval($tmp['bln']) . '-01');
                            $tmp['tglawal'] = $dt->format('Y-m-d');

                            $dt = $dt->add(new \DateInterval('P1M'))->sub(new \DateInterval('P1D'));
                            $tmp['tglakhir'] = $dt->format('Y-m-d');

                        } else die('Request not valid, no date range');
                    }
                    $tmp['custom']['tglAwal'] = (new \DateTime($tmp['tglawal']))->format('d-m-Y');
                    $tmp['custom']['tglAkhir'] = (new \DateTime($tmp['tglakhir']))->format('d-m-Y');

                    if (@$tmp['groupid'] !== null) {
                        $tmp['custom']['where'] = "kodebarang like '" . $this->strEscape(strtoupper($tmp['groupid'])) . "%'";
                        $tmp['custom']['where'] .= (@$tmp['status'] == 0 ? " AND statusaktif = 'f'" : (@$tmp['status'] == 1 ? " AND statusaktif = 't'" : ""));
                    } else if (@$tmp['stockid'] !== null) {
                        $tmp['custom']['where'] = "id = " . intval($tmp['stockid']);
                    } else {
                        $tmp['custom']['where'] = "LEFT(kodebarang, 2) IN ('FB', 'FE')";
                        $tmp['custom']['where'] .= (@$tmp['status'] == 0 ? " AND statusaktif = 'f'" : (@$tmp['status'] == 1 ? " AND statusaktif = 't'" : ""));
                    }
                    return false;
                },
                'status' => function (&$out, &$req, &$tmp) {
                    if (isset($tmp['status'])) return intval($tmp['status']);
                    else return 0;
                },
                'stockid' => function (&$out, &$req, &$tmp) {
                    $d = null;
                    if (isset($tmp['stockid']) && $tmp['stockid'] != null) $d = Stock::where('id', $tmp['stockid'])->first();
                    else if (isset($tmp['kodebarang'])) $d = Stock::where('kodebarang', $tmp['kodebarang'])->first();
                    if ($d !== null) if (count($d) > 0) return $d->id;
                },
                'groupid' => function (&$out, &$req, &$tmp) {
                    if (isset($tmp['groupid']) && $tmp['groupid'] != null) return strval($tmp['groupid']);
                    else return null;
                },
                'cabangid' => function (&$out, &$req, &$tmp) {
                    return isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang');
                }
            ]
        ];

        // the dynamic infos
        $this->tbinfo = [
    		'kartustock' => [
                'name'      => 'laporanKartuStock',
    			'title'		=> 'Laporan Kartu Stock',
                'exports'   => [
                    'excel' => [
                        'active' => true,
                        'text' => 'Download Sebagai Excel'
                    ],
                    'pdf'   => [
                        'active' => true,
                        'text'   => 'Download Sebagai PDF'
                    ]
                ],
                'heads'     => [
                    'title' => function ($info, $tmp, $typ, $val) {
                        return ($typ == 'pdf' ? '<h3>' : ($typ == 'html' ? '!<h3>' : '')) . $info['title'] . ($typ == 'pdf' || $typ == 'html' ? '</h3>' : '');
                    },
                    'periode' => function ($info, $tmp, $typ, $val) {
                        return 'Periode : ' . $tmp['custom']['tglAwal'] . ' s/d ' . $tmp['custom']['tglAkhir'];
                    },
                    'stock' => function ($info, $tmp, $typ, $val) {
                        return 'Nama Barang : ' . $tmp['custom']['namaBrg'];
                    },
                    'kodebarang' => function ($info, $tmp, $typ, $val) {
                        return 'Kode Barang : ' . $val['kodebarang'];
                    },
                    'rak' => function ($info, $tmp, $typ, $val) {
                        return 'Kode Rak 1 : ' . $val['koderak'];
                    }
                ],
    			'cols'		=> [
    				[
                        [
                            'text' => 'No',
                            'fn'   => function ($r, $c, $v) {
                                return $r;
                            }
                        ],
    					'tglnota'	=> [
    						'text' => 'Tanggal',
                            'type' => 'date'
    					],
                        'nonota'    => [
                            'text' => 'No.Nota'
                        ],
                        'customer'    => [
                            'text' => 'Pemasok / Customer'
                        ],
                        'qtyawal'    => [
                            'text' => 'Stock Awal',
                            'type' => 'int'
                        ],
                        'qtybeli'    => [
                            'text' => 'Pembelian',
                            'type' => 'int'
                        ],
                        'qtyretbeli'    => [
                            'text' => 'Retur Beli',
                            'type' => 'int'
                        ],
                        'qtyjual'    => [
                            'text' => 'Penjualan',
                            'type' => 'int'
                        ],
                        'qtyretjual'    => [
                            'text' => 'Retur Jual',
                            'type' => 'int'
                        ],
                        'qtyantargudang'    => [
                            'text' => 'Antar Gudang',
                            'type' => 'int'
                        ],
                        'qtymutasimasuk'    => [
                            'text' => 'Mutasi Masuk',
                            'type' => 'int'
                        ],
                        'qtymutasikeluar'    => [
                            'text' => 'Mutasi Keluar',
                            'type' => 'int'
                        ],
                        'qtykorbeli'    => [
                            'text' => 'Koreksi Beli',
                            'type' => 'int'
                        ],
                        'qtykorretbeli'    => [
                            'text' => 'Koreksi Ret.Beli',
                            'type' => 'int'
                        ],
                        'qtykorjual'    => [
                            'text' => 'Koreksi Jual',
                            'type' => 'int'
                        ],
                        'qtykorretjual'    => [
                            'text' => 'Koreksi Ret.Jual',
                            'type' => 'int'
                        ],
                        'adjopname'    => [
                            'text' => 'Adj Selisih Opname',
                            'type' => 'int'
                        ],
                        'adjclosing'    => [
                            'text' => 'Adj Selisih Stock',
                            'type' => 'int'
                        ],
                        'scrab'    => [
                            'text' => 'Scrab Pengajuan',
                            'type' => 'int'
                        ],
                        'qtyakhir'    => [
                            'text' => 'Stock Akhir',
                            'type' => 'int'
                        ],
                        'rpstok'    => [
                            'text' => 'Stock Akhir (Rp)',
                            'type' => 'money'
                        ],
                        'qtygit'    => [
                            'text' => 'Total GIT AG',
                            'type' => 'int'
                        ],
                        'qtygudang'    => [
                            'text' => 'Stock Gudang',
                            'type' => 'int'
                        ],
                        'rpgit'    => [
                            'text' => 'Stock Gudang (Rp)',
                            'type' => 'money'
                        ],
    				]
    			],
                'foots'     => [
                    'total' => function ($mode, $info, $tmp, $val) {
                        switch ($mode) {
                            case 'set':
                                if (!isset($this->tbtmp)) $this->tbtmp = [];
                                if (!isset($this->tbtmp['ttl'])) {
                                    $this->tbtmp['ttl'] = [
                                        'qtyawal'         => [ 0, 'int', '=' ],
                                        'qtybeli'         => [ 0, 'int', '+' ],
                                        'qtyretbeli'      => [ 0, 'int', '+' ],
                                        'qtyjual'         => [ 0, 'int', '+' ],
                                        'qtyretjual'      => [ 0, 'int', '+' ],
                                        'qtyantargudang'  => [ 0, 'int', '+' ],
                                        'qtymutasimasuk'  => [ 0, 'int', '+' ],
                                        'qtymutasikeluar' => [ 0, 'int', '+' ],
                                        'qtykorbeli'      => [ 0, 'int', '+' ],
                                        'qtykorretbeli'   => [ 0, 'int', '+' ],
                                        'qtykorjual'      => [ 0, 'int', '+' ],
                                        'qtykorretjual'   => [ 0, 'int', '+' ],
                                        'adjopname'       => [ 0, 'int', '=' ],
                                        'adjclosing'      => [ 0, 'int', '=' ],
                                        'scrab'           => [ 0, 'int', '=' ],
                                        'qtyakhir'        => [ 0, 'int', '=' ],
                                        'rpstok'          => [ 0, 'money', '=' ],
                                        'qtygit'          => [ 0, 'int', '=' ],
                                        'qtygudang'       => [ 0, 'int', '=' ],
                                        'rpgit'           => [ 0, 'money', '=' ]
                                    ];
                                }
                                $this->setFootData($this->tbtmp['ttl'], $val);
                                break;

                            case 'get':
                                $res = [
                                    [ 'text' => 'Total', 'cols' => 4 ]
                                ];
                                foreach ($this->tbtmp['ttl'] as $v) {
                                    $res[] = [
                                        'text' => $v[0],
                                        'type' => $v[1]
                                    ];
                                }
                                return $res;
                        }
                    }
                ]
    		],
    		'stockakhirperiode' => [
                'name'      => 'laporanStockAP',
    			'title'		=> 'Laporan Stock Akhir Periode',
                'exports'   => [
                    'excel' => [
                        'active' => true,
                        'text'   => 'Download Sebagai Excel'
                    ],
                    'pdf'   => [
                        'active' => true,
                        'text'   => 'Download Sebagai PDF'
                    ]
                ],
                'chunk'     => 500,
                'approxTotal'  => function ($tmp) {
                    return Stock::whereRaw($tmp['custom']['where'])->count();
                },
                'heads'     => [
                    'title' => function ($info, $tmp, $typ, $val) {
                        return ($typ == 'pdf' ? '<h3>' : ($typ == 'html' ? '!<h3>' : '')) . $info['title'] . ($typ == 'pdf' || $typ == 'html' ? '</h3>' : '');
                    },
                    'tanggal' => function ($info, $tmp, $typ, $val) {
                        return 'Tanggal : ' . $tmp['custom']['tglAwal'] . ' s/d ' . $tmp['custom']['tglAkhir'];
                    },
                    'gudang' => function ($info, $tmp, $typ, $val) {
                        return 'Gudang : ' . $tmp['custom']['kodeCabang'];
                    }
                ],
    			'cols'		=> [
    				[
                        [
                            'text' => 'No',
                            'rows' => 2,
                            'fn'   => function ($r, $c, $v) {
                                return $r;
                            }
                        ],
    					'namastok' => [
    						'text' => 'Nama Barang',
    						'rows' => 2
    					],
    					'barangid' => [
    						'text' => 'Kode Barang',
    						'rows' => 2
    					],
    					'kategoripenjualan' => [
    						'text' => 'Kategori',
    						'rows' => 2
    					],
    					[
    						'text' => 'Rak',
    						'cols' => 3
    					],
    					[
    						'text' => 'Stock Awal',
    						'cols' => 2
    					],
    					[
    						'text' => 'Pembelian',
    						'cols' => 2
    					],
    					[
    						'text' => 'Retur Beli',
    						'cols' => 2
    					],
    					[
    						'text' => 'Penjualan',
    						'cols' => 2
    					],
    					[
    						'text' => 'Retur Jual',
    						'cols' => 2
    					],
    					[
    						'text' => 'Antar Gdg',
    						'cols' => 2
    					],
    					[
    						'text' => 'Mutasi Masuk',
    						'cols' => 2
    					],
    					[
    						'text' => 'Mutasi Keluar',
    						'cols' => 2
    					],
    					[
    						'text' => 'Koreksi Pembelian',
    						'cols' => 2
    					],
    					[
    						'text' => 'Koreksi ReturPembelian',
    						'cols' => 2
    					],
    					[
    						'text' => 'Koreksi Penjualan',
    						'cols' => 2
    					],
    					[
    						'text' => 'Koreksi ReturPenjualan',
    						'cols' => 2
    					],
    					[
    						'text' => 'Adjs Opname',
    						'cols' => 2
    					],
    					[
    						'text' => 'Adjs Stock',
    						'cols' => 2
    					],
    					[
    						'text' => 'Stock Akhir',
    						'cols' => 2
    					],
    					[
    						'text' => 'Total Git AG',
    						'cols' => 2
    					],
    					[
    						'text' => 'Stock Gudang',
    						'cols' => 2
    					],
    					[
    						'text' => 'HPPA'
    					],
    					[
    						'text' => 'Scrab Pengajuan'
    					],
    					'keterangan' => [
    						'text' => 'Keterangan',
    						'rows' => 2
    					],
    					'gudangsementara' => [
    						'text' => 'Umur PB (bln)',
    						'rows' => 2
    					]
    				],
    				[
    					'koderak1' => [
    						'text' => 'Rak 1'
    					],
    					'koderak2' => [
    						'text' => 'Rak 2'
    					],
    					'koderak3' => [
    						'text' => 'Rak 3'
    					],
    					'qtyawal' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpsaldoawal' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtybeli' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpbeli' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtyreturbeli' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpreturbeli' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtyjual' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpjual' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtyreturjual' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpreturjual' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtyag' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpag' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtymutasimasuk' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpmutasimasuk' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtymutasikeluar' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpmutasikeluar' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtykorbeli' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpkorbeli' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtykorretbeli' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpkorretbeli' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtykorjual' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpkorjual' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'qtykorretjual' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpkorretjual' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'adjopname' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpadjopname' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'adjclosing' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpadjclosing' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'stokakhir' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpstokakhir' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
                        'qtygit' => [
                            'text' => 'Qty',
                            'type' => 'int'
                        ],
                        'rpgit' => [
                            'text' => 'Rp',
                            'type' => 'money'
                        ],
    					'stokgudang' => [
    						'text' => 'Qty',
                            'type' => 'int'
    					],
    					'rpstokgudang' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'hppa' => [
    						'text' => 'Rp',
    						'type' => 'money'
    					],
    					'rpscrab' => [
    						'text' => 'Rp',
    						'type' => 'money',
                            'val' => 0
    					]
    				]
    			],
                'foots'     => [
                    'total' => function ($mode, $info, $tmp, $val) {
                        switch ($mode) {
                            case 'set':
                                if (!isset($this->tbtmp)) $this->tbtmp = [];
                                if (!isset($this->tbtmp['ttl'])) {
                                    $this->tbtmp['ttl'] = [
                                        'qtyawal'         => [ 0, 'int', '+' ],
                                        'rpsaldoawal'     => [ 0, 'money', '+' ],
                                        'qtybeli'         => [ 0, 'int', '+' ],
                                        'rpbeli'          => [ 0, 'money', '+' ],
                                        'qtyreturbeli'    => [ 0, 'int', '+'],
                                        'rpreturbeli'     => [ 0, 'money', '+' ],
                                        'qtyjual'         => [ 0, 'int', '+'],
                                        'rpjual'          => [ 0, 'money', '+' ],
                                        'qtyreturjual'    => [ 0, 'int', '+'],
                                        'rpreturjual'     => [ 0, 'money', '+' ],
                                        'qtyag'           => [ 0, 'int', '+'],
                                        'rpag'            => [ 0, 'money', '+' ],
                                        'qtymutasimasuk'  => [ 0, 'int', '+'],
                                        'rpmutasimasuk'   => [ 0, 'money', '+' ],
                                        'qtymutasikeluar' => [ 0, 'int', '+'],
                                        'rpmutasikeluar'  => [ 0, 'money', '+' ],
                                        'qtykorbeli'      => [ 0, 'int', '+'],
                                        'rpkorbeli'       => [ 0, 'money', '+' ],
                                        'qtykorretbeli'   => [ 0, 'int', '+'],
                                        'rpkorretbeli'    => [ 0, 'money', '+' ],
                                        'qtykorjual'      => [ 0, 'int', '+'],
                                        'rpkorjual'       => [ 0, 'money', '+' ],
                                        'qtykorretjual'   => [ 0, 'int', '+'],
                                        'rpkorretjual'    => [ 0, 'money', '+' ],
                                        'adjopname'       => [ 0, 'int', '+'],
                                        'rpadjopname'     => [ 0, 'money', '+' ],
                                        'adjclosing'      => [ 0, 'int', '+'],
                                        'rpadjclosing'    => [ 0, 'money', '+' ],
                                        'stokakhir'       => [ 0, 'int', '+'],
                                        'rpstokakhir'     => [ 0, 'money', '+' ],
                                        'qtygit'          => [ 0, 'int', '+'],
                                        'rpgit'           => [ 0, 'money', '+' ],
                                        'stokgudang'      => [ 0, 'int', '+'],
                                        'rpstokgudang'    => [ 0, 'money', '+' ],
                                        'hppa'            => [ 0, 'money', '+'],
                                        'rpscrab'         => [ 0, 'money', '+' ],
                                        'gudangsementara' => [ 0, 'int', '+'],
                                    ];
                                }
                                $this->setFootData($this->tbtmp['ttl'], $val);
                                break;

                            case 'get':
                                $res = [
                                    [ 'text' => 'Total', 'cols' => 7 ]
                                ];
                                foreach ($this->tbtmp['ttl'] as $v) {
                                    $res[] = [
                                        'text' => $v[0],
                                        'type' => isset($v[1]) ? $v[1] : 'int'
                                    ];
                                }
                                $tmp2 = $res[count($res) - 1];
                                $res[count($res) - 1] = ['text' => ''];
                                $res[] = $tmp2;
                                return $res;
                        }
                    }
                ]
    		]
    	];
    }
}
