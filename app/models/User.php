<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuarios';
	public $timestamps = false; //	Solo para que no nos pida los campos timestamps obligados

	public function equipos(){
		return $this->hasMany('Equipo','id_usuario');
	}

	public function servicios(){
		return $this->hasMany('Servicios');
	}
	public function area(){
		return $this->belongsTo('Areas','id_area');
		// La relación Pertenece a se declara con la función belongsTo
        // esta acepta dos parámetros
        // El primero es la tabla a donde pertecene la relación
        // El segundo es el campo en la tabla actual que se relaciona con el id de la tabla padre
        // En este caso seria el id_area en la tabla usuarios que se relaciona con el id en la tabla padre areas
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
