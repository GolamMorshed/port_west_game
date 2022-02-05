<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ScoreBoardController;
use App\Http\Controllers\LoginController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/get_question/{id}',[QuestionController::class,'show']);
Route::get('/users',[LoginController::class,'index']);
Route::get('/get_score_board',[ScoreBoardController::class,'index']);
Route::post('/submit_score',[ScoreBoardController::class,'store']);



