<?php

namespace App\Models\Interfaces;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Route;
use EXCEL;
use PDF;
use DB;

/**
 * Programming by GearIntellix
 */
class APIMasterController extends Controller
{
    public $permissioned = null;
    protected $apiName  = "test";

    protected $apis = [];
    protected $default = [];
    protected $native = [];

    public function __construct() {
        $this->permissioned = function ($req) {
            $lname = explode(".", $req->route()->getName());
            $lname = array_pop($lname);
            switch ($lname) {
                case "api": return true;
                default: return true;
            }
        };

        $this->__startup();

        // native cannot be modified
        $this->native = [
            // mode: [get, post, delete, put]
            'mode' => ['get', 'post'],
            'params' => [],
            'output' => [
                // type: [json, text]
                'type' => 'json',
                'errorJSON' => [
                    // 'Result' => false,
                    // 'Msg'    => '!%message%'
                ],
                'defaultJSON' => [
                    // 'Result' => false
                ],
                'errorText' => "!Gagal, %message%",
                'defaultText' => "Gagal"
            ],
            'messages' => [
                'apiFailed' => "Installing api failed, please add slug with name [dname]",
                'apiNotFound' => "Sorry your request cannot be found",
                'outputNotValid' => "Internal error, output is not valid",
                'fnNotSetted' => "Internal error, no function handle this request",
                'methodNotAllowed' => "Request di tolak, karena metode tidak diperbolehkan",
                'paramsNotValid' => "!Request di tolak, karena parameter [ %list% ] belum ada"
            ],
            'fn' => function (&$res, $req, $args) {
                /**
                 * Todo here
                 *
                 * [ &$res ]
                 * is the output data, if output type json, this variable type array with value defaultJSON
                 * if output type text, this variable type string with value defaultText
                 *
                 * [ $req ]
                 * is origin request, see laravel Request
                 * 
                 * [ $args ]
                 * structures:
                 *   - params : data parameters
                 *   - dslug  : data from router slug
                 **/

                return false;
            }
        ];
    }

    protected function __startup() {
        // todo here
    }

    /**
     * Private functions
     */

    function isClosure($t) {
        return is_object($t) && ($t instanceof \Closure);
    }

    function isVType($val, $typ) {
        $typ = explode('|', strtolower(strval($typ)));
        foreach ($typ as $t) if (isType($val, $t)) return true;
        return false;
    }

    function isType($val, $typ) {
        $val = gettype($val);
        switch (strtolower($typ)) {
            case 'array': case 'arr':
                return $val == 'array';
            case 'string': case 'str':
                return $val == 'string';
            case 'integer': case 'int':
                return $val == 'integer';
            case 'boolean': case 'bool':
                return $val == 'boolean';
            case 'float':
                return $val == 'float';
            case 'any':
                return true;
            default:
                return $val == strtolower($typ);
        }
    }

    function isAssoc(array $arr) {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    function strSet($str, $val) {
        $str = strval($str);
        if (@$str[0] == "!" && gettype($val) == "array") {
            if (@$str[1] == "!") return substr($str, 1);
            else {
                $str = substr($str, 1);
                foreach ($val as $k => $v) $str = str_ireplace("%" . $k . "%", $v, $str);
                return $str;
            }

        } else return $str;
    }

    function jsonSet($dat, $val) {
        if (gettype($val) != 'array') return $dat;
        switch (gettype($dat)) {
            case 'string':
                return $this->strSet($dat, $val);
                break;
            case 'array':
                foreach ($dat as $k => $v) $dat[$k] = $this->jsonSet($v, $val);
                return $dat;
                break;
            default: return $dat;
        }
    }

    function getOrResolve() {
        $arrs = func_get_args();

        if (gettype($arrs) != "array") $arrs = [];
        switch (count($arrs)) {
            case 0: return $this->getOrResolve($this->default, $this->native); break;
            case 1: return $this->getOrResolve($arrs[0], $this->default, $this->native); break;
            case 2:
                if (gettype($arrs[0]) == "array" && gettype($arrs[1]) == "array" && $this->isAssoc($arrs[1])) {
                    foreach ($arrs[1] as $k => $v) {
                        if (array_key_exists($k, $arrs[0])) {
                            $arrs[0][$k] = $this->getOrResolve($arrs[0][$k], $v);

                        } else $arrs[0][$k] = $this->getOrResolve(null, $v);
                    }
                    return $arrs[0];
                } else return ($arrs[0] === null ? $arrs[1] : $arrs[0]);
                break;
            default:
                $r = [];
                foreach ($arrs as $v) {
                    if (gettype($v) != "array") continue;
                    $r = $this->getOrResolve($r, $v);
                }
                return $r;
                break;
        }
    }

    function outError($type, $form, $data) {
        switch (strtolower($type)) {
            case 'json':
                return $this->jsonSet($form, (gettype($data) == 'array' ? $data : []));

            case 'text': default:
                return $this->strSet($form, (gettype($data) == 'array' ? $data : []));
        }
    }

    /**
     * Public functions
     */
    
    public function api(Request $req) {
        $config = $this->getOrResolve();
        try {
            $route = Route::current();

            $args = [
                'dslug' => $route->parameters(),
                'params' => $req->all()
            ];

            if (array_key_exists('dname', $args['dslug'])) {
                $dname = $args['dslug']['dname'];
                if (isset($this->apis[$dname])) {
                    $config = $this->getOrResolve($this->apis[$dname]);

                    // check methods
                    if (gettype($config['mode']) != 'array') $config['mode'] = [ strval($config['mode']) ];
                    if (!in_array(strtolower($req->getMethod()), $config['mode'])) throw new \Exception($config['messages']['methodNotAllowed']);

                    // check parameters
                    $allow = $this->checkFields($args['params'], $config['params']);
                    if (count($allow) > 0) {
                        $msg = $this->strSet($config['messages']['paramsNotValid'], [
                            'list' => join($this->listFields($allow), ', ')
                        ]);
                        throw new \Exception($msg);
                    }

                    // check fn
                    if (!$this->isClosure($config['fn'])) {
                        throw new \Exception($config['messages']['fnNotSetted']);
                    }

                    // prepare to execute
                    $out = null;
                    $out2 = null;
                    $otype = strtolower($config['output']['type']);
                    switch ($otype) {
                        case 'json': $out = $config['output']['defaultJSON']; break;
                        case 'text': default: $out = $config['output']['defaultText']; break;
                    }

                    // execute and get output data
                    if (($out2 = $config['fn']($out, $req, $args, $config)) === false) {
                        // this output is force to default
                        switch ($otype) {
                            case 'json': $out = $config['output']['defaultJSON']; break;
                            case 'text': default: $out = $config['output']['defaultText']; break;
                        }

                    } else $out = ($out2 !== null ? $out2 : $out);

                    // return output
                    switch ($otype) {
                        case 'json':
                            if (gettype($out) != 'array' || $out === null) throw new \Exception($config['messages']['outputNotValid']);
                            else return response()->json($out);

                        case 'text': default:
                            if (gettype($out) == 'array') throw new \Exception($config['messages']['outputNotValid']);
                            else return strval($out);
                    }

                } else throw new \Exception($config['messages']['apiNotFound']);
            } else throw new \Exception($config['messages']['apiFailed']);

        } catch (\Exception $ex) {
            $errd = [
                'message'   => $ex->getMessage(),
                'line'      => $ex->getLine(),
                'code'      => $ex->getCode(),
                'file'      => $ex->getFile(),
                'trace'     => $ex->getTraceAsString()
            ];
            switch (strtolower($config['output']['type'])) {
                case 'json':
                    $def = $config['output']['errorJSON'];
                    if (count($def) <= 0) $def = [ 'Result' => false ];
                    return response()->json($this->outError($config['output']['type'], $def, $errd));

                case 'text': default:
                    return $this->outError($config['output']['type'], $config['output']['errorText'], $errd);
            }
        }
    }

}
