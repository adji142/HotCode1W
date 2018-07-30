<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\LaporanMasterController;
use Illuminate\Http\Request;
use App\Models\Numerator;
use App\Models\SubCabang;
use App\Models\Stock;
use App\Models\Toko;
use Carbon\Carbon;
use EXCEL;
use PDF;
use DB;


class AnalisaLaporanMasterController extends LaporanMasterController
{
    protected $rpname  = "analisa";

    protected function __startup()
    {
        // the dynamic functions
    	$this->tbfunc = [
	        'stockcompare'     => 'report.rsp_stockitem_compare',
            'progressskhu'     => 'report.rsp_progress_sku_toko',
            'pembelianpertoko' => 'report.rsp_stockpembelianpertoko'
    	];

        // the dynamic index data
        $this->idxfunc = [
            'stockcompare' => function (&$res, $req, $dname, $info) {
                $res = [ 'subcabang' => SubCabang::all() ];
            },
            'progressskhu' => function (&$res, $req, $dname, $info) {
                $res = [
                    'subcabang' => SubCabang::all(),
                    'months' => ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                ];
            },
            'pembelianpertoko' => function (&$res, $req, $dname, $info) {
                $res = [ 'subcabang' => SubCabang::all() ];
            }
        ];

        // the dynamic collumns
    	$this->tbcols = [
	        'stockcompare'     => true,
	        'progressskhu'     => true,
            'pembelianpertoko' => true
    	];

        // the dynamic parameters
    	$this->tbparam = [
        	'stockcompare'     => ['tokoid', 'thn1', 'thn2'],
            'progressskhu'     => ['tglawal', 'tglakhir', 'tokoid', 'cabangid', 'mode'],
            'pembelianpertoko' => ['tglawal', 'tglakhir', 'tokoid', 'cabangid', 'riwayat']
    	];

        // the dynamic constants
        $this->tbconst = [
            'stockcompare' => [
                '_' => function (&$out, &$req, &$tmp) {
                    if (empty(session('subcabang'))) die("Pilih subcabang dahulu");

                    if (!isset($tmp['custom'])) $tmp['custom'] = [];
                    $tmp['custom']['username'] = auth()->user()->username;
                    $tmp['custom']['kodeCabang'] = SubCabang::where('id', isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang'))->first()->kodesubcabang;
                    
                    if (isset($tmp['thn1']) && isset($tmp['thn2'])) {
                        $th1 = intval($tmp['thn1']);
                        $th2 = intval($tmp['thn2']);
                        if ($th1 == $th2) die("Years must differents");

                        $tmp['thn1'] = $th1;
                        $tmp['thn2'] = $th2;

                        $tmp2 = new \DateTime(($th1 + 1) . "-01-01");
                        $tmp2 = $tmp2->sub(new \DateInterval('P1D'));
                        $tmp['custom']['periode1'] = [
                            'tglAwal'  => "01-01-" . $th1,
                            'tglAkhir' => date("Y") == $th1 ? date("d-m-Y") : $tmp2->format("d-m-Y")
                        ];

                        $tmp2 = new \DateTime(($th2 + 1) . "-01-01");
                        $tmp2 = $tmp2->sub(new \DateInterval('P1D'));
                        $tmp['custom']['periode2'] = [
                            'tglAwal'  => "01-01-" . $th2,
                            'tglAkhir' => date("Y") == $th2 ? date("d-m-Y") : $tmp2->format("d-m-Y")
                        ];
                    }

                    if (isset($tmp['tokoid'])) {
                        $d = Toko::where('id', $tmp['tokoid'])->first();
                        if (count($d) > 0) {
                            $tmp['custom']['idwillTko'] = $d->customwilayah;
                            $tmp['custom']['namaTko'] = $d->namatoko;
                            $tmp['custom']['alamatTko'] = $d->alamat;
                            $tmp['custom']['kotaTko'] = $d->kota;

                        } else die('Tokoid ' + $tmp['tokoid'] + ' cannot be found');
                    }
                    return false;
                }
            ],

            'progressskhu' => [
                '_' => function (&$out, &$req, &$tmp) {
                    if (empty(session('subcabang'))) die("Pilih subcabang dahulu");

                    if (!isset($tmp['custom'])) $tmp['custom'] = [];
                    $tmp['custom']['username'] = auth()->user()->username;
                    $tmp['custom']['kodeCabang'] = SubCabang::where('id', isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang'))->first()->kodesubcabang;
                    
                    if (!isset($tmp['tglawal'])) return false;
                    if (!isset($tmp['tglakhir'])) {
                        if (isset($tmp['blnakhir']) && isset($tmp['thnakhir'])) {
                            $dt = new \DateTime($tmp['thnakhir'] . "-" . $tmp['blnakhir'] . "-01");
                            $dt->add(new \DateInterval('P1M'))->sub(new \DateInterval('P1D'));
                            $tmp['tglakhir'] = $dt->format('Y-m-d');

                        } else return false;
                    }
                    $tmp['custom']['tglAwal'] = (new \DateTime($tmp['tglawal']))->format('d-m-Y');
                    $tmp['custom']['tglAkhir'] = (new \DateTime($tmp['tglakhir']))->format('d-m-Y');

                    if (isset($tmp['tokoid'])) {
                        $d = Toko::where('id', $tmp['tokoid'])->first();
                        if (count($d) > 0) {
                            $tmp['custom']['namaTko'] = $d->namatoko;
                            $tmp['custom']['alamatTko'] = $d->alamat;
                            $tmp['custom']['kotaTko'] = $d->kota;
                            $tmp['custom']['idwillTko'] = $d->customwilayah;

                        } else die('Tokoid ' + $tmp['tokoid'] + ' cannot be found');
                    }
                    return false;
                },
                'cabangid' => function (&$out, &$req, &$tmp) {
                    return isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang');
                },
                'mode' => function (&$out, &$req, &$tmp) {
                    return isset($tmp['mode']) ? intval($tmp['mode']) : 0;
                }
            ],

            'pembelianpertoko' => [
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

                    if (isset($tmp['tokoid'])) {
                        $d = Toko::where('id', $tmp['tokoid'])->first();
                        if (count($d) > 0) {
                            $tmp['custom']['namaTko'] = $d->namatoko;
                            $tmp['custom']['alamatTko'] = $d->alamat;
                            $tmp['custom']['kotaTko'] = $d->kota;
                            $tmp['custom']['telpTko'] = $d->telp;
                            $tmp['custom']['idwillTko'] = $d->customwilayah;
                            $tmp['custom']['kontakTko'] = $d->penanggungjawab;
                            $tmp['custom']['statusTko'] = "";

                        } else die('Tokoid ' + $tmp['tokoid'] + ' cannot be found');
                    }
                    return false;
                },
                'cabangid' => function (&$out, &$req, &$tmp) {
                    return isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang');
                },
                'riwayat' => function (&$out, &$req, &$tmp) {
                    if (isset($tmp['riwayat'])) return empty($tmp['riwayat']) ? 0 : 1;
                    else return 0;
                }
            ]
        ];

        // the dynamic infos
        $this->tbinfo = [
    		'stockcompare' => [
                'name'      => 'laporanPembandinganItemToko',
    			'title'		=> 'Laporan Pembandingan Item Toko',
                'exports'   => [
                    'excel' => [
                        'active' => true,
                        'text' => 'Download Sebagai Excel'
                    ],
                    'pdf' => [
                        'active' => true,
                        'text' => 'Download Sebagai PDF'
                    ]
                ],
                'heads'     => [
                    'title' => function ($info, $tmp, $typ, $val) {
                        return ($typ == 'pdf' ? '<h3>' : ($typ == 'html' ? '!<h3>' : '')) . $info['title'] . ($typ == 'pdf' || $typ == 'html' ? '</h3>' : '');
                    },
                    'periode1' => function ($info, $tmp, $typ, $val) {
                        return 'Periode 1 : ' . $tmp['custom']['periode1']['tglAwal'] . " s/d " . $tmp['custom']['periode1']['tglAkhir'];
                    },
                    'periode2' => function ($info, $tmp, $typ, $val) {
                        return 'Periode 2 : ' . $tmp['custom']['periode2']['tglAwal'] . " s/d " . $tmp['custom']['periode2']['tglAkhir'];
                    },
                    'namatko' => function ($info, $tmp, $typ, $val) {
                        return 'Nama Toko : ' . $tmp['custom']['namaTko'];
                    },
                    'alamattko' => function ($info, $tmp, $typ, $val) {
                        return 'Alamat Toko : ' . $tmp['custom']['alamatTko'];
                    },
                    'kotatko' => function ($info, $tmp, $typ, $val) {
                        return 'Kota : ' . $tmp['custom']['kotaTko'];
                    },
                    'idwilltko' => function ($info, $tmp, $typ, $val) {
                        return 'ID Will : ' . $tmp['custom']['idwillTko'];
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
    					'kodebarang'   => [
    						'text' => 'Kode Brg'
    					],
                        'namabarang'    => [
                            'text' => 'Nama Barang'
                        ],
                        'qtyyear1'      => [
                            'text' => function ($tmp) { return @$tmp['thn1']; },
                            'type' => 'int'
                        ],
                        'qtyyear2'      => [
                            'text' => function ($tmp) { return @$tmp['thn2']; },
                            'type' => 'int'
                        ],
                        'nt'            => [
                            'text' => 'N/T',
                            'type' => 'int'
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
                                        'qtyyear1' => [ 0, 'int', '+' ],
                                        'qtyyear2' => [ 0, 'int', '+' ],
                                        'nt'       => [ 0, 'int', '+' ],
                                    ];
                                }
                                $this->setFootData($this->tbtmp['ttl'], $val);
                                break;

                            case 'get':
                                $res = [
                                    [ 'text' => 'Total', 'cols' => 3 ]
                                ];
                                foreach ($this->tbtmp['ttl'] as $v) {
                                    $res[] = [
                                        'text' => $v[0],
                                        'type' => isset($v[1]) ? $v[1] : 'int'
                                    ];
                                }
                                return $res;
                        }
                    }
                ]
    		],

            'progressskhu' => [
                'name'      => 'laporanProgressSKUToko',
                'title'     => 'Laporan Progress SKU Toko',
                'exports'   => [
                    'excel' => [
                        'active' => true,
                        'text' => 'Download Sebagai Excel'
                    ],
                    'pdf' => [
                        'active' => true,
                        'text' => 'Download Sebagai PDF'
                    ]
                ],
                'heads'     => [
                    'title' => function ($info, $tmp, $typ, $val) {
                        return ($typ == 'pdf' ? '<h3>' : ($typ == 'html' ? '!<h3>' : '')) . $info['title'] . ($typ == 'pdf' || $typ == 'html' ? '</h3>' : '');
                    },
                    'periode' => function ($info, $tmp, $typ, $val) {
                        return 'Periode : ' . $tmp['custom']['tglAwal'] . " s/d " . $tmp['custom']['tglAkhir'];
                    },
                    'namatko' => function ($info, $tmp, $typ, $val) {
                        return 'Nama Toko : ' . $tmp['custom']['namaTko'];
                    },
                    'alamattko' => function ($info, $tmp, $typ, $val) {
                        return 'Alamat Toko : ' . $tmp['custom']['alamatTko'];
                    },
                    'kotatko' => function ($info, $tmp, $typ, $val) {
                        return 'Kota : ' . $tmp['custom']['kotaTko'];
                    },
                    'idwilltko' => function ($info, $tmp, $typ, $val) {
                        return 'ID Will : ' . $tmp['custom']['idwillTko'];
                    }
                ],
                'cols'      => [
                    [
                        [
                            'text' => 'No',
                            'fn'   => function ($r, $c, $v) {
                                return $r;
                            }
                        ],
                        'kodebarang'   => [
                            'text' => 'Kode Brg'
                        ],
                        'namabarang'    => [
                            'text' => 'Nama Barang'
                        ],
                        'qtyrata2xnetto' => [
                            'text' => 'Qty Rata-rata',
                            'type' => 'int'
                        ],
                        'hrglama'       => [
                            'text' => 'Hrg Terakhir',
                            'type' => 'money'
                        ],
                        'hrgbmk'        => [
                            'text' => 'Hrg BMK',
                            'type' => 'money'
                        ],
                        'stockgudang'   => [
                            'text' => 'Stock',
                            'type' => 'int'
                        ],
                        'qtyorderblnberjalan' => [
                            'text' => 'Qty Kirim',
                            'type' => 'int'
                        ],
                        'followup'      => [
                            'text' => 'Follow Up'
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
                                        'qtyrata2xnetto' => [ 0, 'int', '+' ],
                                        'hrglama' => [ 0, 'int', '+' ],
                                        'hrgbmk' => [ 0, 'int', '+' ],
                                        'stockgudang' => [ 0, 'int', '+' ],
                                        'qtyorderblnberjalan' => [ 0, 'int', '+' ]
                                    ];
                                }
                                $this->setFootData($this->tbtmp['ttl'], $val);
                                break;

                            case 'get':
                                $res = [
                                    [ 'text' => 'Total', 'cols' => 3 ]
                                ];
                                foreach ($this->tbtmp['ttl'] as $v) {
                                    $res[] = [
                                        'text' => $v[0],
                                        'type' => isset($v[1]) ? $v[1] : 'int'
                                    ];
                                }
                                $res[count($res) - 1]['cols'] = 2;
                                return $res;
                        }
                    }
                ]
            ],
            
            'pembelianpertoko' => [
                'name'      => 'laporanPembelianBarangPerToko',
                'title'     => 'Laporan Pembelian Barang Per Toko',
                'exports'   => [
                    'excel' => [
                        'active' => true,
                        'text' => 'Download Sebagai Excel'
                    ],
                    'pdf' => [
                        'active' => true,
                        'text' => 'Download Sebagai PDF'
                    ]
                ],
                'heads'     => [
                    'title' => function ($info, $tmp, $typ, $val) {
                        return ($typ == 'pdf' ? '<h3>' : ($typ == 'html' ? '!<h3>' : '')) . $info['title'] . ($typ == 'pdf' || $typ == 'html' ? '</h3>' : '');
                    },
                    'periode' => function ($info, $tmp, $typ, $val) {
                        return 'Periode : ' . $tmp['custom']['tglAwal'] . " s/d " . $tmp['custom']['tglAkhir'];
                    },
                    'namatko' => function ($info, $tmp, $typ, $val) {
                        return 'Nama Toko : ' . $tmp['custom']['namaTko'];
                    },
                    'alamattko' => function ($info, $tmp, $typ, $val) {
                        return 'Alamat : ' . $tmp['custom']['alamatTko'];
                    },
                    'telptko' => function ($info, $tmp, $typ, $val) {
                        return 'Telp : ' . $tmp['custom']['telpTko'];
                    },
                    'kontaktko' => function ($info, $tmp, $typ, $val) {
                        return 'Kontak : ' . $tmp['custom']['kontakTko'];
                    },
                    'statustko' => function ($info, $tmp, $typ, $val) {
                        return 'Status : ' . $tmp['custom']['statusTko'];
                    }
                ],
                'cols'      => [
                    [
                        [
                            'text' => 'No',
                            'rows' => 2,
                            'fn'   => function ($r, $c, $v) {
                                return $r;
                            }
                        ],
                        'namabarang'   => [
                            'text' => 'Nama Barang',
                            'rows' => 2
                        ],
                        'ke'    => [
                            'text' => 'Ke',
                            'rows' => 2,
                            'val' => 1
                        ],
                        'hrgjual' => [
                            'text' => 'H.Satuan',
                            'type' => 'money',
                            'rows' => 2
                        ],
                        'tglproforma'       => [
                            'text' => 'Tanggal',
                            'type' => 'date',
                            'rows' => 2
                        ],
                        'salesid'        => [
                            'text' => 'Salesman',
                            'type' => 'money',
                            'rows' => 2
                        ],
                        'nonota'   => [
                            'text' => 'No.Nota',
                            'rows' => 2
                        ],
                        'temponota' => [
                            'text' => 'JKW',
                            'rows' => 2
                        ],
                        [
                            'text' => 'Diskon %',
                            'type' => 'int',
                            'cols' => 3
                        ],
                        'qtyretur' => [
                            'text' => 'Retur',
                            'type' => 'int',
                            'rows' => 2
                        ]
                    ], [
                        'disc1' => [
                            'text' => '#1',
                            'type' => 'int'
                        ],
                        'disc2' => [
                            'text' => '#2',
                            'type' => 'int'
                        ],
                        'disc3' => [
                            'text' => '#3',
                            'type' => 'int'
                        ]
                    ]
                ]
            ]
    	];
    }
}
