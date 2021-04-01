<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    private $permission;
    public function __construct(Permission $permission){
        $this->permission = $permission;
        // $this->middleware("auth");
    }

    public function index()
    {
        $permissions = $this->permission::all();
        return view('admin.permission.list',['permissions'=>$permissions]);
    }


    public function create()
    {
        return view('admin.permission.create');
    }


    public function store(Request $request)
    {

        $permission = new Permission();
        $permission->name = $request->name;
        $permission->display_name = $request->display_name;

        $permission->save();

       return  redirect()->route('permission.list')->with('success','Thêm Mới Thành Công');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $permission = $this->permission::findOrFail($id);
        return view('admin.permission.edit',compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->display_name = $request->display_name;
        $permission->save();

        return redirect()->route('permission.list')->with('info','Cập Nhật Thành Công');
    }


    public function destroy($id)
    {
        $permission = $this->permission::find($id);
        $permission->delete();
        return redirect()->route('permission.list')->with('danger','Xóa Thành Công');
    }
}

