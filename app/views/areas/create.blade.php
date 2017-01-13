@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<strong>REGISTRO DE AREA</strong>
	</div>
	<div class="panel-body">
		<div class="col-md-4">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="#" class="thumbnail">
					{{ HTML::image('img/area.jpg','',['class'=>'']) }}
				</a>
			</div>
		</div>
		<div class="col-md-8">
			{{ Form::open(array('route'=>'areas/store')) }}
			<div class="form-group col-md-12">
				{{ Form::text('nombre','',array("class"=>"form-control","placeholder"=>"NOMBRE DEL AREA")) }}
				<p class="text-primary">Nombre completo oficial del area a dar de alta</p>
			</div>
			<div class="form-group col-md-12">
				<textarea name="descripcion" class="form-control" id="descripcion" placeholder="DESCRIPCIÓN"></textarea>
				<p class="text-primary">Descripción del area</p>
			</div>
			<div class="col-md-2 col-md-offset-10">
				<button type="submit" class="btn btn-info btn-lg pull-right"><i class="fa fa-floppy-o"></i> GUARDAR DATOS</button>
			</div>
			{{ Form::close() }}
		</div>
		
	</div>
</div>
@stop