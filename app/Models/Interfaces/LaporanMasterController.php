<?php

namespace App\Models\Interfaces;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use EXCEL;
use PDF;
use DB;

/**
 * Programming by GearIntellix
 */
class LaporanMasterController extends Controller
{
    public $permissioned = null;
    protected $rpname  = "test";

    protected $idxfunc = [];
    protected $tbfunc  = [];
    protected $tbxsys  = [];
    protected $tbcols  = [];
    protected $tbparam = [];
    protected $tbconst = [];
    protected $tbopt   = [];
    protected $tbinfo  = [];
    protected $tbtmp   = [];

    public function __construct() {
        $this->permissioned = function ($req) {
            $lname = explode(".", $req->route()->getName());
            $lname = array_pop($lname);
            switch ($lname) {
                case "index": case "preview": case "process": return true;
                case "pdf": case "excel": return false;
                default: return true;
            }
        };

        $this->__startup();
    }

    protected function __startup() {
        // todo here
    }

    /**
     * Private functions
     */

    function getCellX($i) {
        if ($i <= 0) return ''; else $i -= 1;
        $abjd = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $c = -1;
        $r = "";
        while ($i >= strlen($abjd)) {
            $i -= strlen($abjd);
            $c += 1;
        }
        if ($c >= 0 && $c < strlen($abjd)) $r .= $abjd[$c];
        else if ($c >= 0) $r .= $this->getCellX($c);
        return $r . $abjd[$i];
    }

    function getCell($x, $y) {
        return $this->getCellX($x) . $y;
    }

    function getCellR($x, $y, $w, $h) {
        if (empty($w) && empty($h)) return $this->getCell($x, $y);
        else return $this->getCell($x, $y) . ':' . $this->getCell($x + $w, $y + $h);
    }

    function isClosure($t) {
        return is_object($t) && ($t instanceof \Closure);
    }

    function getSize($str, $sps = 3) {
        return [strlen($str) + abs($sps), 18];
    }

    function strEscape($str) {
        $str = strval($str);
        $str = str_replace('\\', '\\\\', $str);
        $str = str_replace('\'', '\\\'', $str);
        $str = str_replace('%', '\\%', $str);
        return $str;
    }

    function getFormat($val, $frm = null, $cl = 1) {
        $r = [
            'size' => $this->getSize($val, $cl),
            'format' => $frm,
            'text' => $val
        ];
        switch ($r['format']) {
            case 'date':
                $r['format'] = "dd-mm-yyyy";
                $r['text'] = (new \DateTime($val))->format('d-m-Y');
                $r['size'] = $this->getSize($r['text'], $cl);
                break;
            case 'datetime':
                $r['format'] = "dd-mm-yyyy HH:mm:ss";
                $r['text'] = (new \DateTime($val))->format('d-m-Y H:i:s');
                $r['size'] = $this->getSize($r['text'], $cl);
                break;
            case 'int':
                $val = (is_numeric($val) ? intval($val) : 0);
                $r['format'] = ($val == 0 ? null : "#,#0.##;[Red]#,#0.##");
                $r['text'] = number_format($val, 0, ',', '.');
                $r['size'] = $this->getSize($r['text'], $cl);
                break;
            case 'percent':
                $val = (is_numeric($val) ? intval($val) : 0);
                $r['format'] = "0%";
                $r['text'] = $val . '%';
                $r['size'] = $this->getSize($r['text'], $cl);
                break;
            case 'money':
                $val = (is_numeric($val) ? floatval($val) : 0);
                $r['format'] = ($val == 0 ? null : "#.###,00");
                $r['text'] = number_format($val, 2, ',', '.');
                $r['size'] = $this->getSize($r['text'], $cl);
                break;

            default: $r['format'] = null;
        }
        return $r;
    }

    function report($data, $inv = 0) {
        echo json_encode($data) . ",";
        ob_flush(); flush();
        if ($inv > 0) sleep($inv);
    }

    function setFootData(&$arr, $data) {
        if (gettype($data) != 'array') return [];
        if (gettype($arr) != 'array') $arr = [];
        if (count($arr) <= 0) {
            foreach ($data as $k => $v) {
                if (is_numeric($v)) $arr[$k] = [ 0, 'int', '+' ];
                else $arr[$k] = [ null, 'any', '=' ];
            }
        }
        
        foreach ($arr as $k => $v) {
            if (gettype($v) != 'array' || (gettype($v) == 'array' && !isset($v[0]))) continue;
            if(isset($data[$k])) {
                switch (isset($v[2]) ? $v[2] : '=') {
                    default: case '=': $arr[$k][0] = $data[$k]; break;
                    case '+': $arr[$k][0] += floatval($data[$k]); break;
                    case '-': $arr[$k][0] -= floatval($data[$k]); break;
                }
            }
        }
        return $arr;
    }

    function genConstant($req, $const) {
        $tmp = $req->all();
        if (isset($const)) {
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
        }
        return $tmp;
    }

    function genUICollumn($tmp, $info) {
        $uicols = [ 'h' => [], 'r' => [], 's' => [] ];
        if (isset($info['cols'])) {
            $max = 0;
            $tmpx = [];
            foreach ($info['cols'] as $k => $v) {
                if (gettype($v) == "array") {
                    $cmax = 0;
                    $tmp2 = [];
                    foreach ($v as $k2 => $v2) {
                        if (gettype($v2) == "array") {
                            $tmp3 = $v2;
                            $tmp3['name'] = $k2;
                            $tmp3['text'] = isset($v2['text']) ? ($this->isClosure($v2['text']) ? $v2['text']($tmp) : $v2['text']) : $k2;
                            $tmp3['rows'] = isset($v2['rows']) ? intval($v2['rows']) : 1;
                            $tmp3['cols'] = isset($v2['cols']) ? intval($v2['cols']) : 1;
                            $tmp2[] = $tmp3;

                            $cmax += intval($tmp3['cols']);

                        } else {
                            $tmp2[] = ['text' => $v2, 'name' => $k2, 'rows' => 1, 'cols' => 1];
                            $cmax += 1;
                        }
                    }
                    $tmpx[] = $tmp2;
                    if ($cmax > $max) $max = $cmax;

                } else {
                    $tmpx[] = [['text' => $v, 'name' => $k, 'rows' => 1, 'cols' => 1]];
                    $max += 1;
                }
            }

            $tmp2 = [];
            foreach ($tmpx as $k => $v) {
                $tmp3 = [];
                $tmp4 = count($tmp2) > 0 ? end($tmp2) : null;
                $ii = 0;
                for ($i = 0; $i < $max; ) {
                    if (isset($v[$ii])) {
                        $cc = $v[$ii];
                        for($i2 = 0; $i2 < $cc['cols']; $i2++) {
                            if ($tmp4 !== null && @$tmp4[$i]['rows'] > 1) {
                                $tmp5 = $tmp4[$i];
                                $tmp5['dat'] = $tmp5['dat'];
                                $tmp5['rows'] -= 1;
                                $tmp5['_'] = 2;
                                $tmp3[] = $tmp5;
                                $i2 -= 1;
                                $i += 1;

                            } else {
                                $tmp5 = ['name' => $cc['name'], 'rows' => $cc['rows'], 'dat' => $cc, '_' => 1];
                                if ($i2 > 0) $tmp5['_'] = 0;
                                else $ii += 1;
                                $tmp3[] = $tmp5;
                                $i += 1;
                            }
                        }

                    } elseif (intval($k) > 0 && isset($tmp2[intval($k) - 1][$i])) {
                        $cc = $tmp2[intval($k) - 1][$i];
                        $tmp3[] = ['name' => $cc['name'], 'rows' => ($cc['rows'] - 1), 'dat' => $cc, '_' => 2];
                        $i += 1;

                    } else {
                        $tmp3[] = null;
                        $i += 1;
                    }
                }
                $tmp2[] = $tmp3;
            }

            foreach ($tmp2 as $k => $v) {
                $tmp4 = [];
                if ($v !== null) {
                    $ii = 0;
                    foreach ($v as $k2 => $v2) {
                        if (!isset($uicols['s'][$ii])) $uicols['s'][$ii] = 0;
                        switch ($v2['_']) {
                            case 0: // col spanded
                                break;
                            case 1: // headed
                                $cs = $this->getSize($v2['dat']['text']);
                                if ($v2['dat']['cols'] == 1 && $uicols['s'][$ii] < $cs[0]) $uicols['s'][$ii] = $cs[0];
                                $tmp4[] = $v2['dat'];
                                break;
                            case 2: // row spanded
                                $tmp4[] = false;
                                break;
                            default: // unknown
                                $tmp4[] = null;
                                break;
                        }
                        if (end($tmp2) == $v) $uicols['r'][$v2['dat']['name']] = $v2['dat'];
                        $ii += 1;
                    }
                    $uicols['h'][] = $tmp4;
                }
            }
        }
        return $uicols;
    }

    /**
     * Public functions
     */

    public function index(Request $req, $dname) {
        $func = @$this->idxfunc[$dname];
        $info = @$this->tbinfo[$dname];
        $rpname = $this->rpname;
        $data = [];

        if ($this->isClosure($func)) {
            $res = null;
            $res2 = null;
            if (($res2 = $func($res, $req, $dname, $info)) !== false) {
                $data = ($res2 !== null ? $res2 : $res);
            }
        } else if (gettype($func) == 'array') $data = $func;

    	return view(
            isset($data['view']) ? $data['view'] : 'laporan.' . $this->rpname . '.' . $dname . '.index',
            compact('data', 'info', 'rpname', 'dname')
        );
    }

    public function excel(Request $req, $dname) {
    	return view('laporan.dynamic.excel');
    }

    public function pdf(Request $req, $dname) {
    	return view('laporan.dynamic.pdf');
    }

    public function preview(Request $req, $dname)
    {
        $cols  = @$this->tbcols[$dname];
        $func  = @$this->tbfunc[$dname];
        $const = @$this->tbconst[$dname];
        $info  = @$this->tbinfo[$dname];

        if (!$func || !$info) die('Report not valid');
        $errMsg = '';

        // generate dynamic constants
        $tmp = $this->genConstant($req, $const);

        // generate requirement of columns
        $uicols = $this->genUICollumn($tmp, $info);

        if (isset($info['approxTotal'])) {
            if ($this->isClosure($info['approxTotal'])) $info['approxTotal'] = intval($info['approxTotal']($tmp));
            else $info['approxTotal'] = intval($info['approxTotal']);
        } else $info['approxTotal'] = -1;

        $procurl = "";
        foreach ($_GET as $ii => $vv) {
            if ($procurl != '') $procurl .= '&';
            $procurl .= urlencode($ii) . '=' . urlencode($vv);
        }
        $procurl = route("laporan." . $this->rpname . ".process", ['dname' => $dname]) . '?' . $procurl;

        return view('laporan.dynamic.preview', compact('dname', 'procurl', 'tmp', 'uicols', 'info'));
    }

    public function process(Request $req, $dname) {
        ini_set("max_execution_time", (60 * 60 * 3)); // 3 hours
        ini_set("memory_limit", "2048M"); // 2 gigabytes
        
        $cols  = @$this->tbcols[$dname];
        $func  = @$this->tbfunc[$dname];
        $xsys  = @$this->tbxsys[$dname];
        $param = @$this->tbparam[$dname];
        $const = @$this->tbconst[$dname];
        $info  = @$this->tbinfo[$dname];
        $opt   = @$this->tbopt[$dname];

        if (!$func || !$info) die('Report not valid');
        $errMsg = '';

        // generate dynamic constants
        $tmp = $this->genConstant($req, $const);

        // generate requirement of columns
        $uicols = $this->genUICollumn($tmp, $info);

        // generate dynamic collumns
        if ($this->isClosure($cols)) {
            $res = null;
            $res2 = null;
            if (($res2 = $cols($res, $req, $tmp)) !== false) {
                $cols = ($res2 !== null ? $res2 : $res);
            }
        }

        // generate dynamic functions
        if ($this->isClosure($func)) {
            $res = null;
            $res2 = null;
            if (($res2 = $func($res, $req, $tmp)) !== false) {
                $func = ($res2 !== null ? $res2 : $res);
            }
        }

        // generate dynamic parameters
        if ($this->isClosure($param)) {
            $res = null;
            $res2 = null;
            if (($res2 = $param($res, $req, $tmp)) !== false) {
                $param = ($res2 !== null ? $res2 : $res);
            }
        }

        // generate arguments
        $fnarg = '';
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
                        $fnarg .= '\'' . $this->strEscape($vo) . '\'';
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

        $namasheet  = (isset($info['sheetname']) ? $info['sheetname'] : "Laporan");
        $namafile   = str_slug($info['title']) . "-" . uniqid();
        $username   = strtoupper(auth()->user()->username);

        $ri = 0;
        $data = [];
        $total = 0;
        $cloops = 0;
        $counter = 0;
        $mh = count($uicols['s']);
        $sdate = $xdate = new \DateTime();

        // generate dynamic footers
        if (isset($info['foots'])) {
            if ($this->isClosure($info['foots'])) $info['foots'] = $info['foots']($info, $tmp);
            else if (gettype($info['foots']) != 'array') $info['foots'] = [$info['foots']];
        }

        $firstData = null;
        $xxcel = Excel::create($namafile, function($excel) use(&$xdate, &$ri, &$cloops, &$total, &$counter, &$data, &$firstData, $func, &$xsys, $cols, $param, $fnarg, $mh, $namasheet, $namafile, $username, $dname, &$tmp, &$uicols, &$info) {
            $excel->setTitle($namasheet);
            $excel->setCreator(strtoupper($username))->setCompany('SAS');
            $excel->setmanager(strtoupper($username));
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy(strtoupper($username));
            $excel->setDescription($namasheet);
            $excel->sheet($namasheet, function($sheet) use(&$xdate, &$ri, &$cloops, &$total, &$counter, &$data, &$firstData, $func, &$xsys, $cols, $param, $fnarg, $mh, $dname, &$tmp, &$uicols, &$info) {
                // reporter
                $this->report([
                    'mem' => round(memory_get_usage(false) / 1024 / 1024, 2),
                    'time' => $xdate->format('H:i:s'),
                    'status' => 'Querying data...'
                ]);

                if (!$this->isClosure($xsys)) {
                    $xsys = function ($arg, $cbx) {
                        $bdt = DB::table(DB::raw($arg['func'] . "(" . $arg['fnarg'] . ") "))
                        ->selectRaw(($arg['cols'] === true ? '*' : (gettype($arg['cols']) == 'array' ? join($arg['cols'], ', ') : strval($arg['cols']))))
                        ->get();

                        $chunk = isset($arg['info']['chunk']) ? $arg['info']['chunk'] : 1000;
                        $sdt = [];
                        $ii = 0;

                        foreach ($bdt as $dt) {
                            $sdt[] = $dt;
                            $ii += 1;

                            if ($ii >= $chunk) {
                                $cbx($sdt);
                                $sdt = [];
                                $ii = 0;
                            }
                        }
                        if (count($sdt) > 0) $cbx($sdt);

                        // flush memory
                        $sdt = null; unset($sdt);
                        $bdt = null; unset($bdt);
                    };
                }

                //DB::table(DB::raw($func . "(" . $fnarg . ") "))
                //->selectRaw(($cols === true ? '*' : (gettype($cols) == 'array' ? join($cols, ', ') : strval($cols))))
                //->chunk(1000, function ($dtbl) use(&$xdate, &$ri, &$cloops, &$total, &$counter, &$data, &$sheet, $mh, $dname, &$tmp, &$uicols, &$info) {
                $xsys([
                    'fnarg' => $fnarg,
                    'param' => $param,
                    'func'  => $func,
                    'cols'  => $cols,
                    'info'  => $info,
                    'tmp'   => $tmp

                ], function ($dtbl) use(&$xdate, &$ri, &$cloops, &$total, &$counter, &$data, &$firstData, &$sheet, $mh, $dname, &$tmp, &$uicols, &$info) {
                    // reporter
                    $ydate = new \DateTime();
                    $idate = $ydate->diff($xdate);
                    $xdate = $ydate;
                    $this->report([
                        'mem' => round(memory_get_usage(false) / 1024 / 1024, 2),
                        'interval' => $idate->format('%h H %i M %s S'),
                        'value' => ($total + count($dtbl)),
                        'time' => $ydate->format('H:i:s'),
                        'status' => 'Processing data...'
                    ]);

                    $sdata = [];
                    foreach ($dtbl as $row) {
                        $vv = get_object_vars($row);
                        if ($firstData === null) $firstData = $vv;
                        $sdata[] = $vv;
                        $total += 1;

                        // generate dynamic footers
                        if (isset($info['foots'])) {
                            foreach ($info['foots'] as $k => $d) if ($this->isClosure($d)) $d('set', $info, $tmp, $vv);
                        }                
                    }

                    // know the limit
                    if ($total <= 1000) foreach ($sdata as $d) $data[] = $d;
                    else $data = [];

                    if ($cloops === 0) {
                        if (isset($info['exports']['excel']) && $info['exports']['excel']['active'] === true) {
                            //Excel::create($namafile, function($excel) use (&$ri, &$counter, $mh, $dname, &$tmp, $sdata, &$uicols, &$info, $username, $namasheet) {
                                /*$excel->setTitle($namasheet);
                                $excel->setCreator(strtoupper($username))->setCompany('SAS');
                                $excel->setmanager(strtoupper($username));
                                $excel->setsubject($namasheet);
                                $excel->setlastModifiedBy(strtoupper($username));
                                $excel->setDescription($namasheet);
                                $excel->sheet($namasheet, function($sheet) use (&$ri, &$counter, $mh, $dname, &$tmp, $sdata, &$uicols, &$info, $username) {*/
                                    $sheet->setAutoSize(false);

                                    // heads
                                    foreach ($info['heads'] as $h) {
                                        $ri += 1;
                                        if ($this->isClosure($h)) $h = $h($info, $tmp, 'excel', $firstData);
                                        $sheet->cell($this->getCell(1, $ri), function ($cell) use ($h) {
                                            $cell->setAlignment('left');
                                            $cell->setValue($h);
                                        });
                                        $cs = $this->getSize($h);
                                        $sheet->mergeCells($this->getCellR(1, $ri, $mh - 1, 0));
                                        $sheet->setSize($this->getCell(1, $ri), $cs[0], 18);
                                    }
                                    $ri += 1;

                                    // tbheads
                                    $sheet->setBorder($this->getCellR(1, $ri + 1, $mh - 1, count($uicols['h']) - 1), 'thin');
                                    foreach ($uicols['h'] as $ii => $vv) {
                                        $ii3 = 0;
                                        foreach ($vv as $ii2 => $vv2) {
                                            if ($vv2 === false) {
                                                $ii3 += 1;
                                                continue;
                                            }
                                            $sty = [
                                                'alignment' => [
                                                    'horizontal' => 'center',
                                                    'vertical' => 'center'
                                                ]
                                            ];
                                            $sheet->getStyle($this->getCell($ii3 + 1, $ri + $ii + 1))->applyFromArray($sty);
                                            $sheet->cell($this->getCell($ii3 + 1, $ri + $ii + 1), function ($cell) use ($vv2, $uicols) {
                                                $cell->setBackground('#ccdfff');
                                                $cell->setValue($vv2['text']);
                                            });
                                            $sheet->setSize($this->getCell($ii3 + 1, $ri + $ii + 1), $uicols['s'][$ii3], 18);
                                            if ($vv2['cols'] > 1 || $vv2['rows'] > 1) {
                                                if ($vv2['cols'] > 1) {
                                                    $ts = 0;
                                                    $cs = $this->getSize($vv2['text']);
                                                    for ($ii4 = $ii3; $ii4 < ($ii3 + $vv2['cols']); $ii4++) $ts += $uicols['s'][$ii4];
                                                    if ($cs[0] > $ts) for ($ii4 = $ii3; $ii4 < ($ii3 + $vv2['cols']); $ii4++) $uicols['s'][$ii4] = round(($uicols['s'][$ii4] / $ts) * $cs[0], 2);
                                                    $sheet->setSize($this->getCell($ii3 + 1, $ri + $ii + 1), $uicols['s'][$ii3], 18);
                                                }
                                                $sheet->mergeCells($this->getCellR($ii3 + 1, $ri + $ii + 1, $vv2['cols'] - 1, $vv2['rows'] - 1));
                                                $sheet->getStyle($this->getCell($ii3 + 1, $ri + $ii + 1))->applyFromArray($sty);
                                                $ii3 += $vv2['cols'];
                                            } else $ii3 += 1;
                                        }
                                    }
                                    $ri += count($uicols['h']);
                                    $sheet->setFreeze($this->getCell(1, $ri + 1));

                                    //tbrows
                                    $sheet->setBorder($this->getCellR(1, $ri, $mh - 1, count($sdata)), 'thin');
                                    foreach ($sdata as $vv) {
                                        $ii = 0;
                                        $ri += 1;
                                        $counter += 1;
                                        foreach ($uicols['r'] as $ii2 => $vv2) {
                                            $ii += 1;
                                            $sheet->cell($this->getCell($ii, $ri), function ($cell) use ($ii2, $counter, $vv2, $ii, $vv, $ri, &$sheet, &$uicols) {
                                                $vv3 = null;
                                                if ($this->isClosure(@$vv2['fn'])) $vv3 = $vv2['fn']($counter, $ii2, $vv);
                                                else $vv3 = isset($vv2['val']) ? $vv2['val'] : @$vv[$ii2];

                                                $csx = $this->getFormat($vv3, @$vv2['type'], 1);
                                                if ($csx['format'] !== null) $sheet->getStyle($this->getCell($ii, $ri))->getNumberFormat()->setFormatCode($csx['format']);
                                                if ($uicols['s'][$ii - 1] < $csx['size'][0]) $uicols['s'][$ii - 1] = $csx['size'][0];
                                                $cell->setValue($vv3);
                                            });
                                            $sheet->setSize($this->getCell($ii, $ri), $uicols['s'][$ii - 1], 18);
                                        }
                                    }
                                //});

                            //})->store('xls', storage_path('excel/exports'));
                        }
                
                    } else {
                        if (isset($info['exports']['excel']) && $info['exports']['excel']['active'] === true) {
                            //Excel::load(storage_path('excel/exports') . '/' . $namafile . '.xls', function($excel) use (&$ri, &$counter, $mh, $dname, &$tmp, $sdata, &$uicols, &$info, $namasheet) {
                                //$excel->sheet($namasheet, function ($sheet) use (&$ri, &$counter, $mh, $dname, &$tmp, $sdata, &$uicols, &$info) {
                                    //tbrows
                                    $sheet->setBorder($this->getCellR(1, $ri, $mh - 1, count($sdata)), 'thin');
                                    foreach ($sdata as $vv) {
                                        $ii = 0;
                                        $ri += 1;
                                        $counter += 1;
                                        foreach ($uicols['r'] as $ii2 => $vv2) {
                                            $ii += 1;
                                            $sheet->cell($this->getCell($ii, $ri), function ($cell) use ($ii2, $counter, $vv2, $ii, $vv, $ri, &$sheet, &$uicols) {
                                                $vv3 = null;
                                                if ($this->isClosure(@$vv2['fn'])) $vv3 = $vv2['fn']($counter, $ii2, $vv);
                                                else $vv3 = isset($vv2['val']) ? $vv2['val'] : @$vv[$ii2];

                                                $csx = $this->getFormat($vv3, @$vv2['type'], 1);
                                                if ($csx['format'] !== null) $sheet->getStyle($this->getCell($ii, $ri))->getNumberFormat()->setFormatCode($csx['format']);
                                                if ($uicols['s'][$ii - 1] < $csx['size'][0]) $uicols['s'][$ii - 1] = $csx['size'][0];
                                                $cell->setValue($vv3);
                                            });
                                            $sheet->setSize($this->getCell($ii, $ri), $uicols['s'][$ii - 1], 18);
                                        }
                                    }
                                //});

                            //});->store('xls', storage_path('excel/exports'), false);
                        }
                    }
                    $cloops += 1;

                    // flush memory
                    $sdata = null; unset($sdata);
                    $dtbl = null; unset($dtbl);
                });

                // reporter
                $ydate = new \DateTime();
                $idate = $ydate->diff($xdate);
                $xdate = $ydate;
                $this->report([
                    'mem' => round(memory_get_usage(false) / 1024 / 1024, 2),
                    'interval' => $idate->format('%h h %i m %s s'),
                    'time' => $ydate->format('H:i:s'),
                    'status' => 'Exporting data...',
                    'value' => $total
                ]);

                // generate dynamic footers
                if (isset($info['foots'])) {
                    foreach ($info['foots'] as $k => $d) {
                        if ($this->isClosure($d)) $info['foots'][$k] = $d('get', $info, $tmp, null);
                        else if (gettype($d) != 'array') $info['foots'][$k] = [['text' => $d, 'rows' => 1, 'cols' => 1]];

                        foreach ($info['foots'][$k] as $k2 => $d2) {
                            if (!isset($d2['cols'])) $info['foots'][$k][$k2]['cols'] = 1;
                            else if ($d2['cols'] < 1) $info['foots'][$k][$k2]['cols'] = 1;
                            if (!isset($d2['rows'])) $info['foots'][$k][$k2]['rows'] = 1;
                            else if ($d2['rows'] < 1) $info['foots'][$k][$k2]['rows'] = 1;
                        } 
                    }
                }

                if (isset($info['foots']) && isset($info['exports']['excel']) && $info['exports']['excel']['active'] === true) {
                    // Excel::load(storage_path('excel/exports') . '/' . $namafile . '.xls', function($excel) use (&$ri, $mh, $dname, &$tmp, &$uicols, &$info, $namasheet) {
                        //$excel->sheet($namasheet, function ($sheet) use (&$ri, $mh, $dname, &$tmp, &$uicols, &$info) {
                            // tbfoots
                            $sheet->setBorder($this->getCellR(1, $ri + 1, $mh - 1, count($info['foots']) - 1), 'thin');
                            foreach ($info['foots'] as $ii => $vv) {
                                $ii3 = 0;
                                foreach ($vv as $ii2 => $vv2) {
                                    if ($vv2 === false) {
                                        $ii3 += 1;
                                        continue;
                                    }

                                    $csx = $this->getFormat($vv2['text'], @$vv2['type'], 1);
                                    if ($csx['format'] !== null) $sheet->getStyle($this->getCell($ii3 + 1, $ri + $ii + 1))->getNumberFormat()->setFormatCode($csx['format']);

                                    $sheet->cell($this->getCell($ii3 + 1, $ri + $ii + 1), function ($cell) use ($vv2, $info, $uicols) {
                                        $cell->setBackground('#e8e8e8');
                                        $cell->setValue($vv2['text']);
                                    });
                                    $sheet->setSize($this->getCell($ii3 + 1, $ri + $ii + 1), $uicols['s'][$ii3], 18);
                                    if ($vv2['cols'] > 1 || $vv2['rows'] > 1) {
                                        if ($vv2['cols'] > 1) {
                                            $ts = 0;
                                            for ($ii4 = $ii3; $ii4 < ($ii3 + $vv2['cols']); $ii4++) $ts += $uicols['s'][$ii4];
                                            if ($csx['size'][0] > $ts) for ($ii4 = $ii3; $ii4 < ($ii3 + $vv2['cols']); $ii4++) $uicols['s'][$ii4] = round(($uicols['s'][$ii4] / $ts) * $cs['size'][0], 2);
                                            $sheet->setSize($this->getCell($ii3 + 1, $ri + $ii + 1), $uicols['s'][$ii3], 18);
                                        }
                                        $sheet->mergeCells($this->getCellR($ii3 + 1, $ri + $ii + 1, $vv2['cols'] - 1, $vv2['rows'] - 1));
                                        $ii3 += $vv2['cols'];
                                    } else $ii3 += 1;
                                }
                            }
                            $ri += count($uicols['h']);
                        //});

                    //})->store('xls', storage_path('excel/exports'), false);
                    //$info['exports']['excel']['url'] = asset('storage/excel/exports/' . $namafile . '.xls');
                }
            });
        });

        if ($total > 0 && isset($info['exports']['excel']) && $info['exports']['excel']['active'] === true) {
            $xxcel->store('xls', storage_path('excel/exports'));
            $info['exports']['excel']['url'] = asset('storage/excel/exports/' . $namafile . '.xls');

            // flush memory
            $xxcel = null; unset($xxcel);
        }

        // max pdf is 500 records
        if (count($data) > 0 && $total <= 500 && isset($info['exports']['pdf']) && $info['exports']['pdf']['active'] === true) {
            $pdf = PDF::loadView('laporan.dynamic.pdf', [
                'name'       => $dname,
                'args'       => $tmp,
                'datas'      => $data,
                'cols'       => $uicols,
                'info'       => $info,
                'username'   => $username
            ],
            [],
            [
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
            ]);
            $pdf->save(storage_path('excel/exports') . '/' . $namafile . '.pdf');
            $info['exports']['pdf']['url'] = asset('storage/excel/exports/' . $namafile . '.pdf');
        }

        // reporter
        $ydate = new \DateTime();
        $idate = $ydate->diff($xdate);
        $xdate = $ydate;
        $this->report([
            'mem'      => round(memory_get_usage(false) / 1024 / 1024, 2),
            'interval' => $idate->format('%h h %i m %s s'),
            'time'     => $ydate->format('H:i:s'),
            'status'   => 'Fetching data...'
        ]);

        // reporter
        $ydate = new \DateTime();
        $idate = $ydate->diff($sdate);
        $done = [
            'done'     => $total > 0,
            'mem'      => round(memory_get_usage(false) / 1024 / 1024, 2),
            'interval' => $idate->format('%h h %i m %s s'),
            'time'     => $ydate->format('H:i:s'),
            'total'    => $total,
            'exports'  => [],
            'notice'   => [],
            'data'     => [],
            'head'     => []
        ];

        if (isset($info['heads'])) {
            foreach ($info['heads'] as $h) {
                if ($this->isClosure($h)) $h = $h($info, $tmp, 'html', $firstData);
                $done['head'][] = $h;
            }
        }

        if ($total > 500 && isset($info['exports']['pdf']) && $info['exports']['pdf']['active'] === true) {
            $done['notice'][] = [
                'type' => 'warning',
                'text' => 'Tidak dapat export ke PDF karena data terlalu besar'
            ];
        }

        if (count($data) > 0) {
            $ccounter = 0;
            foreach ($data as $vv) {
                $sdata = [];
                $ccounter += 1;
                foreach ($uicols['r'] as $ii2 => $vv2) {
                    $vv3 = null;
                    if ($this->isClosure(@$vv2['fn'])) $vv3 = $vv2['fn']($ccounter, $ii2, $vv);
                    else $vv3 = isset($vv2['val']) ? $vv2['val'] : @$vv[$ii2];
                    $sdata[] = $vv3;
                }
                $done['data'][] = $sdata;

                // flush memory
                $sdata = null; unset($sdata);
            }
        }

        if (isset($info['exports'])) {
            foreach ($info['exports'] as $k => $v) {
                if (!isset($v['url'])) continue;
                $done['exports'][$k] = [
                    'text' => $v['text'],
                    'url'  => $v['url']
                ];
            }
        }

        $this->report($done, 1);

        // flush memory
        $done = null; unset($done);
        gc_collect_cycles();
    }
}
