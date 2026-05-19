//********* Contrato************
function CrudContrato(operacion)
{ const ruta=base_url+operacion;
  var op=operacion.split('/');
  var datos=$("#FormContrato").serialize();
  var msj;
  switch (op[1])
  {
    case 'add':  msj='Registro Insertado con Éxito'; break;
    case 'edit': msj='Registro Modificado con Éxito'; break;
    case 'delete': msj='Registro Eliminado con Éxito'; datos=null; break;
    case 'confirmar': msj='Contrato Confirmado con Éxito'; datos=null; break;    
  }
  AccionAjax(ruta, datos, ListarContrato, msj);
}// fin CrudContrato

function LimpiarCamposContrato()
{
  // pone en blanco todos los campos de ingreso de datos del contrato
  $('#SelBuscarCliente').val('0').trigger('change');

    document.getElementsByName("txt_actividad")[0].value = "";
    document.getElementsByName("txt_razon_social")[0].value = "Sin dato";
    document.getElementsByName("txt_contrato")[0].value = "";
    document.getElementsByName("txt_fecha_inicio")[0].value = "";
    document.getElementsByName("txt_tiempo")[0].value = "1";    
} // fin LimpiarCamposContrato()


function LlenarCliente()
{ 
  const ruta=base_url+'contrato/listar_clientes';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        $(e).each(function(i, v){ // indice, valor
                       $("#SelBuscarCliente").append('<option value="' + v.IDCLIENTE + '">' + v.BCLIENTE + '</option>');
                               });
     }
});  
} // final funcion llenar cliente combobox

function LlenarCatalogo()
{ 
  const ruta=base_url+'contrato/listar_catalogo';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        $(e).each(function(i, v){ // indice, valor
                       $("#SelBuscarCatalogo").append('<option value="' + v.IDCATALOGO + '">' + v.BESPACIO + '</option>');
                               });
     }
});  
} // final funcion Llenar_servicios combobox

function CrudDetalle(operacion)
{ const ruta=base_url+operacion;
  var op=operacion.split('/');  
  var msj;
  switch (op[1])
  {
    case 'addetalle':  msj='Registro Insertado con Éxito'; break;
    case 'del_detalle': msj='Registro Eliminado con Éxito'; break;
  }  
  AccionAjax(ruta, null, ListarContrato, msj);
}// fin CrudDetalle

function ListarDetalle(operacion){
  const ruta=base_url+operacion;  
  var boton_eliminar="<button type='button' class='EliminarDetalle btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>";
  
  $("#TablaDetalle").DataTable({     
    "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": ruta,
        "dataSrc":""       
      },
      "columns":[
      {"data": "IDDETALLE"},
      {"data": "IDARRIENDO"},
      {"data": "DISTRIBUCION"},
      {"data": "TIPO"},
      {"data": "DESCRIPCION"},
      {"data": "ALQUILER"},
      {"defaultContent":boton_eliminar}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion listarContrato

function ListarContrato(){
  var boton_editar="<button type='button' class='EditarContrato btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalContrato' ><i class='fas fa-edit'></i></button>";
  var boton_detalle="<button type='button' class='DetalleContrato btn btn-info btn-sm' data-toggle='modal' data-target='#ModalDetalle'><i class='fas fa-table'></i> Detalle</button>";
  var boton_confirmar="<button type='button' class='ConfirmarContrato btn btn-success btn-sm'><i class='fas fa-thumbs-up'></i> Confirmar</button>";
  var boton_eliminar="<button type='button' class='EliminarContrato btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>";
  
  $("#TablaContrato").DataTable({     
    "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": base_url+'contrato/listar',
        "dataSrc":""       
      },
      "columns":[
      {"data": "IDARRIENDO"},
      {"data": "IDCLIENTE"},
      {"data": "REPRESENTANTE"},
      {"data": "ACTIVIDAD"},
      {"data": "RAZONSOCIAL"},
      {"data": "CONTRATO"},
      {"data": "FECHA_INICIO"},
      {"data": "TIEMPOCONTRATO"},
      {"data": "MONTO"},
      {"defaultContent":boton_editar+" "+boton_detalle+" "+boton_confirmar+" "+boton_eliminar}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion listarContrato