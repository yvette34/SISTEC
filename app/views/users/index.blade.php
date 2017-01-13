@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<strong>USUARIOS</strong>
		<a class="btn btn-success btn-sm pull-right" href="{{ URL::route('users/register') }}" role="button">
			<i class="fa fa-check-square-o"></i> <strong>NUEVO USUARIO</strong>
		</a>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-condensed small" id="tblUsuarios">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>Area</th>
						<th>Cargo</th>
						<th>Rol</th>
						<th>Email</th>
						<th>Usuario</th>
						<th>Operaciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
					<?php
					$rol = "";
					switch ($user->rol) {
						case '0':
							$rol = "Administrador";
							break;
						case '1':
							$rol = "General";
							break;
						case '2':
							$rol = "Encargado de area";
							break;
					}
					?>
					<tr>
						<td>{{ $user->nombres }}</td>
						<td>{{ $user->apellidos }}</td>
						<td>{{ Areas::find($user->id_area)->nombre }}</td>
						<td>{{ $user->cargo }}</td>
						<td>{{ $rol }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->usuario }}</td>
						<td>
							<a title="click para ver el detalle" class="btn btn-default btn-info btn-xs hidden" href="{{ URL::route('users/show',['id'=>$user->id]) }}"><i class="fa fa-eye"></i> Ver</a>
							@if(Session::get('usuario') == $user->id && Session::get('rol')=="1")
								<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="{{ URL::route('users/edit',['id'=>$user->id]) }}"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
								<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#"><i class="fa fa-trash"></i> Eliminar</a>
							@elseif(Session::get('rol')=="0")
								<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="{{ URL::route('users/edit',['id'=>$user->id]) }}"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
								<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#"><i class="fa fa-trash"></i> Eliminar</a>
							@else
								<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="#" disabled><i class="fa fa-pencil-square-o"></i> Actualizar</a>
								<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#"><i class="fa fa-trash"></i> Eliminar</a>

							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>{{-- TABLE RESPONSIVE --}}
	</div>{{-- PANEL BODY --}}
</div>{{-- PANEL --}}
@stop
@section('javascript')
@parent
<script type="text/javascript">
	$('#tblUsuarios').DataTable();
</script>
@stop