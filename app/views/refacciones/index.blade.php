@extends("template")
@section("content")
<div class="panel panel-info">
	  <div class="panel-heading">
			<h3 class="panel-title">REFACCIONES</h3>
	  </div>
	  <div class="panel-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-condensed small">
					<thead>
						<tr>
							<th>Folio de Servicio</th>
							<th>Descripción</th>
							<th>Cantidad</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach ($refacciones as $refaccion) {
							$servicio = Servicios::find($refaccion->id_servicio);
							$style = ""; $estado = "";
							if($refaccion->estado == 0){$style="warning"; $estado="EN ESPERA";}else{$style="success";$estado="RECIBIDA";}
							?>
							<tr>
								<td>{{ $servicio->folio }}</td>
								<td>{{ $refaccion->descripcion }}</td>
								<td>{{ $refaccion->cantidad }}</td>
								<td><a href="{{ URL::route('refacciones/change',['id'=>$refaccion->id]) }}" title="cambiar estado" class="btn btn-block btn-{{ $style }} btn-xs"><i class="fa fa-exchange"></i> {{ $estado }}</a></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
	  </div>
</div>
@stop