<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\CustomreportGroup;

class RoleController extends Controller
{
    public function index()
    {
        // // Mendapatkan daftar Permission per grup
        // $dataObj = Role::where('groupapps','TRADING')->orderBy('updated_at','desc')->get();
        // echo '<pre>';
        // foreach($dataObj as $d) {
        //     $query = "
        //         SELECT sp.slug FROM secure.permissions sp
        //         JOIN secure.permissionrole spr ON spr.permission_id = sp.id
        //         WHERE spr.role_id = $d->id
        //         ORDER BY STRING_TO_ARRAY(nested, '.')::int[] ASC";
        //     $result = DB::select($query);
        //     echo '        $r_'.str_slug($d->name).' = App\Models\Role::where("name", "'.$d->name.'")->where("groupapps","TRADING")->first();'."\n";
        //     echo '        $r_'.str_slug($d->name)."->permission()->attach([\n";
        //     foreach($result as $slug) {echo '            $p[\''.$slug->slug."'],\n";}
        //     echo "        ]);\n\n";
        // }

        // // Mendapatkan daftar User per grup
        // $dataObj = Role::where('groupapps','TRADING')->orderBy('updated_at','desc')->get();
        // echo '<pre>';
        // foreach($dataObj as $d) {
        //     $query = "
        //         SELECT su.username FROM secure.users su
        //         JOIN secure.roleuser sur ON sur.user_id = su.id
        //         WHERE sur.role_id = $d->id";
        //     $result = DB::select($query);
        //     echo '        $r_'.str_slug($d->name).' = App\Models\Role::where("name", "'.$d->name.'")->where("groupapps","TRADING")->first();'."\n";
        //     echo '        $r_'.str_slug($d->name)."->user()->attach([\n";
        //     foreach($result as $user) {echo '            $u[\''.$user->username."'],\n";}
        //     echo "        ]);\n\n";
        // }

        // // Mendapatkan daftar User
        // $dataObj = User::orderBy('id')->get();
        // echo '<pre>';
        // echo '        DB::table("secure.users")->insert(['."\n";
        // foreach($dataObj as $d) {
        //     echo '            ['."\n";
        //     echo '                \'username\' => \''.$d->username.'\','."\n";
        //     echo '                \'name\'     => \''.$d->name.'\','."\n";
        //     echo '                \'email\'    => '.(($d->email) ? '\''.$d->email.'\'' : 'null').','."\n";
        //     echo '                \'password\' => bcrypt(\''.$d->username.'\'),'."\n";
        //     echo '                \'karyawanid\' => '.(($d->karyawanid) ? $d->karyawanid : 'null').','."\n";
        //     echo '            ],'."\n";
        // }
        // echo '        ]);';
        // exit;

        $dataObj = Role::where('groupapps','TRADING')->orderBy('updated_at')->get();
        return view('role.index',['dataObj' => $dataObj]);
    }

    public function form($id = null)
    {
        $dataSet = Role::find($id);
        // $permissionObj = Permission::select(['id','name','slug','nested'])
        //                 ->orderByRaw("STRING_TO_ARRAY(nested, '.')::int[] ASC")
        //                 ->get();
        $permissionObj = Permission::select(
                DB::raw("*, ARRAY_LENGTH(STRING_TO_ARRAY(nested, '.'), 1) - 1 AS depth,
                    CASE
                        WHEN (ARRAY_LENGTH(STRING_TO_ARRAY(nested, '.'), 1) - 1) > 0
                        THEN SUBSTRING(nested,1,LENGTH(nested)-(LENGTH(SPLIT_PART(REVERSE(nested), '.', 1))+1))
                        ELSE null
                    END AS parent")
                )
            ->where('groupapps','TRADING')
            ->orderByRaw("STRING_TO_ARRAY(nested, '.')::int[] ASC")
            ->get();

        $customreportgroupObj = CustomreportGroup::all();

        if($id){
            foreach ($permissionObj as $row) {
               $access = $row->role()->where('secure.roles.id',$id)->exists();
               if($access) {
                    $row->access = 1;
               }else{
                    $row->access = 0;
               }
            }

            foreach ($customreportgroupObj as $row) {
               $access = $row->role()->where('secure.roles.id',$id)->exists();
               if($access) {
                    $row->access = 1;
               }else{
                    $row->access = 0;
               }
            }
        }

        $checkbox_loop = $this->checkbox_loop($permissionObj);

        return view('role.form',['dataSet' => $dataSet, 'permissionObj' => $permissionObj, 'checkbox_loop' => $checkbox_loop, 'customreportgroupObj' => $customreportgroupObj]);
    }

    private function checkbox_loop($data,$nested=null,$loop=0)
    {
        $parent = array_unique(array_flatten(array_pluck($data, 'parent')));

        $return = '<ul class="no-style">'."\n";
        foreach ($data as $row) {
            $hasChild = array_search($row->nested,$parent);

            if($row->parent == $nested) {
                $return .= '<li data-id="'.$row->nested.'">'."\n";
                $return .= '    <div class="checkbox">'."\n";
                $return .= '        <label>'."\n";
                $return .= '            <input type="checkbox" class="flat check_tree" data-id="'.$row->slug.'" name="permission['.$row->id.']" '.($row->access ? "checked" : "").'> '.$row->name."\n";
                $return .= '        </label>'."\n";
                $return .= '    </div>'."\n";
                if($hasChild) {
                    $return .= $this->checkbox_loop($data,$row->nested);
                }
                $return .= '</li>'."\n";
            }
        }
        $return .= '</ul>'."\n";

        return $return;
    }

    public function formuser($id = null)
    {
        $dataSet = Role::find($id);
        $userObj = User::all();
        return view('role.formuser',['dataSet' => $dataSet,'userObj' => $userObj]);
    }

    public function save(Request $request, $id = null)
    {
        if ($id) {
            $role = Role::find($id);
            $role->groupapps = 'TRADING';
            $role->name      = $request->name;
            $role->updatedby = strtoupper($request->user()->username);
            $role->save();

            $desc = "Role telah sukses diubah.";
        }else {
            $role = new Role;
            $role->groupapps = 'TRADING';
            $role->name      = $request->name;
            $role->createdby = strtoupper($request->user()->username);
            $role->save();

            $desc = "Role baru telah sukses ditambah.";
        }

        // Role Menu
        $id = $role->id;
        $permission = $request->permission;
        if($permission) {
            foreach ($permission as $rkey => $rval) {
                $menu[]  = ['permission_id'=>$rkey];
            }
            $role->permission()->detach();
            $role->permission()->attach($menu);
        }

        // Role Menu
        $id = $role->id;
        $customreportgroup = $request->customreportgroup;
        if($customreportgroup) {
            foreach ($customreportgroup as $rkey => $rval) {
                $report[]  = ['customreportgroup_id'=>$rkey];
            }
            $role->customreportgroup()->detach();
            $role->customreportgroup()->attach($report);
        }

        return redirect()->route('role.index')->with('message', ['status'=>'success','desc'=>$desc]);
    }

    public function saveuser(Request $request, $id = null)
    {
        $role = Role::find($id);
        if ($request->tambah_user) {
            $role->user()->syncWithoutDetaching([$request->tambah_user]);
            $desc = "User telah sukses ditambah.";
        }elseif ($request->hapus_user) {
            $role->user()->detach($request->hapus_user);
            $desc = "User telah sukses dihapus.";
        }else{
            $desc = null;
        }

        return redirect()->route('role.user',$id)->with('message', ['status'=>'success','desc'=>$desc]);
    }

    public function delete(Request $request, $id)
    {
        $role    = Role::find($id);
        $role->delete();

        if($request->ajax()) {
            return json_encode('success');
        }else {
            return redirect()->route('role.index')->with('message', ['status'=>'success','desc'=>"Role telah sukses dihapus."]);
        }
    }
}
