<?php

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
    return view('layouts.evento.index');
})->middleware('auth');

Auth::routes();

Route::group(['middleware' => ['auth']],function(){

Route::get('/contabilidad', [App\Http\Controllers\ContabilidadController::class, 'index']);

Route::get('/Calendar', [App\Http\Controllers\CalendarController::class, 'index']);

Route::post('/Calendar/mostrar', [App\Http\Controllers\CalendarController::class, 'show']);

Route::post('/Calendar/agregar', [App\Http\Controllers\CalendarController::class, 'store']);

Route::post('/Calendar/editar/{id}', [App\Http\Controllers\CalendarController::class, 'edit']);

Route::post('/Calendar/borrar/{id}', [App\Http\Controllers\CalendarController::class, 'destroy']);

Route::post('/Calendar/actualizar/{calendar}', [App\Http\Controllers\CalendarController::class, 'update']);

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
