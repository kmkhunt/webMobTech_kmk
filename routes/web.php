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

Route::middleware(['auth'])->group(function () {
    Route::any("post/add", ["as" => "post_add","uses" => "PostController@add"]);
    Route::any("post/edit/{id?}", ["as" => "post_edit","uses" => "PostController@edit"]);
    Route::get('/home', ["as" => "home","uses" => "PostController@list"]);
    Route::get('/posts/index', 'PostController@index');
    Route::any("posts", ["as" => "post_list","uses" => "PostController@index"]);
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
