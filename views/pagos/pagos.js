//**********FUNCIONES PAGOS

function PanelPagos(op)
{
   $("#lst_contratos").hide(); //ocultar buscador contratos
   $("#lst_pagos").hide(); //ocultar buscador pagos
   $("#DetallePagoPeriodo").hide(); //ocultar formulario de pago

 switch (op)
 {
 case 0:
   $("#lst_contratos").show("slow"); // mostrar contratos
   document.getElementById("TextAreaContrato").value="Contratos Vigentes";
   break;
 case 1:
   $("#lst_pagos").show("slow"); // mostrar pagos
   break;   
 case 2:
   $("#DetallePagoPeriodo").show("slow"); // mostrar detalle de pago
   break;   
 }
    
    
}

function ListarResumenContrato(){
  //var boton_editar="<button type='button' class='EditarCliente btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalCliente' ><i class='fas fa-edit'></i></button>";
  var boton_seleccionar="<button type='button' class='SeleccionarContrato btn btn-success btn-sm'><i class='fas fa-table'></i> Seleccionar</button>";
  $("#TablaResumenContrato").DataTable({     
 
   "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": false,

      "ajax":{
        "url": base_url+'pagos/listar',
        "dataSrc":""
      },
      "columns":[
      {"data": "IDARRIENDO"},
      {"data": "GENERAL"},
      {"data": "REFERENCIAL"},
      {"data": "ESPECIFICO"},
      
      {"defaultContent": boton_seleccionar}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion ListarResumenContrato

function ListarPagos(id){
  //var boton_editar="<button type='button' class='EditarCliente btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalCliente' ><i class='fas fa-edit'></i></button>";
  var boton_seleccionar="<button type='button' class='SeleccionarPago btn btn-warning btn-sm'><i class='fa fa-money-bill'></i> Pagar</button>";
  $("#TablaListaPagos").DataTable({     
 
   "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": true,

      "ajax":{
        "url": base_url+'pagos/listar_pagos/'+id,
        "dataSrc":""
      },
      "columns":[
      {"data": "IDARRIENDO"},
      {"data": "IDPAGO"},
      {"data": "PERIODO"},
      {"data": "MONTO"},
      {"data": "PENDIENTE"},
      
      {"defaultContent": boton_seleccionar}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion ListarPagos

function Listar_Detalle_Pago_Periodo(id){
  //var boton_editar="<button type='button' class='EditarCliente btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalCliente' ><i class='fas fa-edit'></i></button>";
  
  $("#TablaDetalleContrato").DataTable({     
 
   "responsive":true,
   "searching": false,
   "destroy":true, 
   "order": [],
   "paging": false,
   "autoWidth": true,

      "ajax":{
        "url": base_url+'pagos/listar_detalle/'+id,
        "dataSrc":""
      },
      "columns":[
      {"data": "DISTRIBUCION"},
      {"data": "TIPO"},
      {"data": "DESCRIPCION"},
      {"data": "ALQUILER"},      
      ],
      buttons: [ ]       
    }); 
} // fin de funcion ListarPagos