<?php

class AreasController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$areas = Areas::all();
		$servicios = Servicios::all();
		return View::make("areas.inicio")->with(array("areas"=>$areas,'servicios'=>$servicios));
	}


	/**
	 * Show the form for creating a new area.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make("areas.create");
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$datos = Input::all();
		$area = new Areas;
		$area->nombre = $datos['nombre'];
		$area->descripcion = $datos['descripcion'];
		if($area->save()){
			$mensaje = "Se registro con exito";
			$areas = Areas::all();
			return View::make("areas.inicio")->with(array("areas"=>$areas,'msj'=>$mensaje));
		}else{
			$mensaje = "Error en el registro";
			$areas = Areas::all();
			return View::make("areas.inicio")->with(array("areas"=>$areas,'msj'=>$mensaje));
		}
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
		$area = Areas::find($id);
		return View::make("areas.edit")->with(array("area"=>$area));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$datos = Input::all();
		$area = Areas::find($id);
		$area->nombre = $datos['nombre'];
		$area->descripcion = $datos['descripcion'];
		$area->save();

		$areas = Areas::all();
		$mensaje = "Actualizado con exito";
		return View::make("areas.inicio")->with(array("areas"=>$areas,'msj'=>$mensaje));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$area = Areas::find($id);
		$area->destroy();
	}


}
