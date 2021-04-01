<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private $role;
    public function __construct(Role $role){
        $this->role = $role;
        // $this->middleware("auth");
    }

    public function index()
    {
        $roles = $this->role::all();
        return view('admin.role.list',['roles'=>$roles]);
    }


    public function create()
    {
        $permissions = Permission::all();
        return view('admin.role.create',compact('permissions'));
    }


    public function store(Request $request)
    {
        // $this->validate([
        //     'name'=>'required',
        // ]);
        $role = new Role();
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->save();
        $role->permissions()->attach($request->permission);

       return  redirect()->route('role.list')->with('success','Role Created');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $role = $this->role::findOrFail($id);
        $permissions = Permission::all();
        $permissionOfRole = DB::table('role_permission')->where('role_id', $id)->pluck('permission_id');
        return view('admin.role.edit',compact('role','permissions','permissionOfRole'));
    }

    public function update(Request $request, $id)
    {
        $role = $this->role::findOrFail($id);
        DB::table('role_permission')->where('role_id',$id)->delete();
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->save();
        $role->permissions()->attach($request->permission);

        return redirect()->route('role.list')->with('success','Role Update');
    }


    public function destroy($id)
    {
        DB::table('role_permission')->where('role_id',$id)->delete();
        DB::table('users')->where('role_id',$id)->delete();
        $role = $this->role::find($id);
        $role->delete();
        return redirect()->route('role.list')->with('success','Role Delete');
    }
}
