<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Soldier;

class SoldierController extends Controller
{
    //
	/**
	 * Crea soldado
	 */
    public function createSoldier(Request $request)
	{
		
		$response = "";
		$data = $request->getContent();
		$data = json_decode($data);
		if($data){

			$soldier = new Soldier();

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

	/**
	 * Actualiza soldado al completo
	 */
	public function updateSoldier(Request $request, $id){

		$response = "";

		$soldier = Soldier::find($id);

		if($soldier){

			$data = $request->getContent();
			$data = json_decode($data);

			if($data){

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

	/**
	 * Actualiza estado del soldado
	 * Recibe state por body y id de soldado por param
	 */
	public function updateSoldierState(Request $request, $id){

		$response = "";

		$soldier = Soldier::find($id);

		if($soldier){

			$data = $request->getContent();

			$data = json_decode($data);

			if($data){

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

	/**
	 * Muestra lista de soldados además del equipo al que pertenece
	 */
	public function soldiersList(){

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

	/**
	 * Detalles de un soldado en concreto junto con su team y leader de ese team
	 * Recibe id por param
	 */
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

	/**
	 * Historial de misiones de un soldado 
	 * Recibe soldado por param
	 */
	public function missionHistoryList($soldier_id){

		$response = "";
		$soldier = Soldier::find($soldier_id);

		$response= [];

		$response[] = [				 
			"soldier_id" => $soldier->id
		];

		for ($i=0; $i <count($soldier->mission) ; $i++) { 
			$response['mission_id'] = $soldier->mission[$i]->id;
			$response['starting_date'] = $soldier->mission[$i]->starting_date;
			$response['state'] =$soldier->mission[$i]->state;
		}

		return response()->json($response);
	}
	
}
