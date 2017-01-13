@extends('template')
@section('content')
	<div class="panel panel-info">
		  <div class="panel-heading">
				<h3 class="panel-title">ACTUALIZACIÓN DE MANTENIMIENTO.<span class="small">Complete los campos.</span></h3>
		  </div>
		  <div class="panel-body">
		  	<?php $estados = array("1"=>"Excelente","2"=>"Bueno","3"=>"Regular","4"=>"Requiere Sustitución"); ?>
			{{ Form::open(array("route"=>array("mantenimiento/update",$soporte->id),"class"=>"form")) }}
			<div class="form-group col-md-2">
				{{ Form::number("folio",$soporte->folio,array("class"=>"form-control","readonly")) }}
				<p class="text-primary">Folio</p>
			</div>
			<div class="form-group col-md-2">
				{{ Form::input("date","fecha",$soporte->fecha,array("class"=>"form-control")) }}
				<p class="text-primary">Fecha de registro</p>
			</div>
			<div class="form-group col-md-2">
				{{ Form::select("estado_equipo",$estados,$soporte->estado_equipo,array("class"=>"form-control")) }}
				<p class="text-primary">Estado de equipo</p>
			</div>
			<div class="form-group col-md-2">
				{{ Form::select("tipo",array("0"=>"preventivo","1"=>"correctivo"),$soporte->tipo,array("class"=>"form-control")) }}
				<p class="text-primary">Tipo de mantenimiento</p>
			</div>
			<div class="form-group col-md-4">
				{{ Form::text("resguardo",$soporte->resguardo_a,array("class"=>"form-control")) }}
				<p class="text-primary">Resguardo a</p>
			</div>
			<div class="form-group col-md-3">
				{{ Form::select('area',$areas,$soporte->usuario->area->id,array("class"=>"form-control","id"=>"areas_mantenimiento_update")) }}
				<p class="text-primary">Area</p>
			</div>
			<div class="form-group col-md-3">
				<select id="usuario_mantenimiento_update" name="id_usuario" class="form-control">
					<option value="">Seleccione el usuario común</option>
					@foreach($usuarios as $user)
						@if($user['id'] == $usuario->id)
							<option value="{{ $user['id'] }}" selected>{{ $user['nombres'] }} {{ $user['apellidos'] }}</option>
						@else
							<option value="{{ $user['id'] }}">{{ $user['nombres'] }} {{ $user['apellidos'] }}</option>
						@endif
					@endforeach
				</select>
				<p class="text-primary">Usuario</p>
			</div>
			<div class="form-group col-md-6">
				{{ Form::textarea("observaciones",$soporte->observaciones,array("class"=>"form-control","rows"=>"2")) }}
				<p class="text-primary">Observaciones</p>
			</div>
			<div class="clearfix"></div>
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-condensed small">
					<caption>Datos del equipo. <span class="text-warning"><strong>Nota!</strong> Dependiendo el usuario seleccionado se cargaran los equipos a su resguardo. <strong>Marca</strong> el equipo a revisar.</span></caption>
					<thead>
						<tr>
							<th>No.Inventario</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Sistema Operativo</th>
							<th>Memoria Ram</th>
							<th>Disco Duro</th>
							<th>Operaciones</th>
						</tr>
					</thead>
					<tbody id="filas_equipo">
						@foreach($equipos_usuario as $equipo)
							<tr>
								<td>{{ $equipo->no_inventario }}</td>
								<td>{{ $equipo->marca }}</td>
								<td>{{ $equipo->modelo }}</td>
								<td>{{ $equipo->caracteristicas[0]->so }}</td>
								<td>{{ $equipo->caracteristicas[0]->ram }}</td>
								<td>{{ $equipo->caracteristicas[0]->disco_duro }}</td>
								<td>
									@foreach($equipos_soporte as $eq)
										@if($eq['id_equipo'] == $equipo->id)
											<?php $checked = "checked" ?>
											<?php break;?>
										@else
											<?php $checked = "" ?>
										@endif
									@endforeach
									<input name="equipo[]" type="checkbox" value="{{ $equipo->id }}" {{ $checked }}> Revisión.
								</td>
							</tr>
							@endforeach
					</tbody>
				</table>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-4 thumbnail">
				{{-- Accesorios --}}
				<p class="text-primary">ACCESORIOS</p>
				<div class="form-group col-md-6">
					{{ Form::text("teclado",$accesorios[0]->teclado,array("class"=>"form-control")) }}
					<p class="text-primary">Teclado</p>
				</div>
				<div class="form-group col-md-6">
					{{ Form::text("mouse",$accesorios[0]->mouse,array("class"=>"form-control")) }}
					<p class="text-primary">Mouse</p>
				</div>
				<div class="form-group col-md-6">
					{{ Form::text("regulador",$accesorios[0]->regulador,array("class"=>"form-control")) }}
					<p class="text-primary">Regulador</p>
				</div>
				<div class="form-group col-md-6">
					{{ Form::text("otros",$accesorios[0]->otros,array("class"=>"form-control")) }}
					<p class="text-primary">Otros</p>
				</div>
			</div>
			<div class="col-md-4 thumbnail">
				{{-- Software del equipo --}}
				<p class="text-primary">SOFTWARE DEL EQUIPO</p>
				{{-- Debemos de crear una arreglo con los elementos de los check --}}
		  		<?php $checks = array("Antivirus","Office","Adobe Reader","CCleaner","Firefox","Chrome","Java","Flash Player","Winzip-Rar","Nero","Otros"); ?>
				@foreach($checks as $check)
					@if($check == "Otros")
						@foreach ($software as $sf)
							@if(in_array($sf, $checks))	{{-- Si las etiqeutas coinciden se continua con el for --}}
								<?php continue; ?>
							@else
								<div class="col-md-12">Otros: {{ Form::text('software[]',$sf,array("class"=>"")); }}</div>
							@endif
						@endforeach
					@else
						{{-- Ponemos el check que corresponda a la etiqueta --}}
						@if(in_array($check,$software))	{{-- Si las etiqeutas coinciden se marca el check --}}
							<div class="col-md-6"> {{ Form::checkbox('software[]', $check, true); }} {{ $check }}</div>
						@else
							<div class="col-md-6"> {{ Form::checkbox('software[]', $check); }} {{ $check }}</div>
						@endif
					@endif
				@endforeach
			</div>
			<div class="col-md-4 thumbnail">
				{{-- Caracteristicas del mantenimiento --}}
				<p class="text-primary">CARACTERISTICAS DEL MANTENIMIENTO</p>
				<?php $checks = array("Limpieza Externa","Limpieza Interna","Limpieza de archivos temporales","Limpieza de registro","Actualizaciones de antivirus","Analisis de virus","Desfragmentación de disco","Check Disk","Otros"); ?>
				@foreach($checks as $check)
					@if($check == "Otros")
						<?php $otros = ""; ?>
						@foreach ($caracteristicas as $cr)
							@if(in_array($cr, $checks))	{{-- Si las etiqeutas coinciden se continua con el for --}}
								<?php continue; ?>
							@else
								<?php $otros = $cr; ?>
							@endif
						@endforeach
						<div class="col-md-12">Otros: {{ Form::text('caracteristicas[]',$otros,array("class"=>"")); }}</div>
					@else
						{{-- Ponemos el check que corresponda a la etiqueta --}}
						@if(in_array($check,$caracteristicas))	{{-- Si las etiqeutas coinciden se marca el check --}}
							<div class="col-md-6"> {{ Form::checkbox('caracteristicas[]', $check,true); }} {{ $check }}</div>
						@else
							<div class="col-md-6"> {{ Form::checkbox('caracteristicas[]', $check); }} {{ $check }}</div>
						@endif
					@endif
				@endforeach
			</div>
			<div class="col-md-3 col-md-offset-9">
				<button type="submit" class="btn btn-info btn-lg pull-right">
				<i class="fa fa-refresh"></i> ACTUALIZAR DATOS
				</button>
			</div>
			{{ Form::close() }}
		  </div>{{-- PANEL BODY --}}
	</div>{{-- PANEL --}}
@stop