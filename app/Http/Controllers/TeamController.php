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

    public function createTeam(Request $request)
	{
		
		$response = "";

		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		//Si hay un json válido, crear el equipo
		if($data){

			$team = new Team();

			//TODO: Validar los datos antes de guardar el equipo

			$team->name = $data->name;
			
			if(isset($data->soldier_id)){
				$team->soldier_id = $data->soldier_id;
		   }

		   if(isset($data->mission_id)){
			$team->mission_id = $data->mission_id;
	   		}
            
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

	public function updateTeam(Request $request, $id){

		$response = "";

		//Buscar el autor por su id

		$team = Team::find($id);

		if($team){

			//Leer el contenido de la petición
			$data = $request->getContent();

			//Decodificar el json
			$data = json_decode($data);

			//Si hay un json válido, buscar el equipo
			if($data){
			
				//TODO: Validar los datos antes de guardar el autor
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

	public function deleteTeam(Request $request, $id){

		$response = "";
		
		//Buscar el autor por su id

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

	public function addLeader(Request $request, $id){

		$response = "";
		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		$team = Team::find($id);

		//Si hay un json válido, crear el libro
		if($data&&$team&&Soldier::find($data->leader)){

			//TODO: Validar los datos antes de guardar el libro

			$team->leader_id = (isset($data->leader) ? $data->leader : $team->leader_id);
			
			try{
				$team->save();
				$response = "OK";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}

		}
		return response($response);

	}

	public function addSoldier(Request $request){

		$response = "";
		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		$soldier = Soldier::find($data->soldier);
		$team = Team::find($data->team);

		//Si hay un json válido, crear el libro
		if($data&&$team&&$soldier){

			//TODO: Validar los datos antes de guardar el libro

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

	public function getSoldiersFromTeam($team_id){

		$soldiers = Soldier::all();
		
		$response = [];

		foreach ($soldiers as $soldier) {

			if($soldier->team_id === $team_id){

				//$response[] =  $soldier->id;
				$response[] = [
					"id" => $soldier->id
				];
			}
				
		}
		return $response;
	}

	public function assignMission(Request $request){

		$response = "";
		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		$team = Team::find($data->team);
		$soldiers = $this->getSoldiersFromTeam($team->id);
		$mission = Mission::find($data->mission);

		//Si hay un json válido, crear el libro
		if($data&&$mission&&$team){

			//TODO: Validar los datos antes de guardar el libro

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

}
