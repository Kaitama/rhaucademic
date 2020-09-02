<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/**
 * DASHBOARD ROUTING
 */
Route::prefix('dashboard')->group(function () {
	// DASHBOARD
	Route::get('/', 'DashboardController@index')->name('dashboard-index');

	// PEGAWAI
	Route::get('/pegawai', 'PegawaiController@index')->name('pegawai-index');
});