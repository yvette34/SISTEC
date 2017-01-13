@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<strong>SERVICIOS</strong>
		<a class="btn btn-success btn-sm pull-right" href="{{URL::route('servicios/create')}}" role="button">
			<i class="fa fa-check-square-o"></i> <strong>SOLICITAR</strong>
		</a>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-condensed small" id="tblServicios">
				<thead>
					<tr>
						<th>Folio</th>
						<th>Area del equipo</th>
						<th>Reporto</th>
						<th>Usuario del equipo</th>
						<!--	<th>Solución</th>	-->
						<th>Fecha de solicitud</th>
						<th>Descripción</th>
						<!--	<th>Fecha de solución</th>	-->
						<th>Estado</th>
						<th>Operaciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($servicios as $servicio)
					<?php
					//	Este es el usuario que reporto
					$usuario = Servicios::find($servicio->id)->usuario;
					//	Usuario común del equipo
					$usuario_comun = User::find($servicio->usuario_equipo);
							//	Asiganmos estilo y nombre al estado
						$estado = array('estilo'=>'','etiqueta'=>'','fila'=>'');
						switch ($servicio->estado) {
							case '0':
								$estado['estilo'] = "btn-xs btn-block btn-danger";
								$estado['etiqueta'] = "Por Atender";
								$estado['fila'] = "bg-danger";
								break;
							case '1':
								$estado['estilo'] = "btn-xs btn-block btn-info";
								$estado['etiqueta'] = "En Reparación";
								$estado['fila'] = "bg-info";
								break;
							case '3':
								$estado['estilo'] = "btn-xs btn-block btn-success";
								$estado['etiqueta'] = "Terminado";
								$estado['fila'] = "bg-success";
								break;
							case '2':
								$estado['estilo'] = "btn-xs btn-block btn-warning";
								$estado['etiqueta'] = "En Espera de Refacciones";
								$estado['fila'] = "bg-warning";
								break;
						}
					?>
					<tr class="{{ $estado['fila'] }}">
						<td>{{ $servicio->folio }}</td>
						{{-- Aqui ponemos el area del usuario comun del equipo --}}
						<td>{{ Areas::find(User::find($servicio->usuario_equipo)->id_area)->nombre }}</td>
						<td>{{ $usuario->nombres." ".$usuario->apellidos }}</td>
						<td>{{ $usuario_comun->nombres." ".$usuario_comun->apellidos }}</td>
						<!--	<td>#REFERENCIA#</td>	-->
						<td>{{ $servicio->fecha_reporte }}</td>
						<td>{{ $servicio->falla_reportada }}</td>
						<!--	<td>#REFERENCIA#</td>	-->
						@if(Session::get('rol')=='0')
						<td><a title="click para actualizar" class="btn {{ $estado['estilo'] }}" href="{{URL::route('servicios/seguimiento',array('id'=>$servicio->id))}}"><i class="fa fa-pencil-square-o"></i> {{ $estado['etiqueta'] }}</a></td>
						@else
						<td><a title="" class="btn {{ $estado['estilo'] }}" href="#" disabled="disabled"><i class="fa fa-pencil-square-o"></i> {{ $estado['etiqueta'] }}</a></td>
						@endif
						<td>
							<div class="btn-group-xs btn-group-horizontal">
								{{-- Si el usuario es General y reporto el servicio, entonces tendra libres las opciones --}}
								@if((Session::get('rol') == 1 || Session::get('rol')==2) && Session::get('usuario') == $usuario->id)
									<a title="click para ver el detalle" class="btn btn-info" href="{{URL::route('servicios/show',array('id'=>$servicio->id))}}" role="button">
									<i class="fa fa-eye"></i> Ver</a>
									<a title="click para modificar" class="btn btn-primary" href="{{URL::route('servicios/edit',array('id'=>$servicio->id))}}" role="button">
									<i class="fa fa-pencil-square-o"></i> Actualizar</a>
									@if($servicio->estado != 0)
									<a class="btn btn-default btn-danger" href="{{URL::route('servicios/reporteServicios',array('id'=>$servicio->id))}}" role="button"><i class="fa fa-file-pdf-o" target="__blanck"></i> Imprimir a PDF</a>
									@endif
								{{-- Si el usuario es administrador tendra libres todas las opciones --}}
								@elseif(Session::get('rol')==0)
									<a title="click para ver el detalle" class="btn btn-info" href="{{URL::route('servicios/show',array('id'=>$servicio->id))}}" role="button">
									<i class="fa fa-eye"></i> Ver</a>
									<a title="click para modificar" class="btn btn-primary" href="{{URL::route('servicios/edit',array('id'=>$servicio->id))}}" role="button">
									<i class="fa fa-pencil-square-o"></i> Actualizar</a>
									@if($servicio->estado != 0)
									<a class="btn btn-default btn-danger" href="{{URL::route('servicios/reporteServicios',array('id'=>$servicio->id))}}" role="button" target="__blanck"> <i class="fa fa-file-pdf-o"></i> Imprimir a PDF</a>	
									@endif							
								{{-- Si el usuario es general y no reporto el servicio no tendra acceso a las opciones --}}
								@else
									<a title="click para ver el detalle" class="btn btn-info" href="#" role="button" disabled>
									<i class="fa fa-eye"></i> Ver</a>
									<a title="click para modificar" class="btn btn-primary" href="#" role="button" disabled>
									<i class="fa fa-pencil-square-o"></i> Actualizar</a>
									@if($servicio->estado != 0)
									<a class="btn btn-default btn-danger hidden" href="#" role="button" disabled target="__blanck"><i class="fa fa-file-pdf-o"></i> Imprimir a PDF</a>
									@endif
								@endif
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>	{{-- Div table --}}
	</div>{{-- Panel Body --}}
</div>{{-- Panel --}}
@stop
@section('javascript')
@parent
<script type="text/javascript">
	$('#tblServicios').DataTable();
</script>
@stop