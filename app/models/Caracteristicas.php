<?php
class Caracteristicas extends Eloquent{
	protected $table = "caracteristicas_mantenimiento";
	public $timestamps = false;

	//	Relacion a soporte
	public function soporte(){
		return $this->belongsTo('Soporte','id_soporte');
	}
}