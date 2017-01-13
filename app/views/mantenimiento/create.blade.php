@extends('template')
@section('content')
	<div class="panel panel-info">
		  <div class="panel-heading">
				<h3 class="panel-title">REGISTRO DE MANTENIMIENTO.<span class="small">Complete los campos.</span></h3>
		  </div>
		  <div class="panel-body">
		  	<?php $estados = array("1"=>"Excelente","2"=>"Bueno","3"=>"Regular","4"=>"Requiere Sustitución"); ?>
			{{ Form::open(array("route"=>"mantenimiento/store","class"=>"form")) }}
			<div class="form-group col-md-2">
				{{ Form::number("folio",$folio,array("class"=>"form-control","readonly")) }}
				<p class="text-primary">Folio</p>
			</div>
			<div class="form-group col-md-2">
				{{ Form::input("date","fecha",date("Y-m-d"),array("class"=>"form-control")) }}
				<p class="text-primary">Fecha</p>
			</div>
			<div class="form-group col-md-2">
				{{ Form::select("estado_equipo",$estados,"1",array("class"=>"form-control")) }}
				<p class="text-primary">Estado de equipo</p>
			</div>
			<div class="form-group col-md-2">
				{{ Form::select("tipo",array("0"=>"preventivo","1"=>"correctivo"),"0",array("class"=>"form-control")) }}
				<p class="text-primary">Tipo de mantenimiento</p>
			</div>
			<div class="form-group col-md-4">
				{{ Form::text("resguardo","",array("class"=>"form-control")) }}
				<p class="text-primary">Resguardo a</p>
			</div>
			<div class="form-group col-md-3">
				{{ Form::select('area',$areas,"0",array("class"=>"form-control","id"=>"areas_mantenimiento_create")) }}
				<p class="text-primary">Area</p>
			</div>
			<div class="form-group col-md-3">
				{{ Form::select('id_usuario',array(""=>"Seleccione el usuario"),"2",array("class"=>"form-control","id"=>"usuario_mantenimiento_create")) }}
				<p class="text-primary">Usuario</p>
			</div>
			<div class="form-group col-md-6">
				{{ Form::textarea("observaciones","",array("class"=>"form-control","rows"=>"2")) }}
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
						
					</tbody>
				</table>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-4 thumbnail">
				{{-- Accesorios --}}
				<p class="text-primary">ACCESORIOS</p>
				<div class="form-group col-md-6">
					{{ Form::text("teclado","",array("class"=>"form-control")) }}
					<p class="text-primary">Teclado</p>
				</div>
				<div class="form-group col-md-6">
					{{ Form::text("mouse","",array("class"=>"form-control")) }}
					<p class="text-primary">Mouse</p>
				</div>
				<div class="form-group col-md-6">
					{{ Form::text("regulador","",array("class"=>"form-control")) }}
					<p class="text-primary">Regulador</p>
				</div>
				<div class="form-group col-md-6">
					{{ Form::text("otros","",array("class"=>"form-control")) }}
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
						<div class="col-md-12">Otros: {{ Form::text('software[]',"",array("class"=>"")); }}</div>
					@else
						<div class="col-md-6"> {{ Form::checkbox('software[]', $check); }} {{ $check }}</div>
					@endif
				@endforeach
			</div>
			<div class="col-md-4 thumbnail">
				{{-- Caracteristicas del mantenimiento --}}
				<p class="text-primary">CARACTERISTICAS DEL MANTENIMIENTO</p>
				<?php $checks = array("Limpieza Externa","Limpieza Interna","Limpieza de archivos temporales","Limpieza de registro","Actualizaciones de antivirus","Analisis de virus","Desfragmentación de disco","Check Disk","Otros"); ?>
				@foreach($checks as $check)
					@if($check == "Otros")
						<div class="col-md-12">Otros: {{ Form::text('caracteristicas[]',"",array("class"=>"")); }}</div>
					@else
						<div class="col-md-6">{{ Form::checkbox('caracteristicas[]', $check); }} {{ $check }}</div>
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