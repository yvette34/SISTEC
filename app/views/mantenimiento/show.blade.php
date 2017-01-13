@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Detalle del mantenimiento</h3>
	</div>
	<div class="panel-body">
		<div class="col-md-4">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="#" class="thumbnail">
					{{ HTML::image('img/mantenimiento.png','',['class'=>'']) }}
				</a>
			</div>
		</div>
		<div class="col-md-8 small">
			<div class="col-md-4">
				<p class="text-primary">Detalle del mantenimiento.</p>
				<?php
					$estado_equipo = "";
					$tipo = "";
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
					switch ($soporte->tipo) {
						case '0':
							$tipo = "Preventivo";
							break;
						case '1':
							$tipo = "Correctivo";
							break;
					}
				?>
				<ul class="list-group">
					<li class="list-group-item">Folio: <strong>{{ $soporte->folio }}</strong></li>
					<li class="list-group-item">Fecha de registro: <strong>{{ $soporte->fecha }}</strong></li>
					<li class="list-group-item">Estado del equipo: <strong>{{ $estado_equipo }}</strong></li>
					<li class="list-group-item">Tipo de mantenimiento: <strong>{{ $tipo }}</strong></li>
					<li class="list-group-item">Resguardo a: <strong>{{ $soporte->resguardo_a }}</strong></li>
					<li class="list-group-item">Area: <strong>{{ $soporte->usuario->area->nombre }}</strong></li>
					<li class="list-group-item">Usuario: <strong>{{ $soporte->usuario->nombres }} {{ $soporte->usuario->apellidos }}</strong></li>
					<li class="list-group-item">Observaciones: <strong>{{ $soporte->observaciones }}</strong></li>
				</ul>
			</div>
			<div class="col-md-4">
				<p class="text-primary">Software del equipo.</p>
				<ul class="list-group">
					@foreach($soporte->software as $software)
					<li class="list-group-item"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> {{ $software->nombre }}</li>
					@endforeach
				</ul>
			</div>
			<div class="col-md-4">
				<p class="text-primary">Caracteristicas del mantenimiento.</p>
				<ul class="list-group">
					@foreach($soporte->caracteristicas as $caracteristica)
					<li class="list-group-item"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> {{ $caracteristica->nombre }}</li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="clearfix"></div>
		<p class="text-primary">Equipos revisados.</p>
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-condensed small">
				<thead>
					<tr>
						<th>No.Inventario</th>
						<th>Marca</th>
						<th>Modelo</th>
						<th>Descripción</th>
						<th>Estado</th>
						<th>Area</th>
						<th>Usuario responsable</th>
					</tr>
				</thead>
				<tbody>
					{{-- Buscamos los equipos a los que se les dio soporte --}}
					<?php
						$ids_equipos = SoporteEquipo::where("id_soporte","=",$soporte->id)->get()->toArray();
						//var_dump($ids_equipos[0]['id_equipo']);
					?>
					@foreach($ids_equipos as $equipo)
					<?php $equipo = Equipo::find($equipo['id_equipo']); ?>
					<?php 
					//	Asignamos el estado del equipo
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
					<tr>
						<td>{{ $equipo->no_inventario }}</td>
						<td>{{ $equipo->marca }}</td>
						<td>{{ $equipo->modelo }}</td>
						<td>{{ $equipo->descripcion }}</td>
						<td>{{ $estado }}</td>
						<td>{{ $equipo->usuario->area->nombre }}</td>
						<td>{{ $equipo->usuario->nombres }} {{ $equipo->usuario->apellidos }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="clearfix"></div>
		<div class="btn-group-xs btn-group-horizontal pull-right">
			<a title="click para modificar" class="btn btn-primary" href="{{URL::route('mantenimiento/edit',array('id'=>$soporte->id))}}" role="button">
			<i class="fa fa-pencil-square-o"></i> Actualizar</a>
			<a class="btn btn-default btn-danger" href="#" role="button">Eliminar</a>
		</div>
	</div>{{-- PANEL BODY --}}
</div>{{-- PANEL --}}
@stop