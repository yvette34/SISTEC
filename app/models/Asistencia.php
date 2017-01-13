<?php
class Asistencia extends Eloquent{
	protected $table = "asistencia_tecnica";
	public $timestamps = false;
	public function servicio(){
		return $this->belongsTo('Servicios','id_servicio');
	}
}