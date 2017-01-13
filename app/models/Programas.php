<?php
class Programas extends Eloquent{
	protected $table = "programas_instalar";
	public $timestamps = false;
	public function servicio(){
		return $this->belongsTo('Servicios','id_servicio');
	}
}