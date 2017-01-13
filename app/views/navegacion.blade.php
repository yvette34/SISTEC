<?php
$vista = Route::currentRouteName();
$current = array(
'index' => '',
'servicios' => '',
'mantenimiento' => '',
'equipo' => '',
'usuarios' => '',
'refacciones' => '',
'areas' => '',
'asignacion' => ''
);
switch ($vista) {
  case 'refacciones':
    $current['refacciones'] = 'active';
  break;
  case 'servicios':
    $current['servicios'] = 'active';
  break;
  case 'mantenimiento':
    $current['mantenimiento'] = 'active';
  break;
  case 'equipos':
    $current['equipo'] = 'active';
  break;
  case 'users':
    $current['usuarios'] = 'active';
  break;
  case 'areas':
    $current['areas'] = 'active';
  break;
  case 'asignacion':
    $current['asignacion'] = 'active';
  break;
  default:
    $current['index'] = 'active';
  break;
}
?>
@if(Session::has("usuario") && Session::has("rol"))
<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand {{$current['index']}}" href="{{URL::route('/')}}">SISTEC</a>
  </div>
  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
      @if(Session::has('rol') && Session::get('rol')==0)
        <li class="{{$current['servicios']}}"><a href="{{URL::route('servicios')}}">Servicio</a></li>
        <li class="{{$current['mantenimiento']}}"><a href="{{URL::route('mantenimiento')}}">Mantenimiento</a></li>
        <li class="{{$current['equipo']}}"><a href="{{URL::route('equipos')}}">Equipo</a></li>
        <li class="{{$current['usuarios']}}"><a href="{{URL::route('users')}}">Usuarios</a></li>
        <li class="{{$current['refacciones']}}"><a href="{{URL::route('refacciones')}}">Refacciones</a></li>
        <li class="{{$current['areas']}}"><a href="{{URL::route('areas')}}">Areas</a></li>
        <!--
        <li class="{{$current['asignacion']}}"><a href="{{URL::route('equipos/asignacion')}}">Asignación de Equipos</a></li>
        -->
      @else
        <li class="{{$current['servicios']}}"><a href="{{URL::route('servicios')}}">Servicio</a></li>
        <li class="{{$current['equipo']}}"><a href="{{URL::route('equipos')}}">Equipos Registrados</a></li>
        <li class="{{$current['usuarios']}}"><a href="{{URL::route('users/edit',['id'=>Session::get('usuario')])}}">Perfil</a></li>
        <!--
        <li class="{{$current['asignacion']}}"><a href="{{URL::route('equipos/asignacion')}}">Asignación de Equipos</a></li>
        -->
      @endif
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="{{ URL::route('users/logout') }}">
        @if(Session::has("usuario") && Session::has("rol"))
        <?php 
          $rol = "";
          switch (Session::get('rol')) {
            case '0':
              $rol = "Administrador";
              break;
            case '1':
              $rol = "General";
              break;
            case '2':
              $rol = "Encargado de area";
              break;
          }
        ?> 
        [<strong>{{{ $rol }}}</strong>]
        [ {{{ Session::get('usuario_nombre') }}} ] Salir
        @endif
      </a></li>
    </ul>
    </div><!-- /.navbar-collapse -->
  </nav>
@endif