@extends('template')
@section('content')
<div class="panel panel-info">
	  <div class="panel-heading">
			<strong>EQUIPOS</strong>
			<a class="btn btn-success btn-sm pull-right" href="{{ URL::route('equipos/create') }}" role="button"><i class="fa fa-check-square-o"></i> <strong>ASIGNAR EQUIPO</strong></a>
	  </div>
	  <div class="panel-body">
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-condensed small" id="tblEquipos">
				<thead>
					<tr>
						<th>No.Inventario</th>
						<th>Marca</th>
						<th>Modelo</th>
						<th>Descripción</th>
						<th>Estado</th>
						<th>Area</th>
						<th>Usuario responsable</th>
						<th>Operaciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($equipos as $equipo)
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
					//	Debemos de tomar el usuario responsable del equipo
					$usuario = User::find($equipo->id_usuario);
					$area = Areas::find($usuario->id_area);
					?>
						<tr>
							<td>{{ $equipo->no_inventario }}</td>
							<td>{{ $equipo->marca }}</td>
							<td>{{ $equipo->modelo }}</td>
							<td>{{ $equipo->descripcion }}</td>
							<td>{{ $estado }}</td>
							<td>{{ $area->nombre }}</td>
							<td>{{ $usuario->nombres}} {{ $usuario->apellidos }}</td>
							<td>
								{{-- Si el usuario es general y el usuario logeado corresponde al usuario del equipo--}}
								@if(Session::get('rol')==1 && Session::get('usuario')==$usuario->id)
									<a title="click para ver el detalle" class="btn btn-default btn-info btn-xs" href="{{ URL::route('equipos/show',['id'=>$equipo->id]) }}"><i class="fa fa-eye"></i> Ver</a>
									<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="{{ URL::route('equipos/edit',['id'=>$equipo->id]) }}"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
									<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#"><i class="fa fa-trash"></i> Eliminar</a>
								{{-- De lo contrario si es administrador o encargado de area --}}
								@elseif(Session::get('rol')==0 || Session::get('rol')==2)
									<a title="click para ver el detalle" class="btn btn-default btn-info btn-xs" href="{{ URL::route('equipos/show',['id'=>$equipo->id]) }}"><i class="fa fa-eye"></i> Ver</a>
									<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="{{ URL::route('equipos/edit',['id'=>$equipo->id]) }}"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
									<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#"><i class="fa fa-trash"></i> Eliminar</a>
								{{-- De lo contrario si el usuario es general pero no conincide el usuario del equipo con el usuario logeado--}}
								@else
									<a title="click para ver el detalle" class="btn btn-default btn-info btn-xs" href="#" disabled><i class="fa fa-eye"></i> Ver</a>
									<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="#" disabled><i class="fa fa-pencil-square-o"></i> Actualizar</a>
									<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#" disabled><i class="fa fa-trash"></i> Eliminar</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	  </div>
</div>
@stop
@section('javascript')
@parent
<script type="text/javascript">
	$('#tblEquipos').DataTable();
</script>
@stop