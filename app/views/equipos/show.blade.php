@extends("template")
@section("content")
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">DETALLE DEL EQUIPO</h3>
	</div>
	<div class="panel-body">
		<div class="col-md-4">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="#" class="thumbnail">
					{{ HTML::image('img/equipo.jpg','',['class'=>'']) }}
				</a>
			</div>
		</div>
		<div class="col-md-8">
			<div class="col-md-6">
				<p class="text-primary">Equipo.</p>
				<?php
				$usuario = User::find($equipo->id_usuario);
				$area = Areas::find($usuario->id_area);
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
				* NO. INVENTARIO: <strong>{{ $equipo->no_inventario }}</strong><br>
				* MARCA: <strong>{{ $equipo->marca }}</strong><br>
				* MODELO: <strong>{{ $equipo->modelo }}</strong><br>
				* DESCRIPCIÓN: <br><strong>{{ $equipo->descripcion }}</strong><br>
				* ESTADO: <strong>{{ $estado }}</strong><br>
				* AREA: <strong>{{ $area->nombre }}</strong><br>
				* USUARIO: <strong>{{ $usuario->nombres }} {{ $usuario->apellidos }}</strong><br>
			</div>
			<div class="col-md-6">
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
			<div class="pull-right">
				<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="{{ URL::route('equipos/edit',['id'=>$equipo->id]) }}"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
				<a title="click para eliminar" class="btn btn-default btn-danger btn-xs"  href="#"><i class="fa fa-trash"></i> Eliminar</a>
			</div>
		</div>
	</div>
</div>
@stop