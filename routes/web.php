<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

// Middleware
// Admin Routes
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin/dashboard',[AdminController::class,'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout',[AdminController::class,'AdminDestroy'])->name('admin.logout');
    Route::get('/admin/profile',[AdminController::class,'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store',[AdminController::class,'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password',[AdminController::class,'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password',[AdminController::class,'AdminUpdatePassword'])->name('update.password');


});

// Vendor Routes
Route::middleware(['auth','role:vendor'])->group(function(){
    Route::get('/vendor/dashboard',[VendorController::class,'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/logout',[VendorController::class,'VendorDestroy'])->name('vendor.logout');
    Route::get('/vendor/profile',[VendorController::class,'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/store',[VendorController::class,'VendorProfileStore'])->name('vendor.profile.store');
    Route::get('/vendor/change/password',[VendorController::class,'VendorChangePassword'])->name('vendor.change.password');
    Route::post('/vendor/update/password',[VendorController::class,'VendorUpdatePassword'])->name('update.password');
    Route::get('/vendor/logout',[VendorController::class,'VendorDestroy'])->name('vendor.logout');

});

Route::get('/admin/login',[AdminController::class,'AdminLogin']);
Route::get('/vendor/login',[VendorController::class,'VendorLogin']);
