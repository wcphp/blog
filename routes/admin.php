<?php
use \Illuminate\Support\Facades\Route;


Route::namespace('Admin')->group(function(){
    //登陆退出
    Route::post('login','AuthController@login')->name('auth_login');//登陆
    Route::post('logout','AuthController@logout')->name('auth_logout');//登出



    //登陆才能访问
    Route::middleware(['admin_auth'])->group(function(){

    });






});









