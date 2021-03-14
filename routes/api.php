<?php

use App\Http\Controllers\ServiceController;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:web')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:web')->post(RouteServiceProvider::HOME . '/add', [ServiceController::class, 'create'])->name('service.api.create');
Route::middleware('auth:web')->post(RouteServiceProvider::HOME . '/edit/{service}', [ServiceController::class, 'update'])->name('service.api.update');
Route::middleware('auth:web')->get(RouteServiceProvider::HOME . '/', [ServiceController::class, 'list'])->name('service.api.list');
Route::middleware('auth:web')->get(RouteServiceProvider::HOME . '/delete/{service}', [ServiceController::class, 'delete'])->name('service.api.delete');
Route::middleware('auth:web')->post(RouteServiceProvider::HOME . '/upgrade/{service}/{product}', [ServiceController::class, 'upgrade'])->name('service.api.upgrade');
Route::middleware('auth:web')->post(RouteServiceProvider::HOME . '/downgrade/{service}/{product}', [ServiceController::class, 'downgrade'])->name('service.api.downgrade');
