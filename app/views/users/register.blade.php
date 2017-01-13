@extends("template")
{{-- Validamos la session --}}
@if(!Session::has("usuario"))
@section("nav")
@stop
@endif
{{-- Quitamos el aviso del sistema --}}
@section("aviso")
@stop
@section("content")
<div class="panel panel-info">
	  <div class="panel-heading">
			<h3 class="panel-title">REGISTRO DE USUARIO <span class="small">Complete los campos</span></h3>
	  </div>
	  <div class="panel-body">
	  	{{ Form::open(["route"=>"users/register"]) }}
			<div class="form-group col-md-4">
				{{-- Nombres --}}
				@if(isset($nombres))
				{{ Form::text("nombres",$nombres,["class"=>"form-control","placeholder"=>"NOMBRE DEL USUARIO"]) }}
				@else
				{{ Form::text("nombres","",["class"=>"form-control","placeholder"=>"NOMBRE DEL USUARIO"]) }}
				@endif
				<p class="text-primary">Nombre(s) del usuario.</p>
			</div>
			<div class="form-group col-md-4">
				{{-- Apellidos --}}
				@if(isset($apellidos))
				{{ Form::text("apellidos",$apellidos,["class"=>"form-control","placeholder"=>"APELLIDOS DEL USUARIO"]) }}
				@else
				{{ Form::text("apellidos","",["class"=>"form-control","placeholder"=>"APELLIDOS DEL USUARIO"]) }}
				@endif
				<p class="text-primary">Apellidos del usuario</p>
			</div>
			<div class="form-group col-md-4">
				{{-- Area --}}
				@if(isset($id_area))
				{{ Form::select("id_area",$areas,$id_area,["class"=>"form-control","placeholder"=>"AREA A LA QUE PERTENECE EL USUARIO"]) }}
				@else
				{{ Form::select("id_area",$areas,"",["class"=>"form-control","placeholder"=>"AREA A LA QUE PERTENECE EL USUARIO"]) }}
				@endif
				<p class="text-primary">Area a la que pertenece.</p>
			</div>
			<div class="clearfix"></div>

			<div class="form-group col-md-4">
				{{-- Correo --}}
				@if(isset($email))
				{{ Form::text("email",$email,["class"=>"form-control","placeholder"=>"CORREO ELECTRONICO"]) }}
				@else
				{{ Form::text("email","",["class"=>"form-control","placeholder"=>"CORREO ELECTRONICO"]) }}
				@endif
				<p class="text-primary">Email/Correo electronico.</p>
			</div>
			<div class="form-group col-md-4">
				{{-- Cargo --}}
				@if(isset($cargo))
				{{ Form::text("cargo",$cargo,["class"=>"form-control","placeholder"=>"CARGO PUESTO ACTUAL"]) }}
				@else
				{{ Form::text("cargo","",["class"=>"form-control","placeholder"=>"CARGO PUESTO ACTUAL"]) }}
				@endif
				<p class="text-primary">Puesto/Cargo.</p>
			</div>
			<div class="form-group col-md-4">
				{{-- Rol --}}
				@if(isset($rol))
					@if(Session::has('usuario') && Session::get('rol')==0)
						{{ Form::select("rol",[""=>"Seleccione una opción","0"=>"Administrador","1"=>"General","2"=>"Encargado de area"],$rol,["class"=>"form-control","placeholder"=>"ROL,ADMINISTRADOR O GENERAL"]) }}
					@else
						{{ Form::select("rol",[""=>"Seleccione una opción","1"=>"General"],$rol,["class"=>"form-control","placeholder"=>"ROL,ADMINISTRADOR O GENERAL"]) }}
					@endif
				@else
					@if(!Session::has("usuario") || !Session::has("rol") || Session::get('rol')!=0)
						{{ Form::select("rol",[""=>"Seleccione una opción","1"=>"General"],"1",["class"=>"form-control","placeholder"=>"ROL,ADMINISTRADOR O GENERAL"]) }}
					@else
						{{ Form::select("rol",[""=>"Seleccione una opción","0"=>"Administrador","1"=>"General","2"=>"Encargado de area"],"",["class"=>"form-control","placeholder"=>"ROL,ADMINISTRADOR O GENERAL"]) }}
					@endif
				@endif
				<p class="text-primary">Rol para el sistema.</p>
			</div>
			<div class="clearfix"></div>

			<div class="form-group col-md-4">
				{{-- Usuario --}}
				@if(isset($usuario))
				{{ Form::text("usuario",$usuario,["class"=>"form-control","placeholder"=>"NOMBRE DE USUARIO PARA ACCESO AL SISTEMA"]) }}
				@else
				{{ Form::text("usuario","",["class"=>"form-control","placeholder"=>"NOMBRE DE USUARIO PARA ACCESO AL SISTEMA"]) }}
				@endif
				<p class="text-primary">Nombre de usuario para acceso al sistema.</p>
			</div>
			<div class="form-group col-md-4">
				{{ Form::password("password",["class"=>"form-control","placeholder"=>"PASSWORD/CLAVE"]) }}
				<p class="text-primary">Clave de acceso</p>
			</div>
			<div class="form-group col-md-4">
				{{ Form::password("passwordVerificacion",["class"=>"form-control","placeholder"=>"RESCRIBE EL PASSWORD/CLAVE"]) }}
				<p class="text-primary">Verificación de la clave.</p>
			</div>
			<div class="clearfix"></div>

			<div class="col-md-2 col-md-offset-10">
				<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-floppy-o"></i> GUARDAR DATOS</button>
			</div>
			{{ Form::close() }}
	  </div>{{-- PANEL BODY --}}
</div>{{-- PANEL --}}
@stop