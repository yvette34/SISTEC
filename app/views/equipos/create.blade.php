@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">ASIGNACIÓN DE EQUIPO</h3>
	</div>
	<div class="panel-body">
		{{ Form::open(["route"=>"equipos/create","class"=>"form"]) }}
		@if(Session::get('rol')==0 || Session::get('rol') == 2)
			{{-- AREA --}}
			<div class="form-group col-md-6">
				{{-- si el usuario es administrador o encargado de area podra seleccionar el area manualmente de lo contrario si el usuario es general  solo podra visualizar su area --}}
				@if(Session::get('rol')==0 || Session::get('rol') == 2)
					<?php
						$areas = Areas::all()->lists("nombre","id");
						$areas[""] = "Seleccione un opción";
					?>
					{{ Form::select("id_area",$areas,"",["class"=>"form-control","id"=>"areas_equipos_create","required"=>"true"]) }}
				@else
					<input type="text" class="form-control" value="{{ $area->nombre }}" readonly placeholder="AREA O COORDINACIÓN">
				@endif
			<p class="text-primary">Area o coordinación.</p>
			</div>
			{{-- USUARIO --}}
			<div class="form-group col-md-6">
				@if(Session::get('rol')==0 || Session::get('rol') == 2)
						{{ Form::select("id_usuario",array(""=>"Seleccione el usuario"),"",["class"=>"form-control","id"=>"usuario_equipos_create","required"=>"true"]) }}
				@else
						<input name="id_usuario" type="text" class="form-control" id="" value="{{ Session::get('usuario_nombre') }}" readonly placeholder="USUARIO QUE REPORTA">|
				@endif
				<p class="text-primary">Usuario común del equipo.</p>
			</div>
			<div class="clearfix"></div>
		@else
			{{ Form::text("id_usuario",Session::get('usuario'),["class"=>"","hidden"]) }}
			<div class="col-md-12 form-group">
				{{ Form::text("usuario",Session::get('usuario_nombre'),["class"=>"col-md-4 form-control","readonly","placeholder"=>"Usuario del equipo"]) }}
				<p class="text-primary">Usuario común del equipo</p>
			</div>
		@endif
		<div class="col-md-3 form-group">
			@if(isset($no_inventario))
			{{ Form::text("no_inventario",$no_inventario,["class"=>"form-control","placeholder"=>"NO. INVENTARIO"]) }}
			@else
			{{ Form::text("no_inventario","",["class"=>"form-control","placeholder"=>"NO. INVENTARIO"]) }}
			@endif
			<p class="text-primary">No. de inventario</p>
		</div>
		<div class="col-md-3 form-group">
			@if(isset($marca))
			{{ Form::text("marca",$marca,["class"=>"form-control","placeholder"=>"MARCA"]) }}
			@else
			{{ Form::text("marca","",["class"=>"form-control","placeholder"=>"MARCA"]) }}
			@endif
			<p class="text-primary">Marca</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($modelo))
			{{ Form::text("modelo",$modelo,["class"=>"form-control","placeholder"=>"MODELO"]) }}
			@else
			{{ Form::text("modelo","",["class"=>"form-control","placeholder"=>"MODELO"]) }}
			@endif
			<p class="text-primary">Modelo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($estado))
			{{ Form::select("estado",[""=>"Seleccione el estado del equipo","0"=>"Requiere Sustitución","1"=>"Regular","2"=>"Bueno","3"=>"Excelente"],$estado,["class"=>"form-control"]) }}
			@else
			{{ Form::select("estado",[""=>"Seleccione el estado del equipo","0"=>"Requiere Sustitución","1"=>"Regular","2"=>"Bueno","3"=>"Excelente"],"",["class"=>"form-control"]) }}
			@endif
			<p class="text-primary">Estado de equipo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($usuario_pc))
			{{ Form::text("usuario_pc",$usuario_pc,["class"=>"form-control","placeholder"=>"USUARIO DEL PC PARA ACCESO"]) }}
			@else
			{{ Form::text("usuario_pc","",["class"=>"form-control","placeholder"=>"USUARIO DEL PC PARA ACCESO"]) }}
			@endif
			<p class="text-primary">Usuario común del equipo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($grupo_trabajo))
			{{ Form::text("grupo_trabajo",$grupo_trabajo,["class"=>"form-control","placeholder"=>"GRUPO DE TRABAJO"]) }}
			@else
			{{ Form::text("grupo_trabajo","",["class"=>"form-control","placeholder"=>"GRUPO DE TRABAJO"]) }}
			@endif
			<p class="text-primary">Grupo de trabajo</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($so))
			{{ Form::text("so",$so,["class"=>"form-control","placeholder"=>"SISTEMA OPERATIVO"]) }}
			@else
			{{ Form::text("so","",["class"=>"form-control","placeholder"=>"SISTEMA OPERATIVO"]) }}
			@endif
			<p class="text-primary">Sistema Operativo</p>
		</div>
		<div class="clearfix"></div>
		<div class="form-group col-md-2">
			@if(isset($ram))
			{{ Form::text("ram",$ram,["class"=>"form-control","placeholder"=>"RAM - GB"]) }}
			@else
			{{ Form::text("ram","",["class"=>"form-control","placeholder"=>"RAM - GB"]) }}
			@endif
			<p class="text-primary">RAM en GB</p>
		</div>
		<div class="form-group col-md-2">
			@if(isset($disco_duro))
			{{ Form::text("disco_duro",$disco_duro,["class"=>"form-control","placeholder"=>"DISCO DURO - GB"]) }}
			@else
			{{ Form::text("disco_duro","",["class"=>"form-control","placeholder"=>"DISCO DURO - GB"]) }}
			@endif
			<p class="text-primary">Disco Duro en GB</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($ip))
			{{ Form::text("ip",$ip,["class"=>"form-control","placeholder"=>"IP: 0.0.0.0"]) }}
			@else
			{{ Form::text("ip","",["class"=>"form-control","placeholder"=>"IP: 0.0.0.0"]) }}
			@endif
			<p class="text-primary">Dirección IP</p>
		</div>
		<div class="form-group col-md-3">
			@if(isset($mac))
			{{ Form::text("mac",$mac,["class"=>"form-control","placeholder"=>"MAC: 00-00-00-00-00-00"]) }}
			@else
			{{ Form::text("mac","",["class"=>"form-control","placeholder"=>"MAC: 00-00-00-00-00-00"]) }}
			@endif
			<p class="text-primary">Dirección MAC</p>
		</div>
		<div class="form-group col-md-2">
			@if(isset($nodo))
			{{ Form::text("nodo",$nodo,["class"=>"form-control","placeholder"=>"NODO"]) }}
			@else
			{{ Form::text("nodo","",["class"=>"form-control","placeholder"=>"NODO"]) }}
			@endif
			<p class="text-primary">Nodo</p>
		</div>
		<div class="form-group col-md-12">
			@if(isset($descripcion))
			{{ Form::textarea("descripcion",$descripcion,["class"=>"form-control","placeholder"=>"DESCRIPCIÓN","rows"=>"2"]) }}
			@else
			{{ Form::textarea("descripcion","",["class"=>"form-control","placeholder"=>"DESCRIPCIÓN","rows"=>"2"]) }}
			@endif
			<p class="text-primary">Descripción general del equipo</p>
		</div>
		<div class="form-group col-md-2 col-md-offset-10">
			<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-floppy-o"></i> GUARDAR REGISTRO</button>
		</div>
		{{ Form::close() }}
	</div>{{-- PANEL BODY --}}
</div>{{-- PANEL --}}
@stop