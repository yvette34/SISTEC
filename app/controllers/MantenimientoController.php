<?php
//	Requerimos la base de TCPDF
use Maxxscho\LaravelTcpdf\LaravelTcpdf as baseTcpdf;

class MantenimientoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$soportes = Soporte::all();
		return View::make('mantenimiento.index')->with(array("soportes"=>$soportes));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Tomamos el ultimo folio registrado de mantenimientos
		$last_folio = DB::table('soportes')->max('folio');
		//	lo incrementamos
		$last_folio++;
		//	Tomamos las areas para llenar el select
		$areas = Areas::all()->lists("nombre","id");
		$areas[0] = "Seleccione el area"; 
		//	Retornamos al formulario
		return View::make('mantenimiento.create')->with(array("folio"=>$last_folio,"areas"=>$areas));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$mensajeGlobal = "";
		//	Reglas de validacion
		$validator = Validator::make(
			[
				"folio"=>Input::get("folio"),
				"fecha"=>Input::get("fecha"),
				"id_usuario"=>Input::get("id_usuario"),
				"id_equipo"=>Input::get("equipo")
			],
			[
				"folio"=>"required",
				"fecha"=>"required",
				"id_usuario"=>"required",
				"id_equipo"=>"required"
			]
		);

		//	Creamos los mensajes de validacion en caso de error
		if($validator->fails()){
				if($validator->messages()->first('folio') != ''){ $mensajeGlobal .= "El campo folio es requerido. <br>"; }
				if($validator->messages()->first('fecha') != ''){ $mensajeGlobal .= "El campo fecha es requerido. <br>"; }
				if($validator->messages()->first('id_usuario') != ''){ $mensajeGlobal .= "Seleccione un usuario. <br>"; }
				if($validator->messages()->first('id_equipo') != ''){ $mensajeGlobal .= "Seleccione el equipo(s) a checar. <br>"; }
		}

		//	En caso de error
		if($mensajeGlobal != ""){
			//	Tomamos el ultimo folio registrado de mantenimientos
			$last_folio = DB::table('soportes')->max('folio');
			//	lo incrementamos
			$last_folio++;
			//	Tomamos las areas para llenar el select
			$areas = Areas::all()->lists("nombre","id");
			$areas[0] = "Seleccione el area";
			//	Retornamos al formulario
			return View::make('mantenimiento.create')->with(array("folio"=>$last_folio,"areas"=>$areas,"mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"));
		}

		//	Comanzamos la transaccion
		try {
			DB::beginTransaction();
			$post = Input::all();
			//	Guardamos los datos en la tabla de soprote
			$soporte = new Soporte;
			$soporte->tipo = $post['tipo'];
			$soporte->fecha = $post['fecha'];
			$soporte->folio = $post['folio'];
			$soporte->estado_equipo = $post['estado_equipo'];
			$soporte->observaciones = $post['observaciones'];
			$soporte->resguardo_a = $post['resguardo'];
			$soporte->id_usuario = $post['id_usuario'];
			$soporte->save();

			//	Despues guardamos os datos en la tabla de soporte_equipo. Para cada equipo a checar gaurdamos un registro
			#	Recuperamos el ultimo folio de soporte que se inserto
			$last_id = DB::table('soportes')->max('id');
			foreach ($post['equipo'] as $key => $value) {
				$soporteEquipo = new SoporteEquipo;
				$soporteEquipo->id_soporte = $last_id;
				$soporteEquipo->id_equipo = $value;
				$soporteEquipo->save();
			}

			//	Guardar los datos de la tabla de accesorios
			$accesorios = new Accesorios;
			$accesorios->teclado = $post['teclado'];
			$accesorios->mouse = $post['mouse'];
			$accesorios->regulador = $post['regulador'];
			$accesorios->otros = $post['otros'];
			$accesorios->id_soporte = $last_id;
			$accesorios->save();

			//	Guardar los datos en la tabla de software
			foreach ($post['software'] as $key => $value) {
				$software = new Software;
				$software->nombre = $value;
				$software->id_soporte = $last_id;
				$software->save();
			}

			//	Guardar en la tabla de Caracteristicas del mantenimiento
			foreach ($post['caracteristicas'] as $key => $value) {
				$caracteristicas = new Caracteristicas;
				$caracteristicas->nombre = $value;
				$caracteristicas->id_soporte = $last_id;
				$caracteristicas->save();
			}
			DB::commit();
			//	Retornamos a la vista principal y listamos los datos
			$soportes = Soporte::all();
			$mensaje = "Se registro correctamente el servicio.";
			$tipo = "alert-success";
			return View::make('mantenimiento.index')->with(array("mensajeGlobal"=>$mensaje,"tipo"=>$tipo,"soportes"=>$soportes));
		} catch (Exception $e) {
			DB::rollback();
			//	Tomamos el ultimo folio registrado de mantenimientos
			$last_folio = DB::table('soportes')->max('folio');
			//	lo incrementamos
			$last_folio++;
			//	Tomamos las areas para llenar el select
			$areas = Areas::all()->lists("nombre","id");
			$areas[0] = "Seleccione el area";
			$mensajeGlobal = "Occurrio un error interno de sistema. Contacte a su administrador de sistemas.";
			//	Retornamos al formulario
			return View::make('mantenimiento.create')->with(array("folio"=>$last_folio,"areas"=>$areas,"mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger"));
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
		$soporte = Soporte::find($id);
		return View::make('mantenimiento.show',["soporte"=>$soporte]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Tomamos los datos del mantenimiento
		#	El mantenimiento
		$soporte = Soporte::find($id);
		#	Necesitamos la areas
		$areas = Areas::all()->lists("nombre","id");
		$areas[0] = "Seleccione el area";
		#	El usuario y su area (El id del area la trae el usuario)
		$usuario = $soporte->usuario;
		#	Usuarios del area
		$usuarios = User::where("id_area","=",$usuario->id_area)->get()->toArray();
		#	Equipos del usuario
		$equipos_usuario = $usuario->equipos;
		#	Los equipos a los que se les dio soporte
		$equipos_soporte = SoporteEquipo::where('id_soporte',"=",$id)->get()->toArray();
		#	Accesorios
		$accesorios = $soporte->accesorios;
		#	Software
		$software = $soporte->software->lists("nombre");
		#	Caracteristicas del mantenimiento
		$caracteristicas = $soporte->caracteristicas->lists("nombre");
		$datos = array(
			"soporte"=>$soporte,
			"areas"=>$areas,
			"usuario"=>$usuario,
			"equipos_usuario"=>$equipos_usuario,
			"equipos_soporte"=>$equipos_soporte,
			"accesorios"=>$accesorios,
			"software"=>$software,
			"caracteristicas"=>$caracteristicas,
			"usuarios" =>$usuarios
			);
		return View::make('mantenimiento.edit')->with($datos);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$mensajeGlobal = "";
		//	Reglas de validacion
		$validator = Validator::make(
			[
				"folio"=>Input::get("folio"),
				"fecha"=>Input::get("fecha"),
				"id_usuario"=>Input::get("id_usuario"),
				"id_equipo"=>Input::get("equipo")
			],
			[
				"folio"=>"required",
				"fecha"=>"required",
				"id_usuario"=>"required",
				"id_equipo"=>"required"
			]
		);

		//	Creamos los mensajes de validacion en caso de error
		if($validator->fails()){
				if($validator->messages()->first('folio') != ''){ $mensajeGlobal .= "El campo folio es requerido. <br>"; }
				if($validator->messages()->first('fecha') != ''){ $mensajeGlobal .= "El campo fecha es requerido. <br>"; }
				if($validator->messages()->first('id_usuario') != ''){ $mensajeGlobal .= "Seleccione un usuario. <br>"; }
				if($validator->messages()->first('id_equipo') != ''){ $mensajeGlobal .= "Seleccione el equipo(s) a checar. <br>"; }
		}

		//	En caso de error
		if($mensajeGlobal != ""){
			//	Soporte
			$soporte = Soporte::find($id);
			//	Areas
			$areas = Areas::all()->lists("nombre","id");
			$areas[0] = "Seleccione el area";
			//	Usuarios del area
			$usuarios = User::where("id_area","=",$soporte->usuario->id_area)->get()->toArray();
			//	Equipos a checar
			$equipos_soporte = SoporteEquipo::where('id_soporte',"=",$id)->get()->toArray();

			//	Datos a retornar
			$datos = array(
			"soporte"=>$soporte,
			"areas"=>$areas,
			"usuario"=>$soporte->usuario,
			"equipos_usuario"=>$soporte->usuario->equipos,
			"equipos_soporte"=>$equipos_soporte,
			"accesorios"=>$soporte->accesorios,
			"software"=>$soporte->software->lists("nombre"),
			"caracteristicas"=>$soporte->caracteristicas->lists("nombre"),
			"usuarios" =>$usuarios,
			"mensajeGlobal" => $mensajeGlobal,
			"tipo"=>"alert-danger"
			);
			return View::make('mantenimiento.edit')->with($datos);
		}

		//	Comenzamos la transaccion
		try {
			DB::beginTransaction();
			$post = Input::all();
			//	Guardamos los datos en la tabla de soporte
			$soporte = Soporte::find($id);
			$soporte->tipo = $post['tipo'];
			$soporte->fecha = $post['fecha'];
			$soporte->folio = $post['folio'];
			$soporte->estado_equipo = $post['estado_equipo'];
			$soporte->observaciones = $post['observaciones'];
			$soporte->resguardo_a = $post['resguardo'];
			$soporte->id_usuario = $post['id_usuario'];
			$soporte->save();

			//	Guardar los datos de la tabla de accesorios
			$accesorios = $soporte->accesorios;
			$accesorios = Accesorios::find($accesorios[0]->id);
			$accesorios->teclado = $post['teclado'];
			$accesorios->mouse = $post['mouse'];
			$accesorios->regulador = $post['regulador'];
			$accesorios->otros = $post['otros'];
			$accesorios->save();

			//	Guardar los datos en la tabla de software
			$software = Software::where("id_soporte","=",$id)->delete();
			foreach ($post['software'] as $key => $value) {
				$software = new Software;
				$software->nombre = $value;
				$software->id_soporte = $id;
				$software->save();
			}

			//	Guardar en la tabla de Caracteristicas del mantenimiento
			$caracteristicas = Caracteristicas::where("id_soporte","=",$id)->delete();
			foreach ($post['caracteristicas'] as $key => $value) {
				$caracteristicas = new Caracteristicas;
				$caracteristicas->nombre = $value;
				$caracteristicas->id_soporte = $id;
				$caracteristicas->save();
			}

			//	Actualizamos los datos del equipo
			SoporteEquipo::where("id_soporte","=",$id)->delete();
			foreach ($post['equipo'] as $key => $value) {
				$soporteEquipo = new SoporteEquipo;
				$soporteEquipo->id_soporte = $id;
				$soporteEquipo->id_equipo = $value;
				$soporteEquipo->save();
			}

			DB::commit();
			//	Retornamos a la vista principal y listamos los datos
			$soportes = Soporte::all();
			$mensaje = "Se actualizo con exito el registro.";
			$tipo = "alert-success";
			return View::make('mantenimiento.index')->with(array("mensajeGlobal"=>$mensaje,"tipo"=>$tipo,"soportes"=>$soportes));
		} catch (Exception $e) {
			DB::rollback();
			//	Soporte
			$soporte = Soporte::find($id);
			//	Areas
			$areas = Areas::all()->lists("nombre","id");
			$areas[0] = "Seleccione el area";
			//	Usuarios del area
			$usuarios = User::where("id_area","=",$soporte->usuario->id_area)->get()->toArray();
			//	Equipos a checar
			$equipos_soporte = SoporteEquipo::where('id_soporte',"=",$id)->get()->toArray();
			//	MEnsaje del error
			$mensajeGlobal = "A ocurrido un error interno en el sistema. Por favor contacte a su administrador de sistemas.<br>".$e;
			//	Datos a retornar
			$datos = array(
			"soporte"=>$soporte,
			"areas"=>$areas,
			"usuario"=>$soporte->usuario,
			"equipos_usuario"=>$soporte->usuario->equipos,
			"equipos_soporte"=>$equipos_soporte,
			"accesorios"=>$soporte->accesorios,
			"software"=>$soporte->software->lists("nombre"),
			"caracteristicas"=>$soporte->caracteristicas->lists("nombre"),
			"usuarios" =>$usuarios,
			"mensajeGlobal" => $mensajeGlobal,
			"tipo"=>"alert-danger"
			);
			return View::make('mantenimiento.edit')->with($datos);
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
		//
	}

	/**
	 * Busca usuario de un area determinada
	 */
	public function searchUsersFromArea($id){
		$area = Areas::find($id);
		$usuarios = $area->usuarios;
		$html = "<option value='' selected>Seleccione al usuario...</option>";
		foreach ($usuarios as $usuario) {
			$html .= "<option value=\"$usuario->id\">".$usuario->nombres . " " .$usuario->apellidos. "</option>";
		}
		return $html;
	}

	/**
	 * Busca el equipo que corresponde a determinado usuario
	 */
	public function searchEquipoFromUsuario($id){
		$usuario = User::find($id);
		$equipos = $usuario->equipos;
		//	Si no hay registros retorna una fila vacia
		$response = array();
		$html = "";
		foreach ($equipos as $equipo) {
			$html .= "<tr>";
			$html .= "<td>$equipo->no_inventario</td>";
			$html .= "<td>$equipo->marca</td>";
			$html .= "<td>$equipo->modelo</td>";
			$html .= "<td>".$equipo->caracteristicas[0]->so."</td>";
			$html .= "<td>".$equipo->caracteristicas[0]->ram."</td>";
			$html .= "<td>".$equipo->caracteristicas[0]->disco_duro."</td>";
			$html .= "<td><input name=\"equipo[]\" type=\"checkbox\" value=\"$equipo->id\"> Revisión.</td>";
			$html .= "</tr>";
		}
		return $html;
	}

	public function getEquiposDeUsuario($id){
		$usuario = User::find($id);
		$html = "<option value='' selected>Seleccione el equipo.</option>";
		foreach ($usuario->equipos as $equipo) {
			$html .= "<option value='$equipo->id'>$equipo->descripcion</option>";
		}
		return $html;
	}

	public function reporetM($id){
		$soporte = Soporte::find($id);
		$pdf = new PdfServicio('P', 'mm', PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->Addpage();
		$html = '';
		//	ESTILOS
		$html .= "<style>
		.titulo{
			color: #FFF;
		}
		</style>";
		//	SECCION 1
		$estado_equipo = "";
			switch ($soporte->estado_equipo) {
				case '1':
					$estado_equipo = "Excelente";
				break;
				case '2':
					$estado_equipo = "Bueno";
				break;
				case '3':
					$estado_equipo = "Regular";
				break;
				case '4':
					$estado_equipo = "Requiere Sustitución";
				break;
			}
		$html .='
		<table border="1">
			<tr>
				<td align="left" colspan="3">Resguardo a: </td><td align="center" colspan="7">' . User::find($soporte->id_usuario)->nombres . ' ' . User::find($soporte->id_usuario)->apellidos . '</td>
				<td align="left" colspan="2">Fecha: </td><td colspan="4">'.$soporte->fecha.'</td>
				<td align="left" colspan="2">Folio: </td><td colspan="4">'.$soporte->folio.'</td>
			</tr>
			<tr>
				<td align="left" colspan="3">Área: </td><td colspan="7">'.Areas::find(User::find($soporte->id_usuario)->id_area)->nombre.'</td>
				<td align="left" colspan="6">Estatus del equipo: </td><td colspan="6">'.$estado_equipo.'</td>
			</tr>
		</table>
		';
		//	SECCION 2
		$html .= '<table border="1"><tr><td class="titulo" bgcolor="#000" align="center" colspan="10">Equipos Revisados</td></tr>';
		$html .= '<tr><td>No. Inventario</td><td>Marca</td><td>Modelo</td><td>Sistema Operativo</td><td>Memoria RAM</td><td>Disco Duro</td><td colspan="4">Descripción</td></tr>';
		$ids_equipos = SoporteEquipo::where("id_soporte","=",$soporte->id)->get()->toArray();
		foreach($ids_equipos as $equipo){
			$equipo = Equipo::find($equipo['id_equipo']);
			$html .= '
				<tr>
					<td>'.$equipo->no_inventario.'</td>
					<td>'.$equipo->marca.'</td>
					<td>'.$equipo->modelo.'</td>
					<td>'.$equipo->caracteristicas[0]->so.' </td>
					<td>'.$equipo->caracteristicas[0]->ram.' GB</td>
					<td>'.$equipo->caracteristicas[0]->disco_duro.' GB</td>
					<td colspan="4">'.$equipo->descripcion.'</td>
				</tr>
			';
		}
		$html .= '</table>';
		//	SECCION 3
		$html .= '
			<table border="1">
				<tr><td colspan="6" align="center" class="titulo" bgcolor="#000">Accesorios</td><td colspan="6" align="center" class="titulo" bgcolor="#000">Software del equipo</td></tr>
				<tr><td align="left" colspan="2">Teclado: </td><td align="center" colspan="4">'.$soporte->accesorios[0]->teclado.'</td>
					<td rowspan="4" colspan="6"><ul>';
					foreach($soporte->software as $software){
						$html .= '<li>'.$software->nombre.'</li>';
					}
				$html .= '</ul></td>
				</tr>
				<tr><td align="left" colspan="2">Mouse: </td><td align="center" colspan="4">'.$soporte->accesorios[0]->mouse.'</td></tr>
				<tr><td align="left" colspan="2">Regulador: </td><td align="center" colspan="4">'.$soporte->accesorios[0]->regulador.'</td></tr>
				<tr><td align="left" colspan="2">Otros: </td><td align="center" colspan="4">'.$soporte->accesorios[0]->otros.'</td></tr>
			</table>
		';
		//	SECCION 4
		$tipo = "";
		switch ($soporte->tipo) {
			case '0':
				$tipo = "Preventivo";
				break;
			case '1':
				$tipo = "Correctivo";
				break;
		}
		$html .= '
			<table border="1">
				<tr><td align="center" class="titulo" bgcolor="#000" colspan="10">Caracteristicas del mantenimiento</td></tr>
				<tr><td colspan="3" align="center">Tipo de Mantenimiento: </td><td rowspan="2" colspan="7"><ul>';
				foreach($soporte->caracteristicas as $caracteristica){
					$html .= '<li>'.$caracteristica->nombre.'</li>';
				}
		$html .= '</ul></td></tr><tr><td colspan="3" align="center"><strong>'.$tipo.'</strong></td></tr></table>';

		//	SECCION 5
		$html .= '
		<table border="1">
		    <tr><td align="center" class="titulo" bgcolor="#000">Observaciones</td></tr>
		    <tr><td align="center"> '. $soporte->observaciones .'</td></tr>
		</table>
		';

		//	SECCION 6
		$html .= '
			<table>
			    <tr>
			        <td align="center">
			            <p>___________________________________________<br>Técnico Encargado del Mantenimiento<br>(Nombre y Firma)</p>
			        </td>
			        <td align="center">
			        	<p>___________________________________________<br>Firma de Conformidad del Usuario<br>(Nombre y Firma)</p>
			        </td>
			    </tr>
			</table>
	    ';

		$pdf->SetFont('', '', 9.5);
		$pdf->writeHTMLCell(190,0,10,23,$html,0,0,0,false,'C');
        //$pdf->writeHTML($html,false,false,false,false,'C');
		$pdf->Output('reporte_soporte.pdf', 'I');
	}

}


//	Extendemos de TCPDF class para poder definir los metodos header y footer
class PdfServicio extends baseTcpdf{
	public function Header() {
        // Logos
        $image_file = public_path().'/img/logooficial.jpg';
        $this->Image($image_file, 10, 10, 40, '', 'JPEG', '', '', false, 300, 'L', false, false, 0, false, false, false);
        $this->SetFont('', '', 7);
        $html = '<h3>SUBCOORDINACIÓN DE INFORMATICA</h3><strong>Actualización de Inventario y Mantenimiento Preventivo y Correctivo</strong>';
        $this->writeHTMLCell(100,0,60,10,$html,0,0,0,false,'C');
        $image_file = public_path().'/img/gob_estado.jpg';
        $this->Image($image_file, 10, 10, 20, '', 'JPEG', '', '', false, 300, 'R', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer() {
    	/*
        $image_file = public_path().'\img\fo_membretada.jpg';
        $this->Image($image_file, 10, 280, 190, 10, 'JPEG', '', 'B', false, 300, 'C', false, false, 0, false, false, false);
        */
    }
}