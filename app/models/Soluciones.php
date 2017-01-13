<?php
class Soluciones extends Eloquent{
	protected $table = "soluciones";
	public $timestamps = false;
	public function servicio(){
		return $this->belongsTo('Servicios','id_servicio');
	}
}