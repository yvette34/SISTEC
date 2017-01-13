@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">DETALLE DEL SERVICIO</h3>
	</div>
	<div class="panel-body">
		<div class="col-md-4">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="#" class="thumbnail">
					{{ HTML::image('img/servicio.jpg','',['class'=>'']) }}
				</a>
			</div>
		</div>
		<div class="col-md-8">
			<div class="col-md-4">
				<p class="text-primary">Servicio.</p>
				<?php
				$usuario = Servicios::find($servicio->id)->usuario;
				$area = Areas::find($usuario->id_area);
						//	Asiganmos estilo y nombre al estado
				$estado = array('estilo'=>'','etiqueta'=>'','fila'=>'');
				switch ($servicio->estado) {
					case '0':
						$estado['estilo'] = "btn-xs btn-danger";
						$estado['etiqueta'] = "Pendiente";
						$estado['fila'] = "bg-danger";
						break;
					case '1':
						$estado['estilo'] = "btn-xs btn-info";
						$estado['etiqueta'] = "Revisión";
						$estado['fila'] = "bg-info";
						break;
					case '3':
						$estado['estilo'] = "btn-xs btn-success";
						$estado['etiqueta'] = "Terminado";
						$estado['fila'] = "bg-success";
						break;
					case '2':
						$estado['estilo'] = "btn-xs btn-warning";
						$estado['etiqueta'] = "Espera";
						$estado['fila'] = "bg-warning";
						break;
					}
				?>
				* FOLIO: <strong> {{ $servicio->folio }}</strong><br>
				* ESTADO:
				@if(Session::get("rol") == 0)
				<a title="click para actualizar" class="btn {{ $estado['estilo'] }}" href="{{URL::route('servicios/seguimiento',array('id'=>$servicio->id))}}"><i class="fa fa-pencil-square-o"></i> {{ $estado['etiqueta'] }}</a>
				@else
				<a title="click para actualizar" class="btn {{ $estado['estilo'] }}" href="#" disabled><i class="fa fa-pencil-square-o"></i> {{ $estado['etiqueta'] }}</a>
				@endif
				<br>
				* FECHA DE SOLICITUD: <strong> {{ $servicio->fecha_reporte }}</strong><br>
				* AREA: <strong> {{ $area->nombre }}</strong><br>
				* USUARIO QUE REPORTO: <strong> {{ $usuario->nombres }} {{ $usuario->apellidos }}</strong><br>
				* USUARIO COMÚN DEL EQUIPO: <strong> {{ User::find($servicio->usuario_equipo)->nombres }} {{ User::find($servicio->usuario_equipo)->apellidos }}</strong><br>
				* DESCRIPCIÓN DEL PROBLEMA: <br><strong> {{ $servicio->falla_reportada }}</strong>
			</div>
			<div class="col-md-4">
				<p class="text-primary">Equipo.</p>
					<?php
					$estado = "";
						switch ($equipo->estado) {
							case '0':
								$estado = "Requiere Sustitución";
								break;
							case '1':
								$estado = "Regular";
								break;
							case '2':
								$estado = "Bueno";
								break;
							case '3':
								$estado = "Excelente";
								break;
						}
					?>
					* NO. INVENTARIO: <strong>{{ $equipo->no_inventario  }}</strong><br>
					* MARCA: <strong>{{ $equipo->marca  }}</strong><br>
					* MODELO: <strong>{{ $equipo->modelo }}</strong><br>
					* ESTADO: <strong>{{ $estado  }}</strong><br>
					* DESCRIPCIÓN: <br><strong>{{ $equipo->descripcion  }}</strong><br>
			</div>
			<div class="col-md-4">
				<p class="text-primary">Otras caracteristicas.</p>
					* SISTEMA OPERATIVO: <strong>{{ $equipo->caracteristicas[0]->so }}</strong><br>
					* RAM: <strong>{{ $equipo->caracteristicas[0]->ram }}</strong><br>
					* DISCO DURO: <strong>{{ $equipo->caracteristicas[0]->disco_duro }}</strong><br>
					* IP: <strong>{{ $equipo->caracteristicas[0]->ip }}</strong><br>
					* MAC: <strong>{{ $equipo->caracteristicas[0]->mac }}</strong><br>
					* NODO: <strong>{{ $equipo->caracteristicas[0]->nodo }}</strong><br>
					* USUARIO DE PC: <strong>{{ $equipo->caracteristicas[0]->usuario_pc }}</strong><br>
					* GRUPO DE TRABAJO: <strong>{{ $equipo->caracteristicas[0]->grupo_trabajo }}</strong><br>
			</div>
			<div class="col-md-12">
				<div class="btn-group-xs btn-group-horizontal col-md-offset-9">
					<a title="click para modificar" class="btn btn-primary" href="{{URL::route('servicios/edit',array('id'=>$servicio->id))}}" role="button">
					<i class="fa fa-pencil-square-o"></i> Actualizar</a>
					<a class="btn btn-default btn-danger" href="#" role="button"><i class="fa fa-trash"></i> Eliminar</a>
				</div>
			</div>
			
		</div>
	</div>
</div>
@stop