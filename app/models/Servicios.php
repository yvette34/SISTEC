<?php
class Servicios extends Eloquent{
	protected $table = 'servicios';
	public $timestamps = false;

	public function equipo(){
		return $this->belongsTo('Equipo','id_equipo');
	}

	public function usuario(){
		return $this->belongsTo('User','id_usuario');
	}

	public function refacciones(){
		return $this->hasMany("Refaccion","id_servicio");
	}
	public function asistencias(){
		return $this->hasMany("Asistencia","id_servicio");
	}
	public function revisiones(){
		return $this->hasMany("Revisiones","id_servicio");
	}
	public function programas(){
		return $this->hasMany("Programas","id_servicio");
	}
	public function soluciones(){
		return $this->hasMany("Soluciones","id_servicio");
	}
}