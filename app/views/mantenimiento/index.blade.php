@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<strong>MANTENIMIENTOS</strong>
		<a class="btn btn-success btn-sm pull-right" href="{{ URL::route('mantenimiento/create') }}" role="button">
			<i class="fa fa-check-square-o"></i> <strong>REGISTRAR</strong>
		</a>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-condensed small" id="tblMantenimientos">
				<thead>
					<tr>
						<th>Folio</th>
						<th>Fecha de registro</th>
						<th>Tipo</th>
						<th>Usuario</th>
						<th>Observaciones</th>
						<th>Operaciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($soportes as $soporte)
					<tr>
						<td>{{ $soporte->folio }}</td>
						<td>{{ $soporte->fecha }}</td>
						@if($soporte->tipo == 0)
						<td>Preventivo</td>
						@else
						<td>Correctivo</td>
						@endif
						<td>{{ $soporte->usuario->nombres }} {{ $soporte->usuario->apellidos }}</td>
						<td>{{ $soporte->observaciones }}</td>
						<td>
							<div class="btn-group-xs btn-group-horizontal">
								<a title="click para ver el detalle" class="btn btn-info" href="{{URL::route('mantenimiento/show',array('id'=>$soporte->id))}}" role="button">
								<i class="fa fa-eye"></i> Ver</a>
								<a title="click para modificar" class="btn btn-primary" href="{{URL::route('mantenimiento/edit',array('id'=>$soporte->id))}}" role="button">
								<i class="fa fa-pencil-square-o"></i> Actualizar</a>
								<a class="btn btn-default btn-danger" href="{{URL::route('mantenimiento/reporetM',array('id'=>$soporte->id))}}" role="button"><i class="fa fa-file-pdf-o" target="__blanck"></i> Imprimir a PDF</a>
								<a class="btn btn-default btn-danger hidden" href="#" role="button">Borrar</a>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
@section('javascript')
@parent
<script type="text/javascript">
	$('#tblMantenimientos').DataTable();
</script>
@stop