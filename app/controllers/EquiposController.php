<?php
class EquiposController extends \BaseController {
	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$equipos = Equipo::all();
		return View::make('equipos.index')->with('equipos',$equipos);
	}
	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		if($_POST){
			$mensajeGlobal = "";
			$validator = Validator::make(
				[
					"id_usuario" => Input::get("id_usuario"),
					"no_inventario" => Input::get("no_inventario"),
					"marca" => Input::get("marca"),
					"modelo" => Input::get("modelo"),
					"estado" => Input::get("estado")
				],
				[
					"id_usuario"=>"required",
					"no_inventario"=>"required",
					"marca"=>"required",
					"modelo"=>"required",
					"estado"=>"required"
				]
			);
					//	Creamos el mensajeGlobal en caso de existir fallas en las validaciones
			if($validator->fails()){
				if($validator->messages()->first("id_usuario") != ""){ $mensajeGlobal .= "El campo Usuario no es correcto.<br>";}
				if($validator->messages()->first("no_inventario") != ""){ $mensajeGlobal .= "El campo No. Inventario es obligatorio.<br>";}
				if($validator->messages()->first("marca") != ""){ $mensajeGlobal .= "El campo Marca es obligatorio.<br>";}
				if($validator->messages()->first("modelo") != ""){ $mensajeGlobal .= "El campo Modelo es obligatorio.<br>";}
				if($validator->messages()->first("estado") != ""){ $mensajeGlobal .= "Seleccione el estado del equipo<br>";}
			}
					//	Si existen errores retornamos
			if($mensajeGlobal != ""){
				return View::make("equipos.create",Input::all(),["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"]);
			}
			try {
				DB::beginTransaction();
				$equipo = new Equipo;
				$equipo->no_inventario = Input::get("no_inventario");
				$equipo->marca = Input::get("marca");
				$equipo->modelo = Input::get("modelo");
				$equipo->descripcion = Input::get("descripcion");
				$equipo->estado = Input::get("estado");
				$equipo->id_usuario = Input::get("id_usuario");
				$equipo->save();
				$caracteristicas_equipo = new CaracteristicasEquipo;
				$caracteristicas_equipo->so = Input::get("so");
				$caracteristicas_equipo->ram = Input::get("ram");
				$caracteristicas_equipo->disco_duro = Input::get("disco_duro");
				$caracteristicas_equipo->ip = Input::get("ip");
				$caracteristicas_equipo->mac = Input::get("mac");
				$caracteristicas_equipo->nodo = Input::get("nodo");
				$caracteristicas_equipo->usuario_pc = Input::get("usuario_pc");
				$caracteristicas_equipo->grupo_trabajo = Input::get("grupo_trabajo");
				$caracteristicas_equipo->id_equipo = $equipo->id;
				$caracteristicas_equipo->save();
				DB::commit();
				$mensajeGlobal = "Se asigno correctamente el equipo.";
				$equipos = Equipo::all();
				return View::make("equipos.index",["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-success","equipos"=>$equipos]);
			} catch (Exception $e) {
				DB::rollback();
				$mensajeGlobal = "Error de proceso, contacte al administrador de sistemas.";
				return View::make("equipos.create",Input::all(),["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"]);
			}
		}else{
			return View::make('equipos.create');
		}
	}
	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store()
	{
		//
	}
	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show($id)
	{
		$equipo = Equipo::find($id);
		$equipo->caracteristicas;
		return View::make("equipos.show",["equipo"=>$equipo]);
	}
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit($id)
	{
		$mensajeGlobal = "";
		if($_POST){
			$validator = Validator::make(
				[
					"id_usuario" => Input::get("id_usuario"),
					"no_inventario" => Input::get("no_inventario"),
					"marca" => Input::get("marca"),
					"modelo" => Input::get("modelo"),
					"estado" => Input::get("estado")
				],
				[
					"id_usuario"=>"required",
					"no_inventario"=>"required",
					"marca"=>"required",
					"modelo"=>"required",
					"estado"=>"required"
				]
			);
					//	Creamos el mensajeGlobal en caso de existir fallas en las validaciones
			if($validator->fails()){
				if($validator->messages()->first("id_usuario") != ""){ $mensajeGlobal .= "El campo Usuario no es correcto.<br>";}
				if($validator->messages()->first("no_inventario") != ""){ $mensajeGlobal .= "El campo No. Inventario es obligatorio.<br>";}
				if($validator->messages()->first("marca") != ""){ $mensajeGlobal .= "El campo Marca es obligatorio.<br>";}
				if($validator->messages()->first("modelo") != ""){ $mensajeGlobal .= "El campo Modelo es obligatorio.<br>";}
				if($validator->messages()->first("estado") != ""){ $mensajeGlobal .= "Seleccione el estado del equipo<br>";}
			}
					//	Si existen errores retornamos
			if($mensajeGlobal != ""){
				$equipo = Equipo::find($id);
				$equipo->caracteristicas;
				return View::make("equipos.edit",["equipo"=>$equipo,"mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"]);
			}
				//	Comenzamos la transaccion
			try {
				DB::beginTransaction();

				//	Datos del equipo
				$equipo = Equipo::find($id);
				$equipo->no_inventario = Input::get("no_inventario");
				$equipo->marca = Input::get("marca");
				$equipo->modelo = Input::get("modelo");
				$equipo->descripcion = Input::get("descripcion");
				$equipo->estado = Input::get("estado");
				$equipo->id_usuario = Input::get("id_usuario");

				//	Caracteristicas del equipo
				$ce = $equipo->caracteristicas; //	Para obtener el id
				$caracteristicas_equipo = CaracteristicasEquipo::find($ce[0]->id);
				$caracteristicas_equipo->so = Input::get("so");
				$caracteristicas_equipo->ram = Input::get("ram");
				$caracteristicas_equipo->disco_duro = Input::get("disco_duro");
				$caracteristicas_equipo->ip = Input::get("ip");
				$caracteristicas_equipo->mac = Input::get("mac");
				$caracteristicas_equipo->nodo = Input::get("nodo");
				$caracteristicas_equipo->usuario_pc = Input::get("usuario_pc");
				$caracteristicas_equipo->grupo_trabajo = Input::get("grupo_trabajo");
				$caracteristicas_equipo->id_equipo = $id;

				$caracteristicas_equipo->save();
				$equipo->save();

				DB::commit();
				$mensajeGlobal = "Se asigno correctamente el equipo.";
				$equipos = Equipo::all();
				return View::make("equipos.index",["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-success","equipos"=>$equipos]);
			} catch (Exception $e) {
				DB::rollback();
				$equipo = Equipo::find($id);
				$equipo->caracteristicas;
				$mensajeGlobal = "Error de proceso, contacte al administrador de sistemas.<br>".$e;
				return View::make("equipos.edit",["equipo"=>$equipo,"mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"]);
			}
		}else{
			$equipo = Equipo::find($id);
			$equipo->caracteristicas;
			return View::make("equipos.edit",["equipo"=>$equipo]);
		}
	}
	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id)
	{
		//
	}
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id)
	{
		//
	}
	/**
	* Metodo para buscar un equipo y sus caracteristicas por el ID. Este se usa en el evento del select de la vista SERVICIOS.CREATE
	*/
	public function search($id){
		//$equipo = Equipo::find($id)->toJson();
				//	TODO Validar cuando no existan caracteristicas o no se aya mandado el id del equipo a buscar
		$equipo = Equipo::find($id);
		$caracteristicas = $equipo->caracteristicas;
		$usuario = $equipo->usuario->nombres . " " . $equipo->usuario->apellidos;
		$response = array(
			'id'=>$equipo->id,
			'no_inventario'=>$equipo->no_inventario,
			'tipo'=>$equipo->tipo,
			'marca'=>$equipo->marca,
			'modelo'=>$equipo->modelo,
			'descripcion'=>$equipo->descripcion,
			'estado'=>$equipo->estado,
			'area'=>$equipo->usuario->area->nombre,
			'usuario'=>$usuario,
			'id_usuario'=>$equipo->id_usuario,
			'so'=>$caracteristicas[0]->so,
			'ram'=>$caracteristicas[0]->ram,
			'disco_duro'=>$caracteristicas[0]->disco_duro,
			'ip'=>$caracteristicas[0]->ip,
			'mac'=>$caracteristicas[0]->mac,
			'nodo'=>$caracteristicas[0]->nodo,
			'usuario_pc'=>$caracteristicas[0]->usuario_pc,
			'grupo_trabajo'=>$caracteristicas[0]->grupo_trabajo
		);
		//$equipo = json_encode($equipo);
		return $response;
		//return Equipo::find($id)->toJson();
	}

	/**
	 * Accion para la asignación de equipos 
	 */
	public function asignacion(){
		//	Si la petición es POST se va guardar el registro
		if($_POST){
			$equiposAsignar = Input::get('equipoAsignar');
			$usuario = Input::get('id_usuario');
			foreach ($equiposAsignar as $equipo) {
				try {
					DB::beginTransaction();
					$asignacion = new Asignacion;
					$asignacion->id_usuario = $usuario;
					$asignacion->id_equipo = $equipo;
					$asignacion->save();
					DB::commit();
				} catch (Exception $e) {
					DB::rollback();
					$mensajeGlobal = "Error al realizar la operación.";
					return View::make("equipos.asignacion",["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"]);
				}
			}
			$mensajeGlobal = "Se asigno correctamente el equipo.";
			return View::make("equipos.asignacion",["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-success"]);
		}else{
			return View::make("equipos.asignacion");
		}
	}

	/**
	 * Accion tipo AJAX para tomar los equipos de un area determinada
	 */
	public function getequiposfromarea($area){
		//	Seleccionamos los usuarios de del area
		$usuarios = User::where("id_area","=",$area)->lists('id');
		$html = "";
		//	Seleccionamos los equipos de cada usuarios
		foreach ($usuarios as $usuario) {
			$equipos = Equipo::where("id_usuario","=",$usuario)->get();	//	Puede retornar mas de una fila
			//	Si existen equipos para ese usuario
			foreach ($equipos as $equipo) {
				if(count($equipo)>0){
					$caracteristicas = $equipo->caracteristicas;
					$html .= "
						<tr>
							<td>$equipo->no_inventario</td>
							<td>$equipo->marca</td>
							<td>$equipo->modelo</td>
							<td>".$caracteristicas[0]->so."</td>
							<td>".$caracteristicas[0]->ram."</td>
							<td>".$caracteristicas[0]->disco_duro."</td>
							<td><input name=\"equipoAsignar[]\" type=\"checkbox\" value=\"$equipo->id\"> Asignar.</td>
						</tr>
					";
				}
			}
		}
		return $html;
	}

	/**
	 * Retorna los equipos de el usuario pasado como parametro
	 */
	public function cargar_equipos($usuario){
		$equipos = Equipo::where("id_usuario","=",$usuario)->get()->toArray();
		$html = "<option value=''>Seleccione una opción...</option>";
		foreach ($equipos as $equipo) {
			$html .= "<option value='".$equipo['id']."''>".$equipo['descripcion']."</option>";
		}
		return $html;

	}
}