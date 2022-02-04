<?php

use App\Http\Controllers\TodoController;
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

Route::get('/get-all', [TodoController::class,'getAll']);
Route::post('/store',[TodoController::class, 'store']);
Route::patch('/update',[TodoController::class,'update']);
Route::delete('/remove',[TodoController::class,'remove']);
