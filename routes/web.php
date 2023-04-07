<?php

use App\Http\Controllers\AmoCRM\InfoController;
use App\Services\AmoCRMService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/auth', [AmoCRMService::class, 'auth'])->middleware('amocrm.logger')->name('auth');
Route::get('/info', [InfoController::class, 'index'])->middleware('amocrm.logger')->name('info');
