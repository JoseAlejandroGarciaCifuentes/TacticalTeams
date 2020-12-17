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
                $soldier->state = (isset($data->state) ? $data->state: $soldier->state);
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

	
}
