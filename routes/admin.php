<?php
use \Illuminate\Support\Facades\Route;

//登陆退出
Route::post('login','AuthController@login')->name('system_login');//登陆
Route::post('logout','AuthController@logout')->name('system_logout');//登出



//登陆才能访问
Route::middleware(['admin_auth'])->group(function(){

});





