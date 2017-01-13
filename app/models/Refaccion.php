<?php
class Refaccion extends Eloquent{
	protected $table = "refacciones";
	public $timestamps = false;
	public function servicio(){
		return $this->belongsTo('Servicios','id_servicio');
	}
}