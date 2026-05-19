
  <section class="content-header">
      <div class="container-fluid">
        <div class="row">         
            <i class="fas fa-users"></i> 
             <strong>Registros/Pagos</strong>          
        </div>        
    </div><!-- /.container-fluid -->
  </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid" id="ReportePago">
      <div class="card-header">
       <table id="Tablacabecera"  class="table table-bordered">
        <thead class=" table-primary">
         <tr>
           <th><h6> 
             <p class="font-weight-bold text-center">DESCRIPCIÓN CONTRATO VIGENTE</p> 
             </h6>
           </th>
         </tr>
         </thead>
         <tbody>
           <tr>
            <th> <textarea class="form-control " id="TextAreaContrato" name="TextAreaContrato" rows="3" disabled></textarea>
              </th>
           </tr>
         
         </tbody>
          
            </table>
    
  </div>

        <div class="row">
          <div class="col-12" id="lst_contratos">            
            <table id="TablaResumenContrato"  
                   class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-primary">
                  <tr>
                    <th>Id</th>
                    <th>Datos Generales</th>
                    <th>Datos Referenciales</th>
                    <th>Datos Específicos</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                <tfoot class=" table-primary">
                  <tr>
                    <th>Id</th>
                    <th>Datos Generales</th>
                    <th>Datos Referenciales</th>
                    <th>Datos Específicos</th>
                    <th>Acciones</th>
                  </tr>
                </tfoot>                
            </table>
          </div>
        </div>
       
<div class="row">
       <div class="col-12" id="lst_pagos">
        
            <table id="TablaListaPagos"  
                   class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">

                  <tr> 
                  <th>IdArriendo</th>                   
                    <th>IdPago</th>
                    <th>Periodo</th>
                    <th>Monto</th>
                    <th>Pendiente</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                 
                <tfoot class=" table-primary">
                  <tr>    
                  <th>IdArriendo</th>                
                    <th>IdPago</th>
                    <th>Periodo</th>
                    <th>Monto</th>
                    <th>Pendiente</th>
                    <th>Acción</th>
                  </tr>
                </tfoot>                
            </table>
          </div>
        </div>

<div class="row">

<div class="col-12 " id="DetallePagoPeriodo">
  <div class="card-header ">
   <h6> 
    <p class="font-weight-bold text-center">COMPROBANTE DE PAGO</p> 
  </h6>
  </div>
            <table id="TablaDetalleContrato"  
                   class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">

                  <tr>
                    <th>Distribucion</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Alquiler</th>                    
                  </tr>
                </thead>
                 
                <tfoot class=" table-primary">
                  <tr>
                    <th>Distribucion</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Alquiler</th>
                  </tr>
                </tfoot>                
            </table>
    <div class="card-header">
     <h6> 
      <p class="font-weight-bold text-center">DESCRIPCIÓN DE PAGO</p></h6>
     <input class="form-control" type="text" id="PagoPeriodo" name="PagoPeriodo" disabled>    
    </div>

<div class="content-header">
<button type="button" id="btnImprimirComprobante" class="btn btn-primary btn-lm" onclick="window.print()"> <i class="fas fa-print"></i>
    Imprimir Comprobante
    </button>

  <a href="<?php echo URL; ?>pagos" class="btn btn-danger btn-lg active" role="button" aria-pressed="true">Salir</a>
</div>

  </div>

      </div>

    </div>
      <!-- /.container-fluid -->     
    </section>
    <!-- /.content -->

<script src="<?php echo URL; ?>views/pagos/pagos.js"></script>
  

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_EditarCliente").on('click', function(e){
      e.preventDefault();
       Editar_Cliente();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Clientes");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposCliente();
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
  //  $("#lst_pagos").hide(); //ocultar pagos
   // $("#lst_contratos").show("slow"); // mostrar buscador contratos
    PanelPagos(0);
    ListarResumenContrato();
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarCliente', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");

 document.getElementsByName("txt_idcliente")[0].value=$(this).parents("tr").find("td").eq(0).html();
 document.getElementsByName("txt_nombre")[0].value=$(this).parents("tr").find("td").eq(1).html();
 document.getElementsByName("txt_cedula")[0].value=$(this).parents("tr").find("td").eq(2).html();
 document.getElementsByName("txt_contactos")[0].value=$(this).parents("tr").find("td").eq(3).html();
 document.getElementsByName("txt_direccion")[0].value=$(this).parents("tr").find("td").eq(4).html();

});
</script>

<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.SeleccionarContrato', function(e){
 e.preventDefault();
 var registro=$(this).parents("tr").find("td").eq(1).html()+ ' ** '+ 
 $(this).parents("tr").find("td").eq(2).html()+ ' ** '+
 $(this).parents("tr").find("td").eq(3).html();
    Swal.fire({
      title: 'Selección de Contrato para Registro de Pagos',
      text: registro+" / El sistema desplegará detalle de PAGOS PENDIENTES",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Desplegar Detalle?'
    }).then((result) => {
                          if (result.isConfirmed) {
                           PanelPagos(1); 
           ListarPagos($(this).parents("tr").find("td").eq(0).html());

  document.getElementById("TextAreaContrato").value=registro;
document.getElementById("EtiquetaPago").value=registro;
  
                            }
                       })
});
</script>

<script type="text/javascript">
  $(document).on('click', '.SeleccionarPago', function(e){
 e.preventDefault();
 var registro=' *** '+$(this).parents("tr").find("td").eq(2).html()+' ***' + ' IMPORTE Bs. '+$(this).parents("tr").find("td").eq(3).html();
    Swal.fire({
      title: 'Registrar Período de Pago?',
      text: "PERÍODO " + registro,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Registrar'
    }).then((result) => {
                            if (result.isConfirmed) {
                             PanelPagos(2);
                          //   EjecutarPago($(this).parents("tr").find("td").eq(2).html());  
                          document.getElementsByName("PagoPeriodo")[0].value='Período de Pago: '+registro+' *** usuario: w.arroyo ***' ;  
                             Listar_Detalle_Pago_Periodo($(this).parents("tr").find("td").eq(0).html());
                            }
                       })
});
</script>
