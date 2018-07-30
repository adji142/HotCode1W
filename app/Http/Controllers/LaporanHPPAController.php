<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Numerator;
use App\Models\SubCabang;
use App\Models\Stock;
use Carbon\Carbon;
use DB;

/* Excel */
use EXCEL;
use PDF;


class LaporanHPPAController extends Controller
{
    protected $tbfunc = [
        'proses'    => 'acc.psp_hppa_execute'
    ];

    protected $tbcols = [
        'proses'    => true
    ];

    protected $tbparam = [
        'proses'    => ['tglawal', 'tglakhir', 'stockid', 'cabangid', 'update', 'userid', 'status', 'groupid']
    ];

    protected $tbconst = [];

    protected $tbopt = [
    ];

    public function __construct()
    {
        // the dinamic constant
        $this->tbconst = [
            'proses' => [
                '_' => function (&$out, &$req, &$tmp) {
                    if (!isset($tmp['custom'])) $tmp['custom'] = [];
                    $tmp['custom']['username'] = auth()->user()->username;
                    $tmp['custom']['kodeCabang'] = SubCabang::where('id', isset($tmp['cabangid']) ? $tmp['cabangid'] : session('subcabang'))->first()->kodesubcabang;

                    if (!isset($tmp['tglawal']) && !isset($tmp['tglakhir'])) {
                        if (isset($tmp['bln']) && isset($tmp['thn'])) {
                            $dt = new \DateTime(intval($tmp['thn']) . '-' . intval($tmp['bln']) . '-01');
                            $tmp['tglawal'] = $dt->format('Y-m-d');

                            $dt = (new \DateTime(intval($tmp['thn']) . '-' . (intval($tmp['bln']) + 1) . '-01'))->sub(new \DateInterval('P1D'));
                            $tmp['tglakhir'] = $dt->format('Y-m-d');

                        } else die('Request not valid, no date range');
                    }
                    $tmp['custom']['tglAwal'] = (new \DateTime($tmp['tglawal']))->format('d/m/Y');
                    $tmp['custom']['tglAkhir'] = (new \DateTime($tmp['tglakhir']))->format('d/m/Y');
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
                },
                'userid' => function (&$out, &$req, &$tmp) {
                    return strtoupper(auth()->user()->username);
                },
                'update' => function (&$out, &$req, &$tmp) {
                    return isset($tmp['update']) ? $tmp['update'] : 1;
                },
                'status' => function (&$out, &$req, &$tmp) {
                    if (isset($tmp['status']) && !isset($tmp['stockid']) && !isset($tmp['kodebarang'])) {
                        switch ($tmp['status']) {
                            case 0: default: return 0; break;
                            case 1: return 1; break;
                            case 2: return 2; break;
                        }
                    } else return 2;
                },
                'groupid' => function (&$out, &$req, &$tmp) {
                    if (!isset($tmp['groupid']) || isset($tmp['stockid']) || isset($tmp['kodebarang'])) return "*";
                    else return false;
                }
            ]
        ];
    }

    function isClosure($t) {
        return is_object($t) && ($t instanceof \Closure);
    }

    public function page(Request $req, $dname, $dsub)
    {
        return view('laporan.hppa.' . '/' . $dname . '/' . $dsub);
    }

    public function index(Request $req, $dname)
    {
        set_time_limit(60 * 60 * 3.5); // 3,5 hours

        $ccabangid = session('subcabang');

        $cols  = @$this->tbcols[$dname];
        $func  = @$this->tbfunc[$dname];
        $param = @$this->tbparam[$dname];
        $const = @$this->tbconst[$dname];
        $opt   = @$this->tbopt[$dname];

        $errMsg = '';

        $fnarg = '';
        $tmp = $req->all();
        foreach ($const as $k => $v) {
            if ($this->isClosure($v)) {
                $res = null;
                $res2 = null;
                if (($res2 = $v($res, $req, $tmp)) !== false) {
                    $tmp[$k] = ($res2 !== null ? $res2 : $res);
                }

            } elseif (gettype($v) === 'string') {
                if ($v[0] === '!' && @$v[1] !== '!') eval("\$tmp[\$k] = " . substr($v, 1));
                else $tmp[$k] = substr($v, 1);

            } else $tmp[$k] = $v;
        }

        $_keys = array_keys($tmp);
        foreach ($param as $v) {
            if (!in_array($v, $_keys)) {
                if ($errMsg != '') $errMsg .= ', ';
                else $errMsg = 'Many data not valid: ';
                $errMsg .= $v;

            } else {
                $vo = $tmp[$v];
                if ($fnarg != '') $fnarg .= ', ';
                switch (strtolower(gettype($vo))) {
                    case 'null': $fnarg .= 'null'; break;
                    case 'double': case 'integer': $fnarg .= strval($vo); break;
                    case 'boolean': $fnarg .= $vo ? '1' : '0'; break;
                    case 'string':
                        $vo = str_replace('\\', '\\\\', $vo);
                        $vo = str_replace('\'', '\\\'', $vo);
                        $fnarg .= '\'' . $vo . '\'';
                        break;
                    default:
                        if ($errMsg != '') $errMsg .= ', ';
                        else $errMsg = 'Many data not valid: ';
                        $errMsg .= $v . ' ' . gettype($vo);
                        break;
                }
            }
        }
        if ($errMsg != '') die($errMsg);

        $data = DB::select(DB::raw("
            select " . ($cols === true ? '*' : (gettype($cols) == 'array' ? join($cols, ', ') : strval($cols))) . "
            from " . $func . "(" . $fnarg . ") " . $opt));

        $namasheet  = str_slug("Laporan HPP Rata-rata");
        $namafile   = $namasheet . "-" . uniqid();

        $username   = strtoupper(auth()->user()->username);
        Excel::create($namafile, function($excel) use ($dname, $tmp, $data, $username, $namasheet) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use ($dname, $tmp, $data, $username) {
                $sheet->loadView(
                    'laporan.hppa.' . $dname . '.excel',
                    array(
                        'name'       => $dname,
                        'args'       => $tmp,
                        'datas'      => $data,
                        'username'   => $username
                    )
                );
            });

        })->store('xls', storage_path('excel/exports'));

        /*$config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 30,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => "SAS",
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $pdf = PDF::loadView('laporan.hppa.' . $dname . '.pdf',[
            'name'       => $dname,
            'args'       => $tmp,
            'datas'      => $data,
            'username'   => $username

        ], [], $config);

        $pdf->save(storage_path('excel/exports') . '/' . $namafile . '.pdf');*/

        $urlExcel = asset('storage/excel/exports/' . $namafile . '.xls');
        $urlPDF   = null; asset('storage/excel/exports/' . $namafile . '.pdf');

        return view('laporan.hppa.' . $dname . '.index', compact('dname', 'tmp', 'data','urlExcel','urlPDF'));
    }

}
