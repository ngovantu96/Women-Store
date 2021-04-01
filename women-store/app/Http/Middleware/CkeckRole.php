<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class CkeckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $Permission=null)
    {

        //1 .lấy tất cả user login vào hẹ thống
        $roleOfUser = DB::table('users')->join('roles','users.role_id','=','roles.id')->where('users.id',auth()->id())
        ->select('roles.*')->get()->pluck('id')->toArray();


        //2 . lấy tất cả các quyền truy cập vaof hệ thống


        $roleOfPermission = DB::table('roles')->join('role_permission','roles.id','=','role_permission.role_id')
        ->join('permissions','permissions.id','=','role_permission.permission_id')->whereIn('roles.id',$roleOfUser)
        ->select('permissions.*')->get()->pluck('id')->unique();

        // dd($roleOfPermission);
        $checkPermission = Permission::where('name',$Permission)->value('id');
        // dd($checkPermission);
        if($roleOfPermission->contains($checkPermission)){
            return $next($request);
        }
        return redirect()->back()->with('warning','Bạn không có quyền truy cập');
    }
}
