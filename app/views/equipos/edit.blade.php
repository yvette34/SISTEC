@extends("template")
@section("content")
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">ACTUALIZACIÓN DE EQUIPO</h3>
	</div>
	<div class="panel-body">
		{{ Form::open(["route"=>["equipos/edit",$equipo->id]]) }}

		@if(Session::get('rol')==0 || Session::get('rol')==2)
			{{-- AREA --}}
			<div class="form-group col-md-6">
				<?php
					$areas = Areas::all()->lists("nombre","id");
					$areas[""] = "Seleccione un opción";
					$usuarios = Areas::find($equipo->usuario->area->id)->usuarios;
				?>
				{{ Form::select("id_area",$areas,$equipo->usuario->area->id,["class"=>"form-control","id"=>"areas_equipos_edit","required"=>"true"]) }}
				<p class="text-primary">Area o coordinación.</p>
			</div>
			{{-- USUARIO --}}
			<div class="form-group col-md-6">
				<select id="usuario_equipos_edit" name="id_usuario" class="form-control" required="true">
					<option value="">Seleccione el usuario</option>
					@foreach($usuarios as $user)
						@if($user['id'] == $equipo->usuario->id)
							<option value="{{ $user['id'] }}" selected>{{ $user['nombres'] }} {{ $user['apellidos'] }}</option>
						@else
							<option value="{{ $user['id'] }}">{{ $user['nombres'] }} {{ $user['apellidos'] }}</option>
						@endif
					@endforeach
				</select>
				<p class="text-primary">Usuario común del equipo.</p>
			</div>
			<div class="clearfix"></div>
		@else
			{{ Form::text("id_usuario",$equipo->usuario->id,["class"=>"","hidden"]) }}
			<div class="col-md-12 form-group">
				{{ Form::text("usuario",$equipo->usuario->nombres." ".$equipo->usuario->apellidos,["class"=>"col-md-4 form-control","readonly","placeholder"=>"Usuario del equipo"]) }}
				<p class="text-primary">Usuario común del equipo</p>
			</div>
		@endif
		<div class="col-md-3 form-group">
			@if(isset($equipo->no_inventario))
			{{ Form::text("no_inventario",$equipo->no_inventario,["class"=>"form-control","placeholder"=>"NO. INVENTARIO"]) }}
			@else
			{{ Form::text("no_inventario","",["class"=>"form-control","placeholder"=>"NO. INVENTARIO"]) }}
			@endif
			<p class="text-primary">No. de inventario</p>
		</div>
		<div class="col-md-3 form-group">
			@if(isset($equipo->marca))
			{{ Form::text("marca",$equipo->marca,["class"=>"form-control","placeholder"=>"MARCA"]) }}
			@else
			{{ Form::text("marca","",["class"=>"form-control","placeholder"=>"MARCA"]) }}
			@endif
			<p class="text-primary">Marca</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo->modelo))
			{{ Form::text("modelo",$equipo->modelo,["class"=>"form-control","placeholder"=>"MODELO"]) }}
			@else
			{{ Form::text("modelo","",["class"=>"form-control","placeholder"=>"MODELO"]) }}
			@endif
			<p class="text-primary">Modelo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo->estado))
			{{ Form::select("estado",[""=>"Seleccione el estado del equipo","0"=>"Requiere Sustitución","1"=>"Regular","2"=>"Bueno","3"=>"Excelente"],$equipo->estado,["class"=>"form-control"]) }}
			@else
			{{ Form::select("estado",[""=>"Seleccione el estado del equipo","0"=>"Requiere Sustitución","1"=>"Regular","2"=>"Bueno","3"=>"Excelente"],"",["class"=>"form-control"]) }}
			@endif
			<p class="text-primary">Estado de equipo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo['caracteristicas'][0]->usuario_pc))
			{{ Form::text("usuario_pc",$equipo['caracteristicas'][0]->usuario_pc,["class"=>"form-control","placeholder"=>"USUARIO DEL PC PARA ACCESO"]) }}
			@else
			{{ Form::text("usuario_pc","",["class"=>"form-control","placeholder"=>"USUARIO DEL PC PARA ACCESO"]) }}
			@endif
			<p class="text-primary">Usuario común del equipo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo['caracteristicas'][0]->grupo_trabajo))
			{{ Form::text("grupo_trabajo",$equipo['caracteristicas'][0]->grupo_trabajo,["class"=>"form-control","placeholder"=>"GRUPO DE TRABAJO"]) }}
			@else
			{{ Form::text("grupo_trabajo","",["class"=>"form-control","placeholder"=>"GRUPO DE TRABAJO"]) }}
			@endif
			<p class="text-primary">Grupo de trabajo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo['caracteristicas'][0]->so))
			{{ Form::text("so",$equipo['caracteristicas'][0]->so,["class"=>"form-control","placeholder"=>"SISTEMA OPERATIVO"]) }}
			@else
			{{ Form::text("so","",["class"=>"form-control","placeholder"=>"SISTEMA OPERATIVO"]) }}
			@endif
			<p class="text-primary">Sistema Operativo</p>
		</div>
		<div class="clearfix"></div>
		<div class="form-group col-md-2">
			@if(isset($equipo['caracteristicas'][0]->ram))
			{{ Form::text("ram",$equipo['caracteristicas'][0]->ram,["class"=>"form-control","placeholder"=>"RAM - GB"]) }}
			@else
			{{ Form::text("ram","",["class"=>"form-control","placeholder"=>"RAM - GB"]) }}
			@endif
			<p class="text-primary">RAM en GB</p>
		</div>
		<div class="form-group col-md-2">
			@if(isset($equipo['caracteristicas'][0]->disco_duro))
			{{ Form::text("disco_duro",$equipo['caracteristicas'][0]->disco_duro,["class"=>"form-control","placeholder"=>"DISCO DURO - GB"]) }}
			@else
			{{ Form::text("disco_duro","",["class"=>"form-control","placeholder"=>"DISCO DURO - GB"]) }}
			@endif
			<p class="text-primary">Disco Duro en GB</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo['caracteristicas'][0]->ip))
			{{ Form::text("ip",$equipo['caracteristicas'][0]->ip,["class"=>"form-control","placeholder"=>"IP: 0.0.0.0"]) }}
			@else
			{{ Form::text("ip","",["class"=>"form-control","placeholder"=>"IP: 0.0.0.0"]) }}
			@endif
			<p class="text-primary">Dirección IP</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($equipo['caracteristicas'][0]->mac))
			{{ Form::text("mac",$equipo['caracteristicas'][0]->mac,["class"=>"form-control","placeholder"=>"MAC: 00-00-00-00-00-00"]) }}
			@else
			{{ Form::text("mac","",["class"=>"form-control","placeholder"=>"MAC: 00-00-00-00-00-00"]) }}
			@endif
			<p class="text-primary">Dirección MAC</p>
		</div>
		<div class="form-group col-md-2">
			@if(isset($equipo['caracteristicas'][0]->nodo))
			{{ Form::text("nodo",$equipo['caracteristicas'][0]->nodo,["class"=>"form-control","placeholder"=>"NODO"]) }}
			@else
			{{ Form::text("nodo","",["class"=>"form-control","placeholder"=>"NODO"]) }}
			@endif
			<p class="text-primary">Nodo</p>
		</div>
		<div class="form-group col-md-12">
			@if(isset($equipo->descripcion))
			{{ Form::textarea("descripcion",$equipo->descripcion,["class"=>"form-control","placeholder"=>"DESCRIPCIÓN","rows"=>"2"]) }}
			@else
			{{ Form::textarea("descripcion","",["class"=>"form-control","placeholder"=>"DESCRIPCIÓN","rows"=>"2"]) }}
			@endif
			<p class="text-primary">Descripción general del equipo</p>
		</div>
		<div class="form-group col-md-2 col-md-offset-10">
			<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-refresh"></i> ACTUALIZAR REGISTRO</button>
		</div>
		{{ Form::close() }}
	</div>{{-- PANEL BODY --}}
</div>{{-- PANEL --}}
@stop