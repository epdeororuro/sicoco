// ********* FUNCION UTILITARIA PARA DATATABLES *********
function initDataTable(selector, url, columns, extraOptions = {}) {
  var defaultOptions = {
    responsive: true,
    destroy: true,
    order: [],
    autoWidth: false,
    ajax: {
      url: url,
      dataSrc: ""
    },
    columns: columns
  };
  var finalOptions = $.extend(true, {}, defaultOptions, extraOptions);
  return $(selector).DataTable(finalOptions);
}

//********* Categorias de productos************
function CrudCategoria(operacion)
{ const ruta=base_url+operacion;
  var op=operacion.split('/');
  var datos=$("#FormCategoria").serialize();
  var msj;
  switch (op[1])
  {
    case 'add':  msj='Registro Insertado con Éxito'; break;
    case 'edit': msj='Registro Modificado con Éxito'; break;
    case 'delete': msj='Registro Eliminado con Éxito'; datos=null; break;
  }
  var callback = (op[0] === 'categoria') ? ListarCategoria : ListarCategoriaEnsamble;
  AccionAjax(ruta, datos, callback, msj);
}// fin CrudCategoria

function RetirarCategoria(id, operacion)
{
  var ruta, msj;
  switch (operacion)
  {
    case 'RETIRAR':
        ruta=base_url+'categoria/retirar/'+id;
        msj='Registro de Categoría fué Retirado con Éxito';
    break;
    case 'HABILITAR':
        ruta=base_url+'categoria/habilitar/'+id;
        msj='El Registro de Categoría fué Habilitado Nuevamente';
    break;
  }  
 $.ajax({
      url:ruta,
      success: function(e){  
        var respuesta=e.split('-');
          if(respuesta[0]=='1')
        {
          switch(respuesta[1])
          {
            case 'ARTICULO':
              ListarCategoria(); 
            break;
            case 'ENSAMBLE':
              ListarCategoriaEnsamble();
            break;
          }
          Swal.fire(msj,'', 'success');   
        }
        else
           Swal.fire(e,'', 'error');
      }
  });
} // fin eliminar categoria

function ListarCategoria(){
  var boton_editar="<button type='button' class='EditarCategoria btn btn-warning btn-lm' data-toggle='modal' data-target='#ModalCategoria' ><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button type='button' class='EliminarCategoria btn btn-danger btn-lm'><i class='fas fa-trash'></i></button>";      
  var boton_retiro="<button type='button' class='BajaCategoria btn btn-info btn-lm'><i class='fas fa-thumbs-down '></i>Retirar</button>";      
  var boton_habilitar="<button type='button' class='HabilitaCategoria btn btn-success btn-lm'><i class='fas fa-thumbs-up  '></i>Habilitar</button>";      
  
  initDataTable("#TablaCategoria", base_url+'categoria/listar', [
    {"data": "IDCATEGORIA"},
    {"data": "DESCRIPCION"},
    {"data": "FECHA_CREACION"},
    {"data": "FECHA_RETIRO"},
    {"data": "VIGENTE", "render": function (data) {
      return '<h4><label class="bg-' + (data === 'SI' ? 'success' : 'danger') + '">' + data + '</label></h4>';
    }},
    {"data":"VIGENTE", "render": function (data) {
      return data === 'SI' ? boton_retiro + " " + boton_editar + " " + boton_eliminar : boton_habilitar;
    }}
  ]);
} // fin de funcion listarCategoria


//**********FUNCIOINES CATEGORIA DE ENSAMBLES
// proyecto anterior para borrar

function ListarCategoriaEnsamble(){
  var boton_editar="<button title='Editar Categoría de Ensamble' type='button' class='EditarCategoria btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalCategoria' ><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button title='Eliminar Categoría Ensamble' type='button' class='EliminarCategoria btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>";      
  var boton_retiro="<button title='Dar de Baja la Categoría' type='button' class='BajaCategoria btn btn-info btn-sm'><i class='fas fa-thumbs-down '></i></button>";      
  var boton_habilitar="<button title='Hablitar Categoría' type='button' class='HabilitaCategoria btn btn-success btn-sm'><i class='fas fa-thumbs-up  '></i></button>";
  var boton_componente="<button title='Componentes del Ensamble' type='button' class='ComponenteEnsamble btn btn-warning btn-sm' ><i class='fas fa-cogs'></i></button>";

  initDataTable("#TablaCategoria", base_url+'catensamble/listar', [
    {"data": "IDCATEGORIA"},
    {"data": "DESCRIPCION"},      
    {"data": "FECHA_CREACION"},
    {"data": "FECHA_RETIRO"},
    {"data": "VIGENTE", "render": function (data) {
      return '<h4><label class="bg-' + (data === 'SI' ? 'success' : 'danger') + '">' + data + '</label></h4>';
    }},
    {"data":"VIGENTE", "render": function (data) {
      return data === 'SI' ? boton_retiro + " " + boton_editar + " " + boton_eliminar + " " + boton_componente : boton_habilitar;
    }}     
  ]);
} // fin de funcion listarCategoriaEnsamble

// *********** funcionies de compenentes de ensambles********+
function ListarComponenteEnsamble(id){
  var boton_eliminar="<button title='Quitar Componente de Ensamble' type='button' class='QuitarComponente btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>";      
  
  initDataTable("#TablaComponente", base_url+'catensamble/listar_componente/'+id, [
    {"data": "ID_DET_PROD"},
    {"data": "COMPONENTE"},
    {"data": "PRECIO"},
    {"data": "CANTIDAD"},
    {"defaultContent": boton_eliminar }
  ], { responsive: false, buttons: [] }); // Oculta botones para componente
} // fin de funcion listarCategoriaEnsamble

// ********funciones roles**********

function LlenarBuscadorPersonas()
{ 
  const ruta=base_url+'rol/listar_operador';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        $(e).each(function(i, v){ // indice, valor
                       $("#SelBuscarPersona").append('<option value="' + v.ID + '">' + v.NOMBRE + '</option>');
                               });
     }
}); 
} // final funcion LlenarBuscadorPersonas

function InsertEncargados(){
  AccionAjax(base_url+'rol/add', $("#FormEncargado").serialize(), ListarEncargado, 'Registro Insertado Con Éxito');
} // final funcion InsertEncargado

function LimpiarCamposEncargado(){
// pone en blanco todos los campos de registro de encargado
 $('#SelBuscarPersona').val(null).trigger('change');
 document.getElementsByName("txt_usuario")[0].value="";
 document.getElementsByName("txt_rol")[0].value="0";
}

function EliminarEncargado(id)
{
  AccionAjax(base_url+'rol/delete/'+id, null, ListarEncargado, 'Registro Eliminado con Éxito');
} // fin eliminar categoria

function BajaEncargado(id)
{
  AccionAjax(base_url+'rol/baja/'+id, null, ListarEncargado, 'Se registró la BAJA de Usuario con Éxito');
} // fin dar -baja

function ListarEncargado(){
 // var boton_editar="<button type='button' class='EditarEncargado btn btn-warning btn-lm' data-toggle='modal' data-target='#ModalEncargado' ><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button type='button' class='EliminarEncargado btn btn-danger btn-lm'><i class='fas fa-trash'></i></button>";      
  var boton_baja="<button type='button' class='BajaEncargado btn btn-info btn-lm'><i class='fas fa-thumbs-down '></i>Baja</button>";      

  initDataTable("#TablaEncargado", base_url+'rol/listar', [
    {"data": "NRO"},
    {"data": "IDENCARGADO"},
    {"data": "NOMBRE"},
    {"data": "USUARIO"},
    {"data": "ROL"},
    {"data": "ALTA"},
    {"data": "BAJA"},
    {"data": "ACTIVO", "render": function (data) {
      return '<h4><label class="bg-' + (data === 'SI' ? 'success' : 'danger') + '">' + data + '</label></h4>';
    }},
    {"data": "ACTIVO", "render": function (data) {
      return data === 'SI' ? boton_baja + " " + boton_eliminar : '<h4><label class= "bg-danger">--</label></h4>';
    }}
  ]); 
} // fin de funcion listarRol


//******* funciones articulo*************
function CrudArticulo(operacion)
{ const ruta=base_url+operacion;
  var op=operacion.split('/');
  var datos=$("#FormArticulo").serialize();
  var msj;
  switch (op[1])
  {
    case 'add':  msj='Registro Insertado con Éxito'; break;
    case 'edit': msj='Registro Modificado con Éxito'; break;
    case 'cambio': msj='Registro Modificado con Éxito'; break;
    case 'delete': msj='Registro Eliminado con Éxito'; datos=null; break;
  }
  //alert(ruta+'-->los datos enviados son: '+datos)
  var callback = (op[0] === 'articulo') ? ListarArticulo : ListarEnsamble;
  AccionAjax(ruta, datos, callback, msj);
}// fin CrudCategoria

function LlenarBuscadorCategoria(op)
{ var ruta=base_url;
  switch(op)
  {
    case 1:
      ruta=ruta+'articulo/listar_categoria';
    break;
    case 2:
      ruta=ruta+'ensambles/listar_categoria';
    break;
  }

  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        $(e).each(function(i, v){ // indice, valor
                       $("#SelBuscarCategoria").append('<option value="' + IDAREA + '">' + DISTRIBUCCION + '</option>');
                               });
     }
}); 
} // final funcion LlenarBuscadorPersonas

function BloquearDatos(op)
{
  // funcion para bloquear los campos de ingreso de datos segun corresponda las opciones seleccionadas
  $('#panel1 :input').prop('disabled', false);
  $('#panel2 :input').prop('disabled', false);
  $("#DivComboBox").show("slow");
  switch (op)
  {
    case 1:
     //editar
     $("#DivComboBox").hide();
    break;
    case 2:
    // cambio de categoria
      $('#panel1 :input').prop('disabled', true);
      $('#panel2 :input').prop('disabled', true);
      $("#DivComboBox").show("slow");
    break;    
  }
} //fin BloquearDatos

function ListarArticulo(){
  var boton_editar="<button type='button' class='EditarArticulo btn btn-warning btn-lm' data-toggle='modal' data-target='#ModalArticulo' ><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button type='button' class='EliminarArticulo btn btn-danger btn-lm'><i class='fas fa-trash'></i></button>";      
  var boton_cambio="<button type='button' class='CambioCategoria btn btn-info btn-lm' data-toggle='modal' data-target='#ModalArticulo' ><i class='fas fa-window-restore'></i>Categoría</button>";
  
  initDataTable("#TablaArticulo", base_url+'articulo/listar', [
    {"data": "IDARTICULO"},
    {"data": "C_DESCRIPCION"},
    {"data": "DESCRIPCION"},      
    {"data": "MINIMO"},
    {"data": "CODBARRA"},      
    {"defaultContent": boton_cambio + " " + boton_editar + " " + boton_eliminar}         
  ]);
} // fin de funcion listarArticulo



function ListarEnsamble(){
  var boton_editar="<button title='Componentes del Ensamble' type='button' class='ComponenteEnsamble btn btn-warning btn-lm' ><i class='fas fa-cogs'></i></button>";
  var boton_eliminar="<button title='Eliminar Ensamble' type='button' class='EliminarArticulo btn btn-danger btn-lm'><i class='fas fa-trash'></i></button>";      
  var boton_cambio="<button title='Cambiar Categoría de Ensamble' type='button' class='CambioCategoria btn btn-info btn-lm' data-toggle='modal' data-target='#ModalArticulo' ><i class='fas fa-window-restore'></i></button>";
  
  initDataTable("#TablaArticulo", base_url+'ensambles/listar', [
    {"data": "IDARTICULO"},
    {"data": "C_DESCRIPCION"},
    {"data": "DESCRIPCION"},      
    {"data": "MINIMO"},
    {"data": "CODBARRA"},      
    {"defaultContent": boton_cambio},
    {"defaultContent": boton_editar},
    {"defaultContent": boton_eliminar}         
  ]);
} // fin de funcion listarArticulo
