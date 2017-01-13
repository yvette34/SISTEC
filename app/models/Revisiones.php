<?php
class Revisiones extends Eloquent{
	protected $table = "reviciones_mantenimiento";
	public $timestamps = false;
	public function servicio(){
		return $this->belongsTo('Servicios','id_servicio');
	}
}