<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Route;
use File;

use App\Models\Permission;

class PermissionController extends Controller
{
    function strEscape($str) {
        $str = strval($str);
        $str = str_replace('\\', '\\\\', $str);
        $str = str_replace('\'', '\\\'', $str);
        $str = str_replace('%', '\\%', $str);
        return $str;
    }

    public function index()
    {
        $dataObj = Permission::where('groupapps','TRADING')->orderByRaw("STRING_TO_ARRAY(nested, '.')::int[] ASC")->get();
        return view('permission.index',['dataObj' => $dataObj]);
    }

    public function sidebar()
    {
        $permissionObj = Permission::select(
                DB::raw("*, ARRAY_LENGTH(STRING_TO_ARRAY(nested, '.'), 1) - 1 AS depth,
                    CASE
                        WHEN (ARRAY_LENGTH(STRING_TO_ARRAY(nested, '.'), 1) - 1) > 0
                        THEN SUBSTRING(nested,1,LENGTH(nested)-(LENGTH(SPLIT_PART(REVERSE(nested), '.', 1))+1))
                        ELSE null
                    END AS parent")
                )
            ->where('asmenu', '=', 1)
            ->where('groupapps','TRADING')
            ->orderByRaw("STRING_TO_ARRAY(nested, '.')::int[] ASC")
            ->get();
        $data = $this->sidebar_loop($permissionObj);

        $dirpath = base_path().'/resources/views/includes/';
        if(!File::isDirectory($dirpath)) File::makeDirectory($dirpath, 0775, true);
        if(File::exists($dirpath.'sidebar-dynamic.blade.php')) File::delete($dirpath.'sidebar-dynamic.blade.php');

        File::put($dirpath.'sidebar-dynamic.blade.php',$data);
        return redirect()->route('permission.index')->with('message', ['status'=>'success','desc'=>"Menu Sidebar telah sukses diperbarui."]);
    }

    private function sidebar_loop($data,$nested=null,$loop=0)
    {
        $parent = array_unique(array_flatten(array_pluck($data, 'parent')));
        $return = "";

        foreach ($data as $row) {
            $hasChild = array_search($row->nested,$parent);

            $routeName = $this->strEscape($row->slug);
            $routeArg = '\'' . $this->strEscape($row->slug) . '\'';
            $sg = explode(",", $row->slug);
            if (count($sg) > 1) {
                // dynamic detected
                $params = [];
                $routers = Route::getRoutes();
                foreach ($routers as $rd) {
                    if ($rd->getName() == $sg[0]) {
                        foreach ($rd->parameterNames() as $i => $p) {
                            $params[] = "'" . $p . "' => '" . $this->strEscape(isset($sg[$i + 1]) ? $sg[$i + 1] : "") . "'";
                        }
                        break;
                    }
                }
                if (count($params) > 0) $routeArg = "'" . $this->strEscape($sg[0]) . "', array(" . join($params, ",") . ")";
                $routeName = $sg[0];
            }

            if($row->parent == $nested) {
                if($row->parent == null) {
                    $return .= '@can(\'' . $this->strEscape($row->slug) . '\')'."\n";
                    $return .= '<div class="menu_section">'."\n";
                    $return .= '<h3>'.$row->name.'</h3>'."\n";
                    if($hasChild) {
                        $return .= '<ul class="nav side-menu">'."\n";
                        $return .= $this->sidebar_loop($data,$row->nested);
                        $return .= '</ul>'."\n";
                    }
                    $return .= '</div>'."\n";
                    $return .= '@endcan'."\n";
                }else{
                    if(Route::has($routeName)){
                        $return .= '@can(\'' . $this->strEscape($row->slug) . '\')'."\n";
                        $return .= '<li>'."\n";
                        $return .= '<a href="{{ route(' . $routeArg . ') }}">'."\n";
                    }else{
                        $return .= '<li>'."\n";
                        $return .= '<a href="javascript:void(0)">'."\n";
                    }
                    if($row->depth == 1) {
                        $return .= '<i class="fa '.$row->icon.'"></i>'."\n";
                    }
                    $return .= '<span>'.$row->name.'</span>'."\n";
                    if($hasChild) {
                        $return .= '<span class="fa fa-chevron-down"></span>';
                    }
                    $return .= '</a>'."\n";
                    if($hasChild) {
                        $return .= '<ul class="nav child_menu">'."\n";
                        $return .= $this->sidebar_loop($data,$row->nested);
                        $return .= '</ul>'."\n";
                    }
                    $return .= '</li>'."\n";
                    if(Route::has($routeName)){
                        $return .= '@endcan'."\n";
                    }
                }
            }
        }

        return $return;
    }

}
