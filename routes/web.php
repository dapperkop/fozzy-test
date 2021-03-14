<?php

use App\Http\Controllers\ServiceController;
use App\Providers\RouteServiceProvider;

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
    return redirect(RouteServiceProvider::HOME);
})->middleware(['auth']);

Route::get(RouteServiceProvider::HOME, [ServiceController::class, 'index'])->middleware(['auth'])->name('service.list');
Route::get(RouteServiceProvider::HOME . '/add', [ServiceController::class, 'add'])->middleware(['auth'])->name('service.form.create');
Route::get(RouteServiceProvider::HOME . '/edit/{service}', [ServiceController::class, 'edit'])->middleware(['auth'])->name('service.form.update');

require __DIR__ . '/auth.php';
