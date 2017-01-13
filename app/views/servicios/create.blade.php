@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">REGISTRO DE SERVICIO. <span class="small">Complete los campos.</span></h3>
	</div>
	<div class="panel-body">
		<form class="form" action="{{ URL::route('servicios/store') }}" method="POST" role="form">

			{{-- Estado del folio, esto en VALIDACION --}}
			<input type="text" class="" id="" name="estado" value="{{ $estado or 0 }}" hidden>

			{{-- AREA --}}
			<div class="form-group col-md-6">
				{{-- ADMINISTRADOR Y ENCARGADO DE AREA --}}
				{{-- Esté campo select tiene un evento para cargar el campo de select de usuarios --}}
				{{-- Si el usuario es administrador o encargado de area, podra seleccionar el area --}}
				@if(Session::get('rol')==0 || Session::get('rol')==2)
					<?php
						//	Tomamos la lista de areas
						$areas = Areas::all()->lists("nombre","id");
						$areas[""] = "Seleccione un opción";
					?>
					{{-- Si el id_area existe, quiere decir que es VALIDACION --}}
					@if(isset($id_area))
					{{-- Inprimimos el combo de areas con el area por defecto de la validacion --}}
						{{ Form::select("id_area",$areas,$id_area,["class"=>"form-control","placeholder"=>"AREA O COORDINACIÓN","id"=>"area_servicios_create","required"=>"true"]) }}
					@else
					{{-- En caso no haber validación de formulario se muestra el combo con las areas --}}
						{{ Form::select("id_area",$areas,"",["class"=>"form-control","placeholder"=>"AREA O COORDINACIÓN","id"=>"area_servicios_create","required"=>"true"]) }}
					@endif
				@else
				{{-- GENERAL --}}
					{{-- Si el usuario es general solo se imprimira el area de ese usuario --}}
					<input type="text" class="form-control" id="" value="{{ $area->nombre }}" readonly placeholder="AREA O COORDINACIÓN">
				@endif
			<p class="text-primary">Area o coordinación.</p>
			</div>

			{{-- USUARIO COMUN--}}
			<div class="form-group col-md-6">
				{{-- ADMINISTRADOR  O ENCARGADO DE AREA --}}
				{{-- Esta campo select se guarda en la DB como usuario_comun --}}
				@if(Session::get('rol')==0 || Session::get('rol')==2)
					<?php 
						//	Si se asigno el id_area y el id_usuario, en VALIDACION
						if(isset($id_area) && isset($id_usuario)){
							//	Creamos la lista de usuarios del area asignada
							$usuarios = Areas::find($id_area)->usuarios->lists("nombres","id");
							$usuario[""] = "Seleccion el usuario";
							?>
							{{-- Campo select poblado con los usuarios del area y seleccionado el usuario mandado por defecto $id_usuario --}}
								{{ Form::select("usuario_equipo",$usuarios,$id_usuario,["class"=>"form-control","id"=>"usuario_comun_servicios_create","required"=>"true"]) }}
							<?php
						}else{
							//	En caso de no ser validacion, el campo quedara vacio hasta que el usuario seleccione un area, con el evento Ajax se poblara el combo.
							?>
							{{ Form::select("usuario_equipo",array(""=>"Seleccione el usuario"),"",["class"=>"form-control","id"=>"usuario_comun_servicios_create","required"=>"true"]) }}
							<?php
						}
					?>
					<p class="text-primary">Usuario común del equipo.</p>
				@else
				{{-- GENERAL --}}
				{{-- Para los usuarios General se cargaran los usuarios del area seleccionando por defecto al usuario logeado--}}
				<?php
					$usersArea = User::where("id_area","=",User::find(Session::get('usuario'))->area->id)->get()->toArray();
					$usuariosArea = array();
					foreach ($usersArea as $user) {
						$usuariosArea[$user['id']] = $user['nombres'] . " " . $user['apellidos'];
					}
				?>
				{{-- Poblamos el select con los usuarios del area --}}
				{{ Form::select("usuario_equipo",$usuariosArea,Session::get('usuario'),["class"=>"form-control","id"=>"usuario_comun_servicios_create"]) }}
					<p class="text-primary">Usuario común del equipo</p>
				@endif
			</div>
			<div class="clearfix"></div>

			{{-- FOLIO --}}
			<div class="form-group col-md-2">
				<input type="text" class="form-control" id="" value="{{ $last_folio or '' }}" name="folio" placeholder="FOLIO" readonly>
				<p class="text-primary">Folio.</p>
			</div>

			{{-- Fecha de solicitud --}}
			<div class="form-group col-md-3">
				<input type="date" class="form-control" id="" value="{{ $fecha_reporte or date('Y-m-d') }}" name="fecha_reporte" placeholder="FECHA">
				<p class="text-primary">Fecha de solicitud.</p>
			</div>

			{{--USUARIO QUE REPORTA --}}
			<div class="form-group col-md-7">
				{{-- Si el usuaruio que reporta es Administrador o Encargado de Area, Se escribira en el input el nombre de este usuario logeado --}}
				@if(Session::get('rol')==0 || Session::get('rol')==2)
					<input type="text" id="" value="{{ Session::get('usuario') }}" name="id_usuario" hidden>
					<input type="text" class="form-control" id="" value="{{ Session::get('usuario_nombre') }}" placeholder="USUARIO QUE REPORTA" readonly>
					<p class="text-primary">Usuario que reporta</p>
				@else
				{{-- Para los usuarios General se cargaran los usuarios del area seleccionando por defecto al usuario logeado--}}
				<?php
					$usersArea = User::where("id_area","=",User::find(Session::get('usuario'))->area->id)->get()->toArray();
					$usuariosArea = array();
					foreach ($usersArea as $user) {
						$usuariosArea[$user['id']] = $user['nombres'] . " " . $user['apellidos'];
					}
				?>
				{{-- Se asigna el id_usaurio sacandolo de la session, ese sera el usuario que reporta y se registra como id_usuario--}}
				<input name="id_usuario" type="text" value="{{ Session::get('usuario') }}" hidden>
				<input name="" type="text" class="form-control" id="" value="{{ Session::get('usuario_nombre') }}" readonly placeholder="USUARIO QUE REPORTA">
				<p class="text-primary">Usuario que reporta.</p>
				@endif
			</div>

			{{-- Descripción del servicio --}}
			<div class="form-group col-md-6">
				<textarea name="falla_reportada" id="" cols="2" class="form-control" placeholder="SERVICIO O FALLA">{{ $falla_reportada or '' }}</textarea>
				<p class="text-primary">Describa la falla o servicio que necesita.</p>
			</div>

			{{-- Equipos --}}
			<div class="form-group col-md-6">
				@if(isset($id_equipo))
				{{ Form::select('id_equipo',$equipos,$id_equipo,array('class'=>'form-control','id'=>'equipos_servicios_create')) }}
				@else
				{{ Form::select('id_equipo',$equipos,"",array('class'=>'form-control','id'=>'equipos_servicios_create')) }}
				@endif
				<p class="text-primary">Selecciona el equipo que desea que se atienda.</p>
			</div>
			<div class="clearfix"></div>

			{{-- Detalles del equipo seleccionado --}}
			<p class="text-primary">Detalles del equipo seleccionado.</p>
			<ul class="list-group col-md-6">
				<li class="list-group-item">No. Inventario: <strong id="inventario"></strong></li>
				<li class="list-group-item">Marca: <strong id="marca"></strong></li>
				<li class="list-group-item">Modelo: <strong id="modelo"></strong></li>
				<li class="list-group-item">Descripción: <strong id="descripcion"></strong></li>
				<li class="list-group-item">Estado: <strong id="estado"></strong></li>
				<li class="list-group-item">Area: <strong id="area"></strong></li>
				<li class="list-group-item">Usuario: <strong id="usuario"></strong></li>
			</ul>
			<ul class="list-group col-md-6">
				<li class="list-group-item">Sistema Operativo: <strong id="so"></strong></li>
				<li class="list-group-item">Memoria RAM: <strong id="ram"></strong> GB</li>
				<li class="list-group-item">Disco Duro: <strong id="hdd"></strong> GB</li>
				<li class="list-group-item">Direccion IP: <strong id="ip"></strong></li>
				<li class="list-group-item">Direccion MAC: <strong id="mac"></strong></li>
				<li class="list-group-item">Nodo: <strong id="nodo"></strong></li>
				<li class="list-group-item">Usuario del pc: <strong id="usuario_pc"></strong></li>
				<li class="list-group-item">Grupo de trabajo: <strong id="grupo_trabajo"></strong></li>
			</ul>

			{{-- Submit --}}
			<div class="col-md-2 col-md-offset-10">
				<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-floppy-o"></i> GUARDAR DATOS</button>
			</div>
		</form>
	</div>
</div>
@stop
@section("javascript")
	@parent
	@if(isset($id_equipo))
		<?php //echo '<script type="text/javascript">getDetalleEquipo('.$id_equipo.');</script>'; ?>
	@endif
@stop