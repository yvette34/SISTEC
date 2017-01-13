<?php
class Software extends Eloquent{
	protected $table = "software";
	public $timestamps = false;

	//	Relacion a soporte
	public function soporte(){
		return $this->belongsTo('Soporte','id_soporte');
	}
}