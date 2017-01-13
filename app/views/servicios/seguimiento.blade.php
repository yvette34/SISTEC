@extends('template')
@section('content')
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">DETALLE DEL SERVICIO</h3>
	</div>
	<div class="panel-body">
		<div class="col-md-4">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="#" class="thumbnail">
					{{ HTML::image('img/servicio.jpg','',['class'=>'']) }}
				</a>
			</div>
		</div>
		<div class="col-md-8">
			<div class="col-md-4">
				<p class="text-primary">Servicio.</p>
				<?php
				//	Usuario que reporto
				$usuario = Servicios::find($servicio->id)->usuario;
				//	Usuario comun del equipo
				$usuario_equipo = User::find($servicio->usuario_equipo);
				//	Area del usuario común del equipo
				$area = Areas::find($usuario_equipo->id_area);
									//	Asiganmos estilo y nombre al estado
				$estado = array('estilo'=>'','etiqueta'=>'','fila'=>'');
				switch ($servicio->estado) {
					case '0':
						$estado['estilo'] = "btn-xs btn-danger";
						$estado['etiqueta'] = "Pendiente";
						$estado['fila'] = "bg-danger";
						break;
					case '1':
						$estado['estilo'] = "btn-xs btn-info";
						$estado['etiqueta'] = "Revisión";
						$estado['fila'] = "bg-info";
						break;
					case '3':
						$estado['estilo'] = "btn-xs btn-success";
						$estado['etiqueta'] = "Terminado";
						$estado['fila'] = "bg-success";
						break;
					case '2':
						$estado['estilo'] = "btn-xs btn-warning";
						$estado['etiqueta'] = "Espera";
						$estado['fila'] = "bg-warning";
						break;
					}
				?>
				* FOLIO: <strong> {{ $servicio->folio }}</strong><br>
				* ESTADO:
				<a title="click para actualizar" class="btn {{ $estado['estilo'] }}" href="#"><i class="fa fa-pencil-square-o"></i> {{ $estado['etiqueta'] }}</a>
				<br>
				* FECHA DE SOLICITUD: <strong> {{ $servicio->fecha_reporte }}</strong><br>
				* AREA: <strong> {{ $area->nombre }}</strong><br>
				* USUARIO QUE REPORTO: <strong> {{ $usuario->nombres }} {{ $usuario->apellidos }}</strong><br>
				* USUARIO COMÚN DEL EQUIPO: <strong> {{ $usuario_equipo->nombres }} {{ $usuario_equipo->apellidos }}</strong><br>
				* DESCRIPCIÓN DEL PROBLEMA: <br><strong> {{ $servicio->falla_reportada }}</strong>
			</div>
			<div class="col-md-4">
				<p class="text-primary">Equipo.</p>
				<?php
				$estado = "";
					switch ($equipo->estado) {
						case '0':
							$estado = "Requiere Sustitución";
							break;
						case '1':
							$estado = "Regular";
							break;
						case '2':
							$estado = "Bueno";
							break;
						case '3':
							$estado = "Excelente";
							break;
					}
				?>
				* NO. INVENTARIO: <strong>{{ $equipo->no_inventario  }}</strong><br>
				* MARCA: <strong>{{ $equipo->marca  }}</strong><br>
				* MODELO: <strong>{{ $equipo->modelo }}</strong><br>
				* ESTADO: <strong>{{ $estado  }}</strong><br>
				* DESCRIPCIÓN: <br><strong>{{ $equipo->descripcion  }}</strong><br>
			</div>
			<div class="col-md-4">
				<p class="text-primary">Otras caracteristicas.</p>
				* SISTEMA OPERATIVO: <strong>{{ $equipo->caracteristicas[0]->so }}</strong><br>
				* RAM: <strong>{{ $equipo->caracteristicas[0]->ram }}</strong><br>
				* DISCO DURO: <strong>{{ $equipo->caracteristicas[0]->disco_duro }}</strong><br>
				* IP: <strong>{{ $equipo->caracteristicas[0]->ip }}</strong><br>
				* MAC: <strong>{{ $equipo->caracteristicas[0]->mac }}</strong><br>
				* NODO: <strong>{{ $equipo->caracteristicas[0]->nodo }}</strong><br>
				* USUARIO DE PC: <strong>{{ $equipo->caracteristicas[0]->usuario_pc }}</strong><br>
				* GRUPO DE TRABAJO: <strong>{{ $equipo->caracteristicas[0]->grupo_trabajo }}</strong><br>
			</div>
		</div>
	</div>
</div>{{-- PANEL DETALLE --}}
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">SEGUIMIENTO DEL SERVICIO</h3>
	</div>
	<div class="panel-body">
		{{ Form::open(array('route'=>array('servicios/seguistore',$servicio->id),"class"=>"form")) }}
		{{-- ASISTENCIA TECNICA --}}
		<?php
		$arrayAsistencias = array("No prende / No inicia","Se reinicia","Malware(virus)","Configurar Correo","Archivos Perdidos");
		$arrayAsistencias2 = array("Bloqueo / Lentitud","Mensaje de error","Conexion a la red","Configurar impresora");
		?>
		<div class="col-md-4 thumbnail">
			<p class="text-primary">ASISTENCIA TÉCNICA</p>
			<div class="clearfix"></div>
			<div class="col-md-6">
				{{-- Para update --}}
				@if($transaccion == "update")
					@foreach($arrayAsistencias as $etiqueta)
						@if(in_array($etiqueta,$asistencias))
							{{ Form::checkbox('problema[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('problema[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
				{{-- Para Create --}}
				@else
					@foreach($arrayAsistencias as $etiqueta)
						@if(isset($post) && in_array($etiqueta,$post['problema']))
							{{ Form::checkbox('problema[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('problema[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
				@endif
			</div>
			<div class="col-md-6">
				{{-- UPDATE --}}
				@if($transaccion == "update")
					@foreach($arrayAsistencias2 as $etiqueta)
						@if(in_array($etiqueta,$asistencias))
							{{ Form::checkbox('problema[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('problema[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
					{{-- Validamos el campo "otros" --}}
					<?php $lastItem = array_pop($asistencias); ?>
					@if(!in_array($lastItem,$arrayAsistencias) || !in_array($lastItem,$arrayAsistencias2))
						Otro:{{ Form::text('problema[]', $lastItem, array('class'=>'small')); }}
					@endif
				{{-- CREATE --}}
				@else
					@foreach($arrayAsistencias2 as $etiqueta)
						@if(isset($post) && in_array($etiqueta,$post['problema']))
							{{ Form::checkbox('problema[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('problema[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
					{{-- Validación de campo otro --}}
					@if(isset($post))
						<?php $lastItem = array_pop($post['problema']);?>
						{{-- Si en el ultimo campo de $post['problema'] no se encuentra en las etiquetas lo imprimimos  --}}
						@if(!in_array($lastItem,$arrayAsistencias) || !in_array($lastItem,$arrayAsistencias2))
							Otro:{{ Form::text('problema[]', $lastItem, array('class'=>'small')); }}
						@endif
					@else
						Otro:{{ Form::text('problema[]', '', array('class'=>'small')); }}
					@endif
				@endif
			</div>
		</div>{{-- ASISTENCIAS --}}
		{{-- REVISION Y/O MANTENIMIENTO --}}
		<div class="col-md-4 thumbnail">
			<p class="text-primary">REVISIÓN Y/O MANTENIMIENTO</p>
			<div class="clearfix"></div>
			<?php
				$arrayRevisiones = array("Impresora","Teclado / Raton","Pantalla","Regulador","Unidad CD/DVD");
				$arrayRevisiones2 = array("Puertos USB","Sonido");
			?>
			<div class="col-md-6">
				{{-- UPDATE --}}
				@if($transaccion == "update")
					@foreach($arrayRevisiones as $etiqueta)
						@if(in_array($etiqueta,$revisiones))
							{{ Form::checkbox('tipo[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('tipo[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
				{{-- CREATE --}}
				@else
					@foreach($arrayRevisiones as $etiqueta)
						@if(isset($post) && in_array($etiqueta,$post['tipo']))
							{{ Form::checkbox('tipo[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('tipo[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
				@endif
			</div>
			<div class="col-md-6">
				{{-- UPDATE --}}
				@if($transaccion == "update")
					@foreach($arrayRevisiones2 as $etiqueta)
						@if(in_array($etiqueta,$revisiones))
							{{ Form::checkbox('tipo[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('tipo[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
					{{-- Validamos el campo "otros" --}}
					<?php $lastItem = array_pop($revisiones); ?>
					@if(!in_array($lastItem,$arrayRevisiones) || !in_array($lastItem,$arrayRevisiones2))
						Otro:{{ Form::text('tipo[]', $lastItem, array('class'=>'small')); }}
					@endif
				{{-- CREATE --}}
				@else
					@foreach($arrayRevisiones2 as $etiqueta)
						@if(isset($post) && in_array($etiqueta,$post['tipo']))
							{{ Form::checkbox('tipo[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('tipo[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
					@if(isset($post))
						<?php $lastItem = array_pop($post['tipo']); ?>
						@if(!in_array($lastItem,$arrayRevisiones) || !in_array($lastItem,$arrayRevisiones2))
							Otro:{{ Form::text('tipo[]', $lastItem, array('class'=>'small')); }}
						@endif
					@else
						Otro:{{ Form::text('tipo[]', '', array('class'=>'small')); }}
					@endif
				@endif
			</div>
		</div>{{-- REVISIONES --}}
		{{-- PROGRAMAS --}}
		<?php
		$arrayProgramas = array("Formateo","Antivirus","Paquete Office","Acrobat Reader","Corel Draw");
		$arrayProgramas2 = array("Quemador CD/DVD","Impresora","Reproductor DVD");
		?>
		<div class="col-md-4 thumbnail">
			<p class="text-primary">INSTALAR / REINSTALAR PROGRAMAS</p>
			<div class="clearfix"></div>
			<div class="col-md-6">
				{{-- UPDATE --}}
				@if($transaccion == "update")
					@foreach($arrayProgramas as $etiqueta)
						@if(in_array($etiqueta,$programas))
							{{ Form::checkbox('nombre[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('nombre[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
				{{-- CREATE --}}
				@else
					@foreach($arrayProgramas as $etiqueta)
						@if(isset($post) && in_array($etiqueta,$post['nombre']))
							{{ Form::checkbox('nombre[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('nombre[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
				@endif
			</div>
			<div class="col-md-6">
				{{-- UPDATE --}}
				@if($transaccion == "update")
					@foreach($arrayProgramas2 as $etiqueta)
						@if(in_array($etiqueta,$programas))
							{{ Form::checkbox('nombre[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('nombre[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
					{{-- Validamos el campo "otros" --}}
					<?php $lastItem = array_pop($programas); ?>
					@if(!in_array($lastItem,$arrayProgramas) || !in_array($lastItem,$arrayProgramas2))
						Otro:{{ Form::text('nombre[]', $lastItem, array('class'=>'small')); }}
					@endif
				{{-- CREATE --}}
				@else
					@foreach($arrayProgramas2 as $etiqueta)
						@if(isset($post) && in_array($etiqueta,$post['nombre']))
							{{ Form::checkbox('nombre[]', $etiqueta,true); }} {{ $etiqueta }} <br>
						@else
							{{ Form::checkbox('nombre[]', $etiqueta); }} {{ $etiqueta }} <br>
						@endif
					@endforeach
					@if(isset($post))
						{{-- Validamos el campo "otros" --}}
						<?php $lastItem = array_pop($post['nombre']); ?>
						@if(!in_array($lastItem,$arrayProgramas) || !in_array($lastItem,$arrayProgramas2))
							Otro:{{ Form::text('nombre[]', $lastItem, array('class'=>'small')); }}
						@endif
					@else
						Otro:{{ Form::text('nombre[]', '', array('class'=>'small')); }}
					@endif
				@endif
			</div>
		</div>{{-- PROGRAMAS --}}
		{{-- REFACCIONES --}}
		<div class="clearfix"></div>
		<div class="col-md-6 thumbnail">
			<p class="text-primary">REFACCIONES</p>
			Cantidad: {{ Form::text('cantidadad','',array('class'=>'small','id'=>'cantidad')) }}
			Descripción: {{ Form::text('descripcion','',array('class'=>'small','id'=>'descripcion')) }}
			<a href="#" class="btn btn-primary btn-xs" id="rfAdd"> Agregar </a>
			<a href="#" class="btn btn-success btn-xs" id="rfClean"> Limpiar </a>
			<div class="table-responsive">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Cantidad</th>
							<th>Descripción</th>
						</tr>
					</thead>
					<tbody id="tblRefacciones">
					</tbody>
				</table>
			</div>
		</div>{{-- REFACCIONES --}}
		<div class="col-md-6 thumbnail">
			{{-- DIAGNOSTICO --}}
			<div class="col-md-12">
				<p class="text-primary">DIAGNOSTICO.</p>
				<div class="form-group">
					{{-- UPDATE --}}
					@if($servicio->diagnostico && $transaccion == "update")
					{{ Form::textarea('diagnostico',$servicio->diagnostico,array('class'=>'form-control','rows'=>'3','cols'=>'150','placeholder'=>'DIAGNOSTICO')); }}
					{{-- CREATE --}}
					@else
						@if(isset($post))
							{{ Form::textarea('diagnostico',$post['diagnostico'],array('class'=>'form-control','rows'=>'3','cols'=>'150','placeholder'=>'DIAGNOSTICO')); }}
						@else
							{{ Form::textarea('diagnostico','',array('class'=>'form-control','rows'=>'3','cols'=>'150','placeholder'=>'DIAGNOSTICO')); }}
						@endif
					@endif
				</div>
				<hr>
			</div>
			{{-- SOLUCIONES --}}
			<div class="col-md-12">
				<p class="text-primary">DESCRIPCIÓN DE LAS SOLUCIONES</p>
				<div class="clearfix"></div>
				{{-- UPDATE --}}
				@if(isset($soluciones) && $transaccion == "update")
				<?php $trabajo_real = explode(':',$soluciones[0]->trabajo_real); ?>
				<div class="form-group col-md-4">
					{{ Form::input('date','fecha',$soluciones[0]->fecha,array('class'=>'form-control')) }}
					<p class="text-primary">Fecha.</p>
				</div>
				<div class="form-group col-md-4">
					<div class="row">
						<div class="col-md-6">{{ Form::text('horas',$trabajo_real[0],array('class'=>'form-control')) }}</div>
						<div class="col-md-6">{{ Form::text('minutos',$trabajo_real[1],array('class'=>'form-control')) }}</div>
						<p class="text-primary">Trabajo Real(H:M)</p>
					</div>
				</div>
				<div class="form-group col-md-4">
					{{ Form::select('estado',array('1'=>'Revisión','2'=>'Espera','3'=>'Terminado'),$servicio->estado,array('class'=>'form-control')) }}
					<p class="text-primary">Estatus del reporte.</p>
				</div>
				<div class="clearfix"></div>
				<div class="form-group col-md-12">
					{{ Form::textarea('descripcion',$soluciones[0]->descripcion,array('class'=>'form-control','rows'=>'3','cols'=>'150')) }}
					<p class="text-primary">Descripción</p>
				</div>
				{{-- CREATE --}}
				@else
				<div class="form-group col-md-4">
					@if(isset($post))
						{{ Form::input('date','fecha',$post['fecha'],array('class'=>'form-control')) }}
					@else
						{{ Form::input('date','fecha',date('Y-m-d'),array('class'=>'form-control')) }}
					@endif
					<p class="text-primary">Fecha.</p>
				</div>
				<div class="form-group col-md-4">
					<div class="row">
						@if(isset($post))
							<div class="col-md-6">{{ Form::text('horas',$post['horas'],array('class'=>'form-control','placeholder'=>'HORAS')) }}</div>
							<div class="col-md-6">{{ Form::text('minutos',$post['minutos'],array('class'=>'form-control','placeholder'=>'MINUTOS')) }}</div>
						@else
							<div class="col-md-6">{{ Form::text('horas','',array('class'=>'form-control','placeholder'=>'HORAS')) }}</div>
							<div class="col-md-6">{{ Form::text('minutos','',array('class'=>'form-control','placeholder'=>'MINUTOS')) }}</div>
						@endif
						<p class="text-primary">Trabajo Real(H:M)</p>
					</div>
				</div>
				<div class="form-group col-md-4">
					@if(isset($post))
						{{ Form::select('estado',array('1'=>'Revisión','2'=>'Espera','3'=>'Terminado'),$post['estado'],array('class'=>'form-control')) }}
					@else
						{{ Form::select('estado',array('1'=>'Revisión','2'=>'Espera','3'=>'Terminado'),$servicio->estado,array('class'=>'form-control')) }}
					@endif
					<p class="text-primary">Estatus del reporte.</p>
				</div>
				<div class="clearfix"></div>
				<div class="form-group col-md-12">
					@if(isset($post))
						{{ Form::textarea('descripcion',$post['descripcion'],array('class'=>'form-control','rows'=>'3','cols'=>'150','placeholder'=>'Breve descripción de la(s) solución(es)')) }}
					@else
						{{ Form::textarea('descripcion','',array('class'=>'form-control','rows'=>'3','cols'=>'150','placeholder'=>'Breve descripción de la(s) solución(es)')) }}
					@endif
					<p class="text-primary">Descripción</p>
				</div>
				@endif
			</div>{{-- SOLUCIONES --}}
		</div>
		<div class="col-md-3 col-md-offset-9">
			<button type="submit" class="btn btn-info btn-lg pull-right">
			<i class="fa fa-refresh"></i> ACTUALIZAR DATOS
			</button>
		</div>
		{{ Form::close() }}
	</div>{{-- PANEL BODY SEGUIMIENTO--}}
</div>{{-- PANEL SEGUIMIENTO--}}
@stop
@section("javascript")
@parent
@if(isset($refacciones) && $transaccion == "update")
<script type="text/javascript">agregaRefaccion("","");</script>
@endif
@if(isset($post) &&	Session::has("refacciones"))
<script type="text/javascript">agregaRefaccion("","");</script>
@endif
@stop