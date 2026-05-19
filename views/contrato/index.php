
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users"></i> 
             <strong>Registros/Contratos</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalContrato">
                <i class="fas fa-plus-circle"></i> 
                 Nuevo Registro
               </button>
            </span>
            </ol>
          </div>
        </div>        
    </div><!-- /.container-fluid -->
  </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
             
              <!-- /.cargar el listado de la tabla -->
              <div class="card-body" id="listado">

                <table id="TablaContrato"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>IdCl</th>
                    <th>Cliente</th>
                    <th>Actividad</th>
                    <th>Razón Social</th>
                    <th>Contrato</th>
                    <th>Inicio</th>
                    <th>[Meses]</th>
                    <th>[Bs.]</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>IdCl</th>
                    <th>Cliente</th>
                    <th>Actividad</th>
                    <th>Razón Social</th>
                    <th>Contrato</th>
                    <th>Inicio</th>
                    <th>[Meses]</th>
                    <th>[Bs.]</th>
                    <th>Acciones</th>
                  </tr>
                  </tfoot>
                
                </table>

               </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
     
    </section>
    <!-- /.content -->
  

     <!-- Modal -->
        <div id="ModalContrato" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Contratos</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
            <div class="modal-body">
                <p class="statusMsg"></p>
              <form role="form" enctype="multipart/form-data" 
                id="FormContrato"  method="POST" autocomplete="off">
                    <div class="form-group">
                      <input  type="hidden"  id="txt_idcontrato" name="txt_idcontrato">
                    </div>

                    <div class="row bg-info text-dark" >
                      <div class="col-sm-12">
                          <label for="SelBuscarCliente">Datos Cliente</label>
                          <select id="SelBuscarCliente" 
                            name="SelBuscarCliente" class="form-control" style="width:100%">
                            <option value="0">Seleccionar..</option>
                          </select>                              
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-6">
                        <label for="txt_actividad">Descripción de la Actividad</label>
                        <input type="text" class="form-control" id="txt_actividad" name="txt_actividad" required  minlength="5" maxlength="100" 
                        placeholder="escriba la Actividad" >
                      </div>

                      <div class="col-sm-6">
                        <label for="txt_razon_social">Razón Social (SI corresponde)</label>
                        <input type="text" class=" form-control" id="txt_razon_social" value="Sin Dato" 
                        name="txt_razon_social" placeholder="Escriba la Razón Social si corresponde" required >
                      </div>
                    </div>  
                         
                    <div class="row">
                      <div class="col-sm-4">
                        <label for="txt_contrato">Contrato</label>
                        <input type="text" class="form-control" id="txt_contrato" name="txt_contrato" required  minlength="2" maxlength="30" placeholder="Nro-Gestión de Fecha:dd/mm/aaaa">
                      </div>

                      <div class="col-sm-4">
                        <label for="txt_fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control " 
                        id="txt_fecha_inicio" name="txt_fecha_inicio" required  minlength="10" maxlength="10" >
                      </div>
                      <div class="col-sm-4">
                        <label for="txt_tiempo">Tiempo de Contrato [Meses]</label>
                        <input type="number" class="form-control" id="txt_tiempo" name="txt_tiempo" value="1" step="1" required>
                      </div>
                    </div>

                </form>
              </div>

              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_InsertContrato" name="btn_InsertContrato" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarContrato" name="btn_EditarContrato" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->


<!-- Modal DETALLE-->
        <div id="ModalDetalle" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Detalle registro Alquiler</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
            <div class="modal-body">
                <p class="statusMsg"></p>
              <form role="form" enctype="multipart/form-data" 
                id="FormDetalle"  method="POST" autocomplete="off">
                    <div class="form-group">
                      <input  type="hidden"  id="txt_idcontrato1" name="txt_idcontrato1">
                    </div>

                    <div class="row bg-info text-dark" >
                      <div class="col-sm-12">
                          <label for="SelBuscarCatalogo">Referencia del Espacio | Servicio</label>
                          <select id="SelBuscarCatalogo" 
                            name="SelBuscarCatalogo" class="form-control" style="width:100%">
                            <option value="0">Seleccionar..</option>
                          </select>                              
                      </div>
                    </div>
                </form>
              </div>

              <div class="modal-footer" id="OpcionAdicionar">
                <button type="button" class="btn btn-success btn-lg " 
                  id="btn_Adicionar" name="btn_Adicionar" >
                  <span><i class="fas fa-save"></i></span>
                  Adicionar
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>
                <!--tabla  DETALLE-->

                 <div class="card-body" id="ListadoDetalle">

                <table id="TablaDetalle"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>IdCo</th>
                    <th>Ubicación</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Alquiler</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>IdCo</th>
                    <th>Ubicación</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Alquiler</th>
                    <th>Acciones</th>
                  </tr>
                  </tfoot>
                
                </table>

               </div>


                  <!--FIN tabla DETALLE-->


            </div>
          </div>
        </div>  <!--FIN Modal DETALLE-->

<script src="<?php echo URL; ?>views/contrato/contrato.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_InsertContrato").on('click', function(e){
      e.preventDefault();
       CrudContrato('contrato/add');
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_EditarContrato").on('click', function(e){
      e.preventDefault();
       CrudContrato('contrato/edit');
    });
  });
</script>


<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Contratos");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposContrato();
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarContrato();
    $("#SelBuscarCliente").select2(LlenarCliente());
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarContrato', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");

$('#SelBuscarCliente').val($(this).parents("tr").find("td").eq(1).html()).trigger('change');

 document.getElementsByName("txt_idcontrato")[0].value=$(this).parents("tr").find("td").eq(0).html();

  document.getElementsByName("txt_actividad")[0].value=$(this).parents("tr").find("td").eq(3).html();
 
 document.getElementsByName("txt_razon_social")[0].value=$(this).parents("tr").find("td").eq(4).html();
 document.getElementsByName("txt_contrato")[0].value=$(this).parents("tr").find("td").eq(5).html();
 document.getElementsByName("txt_fecha_inicio")[0].value=$(this).parents("tr").find("td").eq(6).html();
 document.getElementsByName("txt_tiempo")[0].value=$(this).parents("tr").find("td").eq(7).html();

});
</script>


<script type="text/javascript">
// selecciona un registro para HABILITAR EL REGISTRO DE DETALLE

  $(document).on('click', '.DetalleContrato', function(e){
 e.preventDefault();

// funcion para limpiar el contenido del combobox y no se sobre cargue y repita los items
 $("#SelBuscarCatalogo").html('');


$("#SelBuscarCatalogo").select2(LlenarCatalogo());
 
$('#SelBuscarCatalogo').val('0').trigger('change');

 document.getElementsByName("txt_idcontrato1")[0].value=$(this).parents("tr").find("td").eq(0).html();
ListarDetalle('contrato/listar_detalle/'+ document.getElementsByName("txt_idcontrato1")[0].value);
});
</script>

<script type="text/javascript">
  $(document).ready(function(){
   $("#btn_Adicionar").on('click', function(e){
      e.preventDefault();
     CrudDetalle('contrato/addetalle/'+document.getElementsByName("txt_idcontrato1")[0].value+'-'+ $('#SelBuscarCatalogo').val());

      ListarDetalle('contrato/listar_detalle/'+ document.getElementsByName("txt_idcontrato1")[0].value);
    });
  });
</script>

<script type="text/javascript">
// selecciona un registro para eliminar detalle
  $(document).on('click', '.EliminarDetalle', function(e){
 e.preventDefault();
 
  CrudDetalle('contrato/del_detalle/'+$(this).parents("tr").find("td").eq(0).html());
                            
         ListarDetalle('contrato/listar_detalle/'+ document.getElementsByName("txt_idcontrato1")[0].value);
});
</script>




<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarContrato', function(e){
 e.preventDefault();
 var registro=$(this).parents("tr").find("td").eq(5).html()+' '+$(this).parents("tr").find("td").eq(3).html();
   //registro=registro+' cedula:'+$(this).parents("tr").find("td").eq(3).html();
    Swal.fire({
      title: 'Está seguro de Eliminar este Registro?',
      text: registro+" / Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar el Registro!'
    }).then((result) => {
                          if (result.isConfirmed) {
                          CrudContrato('contrato/delete/'+$(this).parents("tr").find("td").eq(0).html());
                            }
                       })
});
</script>

<script type="text/javascript">
// selecciona un registro para confirmar
  $(document).on('click', '.ConfirmarContrato', function(e){
 e.preventDefault();
 var registro=$(this).parents("tr").find("td").eq(5).html()+' '+$(this).parents("tr").find("td").eq(3).html();
   //registro=registro+' cedula:'+$(this).parents("tr").find("td").eq(3).html();
    Swal.fire({
      title: 'Está seguro de Confirmar este Registro de Contrato?',
      text: registro+" / Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar Contrato!'
    }).then((result) => {
                          if (result.isConfirmed) {
                          CrudContrato('contrato/confirmar/'+$(this).parents("tr").find("td").eq(0).html());
                            }
                       })
});
</script>