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
} // fin LimpiarCamposCliente()

function Editar_Cliente(){
  AccionAjax(base_url+'cliente/edit', $("#FormCliente").serialize(), ListarCliente, 'Registro Modificado Con Éxito');
} // final funcion Editar_Cliente

function EliminarCliente(id)
{
  AccionAjax(base_url+'cliente/delete/'+id, null, ListarCliente, 'Registro Eliminado con Éxito');
} // fin eliminar Cliente

function ListarCliente(){
  var boton_editar="<button type='button' class='EditarCliente btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalCliente' ><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button type='button' class='EliminarCliente btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>";
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
      {"data": "IDCLIENTE"},
      {"data": "NOMBRE"},
      {"data": "CEDULA"},
      {"data": "CONTACTOS"},
      {"data": "DIRECCION"},      
      {"defaultContent": boton_editar+" "+boton_eliminar}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion listarCliente