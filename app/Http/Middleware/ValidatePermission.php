<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class ValidatePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $route = 'home')
    {
        // Check if date missing
        $request->tglmulai = ($request->tglmulai == "" || $request->tglmulai == "00-00-0000"|| $request->tglmulai == "0000-00-00") ? date('d-m-Y') : $request->tglmulai;
        $request->tglselesai = ($request->tglselesai == "" || $request->tglselesai == "00-00-0000"|| $request->tglselesai == "0000-00-00") ? date('d-m-Y') : $request->tglselesai;

        $croute = $request->route();
        $ccontroller = $croute->getController();
        $permission = $request->route()->getName();

        $check = false;
        if (isset($ccontroller->permissioned)) {
            if (is_object($ccontroller->permissioned) && ($ccontroller->permissioned instanceof \Closure)) {
                $check = $ccontroller->permissioned->bindTo($ccontroller, $request);
            } else $check = $ccontroller->permissioned ? true : false;
        }

        if (count($croute->parameterNames()) > 0 && $check) {
            foreach ($croute->parameterNames() as $i => $n) $permission .= "," . ($croute->parameters());[$n];
        }
        $exist      = Permission::where('slug',$permission)->exists();

        if(!Auth::guard($guard)->guest()
            && (
                !$permission || !$exist || $request->user($guard)->can($permission)
            )
        ) {
            return $next($request);
        }

        return $request->ajax ? response()->json('Unauthorized.',401) : redirect()->route($route)->with('message', ['status'=>'danger','desc'=>"Anda tidak memiliki hak untuk mengakses menu/tombol tersebut"]);
    }
}