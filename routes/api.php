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

	Route::post('/update/state/{id}',[SoldierController::class, 'updateSoldierState']);

	Route::get('/show/list',[SoldierController::class, 'soldiersList']);

	Route::get('/show/details/{id}',[SoldierController::class, 'soldierDetails']);

	Route::get('/show/history/list/{id}',[SoldierController::class, 'missionHistoryList']);

});

Route::prefix('missions')->group(function (){

	Route::post('/create',[MissionController::class, 'createMission']);

	Route::post('/update/{id}',[MissionController::class, 'updateMission']);

	Route::post('/add/soldier',[MissionController::class, 'addSoldier']);

	Route::get('/show/list',[MissionController::class, 'missionsList']);

	Route::get('/show/details/{id}',[MissionController::class, 'missionsDetails']);
});

Route::prefix('teams')->group(function (){

	Route::post('/create',[TeamController::class, 'createTeam']);

	Route::post('/update/{id}',[TeamController::class, 'updateTeam']);

	Route::post('/delete/{id}',[TeamController::class, 'deleteTeam']);

	Route::post('/add/leader/{id}',[TeamController::class, 'addLeader']);

	Route::post('/add/soldier',[TeamController::class, 'addSoldier']);

	Route::post('/assign/mission',[TeamController::class, 'assignMission']);

	Route::get('/show/members',[TeamController::class, 'showMembers']);

	Route::post('/out/soldier',[TeamController::class, 'soldierOut']);

	Route::post('/make/boss',[TeamController::class, 'newBoss']);
	
	
});
