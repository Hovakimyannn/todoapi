<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;



Route::get('/get-all', [TodoController::class,'getAll']);
Route::post('/store',[TodoController::class, 'store']);
Route::patch('/update',[TodoController::class,'update']);
Route::delete('/remove',[TodoController::class,'remove']);
