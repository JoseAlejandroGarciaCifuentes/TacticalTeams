<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Team;
use App\Models\Soldier;
use App\Models\Mission;
use App\Models\SoldierMission;

class TeamController extends Controller
{
    //

	/**
	 * Crea el equipo en base al body pasado
	 */
    public function createTeam(Request $request)
	{
		$response = "";
		$data = $request->getContent();

		$data = json_decode($data);

		if($data){

			$team = new Team();

			$team->name = $data->name;
            
			try{
				$team->save();
				$response = "Añadido!";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}

		}else{
			$response = "No has introducido un equipo válido";
		}

		return response($response);
	}

	/**
	 * Actualiza atributos del equipo pasado por parámetro
	 */
	public function updateTeam(Request $request, $id){

		$response = "";
		$team = Team::find($id);

		if($team){

			$data = $request->getContent();

			$data = json_decode($data);

			if($data){
		
				$team->name = (isset($data->name) ? $data->name : $team->name);

				try{
					$team->save();
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}

			
		}else{
			$response = "No team";
		}
		
		return response($response);
	}
	/**
	 * Borra el equipo pasado por parámetro
	 */
	public function deleteTeam(Request $request, $id){

		$response = "";

		$team = Team::find($id);

		if($team){

			try{
				$team->delete();
				$response = "OK";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}
						
		}else{
			$response = "No team";
		}

		return response($response);
	}

	/**
	 * Asigna un líder al equipo, requiere soldier y team en body
	 */
	public function assignLeader(Request $request){

		$response = "";
		$data = $request->getContent();

		$data = json_decode($data);

		$team = Team::find($data->team);
		$soldier = Soldier::find($data->soldier);

		if($data&&$team&&$soldier){

			if(!isset($soldier->team_id)||$soldier->team_id === $team->id){
				$soldier->team_id = $team->id;
				$team->leader_id = $soldier->id;
			
				try{
					$team->save();
					$soldier->save();
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}else{
				$response = "No tiene equipo asignado o es distinto";
			}

		}
		return response($response);

	}

	
	/**
	 * Añade un soldado al equipo además de añadirle a la mision en caso de que el equipo tenga una asignada
	 * Recibe soldier y team por body 
	 */
	public function assignSoldier(Request $request){

		$response = "";
		$data = $request->getContent();

		$data = json_decode($data);

		$soldier = Soldier::find($data->soldier);
		$team = Team::find($data->team);

		if($data&&$team&&$soldier){

			$soldier->team_id = $data->team;

			if(isset($team->mission_id)){
				$soldierMission = new SoldierMission();
				$soldierMission->soldier_id = $soldier->id;
				$soldierMission->mission_id = $team->mission_id;
				$soldierMission->save();
			}

			try{
				$soldier->save();
				$response = "OK";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}

		}else{
			$response = "No";
		}
		return response($response);

	}

	/**
	 * Devuelve un array con los id de los soldados de un equípo pasados por parámetro 
	 */
	public function getSoldiersFromTeam($team_id){

		$soldiers = Soldier::all();
		
		$response = [];

		foreach ($soldiers as $soldier) {

			if($soldier->team_id === $team_id){

				$response[] = [
					"id" => $soldier->id
				];
			}
				
		}
		return $response;
	}

	
	/**
	 * Asigna una mission al equipo además de añadir los soldados de ese equipo a la tabla soldier-mission 
	 */
	public function assignMission(Request $request){

		$response = "";
		$data = $request->getContent();

		$data = json_decode($data);

		$team = Team::find($data->team);
		$soldiers = $this->getSoldiersFromTeam($team->id);
		$mission = Mission::find($data->mission);


		if($data&&$mission&&$team){

			if (!isset($team->mission_id)){

				$team->mission_id = $data->mission;
				
				foreach ($soldiers as $soldier) {
					$soldierMission = new SoldierMission();
					$soldierMission->mission_id = $mission->id;
					$soldierMission->soldier_id = $soldier['id'];
					$soldierMission->save();
				}

				$mission->state = 'In Progress';

				try{
					$team->save();
					$mission->save();
					
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}else{
				$response = "Este equipo ya tiene una mision asignada";
			}

		}
		return response($response);

	}

	/**
	 * Muestra los miembros del equipo pasado por parámetro
	 */
	public function showMembers($id){

		$team = Team::find($id);
		$leader = $team->leader_id;
		$response= [];

		$response[] = [				 
			"team_id" => $team->id,
			"name_id" => $team->name,
			"leader_id" => $team->leader_id
		];
		
		if($team->soldier){
			for ($i=0; $i <count($team->soldier) ; $i++) { 

				$response[$i]['soldiers'] = $team->soldier[$i];

				if($team->leader){
					$response[$i]['leader'] = $team->leader;
				}else{
					$response[$i]['leader'] = "No leader";
				}
			}
		}else{
			$response[$i]['soldiers'] = "No soldiers";
		}
		

		return response()->json($response);

	}

	/**
	 * Elimina a un soldado de un equipo
	 * Soldado por body, team por param
	 */
	public function soldierOut(Request $request, $team_id){

		$response = "";

		$data = $request->getContent();

		$data = json_decode($data);

		$soldier = Soldier::find($data->soldier);

		$team = Team::find($team_id);

		if($soldier){

			$soldier->team_id = null;

			if($team->leader_id){
				if($soldier->id === $team->leader_id){

					$team->leader_id = null;

					try{
						$team->save();
						$response = "leader deleted";
					}catch(\Exception $e){
						$response = $e->getMessage();
					}
				}
			}
			try{
				$soldier->save();
				$response = "soldier deleted";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}
		}else{
			$response = "No team or soldier";
		}

		return response($response);
	}

	/**
	 * Cambia el leader de un equipo 
	 * Recibe soldier y team por body
	 */
	public function newBoss(Request $request){

		$response = "";

		$data = $request->getContent();

		$data = json_decode($data);

		$soldier = Soldier::find($data->soldier);
		$team = Team::find($data->team);

		if($soldier && $team){
			if(isset($soldier->team_id)&&$soldier->team_id === $team->id){
				$team->leader_id = $soldier->id;
				try{
					$team->save();
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}else{
				$response = "Este soldado no pertenece a este equipo";
			}
		}else{
			$response = "No team or soldier";
		}

		return response($response);
	}

}
