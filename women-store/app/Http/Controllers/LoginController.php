<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use App\Http\Middleware\Lo;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function regis(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        return view('home.login');
    }
    public function login(Request $request){
        $user = [
            'email' => $request->email,
            'password' => $request->password,
         ];
         if (!Auth::attempt($user)) {
             return redirect()->route('login')->with('error','tài khoản đăng nhập hoặc mật khẩu sai');
         } else {
             return redirect()->route('admin.dashboard');
         }

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }


}
