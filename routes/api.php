<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::post('auth/login', [AuthController::class, "login"]); //->middleware('apiJwt')

Route::group(['middleware' => ['apiJwt']], function(){
  Route::post('auth/logout', [AuthController::class, "logout"]);

  Route::get('users', [UserController::class, "index"]);
});