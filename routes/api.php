<?php

use App\Http\Controllers\TodoController;
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
Route::get('/get-all', [TodoController::class,'getAll']);
Route::post('/create',[TodoController::class, 'create']);
Route::patch('/update',[TodoController::class,'update']);
Route::delete('/delete',[TodoController::class,'delete']);
Route::patch('/status',[TodoController::class,'status']);
