<?php

use Illuminate\Support\Facades\Route;

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
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('myfriends', [App\Http\Controllers\UserController::class, 'getMyFriendList'])->name('users.myfriends');
Route::post('addfriends', [App\Http\Controllers\UserController::class, 'addFriend'])->name('users.addfriends');
Route::resource('users', App\Http\Controllers\UserController::class);
