<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\MainMenuController;
use App\Http\Controllers\Home\UserController;
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
    return view('auth/login');
});
Route::get('mainMenu/navigateTo/{fid}',[MainMenuController::class,'navigateTo'])->name('mainMenu/navigateTo');
Route::get('user/changePassword',[UserController::class,'changePassword'])->name('user/changePassword');

Route::get('home', [HomeController::class, 'index']);


//Route::middleware(['auth:sanctum', 'verified'])->get('/home', function () {
//    return view('dashboard');
//})->name('dashboard');
