$(document).ready(function() {
  //  SERVICIOS
//  servicios/create: Evento para cargar el combo de usuarios cuando se selecciona un area especifica
$("select#area_servicios_create").change(function(){ controlEvent(this);});
//  servicios/create: Evento para cargar los equipos del usuario seleccionado en este combo.
$("select#usuario_comun_servicios_create").change(function(){ controlEvent(this);});
//  servicios/create: Evento para caragar los detalles de un equipo seleccionado en este combo
$("select#equipos_servicios_create").change(function(){ getDetalleEquipo(this.value); });
// servicios/edit: Evento para cargar los usuarios del area seleccionada
$("select#areas_servicios_update").change(function(){ controlEvent(this); });
//  servicios/edit: Evento para cargar los equipos del usuaruio seleccionado.
$("select#usuario_comun_servicios_update").change(function(){ controlEvent(this); });
//  servicios/edit: Evento para cargar los detalles de un equipo seleccionado en este combo
$("select#equipos_servicios_update").change(function(){ getDetalleEquipo(this.value); });

//  EQUIPOS
//  equipos/create: Evento para cargar los usuarios del area al seleccionar un area de este combo
$("select#areas_equipos_create").change(function(){ controlEvent(this); });
//  equipos/edit: Evento para cargar los usuarios del area al seleccionar un area de este combo
$("select#areas_equipos_edit").change(function(){ controlEvent(this); });

//  MANTENIMIENTOS
//  mantenimiento/create: Evento para cargar los usuarios del area seleccionada de este combo
$("select#areas_mantenimiento_create").change(function(){ controlEvent(this); });
//  mantenimiento/create: Evento para cargar los equipos del usuario seleccionado de este combo
$("select#usuario_mantenimiento_create").change(function(){ controlEvent(this); });
//  mantenimiento/update: Evento para cargar los usuarios del area seleccionada de este combo
$("select#areas_mantenimiento_update").change(function(){ controlEvent(this); });
//  mantenimiento/update: Evento para cargar los equipos del usuario seleccionado de este combo
$("select#usuario_mantenimiento_update").change(function(){ controlEvent(this); });

/*
  $("select#id_equipo").change(function(){
    id = $("#id_equipo" ).val();
    getDetalleEquipo(id);
  });
*/
/*
//  Evento para cargar los equipos de un usuario en el form de create servicios
   $("select#user_create_service").change(function(){
    usuario = $("#user_create_service" ).val();
    if(usuario=="" || usuario=="0"){
      console.log("El usuario no existe.");
      return false;
    }else{
      cargar_equipos(usuario);
    }
  });
*/

//  Evento para agregar refacciones a la session en el modulo de servicios/aeguimiento
  $("#rfAdd").click(function(){
    event.preventDefault();
    cantidad = document.getElementById("cantidad").value;
    descripcion = document.getElementById("descripcion").value;
    if(cantidad == '' || descripcion == ''){
      alert("Se requiere cantidad y descripción.");
    }else{
      agregaRefaccion(cantidad,descripcion);
    }
  });

//  Evento para limpiar una session
  $("#rfClean").click(function(){
    event.preventDefault();
    eliminaSession();
  });
  /*
  //  Eventos para el modulo de mantenimiento
  $("select#areas_mantenimiento").change(function(){
    area = $("#areas_mantenimiento" ).val();
    if(area=="" || area=="0"){
      console.log("El area no existe.");
      return false;
    }else{
      cargar_usuarios(area);
    }
  });
*/
/*
  $("select#id_usuario").change(function(){
    usuario = $("#id_usuario" ).val();
    if(usuario=="" || usuario=="0"){
      console.log("El usuario no existe.");
      return false;
    }else{
      cargar_equipo(usuario);
    }
  });
*/

/*
  //  Modulo de asignacion de equipos
  $("select#areas_asignacion").change(function(){
    area = $("#areas_asignacion" ).val();
    cargarUsuarios(area);
    cargarEquipos(area);
  });
*/
}); //  Document Ready

//  CONTROL DE EVENTOS
function controlEvent(itemHtml){
  //  ItemHtml es el objeto de html donde se aplico el evento
  if(itemHtml.value == 0 || itemHtml.value == "" || itemHtml.value == null || itemHtml.value == undefined){
    console.log("Valor [null|0|'',undefined] en el evento: " + itemHtml.id + ". VALUE = " + itemHtml.value);
    return false;
  }else{
    console.log("Status: 200. Value: " + itemHtml.value + ". ID: " + itemHtml.id);
    //  Dependiendo el id de itemHtml se realizara una funcion especifica
    switch(itemHtml.id){
      case 'area_servicios_create':
        cargarCampoSelect("../users/getuserfromarea",itemHtml.value,"usuario_comun_servicios_create");
        break;
      case 'usuario_comun_servicios_create':
        cargarCampoSelect("../mantenimiento/getEquiposDeUsuario",itemHtml.value,"equipos_servicios_create");
        break;
      case 'areas_servicios_update':
        cargarCampoSelect("../mantenimiento/searchUsersFromArea",itemHtml.value,"usuario_comun_servicios_update");
        break;
      case 'usuario_comun_servicios_update':
        cargarCampoSelect("../mantenimiento/getEquiposDeUsuario",itemHtml.value,"equipos_servicios_update");
        break;
      case 'areas_equipos_create':
        cargarCampoSelect("../mantenimiento/searchUsersFromArea",itemHtml.value,"usuario_equipos_create");
        break;
      case 'areas_equipos_edit':
        cargarCampoSelect("../mantenimiento/searchUsersFromArea",itemHtml.value,"usuario_equipos_edit");
        break;
      case 'areas_mantenimiento_create':
        cargarCampoSelect("../mantenimiento/searchUsersFromArea",itemHtml.value,"usuario_mantenimiento_create");
        break;
      case 'usuario_mantenimiento_create':
        cargarCampoSelect("../mantenimiento/searchEquipoFromUsuario",itemHtml.value,"filas_equipo");
        break;
      case 'areas_mantenimiento_update':
        cargarCampoSelect("../mantenimiento/searchUsersFromArea",itemHtml.value,"usuario_mantenimiento_update");
        break;
      case 'usuario_mantenimiento_update':
        cargarCampoSelect("../mantenimiento/searchEquipoFromUsuario",itemHtml.value,"filas_equipo");
        break;
    }
  }
}

/*
        La funcion caragarCombo se usa para cargar un combo especifico como resultado de un evento de otro elemento html. Utiliza AJAX
        @ Parametros: 
        ruta (A donde se mandaran los datos), 
        dato (Dato que se va mandar. solo se acepta uno), 
        isSelectSalida (el id del select donde se agregara la respuesta).
      */
function cargarCampoSelect(ruta,dato,idSelectSalida){
  url = ruta+dato;
  $.ajax({
        url: url,
        type: 'POST',
        beforeSend: function() {
          //  Code before function
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            //console.log(respuesta);
            $("#"+idSelectSalida).html("");
            $("#"+idSelectSalida).html(respuesta);
          } else {
             console.log("Error en la petición: " + respuesta);
             $("#"+idSelectSalida).html("");
          }
       }
    });
}






/**
*
* Funcion cargar los equipos de un usuario seleccionado en la view servicios/create
  OK
*/
function cargar_equipos(usuario){
  url = '../equipos/cargar_equipos'+usuario; 
  $.ajax({
        url: url,
        type: 'POST',
        data: {'usuario':usuario},
        beforeSend: function() {
          //  Code before function
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            //console.log(respuesta);
            $("#id_equipo").html("");
            $("#id_equipo").html(respuesta);
          } else {
             //console.log(respuesta);
             $("#id_equipo").html("<option value=''>No hay equipos para este usuario.</option>");
          }
       }
    });
}

/**
* Funcion para cargar los equipos de un area determinada, esto era para el modulo de asignacion
*
*/
function cargarEquipos(area){
  url = '../equipos/getequiposfromarea'+area; 
  $.ajax({
        url: url,
        type: 'POST',
        data: {'area':area},
        beforeSend: function() {
          //  Code before function
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            //console.log(respuesta);
            $("#filas_equipo").html("");
            $("#filas_equipo").html(respuesta);
          } else {
             console.log(respuesta);
          }
       }
    });
}


/**
* Funcion para cargar los usuarios del area pasada como parametro
* 
*/
function cargarUsuarios(area){
  url = '../users/getuserfromarea'+area; 
  $.ajax({
        url: url,
        type: 'POST',
        data: {'area':area},
        beforeSend: function() {
          //  Code before function
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            //console.log(respuesta);
            $("#usuario_asignacion").html("");
            $("#usuario_asignacion").html(respuesta);
          } else {
             console.log(respuesta);
          }
       }
    });
}

/**
* Funcion para cargar los equipos de los usuarios en el modulo de alta de servicios
* 
*/
function getEquipos(usuario){
  url = '../mantenimiento/getEquiposDeUsuario'+usuario; 
  $.ajax({
        url: url,
        type: 'POST',
        data: {'id':usuario},
        beforeSend: function() {
          if(usuario=="" || usuario=="0"){
            console.log("El usuario no existe.");
            return false;
          }
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            console.log(respuesta);
            $("#id_equipo").html("");
            $("#id_equipo").html(respuesta);
          } else {
             console.log(respuesta);
          }
       }
    });
}
/**
* Funcion para cargar los equipo del usuario seleccionado
*/
function cargar_equipo(usuario){
  url = '../mantenimiento/searchEquipoFromUsuario'+usuario; 
  $.ajax({
        url: url,
        type: 'POST',
        data: {'id':usuario},
        beforeSend: function() {
          if(usuario=="" || usuario=="0"){
            console.log("El usuario no existe.");
            return false;
          }
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            console.log(respuesta);
            $("#filas_equipo").html("");
            $("#filas_equipo").html(respuesta);
          } else {
             console.log(respuesta);
          }
       }
    });
}
/**
* Funcion para cargar los usuarios en el modulo de mantenimiento 
  OK
*
*/
function cargar_usuarios(area){
  url = '../mantenimiento/searchUsersFromArea'+area; 
  $.ajax({
        url: url,
        type: 'POST',
        data: {'id':area},
        beforeSend: function() {
          if(area=="" || area=="0"){
            console.log("El area no existe.");
            return false;
          }
        },
        error: function(error) {
            console.log("Error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) {
            $("#id_usuario").html("");
            $("#id_usuario").append(respuesta);
          } else {
             console.log(respuesta);
          }
       }
    });
}


/**
*	Esta funcion busca un equipo por su id y lo retorna
*/
function getDetalleEquipo(id){
	url = '../equipos/search'+id;	
	$.ajax({
        url: url,
        type: 'POST',
        data: {'id':id},
        dataType: 'JSON',
        beforeSend: function() {
        	$("#respuesta").html('Buscando equipo...');
        },
        error: function() {
            $("#respuesta").html('Ha surgido un error.');
        },
        success: function(respuesta) {
          if (respuesta) {
          	$("#inventario").html(respuesta.no_inventario);
          	$("#marca").html(respuesta.marca);
          	$("#so").html(respuesta.so);
          	$("#modelo").html(respuesta.modelo);
          	$("#ram").html(respuesta.ram);
          	$("#hdd").html(respuesta.disco_duro);
          	$("#descripcion").html(respuesta.descripcion);
            $("#area").html(respuesta.area);
            $("#usuario").html(respuesta.usuario);
            $("#mac").html(respuesta.mac);
            $("#ip").html(respuesta.ip);
            $("#nodo").html(respuesta.nodo);
            $("#grupo_trabajo").html(respuesta.grupo_trabajo);
            $("#usuario_pc").html(respuesta.usuario_pc);
            estado = '';
            switch(respuesta.estado){
              case '0':
                estado = 'Requiere Sustitución';
              break;
              case '1':
                estado = 'Regular';
              break;
              case '2':
                estado = 'Bueno';
              break;
              case '3':
                estado = 'Excelente';
              break;
            }
            $("#estado").html(estado);
          } else {
            $("#respuesta").html('<div>No hay datoa para ese equipo. </div>');
          }
       }
    });
}

function agregaRefaccion(cantidad,descripcion){
  url = '../refacciones/addsession';
  $.ajax({
        url: url,
        type: 'POST',
        data: {'cantidad':cantidad,'descripcion':descripcion},
        dataType: 'JSON',
        beforeSend: function() {
            //console.log("Antes");
        },
        error: function(error) {
            //console.log("Ha surgido un error: " + error);
        },
        success: function(refacciones) {
          if (refacciones) {
            //console.log(refacciones);
            $("#tblRefacciones").html("");
            html = "";
            for (var i = 0; i < refacciones.length; i++) {
              html += "<tr class=\"small\">";
              html += "<td>"+refacciones[i]['cantidad']+"</td>";
              html += "<td>"+refacciones[i]['descripcion']+"</td>";
              //html += "<td><a href=\"#\" class=\"btn btn-danger btn-xs\" id=\"rfDelete\"> Eliminar </a></td>";
              html += "</tr>";
            };
            $("#tblRefacciones").append(html);
          } else {
            //console.log("No se encontraron datos");
          }
       }
    });
}

function eliminaSession(){
  url = '../refacciones/deletesession';
  $.ajax({
        url: url,
        type: 'POST',
        beforeSend: function() {
            //console.log("Antes");
        },
        error: function(error) {
            //console.log("Ha surgido un error: " + error);
        },
        success: function(respuesta) {
          if (respuesta) { 
            //console.log(respuesta);
            $("#tblRefacciones").html("");
          } else {
            console.log("No se encontraron datos");
          }
       }
    });
}