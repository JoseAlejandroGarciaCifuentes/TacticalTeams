<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mission;
use App\Models\SoldierMission;
use App\Models\Soldier;

class MissionController extends Controller
{
    //

	/**
	 * Crea Mision
	 */
    public function createMission(Request $request)
	{
		
		$response = "";

		$data = $request->getContent();

		$data = json_decode($data);

		if($data){

			$mission = new Mission();

			$mission->description = $data->description;
            $mission->priority = $data->priority;
			$mission->starting_date = $data->starting_date;
			
            if(isset($data->state)){
				$mission->state = $data->state;
		   }

			try{
				$mission->save();
				$response = "Añadido!";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}

		}else{
			$response = "No has introducido una mision válida";
		}

		return response($response);
	}

	/**
	 * Actualiza mission
	 */
	public function updateMission(Request $request, $id){

		$response = "";

		$mission = Mission::find($id);

		if($mission){

			$data = $request->getContent();

			$data = json_decode($data);

			if($data){
			
				$mission->description = (isset($data->description) ? $data->description : $mission->description);
				$mission->priority = (isset($data->priority) ? $data->priority : $mission->priority);
                $mission->state = (isset($data->state) ? $data->state : $mission->state);

				try{
					$mission->save();
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}

			
		}else{
			$response = "No mission";
		}
		
		return response($response);
	}

	/**
	 * Añade soldados a una mission, es decir, tabla soldier-missions
	 * Soldado y mission se reciben por body
	 */
	public function addSoldier(Request $request){

		$response = "";
		$data = $request->getContent();

		$data = json_decode($data);

		if($data&&Mission::find($data->mission)&&Soldier::find($data->soldier)){

			$soldierMission = new SoldierMission();
			$soldierMission->mission_id = $data->mission;
			$soldierMission->soldier_id = $data->soldier;
			try{
				$soldierMission->save();
				$response = "OK";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}

		}
		return response($response);

	}
	/**
	 * Muestra lista de missiones ordenadas por prioridad, de más a menos
	 */
	public function missionsList(){

		$response = "";
		$missions = Mission::orderBy('priority', 'DESC')->get();

		$response= [];

		foreach ($missions as $mission) {
			$response[] = $mission;
		}
		
		//$response = arsort($response, 'priority');
		return response()->json($response);
	}

	/**
	 * Detalles de una mission en concreto junto con el equipo y el lider del equipo
	 * Recibe id de mission por param
	 */
	public function missionsDetails($id){

		$response = "";
		$mission = Mission::find($id);

		if($mission){

			$soldier = $mission->soldier;
			
			$response = [
				"id" => $mission->id,
				"description" => $mission->description,
				"priority" => $mission->priority,
				"starting_date" => $mission->starting_date
			];
			
			if($mission->teamOne){
				$response["team_id"] = $mission->teamOne->id;
				$response['team_name'] = $mission->teamOne->name;

				if($mission->teamOne->leader){
					$response['leader_id'] = $mission->teamOne->leader->id;
					$response['leader_surname'] = $mission->teamOne->leader->surname;
					$response['leader_rank'] = $mission->teamOne->leader->rank;
					$response['badge_number'] = $mission->teamOne->leader->badge_number;
				}
			}

			for ($i=0; $i <count($mission->soldier) ; $i++) { 
				$response[$i]["soldier_id"] = $soldier[$i]->id;
				$response[$i]["badge_number"] = $soldier[$i]->badge_number;
				$response[$i]["rank"] = $soldier[$i]->rank;
				$response[$i]["surname"] = $soldier[$i]->surname;
			}			

		}else{
			$response = "Mision no encontrada";
		}

		return response()->json($response);
	}

	
}
