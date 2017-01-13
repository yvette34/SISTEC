<?php
#
Route::any('/',array('as'=>'/','uses'=>'UserController@login'));

#	Servicios
Route::any('servicios',array('as'=>'servicios','uses'=>'ServiciosController@index'));
Route::any('servicios/create',array('as'=>'servicios/create','uses'=>'ServiciosController@create'));
Route::any('servicios/store',array('as'=>'servicios/store','uses'=>'ServiciosController@store'));
Route::any('servicios/edit{id}',array('as'=>'servicios/edit','uses'=>'ServiciosController@edit'));
Route::any('servicios/update{id}',array('as'=>'servicios/update','uses'=>'ServiciosController@update'));
Route::any('servicios/show{id}',array('as'=>'servicios/show','uses'=>'ServiciosController@show'));
Route::any('servicios/destroy{id}',array('as'=>'servicios/destroy','uses'=>'ServiciosController@destroy'));
Route::any('servicios/seguimiento{id}',array('as'=>'servicios/seguimiento','uses'=>'ServiciosController@seguimiento'));
Route::any('servicios/seguistore{id}',array('as'=>'servicios/seguistore','uses'=>'ServiciosController@seguistore'));
Route::any('servicios/reporteServicios{id}',array('as'=>'servicios/reporteServicios','uses'=>'ServiciosController@reporteServicios'));

#	Equipo
Route::any('equipos',array('as'=>'equipos','uses'=>'EquiposController@index'));
Route::any('equipos/create',array('as'=>'equipos/create','uses'=>'EquiposController@create'));
Route::any('equipos/show{id}',array('as'=>'equipos/show','uses'=>'EquiposController@show'));
Route::any('equipos/edit{id}',array('as'=>'equipos/edit','uses'=>'EquiposController@edit'));
Route::any('equipos/search{id}',array('as'=>'equipos/search','uses'=>'EquiposController@search'));
Route::any('equipos/getequiposfromarea{area}',array('as'=>'equipos/getequiposfromarea','uses'=>'EquiposController@getequiposfromarea'));
Route::any('equipos/cargar_equipos{usuario}',array('as'=>'equipos/cargar_equipos','uses'=>'EquiposController@cargar_equipos'));

#	Refacciones
Route::any('refacciones/addsession',array('as'=>'refacciones/addsession','uses'=>'RefaccionesController@addsession'));
Route::any('refacciones/deletesession',array('as'=>'refacciones/deletesession','uses'=>'RefaccionesController@deletesession'));
Route::any('refacciones',array('as'=>'refacciones','uses'=>'RefaccionesController@index'));
Route::any('refacciones/change{id}',array('as'=>'refacciones/change','uses'=>'RefaccionesController@change'));


#	Usuarios
Route::any('users',array('as'=>'users','uses'=>'UserController@index'));
Route::any('users/login',array('as'=>'users/login','uses'=>'UserController@login'));
Route::any('users/logout',array('as'=>'users/logout','uses'=>'UserController@logout'));
Route::any('users/register',array('as'=>'users/register','uses'=>'UserController@register'));
Route::any('users/edit{id}',array('as'=>'users/edit','uses'=>'UserController@edit'));
Route::any('users/show{id}',array('as'=>'users/show','uses'=>'UserController@show'));
Route::any('users/getuserfromarea{area}',array('as'=>'users/getuserfromarea','uses'=>'UserController@getuserfromarea'));

#	Areas
Route::any('areas',array('as'=>'areas','uses'=>'AreasController@index'));
Route::any('areas/create',array('as'=>'areas/create','uses'=>'AreasController@create'));
Route::any('areas/store',array('as'=>'areas/store','uses'=>'AreasController@store'));
Route::any('areas/edit{id}',array('as'=>'areas/edit','uses'=>'AreasController@edit'));
Route::any('areas/update{id}',array('as'=>'areas/update','uses'=>'AreasController@update'));

#	Mantenimientos
Route::any('mantenimiento',array('as'=>'mantenimiento','uses'=>'MantenimientoController@index'));
Route::any('mantenimiento/create',array('as'=>'mantenimiento/create','uses'=>'MantenimientoController@create'));
Route::any('mantenimiento/store',array('as'=>'mantenimiento/store','uses'=>'MantenimientoController@store'));
Route::any('mantenimiento/show{id}',array('as'=>'mantenimiento/show','uses'=>'MantenimientoController@show'));
Route::any('mantenimiento/edit{id}',array('as'=>'mantenimiento/edit','uses'=>'MantenimientoController@edit'));
Route::any('mantenimiento/update{id}',array('as'=>'mantenimiento/update','uses'=>'MantenimientoController@update'));
Route::any('mantenimiento/searchUsersFromArea{id}',array('as'=>'mantenimiento/searchUsersFromArea','uses'=>'MantenimientoController@searchUsersFromArea'));
Route::any('mantenimiento/searchEquipoFromUsuario{id}',array('as'=>'mantenimiento/searchEquipoFromUsuario','uses'=>'MantenimientoController@searchEquipoFromUsuario'));
Route::any('mantenimiento/getEquiposDeUsuario{id}',array('as'=>'mantenimiento/getEquiposDeUsuario','uses'=>'MantenimientoController@getEquiposDeUsuario'));
Route::any('mantenimiento/reporetM{id}',array('as'=>'mantenimiento/reporetM','uses'=>'MantenimientoController@reporetM'));

#	Asignación de equipos
Route::any('equipos/asignacion',array('as'=>'equipos/asignacion','uses'=>'EquiposController@asignacion'));
