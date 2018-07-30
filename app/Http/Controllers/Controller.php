<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function strRandom($len = 15) {
        $a = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $cl = strlen($a);
        $r = '';
        for ($i = 0; $i < $len; $i++) {
            $r .= $a[rand(0, $cl - 1)];
        }
        return $r;
    }

    function checkFields($data, $fields) {
    	if (gettype($fields) != "array") $fields = [ $fields ];
    	if (gettype($data) != "array") return $fields;
    	foreach ($fields as $k => $v) {
    		if (gettype($v) == "array") {
    			if (isset($data[$k])) if (count($this->CheckFields($data[$k], $v)) <= 0) unset($fields[$k]);
    		} else if (array_key_exists(strval($v), $data)) unset($fields[$k]);
    	}
    	return $fields;
    }

    function listFields($fields, $par = null) {
        $list = [];
        if (gettype($fields) != "array") $fields = [ $fields ];
        foreach ($fields as $k => $v) {
            if (gettype($v) == "array") {
                $lss = $this->listFields($v, ($par === null ? $k : $par . '.' . $k));
                foreach ($lss as $ls) $list[] = $ls;

            } else $list[] = ($par === null ? $v : $par . '.' . $v);
        }
        return $list;
    }

    function strEscape($str) {
        $str = str_replace("\\", "\\\\", $str);
        $str = str_replace("\"", "\\\"", $str);
        $str = str_replace("'", "\\'", $str);
        return $str;
    }
}
