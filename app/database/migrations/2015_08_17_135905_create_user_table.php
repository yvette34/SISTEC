<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
		Schema::table('users', function($table)
		{
			$table->increment('id_usuario'); //El id

			$table->string('nombres',150); // nombre
			$table->string('apellidos',150);	//	apellidos

			$table->string('id_area');	//	Area FK

			$table->string('');	//	cargo
			$table->string('email');	//	email
			$table->string('');	//	usuario
			$table->string('');
			$table->string('');
			$table->string('');
			$table->string('');

			$table->timestamp();

		});
		*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
