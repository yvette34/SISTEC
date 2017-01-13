@extends('template')
{{-- Quitamo el menu de navegacion --}}
@section("nav")
@stop
{{-- Quitamos el aviso del sistema --}}
@section("aviso")
@stop
{{-- Contenido --}}
@section('content')
<center><h1>Sistema de Soporte Técnico</h1><h3>SISTEC</h3></center>
<div class="container">
	<style>
	.slider{
		border-style: inset;
		border-radius: 20px;
		padding: 0px;
		overflow: hidden;
	}
	.slider_img{
		border-radius: 20px;
	}
	</style>
	<div class="col-md-8">{{-- Carrusel de imagenes --}}
		<div id="carousel-id" class="carousel slide slider small" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#carousel-id" data-slide-to="0" class=""></li>
				<li data-target="#carousel-id" data-slide-to="1" class=""></li>
				<li data-target="#carousel-id" data-slide-to="2" class="active"></li>
				<li data-target="#carousel-id" data-slide-to="3" class=""></li>
			</ol>
			<div class="carousel-inner">
				<div class="item">
					<img data-src="" src="{{ asset('img/slider1.jpg') }}" class="img-responsive slider_img">
				</div>
				<div class="item">
					<img data-src="" src="{{ asset('img/slider2.jpg') }}" class="img-responsive slider_img">
				</div>
				<div class="item active">
					<img data-src="" src="{{ asset('img/logo_sumar.png') }}" class="img-responsive slider_img">
				</div>
				<div class="item">
					<img data-src="" src="{{ asset('img/img1.jpg') }}" class="img-responsive slider_img">
				</div>
			</div>
			<a class="left carousel-control slider_img" href="#carousel-id" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
			<a class="right carousel-control slider_img" href="#carousel-id" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
		</div>
	</div>{{-- Carrusel de imagenes --}}
	<div class="col-md-4">{{-- Formulario de acceso --}}
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"><h4><center><i class="fa fa-users fa-lg"></i> ACCESO DE USUARIOS</center></h4></h3>
			</div>
			<div class="panel-body">
				<form action="{{ URL::route('users/login') }}" method="POST" role="form" id="formaccess">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1"><i class="fa fa-user fa-lg"></i></span>
						<input name="usuario" id="usuario" type="text" class="form-control" placeholder="Nombre de usuario" aria-describedby="basic-addon1">
					</div>
					<br>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock fa-lg"></i></span>
						<input name="clave" id="clave" type="password" class="form-control" placeholder="Clave" aria-describedby="basic-addon1">
					</div>
					<br>
					<button type="submit" class="btn btn-success pull-right btn-lg" id="btnSubmit">
					<i class="fa fa-check fa-lg"></i>
					Entrar
					</button>
					<a href="{{ URL::route('users/register') }}">Registrarse</a>
				</form>
			</div>
		</div>
	</div>{{-- Fomulario de acceso --}}
</div>
@stop