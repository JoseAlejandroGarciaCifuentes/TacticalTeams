<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Soldier;

class SoldierController extends Controller
{
    //

    public function createSoldier(Request $request)
	{
		
		$response = "";

		//Leer el contenido de la petición
		$data = $request->getContent();

		//Decodificar el json
		$data = json_decode($data);

		//Si hay un json válido, crear el soldado
		if($data){

			$soldier = new Soldier();

			//TODO: Validar los datos antes de guardar el soldado

			$soldier->name = $data->name;
            $soldier->surname = $data->surname;
            $soldier->badge_number = $data->badge_number;
            $soldier->date_of_birth = $data->date_of_birth;
			$soldier->incorporation_date = $data->incorporation_date;
			$soldier->state = $data->state;
            $soldier->rank = $data->rank;

			try{
				$soldier->save();
				$response = "Añadido!";
			}catch(\Exception $e){
				$response = $e->getMessage();
			}

		}else{
			$response = "No has introducido un soldado válido";
		}

		return response($response);
	}

	public function updateSoldier(Request $request, $id){

		$response = "";

		//Buscar el soldado por su id

		$soldier = Soldier::find($id);

		if($soldier){

			//Leer el contenido de la petición
			$data = $request->getContent();

			//Decodificar el json
			$data = json_decode($data);

			//Si hay un json válido, buscar el soldado
			if($data){
			
				//TODO: Validar los datos antes de guardar el soldado
				$soldier->name = (isset($data->name) ? $data->name: $soldier->name);
                $soldier->surname = (isset($data->surname) ? $data->surname: $soldier->surname);
                $soldier->date_of_birth = (isset($data->date_of_birth) ? $data->date_of_birth: $soldier->date_of_birth);
                $soldier->incorporation_date = (isset($data->incorporation_date) ? $data->incorporation_date: $soldier->incorporation_date);
                $soldier->badge_number = (isset($data->badge_number) ? $data->badge_number: $soldier->badge_number);
                $soldier->rank = (isset($data->rank) ? $data->rank: $soldier->rank);

				try{
					$soldier->save();
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}

			
		}else{
			$response = "No soldier";
		}
		
		return response($response);
	}

	public function updateSoldierState(Request $request, $id){

		$response = "";

		//Buscar el soldado por su id

		$soldier = Soldier::find($id);

		if($soldier){

			//Leer el contenido de la petición
			$data = $request->getContent();

			//Decodificar el json
			$data = json_decode($data);

			//Si hay un json válido, buscar el soldado
			if($data){
			
				//TODO: Validar los datos antes de guardar el soldado
				$soldier->state = (isset($data->state) ? $data->state: $soldier->state);
				
				try{
					$soldier->save();
					$response = "OK";
				}catch(\Exception $e){
					$response = $e->getMessage();
				}
			}
			
		}else{
			$response = "No soldier";
		}
		
		return response($response);
	}

	public function soldiersList(){

		$response = "";
		$soldiers = Soldier::all();

		$response = [];

		for ($i=0; $i <count($soldiers) ; $i++) { 

			$response[$i] = [
				"name" => $soldiers[$i]->name,
				"surname" => $soldiers[$i]->surname,
				"rank" => $soldiers[$i]->rank,
				"badge_number" => $soldiers[$i]->badge_number
			];

			if($soldiers[$i]->team_id){
				$response[$i]['team_id'] = $soldiers[$i]->team->id;
				$response[$i]['team_name'] = $soldiers[$i]->team->name;
			}
		}

		return response()->json($response);
	}

	public function soldierDetails($id){

		$response = "";
		$soldier = Soldier::find($id);

		if($soldier){

			$response = [
				"id" => $soldier->id,
				"name" => $soldier->name,
				"surname" => $soldier->surname,
				"date_of_birth" => $soldier->date_of_birth,
				"incorporation_date" => $soldier->incorporation_date,
				"badge_number" => $soldier->badge_number,
				"state" => $soldier->state,
				"rank" => $soldier->rank
			];

			if($soldier->team_id){
				$response['team_id'] = $soldier->team->id;
				$response['team_name'] = $soldier->team->name;

				if($soldier->team->leader){
					$response['leader_id'] = $soldier->team->leader->id;
					$response['leader_surname'] = $soldier->team->leader->surname;
					$response['leader_rank'] = $soldier->team->leader->rank;
				}

			}

		}else{
			$response = "Soldado no encontrado";
		}

		return response()->json($response);
	}
	
}
