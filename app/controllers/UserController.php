<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$users = User::orderBy('id','DESC')->get();
		return View::make('users.index')->with('users',$users);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function register()
	{
		if(Session::has('rol') && Session::get('rol')==1){
			$mensajeGlobal = "No tiene privilegios para esta acción.";
			$users = User::all();
			return View::make('users/index', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-warning","users"=>$users]);	
		}
		$mensajeGlobal = "";
		$areas = Areas::all()->lists("nombre","id");
		$areas = array_add($areas,"","Seleccione una opción");
		if($_POST){
			//	Validaciones
			$validator = Validator::make(
				[
					"nombres"=>Input::get("nombres"),
					"apellidos"=>Input::get("apellidos"),
					"id_area"=>Input::get("id_area"),
					"rol"=>Input::get("rol"),
					"email"=>Input::get("email"),
					"usuario"=>Input::get("usuario"),
					"password"=>Input::get("password")
				],
				[
					"nombres"=>"required",
					"apellidos"=>"required",
					"id_area"=>"required",
					"rol"=>"required",
					"email"=>"required|unique:usuarios",
					"usuario"=>"required|unique:usuarios",
					"password"=>"required"
				]
			);
			//	Si existe error en la validaciones
			if($validator->fails()){
				if($validator->messages()->first('nombres') != ''){ $mensajeGlobal .= "El campo nombre(s) es obligado. <br>"; }
				if($validator->messages()->first('apellidos') != ''){ $mensajeGlobal .= "El campo apellidos es obligado. <br>"; }
				if($validator->messages()->first('id_area') != ''){ $mensajeGlobal .= "Selecciona el area. <br>"; }
				if($validator->messages()->first('rol') != ''){ $mensajeGlobal .= "Selecciona un rol. <br>"; }
				if($validator->messages()->first('correo') != ''){ $mensajeGlobal .= "El campo correo es obligado. <br>"; }
				if($validator->messages()->first('usuario') != ''){ $mensajeGlobal .= "El campo usuario esta vacio o ya existe ese usuario. <br>"; }
				if($validator->messages()->first('password') != ''){ $mensajeGlobal .= "La contraseña esta vacia. <br>"; }
			}
			//	Validamos que las contraseña coincidan
			if(Input::get("password") != Input::get("passwordVerificacion")){ $mensajeGlobal .= "La constraseña no coincide.<br>"; }
			//	Si han ocurrido errores
			if($mensajeGlobal != ""){
				return View::make('users.register', Input::all(), ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger","areas"=>$areas]);
			}

			//	En caso de no existir errores gaurdamos el usuario
			try{

				DB::beginTransaction();	//	Comenzamos la transaccion

				$usuario = new User;
				$usuario->nombres = Input::get("nombres");
				$usuario->apellidos = Input::get("apellidos");
				$usuario->id_area = Input::get("id_area");
				$usuario->rol = Input::get("rol");
				$usuario->email = Input::get("email");
				$usuario->cargo = Input::get("cargo");
				$usuario->usuario = Input::get("usuario");
				$usuario->password = Crypt::encrypt(Input::get("password"));
				$usuario->save();

				//	Creamos el manseja global en sessiones flash para cuando redireccione persista el mensaje
				$mensajeGlobal .= "Usuario registrado con exito";
				$tipo = "alert-success";
				$users = User::all();
				DB::commit();
				if(Session::has('usuario'))
				{
					return View::make('users/index', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>$tipo,"areas"=>$areas,"users"=>$users]);	
				}else{
					return View::make('users/login', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>$tipo]);
				}


			}catch(Exception $ex){	//	En caso de existir error en la transaccion lanza la exception
				DB::rollback();
				$mensajeGlobal .= "Ocurrio un erro en el proceso. Consulte a su administrador de sistemas.";
				$tipo = "alert-danger";
				return View::make('users/register', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>$tipo,"areas"=>$areas]);
			}
		}
		return View::make("users.register",["areas"=>$areas]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function getStore()
	{
		return "Store";
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//$user = User::find($id);
		return "En Contrucción";
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$mensajeGlobal = "";
		$areas = Areas::all()->lists("nombre","id");
		$areas = array_add($areas,"","Seleccione una opción");
		$usuario = User::find($id);
		if($_POST){
			//	Validaciones
			$validator = Validator::make(
				[
					"nombres"=>Input::get("nombres"),
					"apellidos"=>Input::get("apellidos"),
					"id_area"=>Input::get("id_area"),
					"rol"=>Input::get("rol"),
					"email"=>Input::get("email"),
					"usuario"=>Input::get("usuario"),
					"password"=>Input::get("password")
				],
				[
					"nombres"=>"required",
					"apellidos"=>"required",
					"id_area"=>"required",
					"rol"=>"required",
					"email"=>"required",
					"usuario"=>"required",
					"password"=>"required"
				]
			);
			//	Si existe error en la validaciones
			if($validator->fails()){
				if($validator->messages()->first('nombres') != ''){ $mensajeGlobal .= "El campo nombre(s) es obligado. <br>"; }
				if($validator->messages()->first('apellidos') != ''){ $mensajeGlobal .= "El campo apellidos es obligado. <br>"; }
				if($validator->messages()->first('id_area') != ''){ $mensajeGlobal .= "Selecciona el area. <br>"; }
				if($validator->messages()->first('rol') != ''){ $mensajeGlobal .= "Selecciona un rol. <br>"; }
				if($validator->messages()->first('correo') != ''){ $mensajeGlobal .= "El campo correo es obligado. <br>"; }
				if($validator->messages()->first('usuario') != ''){ $mensajeGlobal .= "El campo usuario esta vacio o ya existe ese usuario. <br>"; }
				if($validator->messages()->first('password') != ''){ $mensajeGlobal .= "La contraseña esta vacia. <br>"; }
			}
			//	Validamos que la contraseñas nuevas coincidan
			if(Input::get("password") != Input::get("passwordVerificacion")){ $mensajeGlobal .= "La constraseña no coincide.<br>"; }
			//	Validamos que el nombre de usuario no exista para otro registro en la tabla
			if(User::whereRaw("id!=? and usuario=?",[$id,Input::get("usuario")])->count() > 0){ $mensajeGlobal .= "El usuario ya existe!"; }
			//	Si han ocurrido errores
			if($mensajeGlobal != ""){
				return View::make('users.edit', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger","areas"=>$areas,"user"=>$usuario]);
			}
			//	Validamos que la contraseña coincida con la del usuario
			if(Input::get("passwordAnterior") != Crypt::decrypt($usuario->password)){
				$mensajeGlobal = "La contraseña anterior no es correcta!";
				return View::make('users.edit', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger","areas"=>$areas,"user"=>$usuario]);
			}
			//	Si no ocurrieron errores de validacion comenzamos la transacion
			try {
				DB::beginTransaction();
					$usuario->nombres = Input::get("nombres");
					$usuario->apellidos = Input::get("apellidos");
					$usuario->id_area = Input::get("id_area");
					$usuario->cargo = Input::get("cargo");
					$usuario->email = Input::get("email");
					$usuario->usuario = Input::get("usuario");
					$usuario->password = Crypt::encrypt(Input::get("password"));
					$usuario->rol = Input::get("rol");

					$usuario->save();

					//Session::put('usuario_nombre',Input::get("nombres") . " " . Input::get("apellidos"));
					//Session::put('usuario',$id);
					//Session::put('rol',Input::get("rol"));

				DB::commit();	
				$mensajeGlobal = "Se actualizo correctamente el registro!";
				$usuarios = User::all();
				return View::make('users.index', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-success","users"=>$usuarios]);
			} catch (Exception $e) {
				DB::rollback();
				$mensajeGlobal = "Ocurrio un error de sistema. Por favor contacta a tu administrador de sistemas.";
				return View::make('users.edit', ["mensajeGlobal"=>$mensajeGlobal,"tipo"=>"alert-danger","areas"=>$areas,"user"=>$usuario]);
			}
		}else{
			return View::make("users.edit",["user"=>$usuario,"areas"=>$areas]);
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function login(){
		if($_POST){
			$user = User::whereRaw("usuario=?",[Input::get("usuario")])->get();
			if(count($user)>0){
				if(Crypt::decrypt($user[0]->password) == Input::get("clave")){
					Session::put('usuario_nombre',$user[0]->nombres . " " . $user[0]->apellidos);
					Session::put('usuario',$user[0]->id);
					Session::put('rol',$user[0]->rol);
					return Redirect::to("servicios");
				}else{
					return View::make("users/login",["mensajeGlobal"=>"Usuario o contraseña incorrecto!","tipo"=>"alert-danger"]);
				}
			}else{
					return View::make("users/login",["mensajeGlobal"=>"Usuario o contraseña incorrecto.","tipo"=>"alert-danger"]);
			}
		}else{
			return View::make("users.login");
		}
	}

	public function logout(){
		Session::forget('usuario');
		Session::forget('usuario_nombre');
		Session::forget('rol');
		return Redirect::to('users/login');
	}

	/**
	 * Funcion para consultar los usuarios
	 */
	public function getuserfromarea($area){
		$usuarios = User::where("id_area","=",$area)->get()->toArray();
		$html = "<option selected>Seleccione...</option>";
		foreach ($usuarios as $usuario) {
			$html .= "<option value='".$usuario['id']."'>" . $usuario['nombres'] . " " . $usuario['apellidos'] . "</option>";
		}
		return $html;
	}
}
