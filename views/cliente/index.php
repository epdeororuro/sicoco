  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users text-guindo"></i> 
             <strong>Postulaciones / Directorio de Clientes</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-guindo font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalCliente">
                <i class="fas fa-plus-circle"></i> 
                 Registrar Nuevo Cliente
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
            <div class="card card-outline card-guindo">
             
              <!-- /.cargar el listado de la tabla -->
              <div class="card-body" id="listado">

                <table id="TablaCliente"  class="table table-bordered table-striped table-hover table-sm" style="width: 100%;">
                 <thead class="bg-guindo">
                  <tr>
                    <th class="text-center" style="width: 5%;">N°</th>
                    <th style="width: 30%;">Nombre Completo</th>
                    <th class="text-center" style="width: 15%;">Cédula / NIT</th>
                    <th class="text-center" style="width: 15%;">Contactos</th>
                    <th style="width: 20%;">Dirección</th>
                    <th class="text-center" style="width: 15%;">Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class="bg-guindo">
                  <tr>
                    <th class="text-center">N°</th>
                    <th>Nombre Completo</th>
                    <th class="text-center">Cédula / NIT</th>
                    <th class="text-center">Contactos</th>
                    <th>Dirección</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
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
        <div id="ModalCliente" class="modal fade" role="dialog">

          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header bg-guindo text-white">
                <h4 class="modal-title" id="titulo">Registrar Nuevo Cliente</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>
              
            <div class="modal-body">
                <p class="statusMsg"></p>
              <form role="form" id="FormCliente" method="POST" autocomplete="off">
                    <div class="form-group">
                      <input type="hidden" id="txt_idcliente" name="txt_idcliente">
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nombre Completo / Razón Social <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="txt_nombre" name="txt_nombre" placeholder="Ej. JUAN PEREZ" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cédula / NIT <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txt_cedula" name="txt_cedula" placeholder="Ej. 1234567" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contactos / Celular <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txt_contactos" name="txt_contactos" placeholder="Ej. 77712345" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Dirección <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="txt_direccion" name="txt_direccion" placeholder="Ej. ZONA CENTRAL CALLE X" required>
                            </div>
                        </div>
                    </div>

                </form>
              </div>

              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_InsertCliente" name="btn_InsertCliente" data-dismiss="modal" onclick="Insert_Cliente();">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarCliente" name="btn_EditarCliente" data-dismiss="modal" onclick="Editar_Cliente();">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->

<script src="<?php echo URL; ?>views/cliente/cliente.js"></script>

<script type="text/javascript">
   $(document).ready(function(){
      // Llamamos a la función de cliente.js para que dibuje la tabla
      ListarCliente();

      // Configuración del modal para "Nuevo Registro"
      $("#btnNuevoRegistro").on('click', function(e){
        e.preventDefault();
        $("#titulo").html("Registrar Nuevo Cliente");
        $("#OpcionEditar").hide();
        $("#OpcionNuevo").show("slow");
        LimpiarCamposCliente();
        document.getElementsByName("txt_idcliente")[0].value = "";
      });

      // Configuración del modal para "Editar Registro"
      $(document).on('click', '.EditarCliente', function(e){
        e.preventDefault();
        $("#titulo").html("Modificar Registro");
        $("#OpcionNuevo").hide();
        $("#OpcionEditar").show("slow");

        // Obtener la fila de la tabla para extraer datos
        var fila = $(this).closest("tr");
        var data = $("#TablaCliente").DataTable().row(fila).data();

        // Llenar los campos con la data recuperada
        document.getElementsByName("txt_idcliente")[0].value = data.IDCLIENTE;
        document.getElementsByName("txt_nombre")[0].value = data.NOMBRE;
        document.getElementsByName("txt_cedula")[0].value = data.CEDULA;
        document.getElementsByName("txt_contactos")[0].value = data.CONTACTOS;
        document.getElementsByName("txt_direccion")[0].value = data.DIRECCION;
      });

      // Eliminación del Registro
      $(document).on('click', '.EliminarCliente', function(e){
        e.preventDefault();
        var fila = $(this).closest("tr");
        var data = $("#TablaCliente").DataTable().row(fila).data();

        Swal.fire({
          title: 'Está seguro de Eliminar este Registro?',
          text: data.NOMBRE + " / Esta operación NO podrá Revertirse",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Eliminar el Registro!'
        }).then((result) => {
          if (result.isConfirmed) {
            EliminarCliente(data.IDCLIENTE);
          }
        });
      });
   });
</script>