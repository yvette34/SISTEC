@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">ACTUALIZACIÓN DE SERVICIO. <span class="small">Complete los campos.</span></h3>
	</div>
	<div class="panel-body">
		{{ Form::open(array('route'=>array('servicios/update',$servicio->id))) }}
			{{-- ESTADO --}}
			<input type="text" class="" id="" name="estado" value="{{ $servicio->estado or 0 }}" hidden>

			{{-- AREA --}}
			<div class="form-group col-md-6">
				{{-- Administrador --}}
				@if(Session::get('rol')==0 || Session::get('rol')==2)
					<?php
						$areas = Areas::all()->lists("nombre","id");
						$areas[""] = "Seleccione un opción";
					?>
					@if(isset($id_area))
						{{ Form::select("id_area",$areas,$id_area,["class"=>"form-control","placeholder"=>"AREA O COORDINACIÓN","id"=>"areas_servicios_update","required"=>"true"]) }}
					@else
						{{ Form::select("id_area",$areas,$area->id,["class"=>"form-control","placeholder"=>"AREA O COORDINACIÓN","id"=>"areas_servicios_update","required"=>"true"]) }}
					@endif
				@else
					<input type="text" class="form-control" id="" value="{{ $area->nombre or ''}}" readonly placeholder="AREA O COORDINACIÓN">
				@endif
			<p class="text-primary">Area o coordinación.</p>
			</div>

			{{-- USUARIO COMUN--}}
			<div class="form-group col-md-6">
				{{-- Si el usuario es administrador o encargado de area--}}
				@if(Session::get('rol') == 0 || Session::get('rol') == 2)
				{{-- En caso de que aya sido asignada el area y el usuario en la VALIDACION --}}
					@if(isset($id_area) && isset($id_usuario))
						<?php
							$usuarios_reg = Areas::find($id_area)->usuarios;
							$usuarios = array();
							foreach ($usuarios_reg as $usuario) {
								$usuarios[$usuario->id] = $usuario->nombres . " " . $usuario->apellidos;
							}
							$usuarios[""] = "Seleecione un usuario...";
						?>
						{{ Form::select("usuario_equipo",$usuarios,$usuario_equipo,["class"=>"form-control","id"=>"usuario_comun_servicios_update"]) }}
					@else
					<?php
					//	Si el usuario es Encargado de area cargamos los usuarios de su area. De lo contrario cargamos los usuarios del area del servicio
						//if(Session::get('rol')==2){}elseif(Session::get('rol')==0){$usuarios_reg = $area->usuarios;}
						$usuarios_reg = $area->usuarios;
						$usuarios = array();
						foreach ($usuarios_reg as $usuario) {
							$usuarios[$usuario->id] = $usuario->nombres . " " . $usuario->apellidos;
						}
						$usuarios[""] = "Seleecione un usuario...";
					?>
						{{ Form::select("usuario_equipo",$usuarios,$servicio->usuario_equipo,["class"=>"form-control","id"=>"usuario_comun_servicios_update"]) }}
					@endif
					<p class="text-primary">Usuario común del equipo.</p>
				@else
				{{-- USUARIO GENERAL --}}
				<?php
				//	Necesitamos todos los usuario del area
				$userArea = User::where("id_area","=",$area->id)->get();
				$usuarios = array();
				foreach ($userArea as $usuario) {
					$usuarios[$usuario->id] = $usuario->nombres . " " . $usuario->apellidos;
				}
				?>
				{{ Form::select("usuario_equipo",$usuarios,$servicio->usuario_equipo,["class"=>"form-control","id"=>"usuario_comun_servicios_update"]) }}
				<p class="text-primary">Usuario común del equipo.</p>
				@endif
			</div>

			{{-- FOLIO --}}
			<div class="form-group col-md-2">
				<input type="text" class="form-control" id="" value="{{ $servicio->folio or '' }}" name="folio" placeholder="FOLIO" readonly>
				<p class="text-primary">Folio.</p>
			</div>

			{{-- FECHA DE REPORTE --}}
			<div class="form-group col-md-3">
				<input type="date" class="form-control" id="" value="{{ $servicio->fecha_reporte or date('Y-m-d') }}" name="fecha_reporte" placeholder="FECHA" readonly>
				<p class="text-primary">Fecha de solicitud.</p>
			</div>

			{{-- USUARIO QUE REPORTO --}}
			<div class="form-group col-md-7">
				{{-- Si el suaurio es administrador o encargado de area solo se mostrara el nombre del usuario que reporto, no se puede editar --}}
				@if(Session::get('rol')==0 || Session::get('rol')==2)
				<?php
					$usuario_reporto = User::find($servicio->id_usuario);
					$nombre_usuario_reporto = $usuario_reporto->nombres . " " . $usuario_reporto->apellidos;
				?>
					<input type="text" value="{{ $servicio->id_usuario }}" name="id_usuario" hidden>
					<input type="text" class="form-control" value="{{ $nombre_usuario_reporto or '' }}" readonly>
					<p class="text-primary">Usuario que reporta.</p>
				@else
				{{-- si el usuario no es administrador solo ponemos el nombre del usuario logeado, ese sera el usuario de reporte--}}
				<input name="id_usuario" type="text" value="{{ Session::get('usuario') }}" hidden>
				<input type="text" class="form-control" value="{{ Session::get('usuario_nombre') }}" readonly>
				<p class="text-primary">Usuario que reporto.</p>
				@endif
			</div>

			{{-- SERVICIO O FALLA A VERIFICAR --}}
			<div class="form-group col-md-6">
				<textarea name="falla_reportada" id="" cols="2" class="form-control" placeholder="SERVICIO O FALLA">{{ $servicio->falla_reportada or '' }}</textarea>
				<p class="text-primary">Describa la falla o servicio que necesita.</p>
			</div>

			{{-- EQUIPO --}}
			<div class="form-group col-md-6">
				@if(isset($servicio->id_equipo))
				{{ Form::select('id_equipo',$equipos,$servicio->id_equipo,array('class'=>'form-control','id'=>'equipos_servicios_update')) }}
				@else
				{{ Form::select('id_equipo',$equipos,"",array('class'=>'form-control','id'=>'equipos_servicios_update')) }}
				@endif
				<p class="text-primary">Selecciona el equipo que desea que se atienda.</p>
			</div>
			<div class="clearfix"></div>
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
			<div class="col-md-2 col-md-offset-10">
				<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-refresh"></i> ACTUALIZAR DATOS</button>
			</div>
		{{ Form::close() }}
	</div>
</div>
@stop
@section("javascript")
@parent
{{-- Cargarmos el detalle del equipo del servicio, para validación y para caso normal --}}
{{-- Par el caso de VALIDACION --}}
@if(isset($id_area) && isset($id_usuario))
<?php echo '<script type="text/javascript">getDetalleEquipo('.$servicio->id_equipo.');</script>'; ?>
{{--  Caso normal --}}
@elseif(isset($servicio->id_equipo))
<?php echo '<script type="text/javascript">getDetalleEquipo('.$servicio->id_equipo.');</script>'; ?>
@endif
@stop