<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Team;

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
}
