<?php
class Equipo extends Eloquent{
	protected $table = "equipos";
	public $timestamps = false;

	public function usuario(){
		return $this->belongsTo('User','id_usuario');
	}

	public function servicios(){
		return $this->hasMany('Servicios');
	}

	public function caracteristicas(){
		return $this->hasMany('CaracteristicasEquipo','id_equipo');
	}

	public function soportes(){
		return $this->belongsToMany("Soporte","soporte_equipo","id_equipo","id_soporte");
	}
}