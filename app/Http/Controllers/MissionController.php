<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mission;
use App\Models\SoldierMission;
use App\Models\Soldier;

class MissionController extends Controller
{
    //

    public function createMission(Request $request)
	{
		
		$response = "";

		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		//Si hay un json válido, crear la mision
		if($data){

			$mission = new Mission();

			//TODO: Validar los datos antes de guardar la mision

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

	public function updateMission(Request $request, $id){

		$response = "";

		//Buscar la mision por su id

		$mission = Mission::find($id);

		if($mission){

			//Leer el contenido de la petición
			$data = $request->getContent();

			//Decodificar el json
			$data = json_decode($data);

			//Si hay un json válido, buscar la mision
			if($data){
			
				//TODO: Validar los datos antes de guardar la mission
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

	public function addSoldier(Request $request){

		$response = "";
		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		//Si hay un json válido, crear el libro
		if($data&&Mission::find($data->mission)&&Soldier::find($data->soldier)){

			$soldierMission = new SoldierMission();

			//TODO: Validar los datos antes de guardar el libro

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

	public function missionsList(){

		$response = "";
		$missions = Mission::all();

		$response= [];

		foreach ($missions as $mission) {
			$response[] = $mission;
		}
		
		return response()->json($response);
	}

	
}
