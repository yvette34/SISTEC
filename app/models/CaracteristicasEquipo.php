<?php
class CaracteristicasEquipo extends Eloquent{
	protected $table = "caracteristicas_equipo";
	public $timestamps = false;
	public function equipo(){
		return $this->belongsTo('Equipo');
	}
}