@extends("template")
@section("content")
<div class="panel panel-info">
	  <div class="panel-heading">
			<h3 class="panel-title">ASIGNACIÓN DE EQUIPO</h3>
	  </div>
	  <div class="panel-body">
			{{ Form::open(["route"=>"equipos/asignacion","class"=>"form"]) }}
			<p class="text-info">Seleccione un usuario y asignele los equipos. </p>
			<div class="panel panel-default col-md-4">
				  <div class="panel-heading">
						<h3 class="panel-title">Usuarios del Area</h3>
				  </div>
				  <div class="panel-body">
				  	{{-- Si el usuario es ADMINISTRADOR --}}
				  	@if(Session::has('rol') && Session::get('rol')==0)
						<?php
							$areas = Areas::all()->lists("nombre","id");
							$areas[""] = "Seleccione un opción";
						?>
						<div class="form-group">
							{{ Form::select("area",$areas,"",["class"=>"form-control","id"=>"areas_asignacion"]) }}
							<p class="text-primary">Area</p>
						</div>
						<div class="form-group">
							{{ Form::select("id_usuario",array(),"",["class"=>"form-control","id"=>"usuario_asignacion"]) }}
							<p class="text-primary">Usuario común del equipo(s).</p>
						</div>
					@else
					{{-- Si el usuario es GENERAL/COMÚN --}}
					<div class="form-group">
						{{ Form::text("id_area",User::find(Session::get('usuario'))->area->nombre,["class"=>"form-control","readonly"]) }}
						<p class="text-primary">Area</p>
					</div>
					<div class="form-group">
						<?php
							//	Consultamos los usuarios del area, El area se define por el usuario que incio session
							$reg_users = User::where("id_area","=",User::find(Session::get('usuario'))->area->id)->get()->toArray();
							//	El arreglo usuarios contendra el resultado a mostrar ene l combo
							$usuarios = array();
							foreach ($reg_users as $usuario) {
								$usuarios[$usuario['id']] = $usuario['nombres'] . " " . $usuario['apellidos'];
							}
						?>
						{{ Form::select("id_usuario",$usuarios,"",["class"=>"form-control"]) }}
						<p class="text-primary">Usuario común del equipo(s).</p>
					</div>
					@endif
				  </div>
			</div>
			<div class="panel panel-default col-md-8">
				  <div class="panel-heading">
						<h3 class="panel-title">Equipos</h3>
				  </div>
				  <div class="panel-body">
				  	{{-- USUARIO ADMINISTRADOR --}}

				  	{{-- USUARIO COMÚN --}}
						{{-- Necesitamos consultar los equipos que el usario(logeado) tiene registrados --}}
						<?php $equipos = Equipo::where("id_usuario","=",Session::get('usuario'))->get()->toArray(); ?>
						{{-- Mostramos los equipos en una tabla --}}
						<div class="clearfix"></div>
						<div class="table-responsive">
							<table class="table table-hover table-bordered table-condensed small" id="tbl_filas_equipo">
								<caption><span class="text-warning"><strong>Nota: </strong> Seleccione los equipos a asignar.</caption>
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
									@foreach($equipos as $equipo)
									<?php 
										$caracteristicas = Equipo::find($equipo['id'])->caracteristicas;
									?>
									<tr>
										<td>{{ $equipo['no_inventario'] }}</td>
										<td>{{ $equipo['marca'] }}</td>
										<td>{{ $equipo['modelo'] }}</td>
										<td>{{ $caracteristicas[0]->so }}</td>
										<td>{{ $caracteristicas[0]->ram }} GB</td>
										<td>{{ $caracteristicas[0]->disco_duro }} GB</td>
										<td><input name="equipoAsignar[]" type="checkbox" value="{{ $equipo['id'] }}"> Asignar.</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
				  </div>
			</div>
			<div class="form-group col-md-2 col-md-offset-10">
				<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-floppy-o"></i> GUARDAR</button>
			</div>
			{{ Form::close() }}
	  </div>
</div>
@stop