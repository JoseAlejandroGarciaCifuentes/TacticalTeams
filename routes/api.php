<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\SoldierController;
use App\Http\Controllers\TeamController;

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

Route::prefix('soldiers')->group(function (){

	Route::post('/create',[SoldierController::class, 'createSoldier']);

	Route::post('/update/{id}',[SoldierController::class, 'updateSoldier']);

});

Route::prefix('missions')->group(function (){

	Route::post('/create',[MissionController::class, 'createMission']);

	Route::post('/update/{id}',[MissionController::class, 'updateMission']);

});

Route::prefix('teams')->group(function (){

	Route::post('/create',[TeamController::class, 'createTeam']);

	Route::post('/update/{id}',[TeamController::class, 'updateTeam']);

	Route::post('/delete/{id}',[TeamController::class, 'deleteTeam']);

});
