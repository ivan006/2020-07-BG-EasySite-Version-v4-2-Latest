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

// Route::get('/', function () {
//     return view('reader');
// });

// Route::get('/', function () {
//   return view('welcome');
// });

Route::get('', 'report_c@show');

Auth::routes();

Route::get('/home', ['middleware' => ['auth'],'uses' => 'report_c@edit'])->name('home');
// Route::get('/home', 'report_c@edit')->name('home');
