//***** funciones de cliente

function Insert_Cliente(){
  AccionAjax(base_url+'cliente/add', $("#FormCliente").serialize(), ListarCliente, 'Registro Insertado Con Éxito');
} // final funcion Insert_Cliente

function LimpiarCamposCliente()
{
    // pone en blanco todos los campos de ingreso de datos del cliente
    document.getElementsByName("txt_nombre")[0].value = "";
    document.getElementsByName("txt_cedula")[0].value = "";
    document.getElementsByName("txt_contactos")[0].value = "";
    document.getElementsByName("txt_direccion")[0].value = "";    
    document.getElementsByName("txt_ref_nombre")[0].value = "";
    document.getElementsByName("txt_ref_parentesco")[0].value = "";
    document.getElementsByName("txt_ref_celular")[0].value = "";
    document.getElementsByName("txt_ref_direccion")[0].value = "";
    document.getElementsByName("txt_ref_coordenadas")[0].value = "";
} // fin LimpiarCamposCliente()

function Editar_Cliente(){
  AccionAjax(base_url+'cliente/edit', $("#FormCliente").serialize(), ListarCliente, 'Registro Modificado Con Éxito');
} // final funcion Editar_Cliente

function EliminarCliente(id)
{
  AccionAjax(base_url+'cliente/delete/'+id, null, ListarCliente, 'Registro Eliminado con Éxito');
} // fin eliminar Cliente

function ListarCliente(){
  $("#TablaCliente").DataTable({     
 
   "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": base_url+'cliente/listar',
        "dataSrc":""
      },
      "columns":[
      {"data": null, "className": "text-center font-weight-bold", "render": function (data, type, row, meta) { return meta.row + 1; }},
      {"data": "NOMBRE", "render": function(data){ return "<strong>" + data + "</strong>"; }},
      {"data": "CEDULA", "className": "text-center"},
      {"data": "CONTACTOS", "className": "text-center"},
      {"data": "DIRECCION"},      
      {"data": null, "className": "text-center text-nowrap", "render": function(data, type, row){
          var btn_ref_class = row.REF_NOMBRE ? 'btn-info' : 'btn-outline-secondary';
          var btn_ref_title = row.REF_NOMBRE ? 'Ver Contacto de Referencia' : 'Sin Referencia Registrada';
          var btn_ref_attr = row.REF_NOMBRE ? '' : 'disabled';
          
          var boton_referencia="<button type='button' class='VerReferencia btn " + btn_ref_class + " btn-sm mx-1' " + btn_ref_attr + " title='" + btn_ref_title + "'><i class='fas fa-info-circle'></i></button>";
          var boton_editar="<button type='button' class='EditarCliente btn btn-warning btn-sm mx-1' data-toggle='modal' data-target='#ModalCliente' title='Editar Cliente'><i class='fas fa-edit'></i></button>";
          var boton_eliminar="<button type='button' class='EliminarCliente btn btn-danger btn-sm mx-1' title='Eliminar Cliente'><i class='fas fa-trash'></i></button>";
          return boton_referencia + boton_editar + boton_eliminar;
      }}
      ]
    }); 
} // fin de funcion listarCliente