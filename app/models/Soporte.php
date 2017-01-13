<?php
class Soporte extends Eloquent{
	protected $table = "soportes";
	public $timestamps = false;

	//	Relacion a equipos
	public function equipo(){
		return $this->belongsTo('Equipo','id_equipo');
	}
	//	Relacion a usuarios
	public function usuario(){
		return $this->belongsTo('User','id_usuario');
	}
	//	Relacion a accesorios
	public function accesorios(){
		return $this->hasMany("Accesorios","id_soporte");
	}
	//	Relacion a software
	public function software(){
		return $this->hasMany("Software","id_soporte");
	}
	//	Relacion a caracteristicas
	public function caracteristicas(){
		return $this->hasMany("Caracteristicas","id_soporte");
	}
	//	Relacion a soporte_equipo
	public function equipos(){
		return $this->belongsToMany("Soporte","soporte_equipo","id_soporte","id_equipo");
	}

}