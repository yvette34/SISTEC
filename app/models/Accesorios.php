<?php
class Accesorios extends Eloquent{
	protected $table = "accesorios";
	public $timestamps = false;

	//	Relacion a soporte
	public function soporte(){
		return $this->belongsTo('Soporte','id_soporte');
	}

}