<?php

class RefaccionesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$refacciones = Refaccion::all();
		return View::make("refacciones.index",["refacciones"=>$refacciones]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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

	public function addsession(){
		$data = Input::all();	//	Tomamos los datos pasados por post
		if(!isset($data['cantidad']) || $data['cantidad'] == "" || !isset($data['descripcion']) || $data['descripcion'] == ""){
			return json_encode(Session::get('refacciones'));
		}
		$refaccion = array("cantidad"=>$data['cantidad'],"descripcion"=>$data['descripcion']);
		Session::push('refacciones',$refaccion);
		return json_encode(Session::get('refacciones'));
	}

	public function deletesession(){
		Session::forget('refacciones');
		return "Session de refacciones limpia";
	}

	/*Cambia el estado de una refaccion */
	public function change($id){
		//	TODO: TRANSACCION
		$refaccion = Refaccion::find($id);
		if($refaccion->estado == 0){
			$refaccion->estado = 1;
		}else{
			$refaccion->estado = 0;
		}
		$refaccion->save();
		$refacciones = Refaccion::all();
		return View::make("refacciones.index",["refacciones"=>$refacciones]);
	}
}
