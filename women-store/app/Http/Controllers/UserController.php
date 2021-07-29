<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
        $this->middleware("auth");
    }

    public function index(){
        $users = $this->user::all();
        $roles = Role::all();
        return view('admin.user.list',['users'=>$users,'roles'=>$roles]);
        // return view('admin.user.list');
    }

    public function create(){
        $roles = Role::all();
        return view('admin.user.create',compact('roles'));
        // return view('admin.user.create');
    }

    public function store(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role_id = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->roles()->attach($request->role);
        return redirect()->route('user.list')->with('success','Thêm mới thành công.');
    }

    public function edit($id){
        $user = $this->user::findOrFail($id);
        $roles = Role::all();
        $userOfRole = DB::table('role_user')->where('user_id', $id)->pluck('role_id');
        return view('home.user.edit',compact('user','roles'));
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);
        DB::table('user')->where('id',$id)->delete();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->role_id = $request->role;

        $user->save();

        $user->roles()->attach($request->role);
         return redirect()->route('user.list')->with('info','Cập nhật thành công.');
    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.list')->with('danger','Xóa thành công.');
    }

    public function view($id){
        $user = User::find($id);

    }

    public function profile(){
        $user =Auth::user();
        return view('admin.user.profile',compact('user'));
    }

    public function editProfile(Request $request){
        $user = Auth::user();
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required|email|unique:user,email,'.$user->id
        ]);
        $user->update($request->all());

        return redirect()->back()->with('info','Chỉnh sửa thành công.');
    }
    public function editPass(){
        $user = Auth::user();
        return view('admin.user.editPass',compact('user'));
    }
}
