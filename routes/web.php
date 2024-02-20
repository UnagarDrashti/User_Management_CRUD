<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminContoller;
use App\Http\Controllers\Admin\UserContoller as AdminUserContoller;
use App\Http\Controllers\Auth\RedirectAuthenticatedUsersController;
use App\Http\Controllers\User\UserContoller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => 'auth'], function() {

    Route::get("/dashboard", [RedirectAuthenticatedUsersController::class, "home"]);
    
    // admin route
    Route::group(['middleware' => 'checkRole:admin'], function() {
        Route::get('/admin/dashboard', [AdminContoller::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('admin/user', AdminUserContoller::class);
    });

    // user route
    Route::group(['middleware' => 'checkRole:user'], function() {
        Route::get('/user/dashboard', [UserContoller::class, 'dashboard'])->name('user.dashboard');
    });
});

require __DIR__.'/auth.php';
