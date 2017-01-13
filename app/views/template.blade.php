<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>SISTEC</title>
		@section('css')
		<?php echo HTML::style('css/bootstrap.min.css'); ?>
		<?php echo HTML::style('css/font-awesome.min.css'); ?>
		<?php echo HTML::style('datatable/media/css/jquery.dataTables.min.css'); ?>
		@show
	</head>
	<body style="background-image: url({{ asset('img/fondo_verde.jpg') }});">
		<div class="container-fluid">
			@section("logos")
			@include('header')
			@show
			{{--@include('header')--}}
			@section("nav")
			@include('navegacion')
			@show
			@if(isset($mensajeGlobal))
			<div class="alert {{ $tipo }}" role="alert">
				<a class="close" data-dismiss="alert">&times;</a>
				<p class="small">
					{{ $mensajeGlobal }}
				</p>
			</div>
			@endif
			{{-- Yield de contenido --}}
			@yield('content')
			{{-- Mensaje de notificacion --}}
			@section("aviso")
			<div class="bg-warning hidden" role="alert">
				<a class="close" data-dismiss="alert">&times;</a>
				<p class="small">
					<label>Nota: </label> La subcoordinación de informatica no se hace responsable de si hay perdida de información a causa de algun servicio o mantenimiento preventivo o correctivo, por virus o fallas de hardware.
					<br>
					<b><u>RECUERDA QUE RESPALDAR LA INFORMACION DEL EQUIPO ES RESPONSABILIDAD DE USUARIO</u></b>
				</p>
			</div>
			@show
			{{-- Footer --}}
			@include('footer')
		</div>
		@section('javascript')
		<?php echo HTML::script('js/jquery.min.js'); ?>
		<?php echo HTML::script('js/bootstrap.min.js'); ?>
		<?php echo HTML::script('datatable/media/js/jquery.dataTables.min.js'); ?>
		<?php echo HTML::script('js/eventos.js'); ?>
		@show
	</body>
</html>