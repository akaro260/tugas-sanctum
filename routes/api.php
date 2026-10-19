<?php

use App\Http\Controllers\authcontroller;

use Illuminate\Support\Facades\Route;

Route::post('/login', [authcontroller::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
Route::get('/profil', [authcontroller::class, 'profil']);
Route::post('/logout', [authcontroller::class,
'logout']);
});