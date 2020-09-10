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
    return view('welcome');
});
Route::redirect('home', 'admin/dashboard');
Auth::routes();
Route::post('custom/login', 'Auth\LoginController@customLogin');

/**
 * Admin Auth Routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function()
{
    // Dashboard
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');

    // users
    Route::resource('users', 'User\UserController');
});