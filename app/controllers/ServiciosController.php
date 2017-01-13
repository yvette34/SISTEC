<?php
//	Requerimos la base de TCPDF
use Maxxscho\LaravelTcpdf\LaravelTcpdf as baseTcpdf;

class ServiciosController extends \BaseController {
	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$servicios = Servicios::all();
		return View::make('servicios.index')->with('servicios',$servicios);
	}
	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		// Seleccionamos el usuario que inicio session
		$usuario = User::find(Session::get("usuario"));
		//	Validamos que el usuario tenga equipos registrados
		//	Si el usuario tiene equipos registrados, o si el usuario es administrador|Encargado de area, puede continuar, de lo contrario manda mensaje de alerta.
		if(count($usuario->equipos)>0 || (Session::get('rol') == 0 || Session::get('rol') == 2)){
			//	Recuperamos el ultimo folio generado y lo incrementamos
			$last_folio = DB::table('servicios')->max('folio');
			$last_folio++;
			//	Si el usuario es rol 'GENERAL'
			if(Session::get('rol')==1){
				//	Se debe recuperar el usuario que tiene abierta la session
				$usuario = User::find(Session::get('usuario'));
				//	Seleccionamos el area del usuario
				$area = Areas::find($usuario->id_area);
				//	Tomamos los equipos que el usuario tenga registrados para llenar el combo
				$equipos = Equipo::where('id_usuario','=',Session::get('usuario'))->lists('descripcion','id');
				$equipos[""] = "Seleccione un equipo";
				//	Mandamos el folio siguiente a la vista de creación
				return View::make('servicios.create')->with(array('last_folio'=>$last_folio,'area'=>$area,'equipos'=>$equipos,'usuario'=>$usuario));
			}else{
				//	De lo contrario si es 'ADMINISTRADOR' | Encargado general
				//	Mandamos el folio siguiente a la vista de creación, y sin equipos
				$equipos[""] = "Seleccione un equipo";
				return View::make('servicios.create')->with(array('last_folio'=>$last_folio,'equipos'=>$equipos));
			}
		}else{
			$servicios = Servicios::all();
			$mensajeGlobal = "<strong>NOTA:</strong> Es necesario registrar el equipo de computo.";
			$tipo = "alert-warning";
			return View::make('servicios.index')->with(array('mensajeGlobal'=>$mensajeGlobal,"tipo"=>$tipo,'servicios'=>$servicios));
		}
	}
	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store()
	{
		//	Mensaje Global
		$mensajeGlobal = "";
		//	Asignamos el id_usuario de acuerdo a la session inicada. Si el usuario es 'GENERAL' Validamos que tenga equipos registrados
		//	Para usuario 'GENERAL'
		if(Session::get('rol')==1){
			//	VALIDACION
			$validator = Validator::make(
				[
					"id_usuario"=>Session::get('usuario'),
					"folio"=>Input::get("folio"),
					"fecha_reporte"=>Input::get("fecha_reporte"),
					"falla_reportada"=>Input::get("falla_reportada"),
					"id_equipo"=>Input::get("id_equipo")
				],
				[
					"id_usuario"=>"required",
					"folio"=>"required|unique:servicios",
					"fecha_reporte"=>"required",
					"falla_reportada"=>"required",
					"id_equipo"=>"required"
				]
			);
		//	De lo contrario para usuarios 'Administradores'		
		}else{
			//	VALIDACION
			$validator = Validator::make(
				[
					"id_usuario"=>Input::get("id_usuario"),
					"folio"=>Input::get("folio"),
					"fecha_reporte"=>Input::get("fecha_reporte"),
					"falla_reportada"=>Input::get("falla_reportada"),
					"id_equipo"=>Input::get("id_equipo")
				],
				[
					"id_usuario"=>"required",
					"folio"=>"required|unique:servicios",
					"fecha_reporte"=>"required",
					"falla_reportada"=>"required",
					"id_equipo"=>"required"
				]
			);
		}
		//	Creamos los mensajes de validacion
		if($validator->fails()){
				if($validator->messages()->first('id_usuario') != ''){ $mensajeGlobal .= "El campo usuario es obligado. <br>"; }
				if($validator->messages()->first('folio') != ''){ $mensajeGlobal .= "El campo folio es obligado. <br>"; }
				if($validator->messages()->first('fecha_reporte') != ''){ $mensajeGlobal .= "El campo fecha es obligado. <br>"; }
				if($validator->messages()->first('falla_reportada') != ''){ $mensajeGlobal .= "Describa el problema del equipo. <br>"; }
				if($validator->messages()->first('id_equipo') != ''){ $mensajeGlobal .= "Debe seleccionar un equipo. <br>"; }
		}
		//	Si existen errores, retorna al formulario
		if($mensajeGlobal != ""){
			//	Recuperamos el ultimo folio generado y lo incrementamos
			$last_folio = DB::table('servicios')->max('folio');
			$last_folio++;
			//	Si el usuario tiene rol 'GENERAL'
			if(Session::get('rol')==1){
				//	Se debe recuperar el usuario que tiene abierta la session
				$usuario = User::find(Session::get('usuario'));
				//	Seleccionamos el area del usuario
				$area = Areas::find($usuario->id_area);
				//	Tomamos los equipos que el usuario tenga registrados
				$equipos = Equipo::where('id_usuario','=',Session::get('usuario'))->lists('descripcion','id');
				$equipos[""] = "Seleccione un equipo";
				//	retornamos
				return View::make('servicios.create', Input::all(), [
					"mensajeGlobal"=>$mensajeGlobal,
					"tipo"=>"alert-danger",
					"area" => $area,
					"usuario" => $usuario,
					"last_folio" => $last_folio,
					"equipos"=>$equipos
					]);
			//	De lo contrario si es 'ADMINISTRADOR'
			}else{
				if(Input::has('id_usuario')){
					$equipos = Equipo::where('id_usuario','=',Input::get('id_usuario'))->lists('descripcion','id');
					$equipos[""] = "Seleccione un equipo";
				}else{
					$equipos[""] = "Seleccione un equipo";
				}
				return View::make('servicios.create', Input::all(), [
					'last_folio'=>$last_folio,
					'equipos'=>$equipos,
					'mensajeGlobal'=>$mensajeGlobal,
					'tipo'=>'alert-danger'
					]);
			}
		}
		//	Si n hay errores comenzamos la transaccion
		try {
			DB::beginTransaction();
			//	Tomamos los datos enviados por el formulario
			$post = Input::all();
			extract($post);
			//	Creamos el modelo referencia para guardar
			$servicio = new Servicios;
			//	Asignamos los campos a guardar
			$servicio->folio = $folio;
			$servicio->estado = $estado;
			$servicio->id_equipo = $id_equipo;
			$servicio->id_usuario = $id_usuario;
			$servicio->falla_reportada = $falla_reportada;
			$servicio->usuario_equipo = $usuario_equipo;
			$servicio->fecha_reporte = $fecha_reporte;
			$servicio->save();
			DB::commit();
			//	Retornamos al index
			$servicios = Servicios::all();
			$mensajeGlobal = "Se registro correctamente el servicio.";
			$tipo = "alert-success";
			return View::make('servicios.index')->with(array('mensajeGlobal'=>$mensajeGlobal,"tipo"=>$tipo,'servicios'=>$servicios));
		} catch (Exception $e) {
			DB::rollback();
			$mensajeGlobal = "Error al registrar el servicio.<br>";
			$tipo = "alert-danger";
			//	Recuperamos el ultimo folio generado y lo incrementamos
			$last_folio = DB::table('servicios')->max('folio');
			$last_folio++;
			//	Si el usuario tiene rol 'GENERAL'
			if(Session::get('rol')==1){
				//	Se debe recuperar el usuario que tiene abierta la session
				$usuario = User::find(Session::get('usuario'));
				//	Seleccionamos el area del usuario
				$area = Areas::find($usuario->id_area);
				//	Tomamos los equipos que el usuario tenga registrados
				$equipos = Equipo::where('id_usuario','=',Session::get('usuario'))->lists('descripcion','id');
				$equipos[""] = "Seleccione un equipo";
				//	retornamos
				return View::make('servicios.create', Input::all(), [
					"mensajeGlobal"=>$mensajeGlobal,
					"tipo"=>"alert-danger",
					"area" => $area,
					"usuario" => $usuario,
					"last_folio" => $last_folio,
					"equipos"=>$equipos
					]);
			//	De lo contrario si es 'ADMINISTRADOR'
			}else{
				if(Input::has('id_usuario')){
					$equipos = Equipo::where('id_usuario','=',Input::get('id_usuario'))->lists('descripcion','id');
					$equipos[""] = "Seleccione un equipo";
				}else{
					$equipos[""] = "Seleccione un equipo";
				}
				return View::make('servicios.create', Input::all(), [
					'last_folio'=>$last_folio,
					'equipos'=>$equipos,
					'mensajeGlobal'=>$mensajeGlobal,
					'tipo'=>'alert-danger'
					]);
			}
		}
	}
	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show($id)
	{
		$servicio = Servicios::find($id);
		$equipo = $servicio->equipo;
		$caracteristicas = Equipo::find($equipo->id)->caracteristicas;
		$usuario = $servicio->usuario;
		return View::make('servicios.show')->with(array('servicio'=>$servicio,'equipo'=>$equipo,'usuario'=>$usuario,'caracteristicas'=>$caracteristicas));
	}
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit($id)
	{
		// Tomamos los datos del servicio con el ID
		$servicio = Servicios::find($id);	//	Servicio
		//	Equipos del usuario que es común que se asigno en el servicio
		$equipos_usuario = Equipo::where("id_usuario","=",$servicio->usuario_equipo)->lists("descripcion","id");
		$equipos_usuario[""] = "Seleccione un equipo...";
		/*
		//	Si esta logeado como administrador | encargado de area, debemos usar el area del usuario comun
		if(Session::get('rol') != 1){
			$area_id = User::find($servicio->usuario_equipo)->id_area;
			$area = Areas::find($area_id);
		}else{
			//	Area del usuario que reporto el servicio, en caso de estar logeado como usuario "general"
			$area = $servicio->usuario->area;
		}
		*/
		$area_id = User::find($servicio->usuario_equipo)->id_area;
		$area = Areas::find($area_id);
		// Mandamos los datos del servicio a la vistas
		return View::make('servicios.edit', array('servicio'=>$servicio,'area'=>$area,'equipos'=>$equipos_usuario));
	}
	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id)
	{
		//	Mensaje Global
		$mensajeGlobal = "";
		if(Session::get('rol')==1){
			$id_usuario = Session::get('usuario');
			//	VALIDACION
			$validator = Validator::make(
				[
					"id_usuario"=>Session::get('usuario'),
					"folio"=>Input::get("folio"),
					"fecha_reporte"=>Input::get("fecha_reporte"),
					"falla_reportada"=>Input::get("falla_reportada"),
					"id_equipo"=>Input::get("id_equipo")
				],
				[
					"id_usuario"=>"required",
					"folio"=>"required",
					"fecha_reporte"=>"required",
					"falla_reportada"=>"required",
					"id_equipo"=>"required"
				]
			);
		}else{
			//	VALIDACION
			$id_usuario = Input::get("id_usuario");
			$validator = Validator::make(
				[
					"id_usuario"=>Input::get("id_usuario"),
					"folio"=>Input::get("folio"),
					"fecha_reporte"=>Input::get("fecha_reporte"),
					"falla_reportada"=>Input::get("falla_reportada"),
					"id_equipo"=>Input::get("id_equipo")
				],
				[
					"id_usuario"=>"required",
					"folio"=>"required",
					"fecha_reporte"=>"required",
					"falla_reportada"=>"required",
					"id_equipo"=>"required"
				]
			);
		}
		//	Creamos los mensajes de validacion
		if($validator->fails()){
			if($validator->messages()->first('id_usuario') != ''){ $mensajeGlobal .= "El campo usuario es obligado. <br>"; }
			if($validator->messages()->first('folio') != ''){ $mensajeGlobal .= "El campo folio es obligado. <br>"; }
			if($validator->messages()->first('fecha_reporte') != ''){ $mensajeGlobal .= "El campo fecha es obligado. <br>"; }
			if($validator->messages()->first('falla_reportada') != ''){ $mensajeGlobal .= "Describa el problema del equipo. <br>"; }
			if($validator->messages()->first('id_equipo') != ''){ $mensajeGlobal .= "Debe seleccionar un equipo. <br>"; }
		}
		//	Si existen errores, retorna al formulario
		if($mensajeGlobal != ""){
			// Tomamos los datos del servicio con el ID
			$servicio = Servicios::find($id);	//	Servicio
			if(Input::has('usuario_equipo')){
				//Equipos del usuario común
				$equipos_usuario = User::find(Input::get('usuario_equipo'))->equipos->lists("descripcion","id");
				$equipos_usuario[""] = "Seleccione un equipo...";
			}else{
				$usuario_comun = User::find($servicio->usuario_equipo);
				$equipos_usuario = $usuario_comun->equipos->lists("descripcion","id");	//	Equipos del usuario
				$equipos_usuario[""] = "Seleccione un equipo...";
			}
			$area = $servicio->usuario->area;
			// Mandamos los datos del servicio a la vistas
			return View::make('servicios.edit', Input::all(), ['mensajeGlobal'=>$mensajeGlobal, 'tipo'=>'alert-danger', 'servicio'=>$servicio,'area'=>$area,'equipos'=>$equipos_usuario]);
		}
		//	Si no hay errores comenzamos la transaccion
		try {
			DB::beginTransaction();
			$post = Input::all();
			$servicio = Servicios::find($id);
			$servicio->folio = $post['folio'];
			$servicio->estado = $post['estado'];
			$servicio->id_equipo = $post['id_equipo'];
			$servicio->id_usuario = $id_usuario;
			$servicio->falla_reportada = $post['falla_reportada'];
			$servicio->usuario_equipo = $post['usuario_equipo'];
			$servicio->fecha_reporte = $post['fecha_reporte'];
			$servicio->save();
			DB::commit();
			//	Retornamos al index
			$servicios = Servicios::all();
			$mensajeGlobal = "Se registro correctamente el servicio.";
			$tipo = "alert-success";
			return View::make('servicios.index')->with(array('mensajeGlobal'=>$mensajeGlobal,"tipo"=>$tipo,'servicios'=>$servicios));
		} catch (Exception $e) {
			DB::rollback();
			$mensajeGlobal = "Error al registrar el servicio.<br>".$e;
			$tipo = "alert-danger";
			// Tomamos los datos del servicio con el ID
			$servicio = Servicios::find($id);	//	Servicio
			if(Input::has('id_usuario')){
				$equipos_usuario = User::find(Input::get('id_usuario'))->equipos->lists("descripcion","id");
			}else{
				$equipos_usuario = $servicio->usuario->equipos->lists("descripcion","id");	//	Equipos del usuario
			}
			$area = $servicio->usuario->area;
			// Mandamos los datos del servicio a la vistas
			return View::make('servicios.edit', Input::all(), ['mensajeGlobal'=>$mensajeGlobal, 'tipo'=>'alert-danger', 'servicio'=>$servicio,'area'=>$area,'equipos'=>$equipos_usuario]);
		}
	}
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id)
	{
						#	TODO
	}
	/**
	 * Esta funcion manda los datos necesarios al formulario de seguimiento
	 */
	public function seguimiento($id)
	{
		$mesajeGlobal = "";
		//	Servicio a editar
		$servicio = Servicios::find($id);
		//	Si el estado es diferente de pendiente consultamos los datos
		if($servicio->estado != "0"){
			//	Limpiamos la session de refacciones para volver a crearla
			Session::forget("refacciones");
			//	Creamos una session con un arreglo de refacciones
			foreach ($servicio->refacciones as $ref) {
				$refaccion = ["cantidad"=>$ref['cantidad'],"descripcion"=>$ref['descripcion']];
				Session::push("refacciones",$refaccion);
			}
			//	Generamos un arreglo con las "asistencia tecnica" registradas
			$asistencias = array();
			foreach ($servicio->asistencias as $asistencia) {
				$asistencias[] = $asistencia->problema;
			}
			//	Generamos un arreglo con las "revisiones" registradas
			$revisiones = array();
			foreach ($servicio->revisiones as $revision) {
				$revisiones[] = $revision->dispositivo;
			}
			//	Generamos un arreglo con los "programas" registrados
			$programas = array();
			foreach ($servicio->programas as $programa) {
				$programas[] = $programa->programa;
			}
			//	Obtenemos la solucion
			$datos = array(
				'servicio'=>$servicio,
				'equipo'=>$servicio->equipo,
				'usuario'=>$servicio->usuario,
				'caracteristicas'=>$servicio->equipo->caracteristicas,
				'refacciones' => Session::get("refacciones"),
				'asistencias' => $asistencias,
				'revisiones' => $revisiones,
				'programas' => $programas,
				'soluciones' => $servicio->soluciones,
				'transaccion' => 'update'
				);
			return View::make('servicios.seguimiento')->with($datos);
		}else{
			//	Limpiamos la session de refacciones
			Session::forget("refacciones");
			//	Si el estado es igual a "Pendiente" llamamos la vista solo con los datos actalues
			$datos = array('servicio'=>$servicio,'equipo'=>$servicio->equipo,'usuario'=>$servicio->usuario,'caracteristicas'=>$servicio->equipo->caracteristicas,'transaccion' => 'create');
			return View::make('servicios.seguimiento')->with($datos);
		}
	}
	/**
	 * Funcion para actualizar los datos de un seguimiento
	 */
	public function seguistore($id) {
		//	Definimos el mensaje global
		$mensajeGlobal = "";
		//	Definimos el servicio a actualizar
		$servicio = Servicios::find($id);
		//	Creamos las reglas de validacion
		$validator = Validator::make(
			[
				"diagnostico"=>Input::get("diagnostico"),
				"fecha"=>Input::get("fecha"),
				"estado"=>Input::get("estado"),
				"descripcion"=>Input::get("descripcion")
			],
			[
				"diagnostico"=>"required",
				"fecha"=>"required",
				"estado"=>"required",
				"descripcion"=>"required"
			]
		);
		//	Vamos generando el mensaje de error en caso de exitir error en la validaciones
		if($validator->fails()){
			if($validator->messages()->first('diagnostico') != ''){ $mensajeGlobal .= "Falta describir el diagnostico del equipo. <br>"; }
			if($validator->messages()->first('fecha') != ''){ $mensajeGlobal .= "El campo fecha es obligatorio. <br>"; }
			if($validator->messages()->first('estado') != ''){ $mensajeGlobal .= "No se ha asignado el estado del servicio. <br>"; }
			if($validator->messages()->first('descripcion') != ''){ $mensajeGlobal .= "Falta la descripción de la solución. <br>"; }
		}
		//	Si existe error, retorna al formulario con los datos capturados
		if($mensajeGlobal != ""){
			//	Si el estado es igual a "Pendiente" llamamos la vista solo con los datos actalues
			$datos = array(
				'mensajeGlobal'=>$mensajeGlobal,
				'tipo'=>'alert-danger',
				'servicio'=>$servicio,
				'equipo'=>$servicio->equipo,
				'usuario'=>$servicio->usuario,
				'caracteristicas'=>$servicio->equipo->caracteristicas,
				'post'=>Input::all(),
				'transaccion' => 'create'
				);
			return View::make('servicios.seguimiento')->with($datos);
		}
		//	Comenzamos la transaccion
		try {
			DB::beginTransaction();
			$post = Input::all();
			//	Actaulizamos los datos del servicio
			$servicio->estado = Input::get("estado");
			$servicio->diagnostico = Input::get("diagnostico");
			$servicio->save();

			//	Actualizamos los datos de la tabla aistencia tecnica
			//	Si el arreglo problema[] trae datos, borramos los datos anteriores e insertamos los nuevos datos.
			if(count($post['problema']) > 0 && $post['problema'][0] != ""){
				//	Eliminamos las asistencias del servicio
				$refAfectadas = Asistencia::where('id_servicio','=',$id)->delete();
				foreach ($post['problema'] as $problema) {
					$asistencia = new Asistencia;
					$asistencia->problema = $problema;
					$asistencia->id_servicio = $id;
					$asistencia->save();
				}
			}
			//	Si el arreglo llega vacio solo barramos los datos anteriores
			else{
				$refAfectadas = Asistencia::where('id_servicio','=',$id)->delete();
			}

			//	Actualizamos los datos de la tabla revisiones
			if(count($post['tipo']) > 0 && $post['tipo'][0] != ""){
				//	Eliminamos las revisiones del servicio
				$refAfectadas = Revisiones::where('id_servicio','=',$id)->delete();
				foreach ($post['tipo'] as $dispositivo) {
					$revision = new Revisiones;
					$revision->dispositivo = $dispositivo;
					$revision->id_servicio = $id;
					$revision->save();
				}
			}else{
				$refAfectadas = Revisiones::where('id_servicio','=',$id)->delete();
			}

			//	Actualizamos los datos de la tabla programas
			if(count($post['nombre']) > 0 && $post['nombre'][0] != ""){
				//	Eliminamos los programas del servicio
				$refAfectadas = Programas::where('id_servicio','=',$id)->delete();
				foreach ($post['nombre'] as $software) {
					$programas = new Programas;
					$programas->programa = $software;
					$programas->id_servicio = $id;
					$programas->save();
				}
			}else{
				$refAfectadas = Programas::where('id_servicio','=',$id)->delete();
			}

			//	Actualizamos los datos de la tabla refacciones
			$refacciones = Session::get('refacciones');
			//	Si hay refacciones en la session
			if(count($refacciones) > 0){
				//	Primero debemos eliminar las refacciones para poder insertarlas despues
				$refAfectadas = Refaccion::where('id_servicio','=',$id)->delete();
				foreach ($refacciones as $refa) {
					$refaccion = new Refaccion;
					$refaccion->cantidad = $refa['cantidad'];
					$refaccion->descripcion = $refa['descripcion'];
					$refaccion->id_servicio = $id;
					$refaccion->save();
					Session::forget('refacciones');
				}
			}else{
				$refAfectadas = Refaccion::where('id_servicio','=',$id)->delete();
			}

			//	Actualizamos los datos de la tabla soluciones
			//	Consultamos la solucion del servicio asignada, si existe la actaulizamos si no existe creamos un nuevo registro
			if($solucion = Soluciones::where('id_servicio','=',$id)->first()) {
				$solucion->fecha = $post['fecha'];
				$solucion->trabajo_real = $post['horas'] . ":" . $post['minutos'];
				$solucion->descripcion = $post['descripcion'];
				$solucion->id_servicio = $id;
				$solucion->save();
			}else{
				$solucion = new Soluciones;
				$solucion->fecha = $post['fecha'];
				$solucion->trabajo_real = $post['horas'] . ":" . $post['minutos'];
				$solucion->descripcion = $post['descripcion'];
				$solucion->id_servicio = $id;
				$solucion->save();
			}

			DB::commit();
			$mensajeGlobal = "Se actualizo correctamente el registro.";
			$servicios = Servicios::all();
			return View::make('servicios.index',["servicios"=>$servicios,"mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-success"]);
		} catch (Exception $e) {
			DB::rollback();
			$mensajeGlobal = "Se produjo un error de sistema. Contacte a su administrador de sistemas." . $e;
			$datos = array(
				'mensajeGlobal'=>$mensajeGlobal,
				'tipo'=>'alert-danger',
				'servicio'=>$servicio,
				'equipo'=>$servicio->equipo,
				'usuario'=>$servicio->usuario,
				'caracteristicas'=>$servicio->equipo->caracteristicas,
				'post'=>Input::all(),
				'transaccion' => 'create'
				);
			return View::make('servicios.seguimiento')->with($datos);
		}	//	Try-catch
	}	//	Seguimiento store

	public function reporteServicios($id){
		$s = Servicios::find($id);
		// create new PDF document
		$pdf = new PdfServicio('P', 'mm', PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->Addpage();
		//return View::make("servicios.reporte",["s"=>$s]);
		//	Stilos
		$html = "";
		//	ESTILOS
		$html .= "<style>
		.titulo{
			color: #FFF;
		}
		</style>";

		//	SECCION 1
		$html .= '
		<table border="">
		    <tr><td colspan="2" align="center">SUBCOORDINACIÓN DE INFORMATICA</td></tr>
		    <tr><td colspan="2" align="center">Reporte de Servicio y/o Falla</td></tr>
		    <tr><td align="left">Folio: <strong>'.$s->folio.'</strong></td><td align="right">Fecha: <strong>'.$s->fecha_reporte.'</strong></td></tr>
		</table>
		';

		//	SECCION 2
		$html .= '
		<table border="1">
		    <tr><td align="center" colspan="10" class="titulo" bgcolor="#000">DATOS DEL SOLICITANTE (LLENAR ÁREA SOLICITANTE)</td></tr>
		    <tr><td colspan="3" align="right">Nombre de quien reporta: </td><td colspan="7" align="center">'. User::find($s->id_usuario)->nombres .' '. User::find($s->id_usuario)->apellidos.'</td></tr>
		    <tr><td colspan="3" align="right">Area: </td><td colspan="7" align="center">'. Areas::find(User::find($s->id_usuario)->area->id)->nombre.'</td></tr>
		    <tr><td colspan="3" align="right">Usuario del equipo: </td><td colspan="7" align="center">'. User::find($s->usuario_equipo)->nombres .' '. User::find($s->usuario_equipo)->apellidos.'</td></tr>
		    <tr><td colspan="3" align="right">Servicio o Falla Reportado: </td><td colspan="7" align="center"> '. $s->falla_reportada.'</td></tr>
		</table>
		';

		//	SECCION 3
		$html .= '
		<table border="1">
	    <tr>
	        <td colspan="2" align="center" class="titulo" bgcolor="#000">Asistencia Tecnica<br>Problemas que presenta el equipo:</td>
	        <td colspan="2" align="center" class="titulo" bgcolor="#000">Revisión y/o Mantenimiento <br> Dispositivos con problemas:</td>
	        <td colspan="2" align="center" class="titulo" bgcolor="#000">Instalar / Reinstalar Programas <br> Programas a instalar:</td>
	    </tr>
	    <tr>
	        <td colspan="2"><ul>
		';
		foreach ($s->asistencias as $asistencia) {
			$html .= '<li>' . $asistencia->problema .'  </li>';
		}
		$html .= '</ul></td><td colspan="2"><ul>';
		foreach ($s->revisiones as $revision) {
			$html .= '<li>' . $revision->dispositivo  .'  </li>';
		}
		$html .= '</ul></td><td colspan="2"><ul>';
		foreach ($s->programas as $programa) {
			$html .= '<li>' . $programa->programa  .'  </li>';
		}
		$html .= '</ul></td colspan="2"></tr></table>';

		//	SECCION 4
		$html .= '
		<table border="1">
		    <tr><td colspan="8" align="center" class="titulo" bgcolor="#000">DATOS DEL EQUIPO</td></tr>
		    <tr>
		        <td align="left">No.Inventario</td><td colspan="3">'. Equipo::find($s->id_equipo)->no_inventario .'</td>
		        <td align="left">Sistema Operativo</td><td colspan="3">'. Equipo::find($s->id_equipo)->caracteristicas[0]->so .'</td>
		    </tr>
		    <tr>
		        <td align="left">Marca</td><td colspan="3">'. Equipo::find($s->id_equipo)->marca .'</td>
		        <td align="left">RAM</td><td colspan="3">'. Equipo::find($s->id_equipo)->caracteristicas[0]->ram .' GB</td>
		    </tr>
		    <tr>
		        <td align="left">Modelo</td><td colspan="3">'. Equipo::find($s->id_equipo)->modelo .'</td>
		        <td align="left">Disco Duro</td><td colspan="3">'. Equipo::find($s->id_equipo)->caracteristicas[0]->disco_duro .' GB</td>
		    </tr>
		    <tr>
		        <td align="left">Descripción</td><td colspan="7">'. Equipo::find($s->id_equipo)->descripcion .'</td>
		    </tr>
		</table>
		';

		//	SECCION 5
		$html .= '
		<table border="1">
		    <tr><td align="center" class="titulo" bgcolor="#000">DIAGNOSTICO</td></tr>
		    <tr><td align="left"> '. $s->diagnostico .'</td></tr>
		</table>
		';

		//	SECCION 7
		$html .= '
		<table border="1">
		    <tr><td colspan="4" align="center" class="titulo" bgcolor="#000">REFACCIONES</td></tr>
		    <tr><td colspan="1">Cantidad</td><td colspan="3">Descripción</td></tr>';
		    foreach ($s->refacciones as $refaccion) {
		            $html .= '<tr><td colspan="1">'. $refaccion->cantidad .'</td><td colspan="3" align="left"> '. $refaccion->descripcion .'</td></tr>';
		    }
		$html.='</table>';

		//	SECCION 8
		$html .= '
		<table border="1">
		    <tr><td colspan="6" align="center" class="titulo" bgcolor="#000">DESCRIPCIÓN DE LA SOLUCION:</td></tr>
		    <tr><td align="left" colspan="2">Fecha Solución:</td><td colspan="4">Descripción</td></tr>
		    <tr><td colspan="2">'. $s->soluciones[0]->fecha .'</td><td rowspan="5" colspan="4">'. $s->soluciones[0]->descripcion .'</td></tr>
		    <tr><td align="left" colspan="2">Trabajo Real(H:M:S):</td></tr>
		    <tr><td colspan="2">'. $s->soluciones[0]->trabajo_real .'</td></tr>
		    <tr><td align="left" colspan="2">Estatus del reporte:</td></tr>
		';
		$estado = "";
	    switch ($s->estado) {
	        case '0':
	            $estado = "Por Atender";
	            break;
	        case '1':
	            $estado = "En Reparación";
	            break;
	        case '3':
	            $estado = "Terminado";
	            break;
	        case '2':
	            $estado = "En Espera de Refacciones";
	            break;
	    }
	    $html .= '<tr><td colspan="2">'. $estado .'</td></tr></table>';

	    //	SECCION 9
	    $html .= '
			<p><strong>NOTA: </strong>La subcoordinación de informatica no se hace responsable de; si hay perdida de información a causa de algún servicio de mantenimiento preventivo o correctivo, por virus o fallas de hardware.</p>
			<p align="center"><strong><u>RECUERDA QUE RESPALDAR LA INFORMACIÓN DEL EQUIPO ES RESPONSABILIDAD DEL USUARIO</u></strong></p>
			<table>
			    <tr>
			        <td align="center">
			            <p>___________________________________________<br>USUARIO DEL EQUIPO<br>(Nombre y Firma)</p>
			        </td>
			        <td align="center">
			        	<p>___________________________________________<br>SERVICIO REALIZADO POR<br>(Nombre y Firma)</p>
			        </td>
			    </tr>
			</table>
	    ';

	    #return $html;

		$pdf->SetFont('', '', 9.5);
		$pdf->writeHTMLCell(190,0,10,35,$html,0,0,0,false,'C');
        //$pdf->writeHTML($html,true,false,true,false,'C');
		//Close and output PDF document
		
		$pdf->Output('reporte_servicio.pdf', 'I');
	}
}	//	Class

//	Extendemos de TCPDF class para poder definir los metodos header y footer
class PdfServicio extends baseTcpdf{
	public function Header() {
        // Logo
        $image_file = public_path().'/img/he_membretada.jpg';
        $this->Image($image_file, 10, 10, 190, 20, 'JPEG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer() {
        $image_file = public_path().'/img/fo_membretada.jpg';
        $this->Image($image_file, 10, 280, 190, '', 'JPEG', '', 'B', false, 300, 'C', false, false, 0, false, false, false);
    }
}