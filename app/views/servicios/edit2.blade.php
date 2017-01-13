

	@if (Session::has('response'))
	<span class="alert alert-success">{{ Session::get('response') }}</span>
	@endif

	<h4>Datos del servicio</h4>
	{{-- podemos usar un Form::model --}}
	{{ Form::open(array('route'=>array('servicios/update',$servicio->id))) }}
	Area: <strong>{{ $area->nombre }}</strong>
	<br>
	Usuario que reporta: <strong>{{ $usuario->nombres . " ". $usuario->apellidos }}</strong>
	<br>
	{{-- Folio --}}
	{{ Form::label('folio','Folio',array('class'=>'')) }}
	{{ Form::text('folio',$servicio->folio,array('class'=>'small','readonly'=>'true')) }}
	{{-- Fecha --}}
	{{ Form::label('fecha','Fecha',array('class'=>'')) }}
	{{-- El campo input recibe como parametros: tipo,name,value, arreglo de opcion --}}
	{{ Form::input('date','fecha_reporte',$servicio->fecha_reporte,array('class'=>'small','readonly'=>'true')) }}
	<br>
	{{-- Area --}}{{-- No se necesita caragar el area ya que el usario de tomara de la session --}}
	{{-- Form::label('area', 'Area #SESSION#', array('class' => ''));   --}}
	{{-- Aqui estamos poblando un select desde un modelo --}}
	{{-- Form::select('area',$areas,'',array('class'=>'')) --}}
	{{-- Usuario --}} {{-- Depende del area --}}
	{{ Form::hidden('id_usuario',$servicio->id_usuario,array('class'=>'')) }}
	{{-- Usuario del equipo --}}
	{{ Form::label('usuario_equipo','Usuario del equipo',array('class'=>'')) }}
	{{ Form::text('usuario_equipo',$servicio->usuario_equipo,array('class'=>'small')) }}
	<br>
	{{-- Falla/Servicio --}}
	{{ Form::label('falla','Servicio o falla reportada',array('class'=>'')) }}
	<br>
	{{ Form::textarea('falla_reportada',$servicio->falla_reportada,array('class'=>'small','rows'=>'4','cols'=>'50')) }}
	{{-- Estado --}}
	{{ Form::hidden('estado',$servicio->estado,array('class'=>'')) }}
	<hr>
	{{-- Dependiendo el usuario se va cargar el equipo relacionado --}}
	<h4>Datos del equipo para este usuario</h4>
	{{ Form::label('equipo','Equipo a revisar',array('class'=>'')) }}
	{{-- Debemos de caragar el equipo a partir de ID. Mandarlo desde el controlador --}}
	{{ Form::select('id_equipo',$equipos_usuario,$equipo_servicio->id,array('class'=>'','id'=>'id_equipo')) }}
	<span class="bg-info hidden">Dependiento el equipo que seleccione se realizara la revision</span>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-striped">
			<tbody>
				<tr>
					<td>No.Inventario</td>
					<td id="inventario"><strong>{{ $equipo_servicio->no_inventario }}</strong></td>
					<td>Sistema Operativo</td>
					<td id="so"><strong>{{ $ces[0]->so }}</strong></td>
				</tr>
				<tr>
					<td>Modelo</td>
					<td id="modelo"><strong>{{ $equipo_servicio->modelo }}</strong></td>
					<td>RAM</td>
					<td id="ram"><strong>{{ $ces[0]->ram }}</strong></td>
				</tr>
				<tr>
					<td>Marca</td>
					<td id="marca"><strong>{{ $equipo_servicio->marca }}</strong></td>
					<td>Disco Duro</td>
					<td id="hdd"><strong>{{ $ces[0]->disco_duro }}</strong></td>
				</tr>
				<tr>
					<td>Descripción</td>
					<td colspan="3" id="descripcion"><strong>{{ $equipo_servicio->descripcion }}</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
	{{ Form::submit('Enviar petición',array('class'=>'')) }}
	{{ Form::close() }}