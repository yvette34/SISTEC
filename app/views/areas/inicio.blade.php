@extends('template')
@section('content')
<div class="panel panel-info">
	  <div class="panel-heading">
			<strong>AREAS</strong>
			<a class="btn btn-success btn-sm pull-right" href="{{ URL::route('areas/create') }}" role="button">
				<i class="fa fa-check-square-o"></i> <strong>NUEVO REGISTRO</strong>
			</a>
	  </div>
	  <div class="panel-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-condensed small" id="tblAreas">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Descipción</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
						@foreach($areas as $area)
						<tr>
							<td>{{ $area->nombre }}</td>
							<td>{{ $area->descripcion }}</td>
							<td>
								<a title="click para editar" class="btn btn-default btn-primary btn-xs"  href="{{URL::route('areas/edit',array('id'=>$area->id))}}"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
	  </div>{{-- PANEL BODY --}}
</div>{{-- PANEL --}}
@stop
@section('javascript')
@parent
<script type="text/javascript">
	$('#tblAreas').DataTable();
</script>
@stop