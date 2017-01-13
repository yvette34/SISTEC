<?php
class Areas extends Eloquent{
	protected $table = "areas";
	public $timestamps = false;

	public function usuarios(){
		return $this->hasMany("User","id_area");
	}
}