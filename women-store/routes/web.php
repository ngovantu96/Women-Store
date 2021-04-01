<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware('check_login')->get('/home', function () {
//     return view('home.index');
// });
// Auth::routes();
Route::get('/',function(){
    return view('admin.index');
});

Route::get('/login', function () {
    return view('home.login');
})->name('login');

Route::get('/registrates', function () {
    return view('home.registrates');
});

Route::post('/registrates',[LoginController::class,'regis'])->name('regis');
Route::post('/login',[LoginController::class,'login'])->name('admin.login');


Route::middleware('check_login')->prefix('home')->group(function(){
Route::get('/logout',[LoginController::class,'logout'])->name('admin.logout');

    Route::get('/',[HomeController::class,'index'])->name('admin.dashboard');

    //user
    Route::prefix('user')->group(function () {
        Route::get('/',[UserController::class,'index'])->name('user.list');
        Route::get('/create',[UserController::class,'create'])->name('user.create')->middleware('check_role:create-user');
        Route::post('/create',[UserController::class,'store'])->name('user.store');
        Route::get('/edit/{id}',[UserController::class,'edit'])->name('user.edit')->middleware('check_role:edit-user');
        Route::post('/update/{id}',[UserController::class,'update'])->name('user.update');
        Route::get('/delete/{id}',[UserController::class,'delete'])->name('user.delete')->middleware('check_role:delete-user');
        Route::get('/profile',[UserController::class,'profile'])->name('user.profile');
        Route::get('/editPass',[UserController::class,'editPass'])->name('user.editPass');

    });

    //permission
    Route::prefix('permission')->group(function () {
        Route::get('/',[PermissionController::class,'index'])->name('permission.list');
        Route::get('/create',[PermissionController::class,'create'])->name('permission.create')->middleware('check_role:create-permission');
        Route::post('/create',[PermissionController::class,'store'])->name('permission.store');
        Route::get('/edit/{id}',[PermissionController::class,'edit'])->name('permission.edit')->middleware('check_role:edit-permission');
        Route::post('/update/{id}',[PermissionController::class,'update'])->name('permission.update');
        Route::get('/delete/{id}',[PermissionController::class,'destroy'])->name('permission.delete')->middleware('check_role:delete-permission');

    });

    //role
    Route::prefix('role')->group(function () {
        Route::get('/',[RoleController::class,'index'])->name('role.list');
        Route::get('/create',[RoleController::class,'create'])->name('role.create')->middleware('check_role:create-role');
        Route::post('/create',[RoleController::class,'store'])->name('role.store');
        Route::get('/edit/{id}',[RoleController::class,'edit'])->name('role.edit')->middleware('check_role:edit-role');
        Route::post('/update/{id}',[RoleController::class,'update'])->name('role.update');
        Route::get('/delete/{id}',[RoleController::class,'destroy'])->name('role.delete')->middleware('check_role:delete-role');

    });



});
