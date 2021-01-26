<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;

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

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/', function () {
	$brands = DB::table('brands')->get();
    return view('home',compact('brands'));
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
	$users = User::all();
    return view('admin.index',compact('users'));
})->name('dashboard');

Route::get('/category', [CategoryController::class, 'index'])->name('all.category');

Route::post('/category/insert', [CategoryController::class, 'store'])->name('category');

Route::get('/edit/categories/{id}', [CategoryController::class, 'edit']);
Route::post('/category/update/{id}', [CategoryController::class, 'update']);
Route::get('/softdelete/categories/{id}', [CategoryController::class, 'softDelete']);

Route::get('category/restore/{id}', [CategoryController::class, 'restore']);
Route::get('pdelete/category/{id}', [CategoryController::class, 'perDelete']);

Route::get('/brand', [BrandController::class, 'index'])->name('all.brand');
Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');

Route::get('edit/brand/{id}', [BrandController::class, 'edit']);
Route::post('brand/update/{id}', [BrandController::class, 'update']);
Route::get('delete/brand/{id}', [BrandController::class, 'delete']);

Route::get('multiple/image', [BrandController::class, 'multipic'])->name('multiple.image');

Route::post('image/store',[BrandController::class, 'storeimage'])->name('store.image');

Route::get('user/logout', [BrandController::class, 'logout'])->name('user.logout');

Route::get('home/slider', [HomeController::class, 'slider'])->name('home.slider');
Route::get('add/slider', [HomeController::class, 'AddSlider'])->name('add.slider');
Route::post('store/slider', [HomeController::class, 'StoreSlider'])->name('store.slider');
