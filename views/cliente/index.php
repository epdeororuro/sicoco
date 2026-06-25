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

                    <!-- Datos del Contacto de Referencia -->
                    <h5 class="text-guindo border-bottom pb-2 mb-3 mt-4"><i class="fas fa-id-card"></i> Contacto de Referencia y Ubicación</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre del Referente</label>
                                <input type="text" class="form-control text-uppercase" id="txt_ref_nombre" name="txt_ref_nombre" placeholder="Nombre completo del familiar/referente">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parentesco / Relación</label>
                                <input type="text" class="form-control text-uppercase" id="txt_ref_parentesco" name="txt_ref_parentesco" placeholder="Ej. Hermano, Tío, Primo">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular Referente</label>
                                <input type="text" class="form-control" id="txt_ref_celular" name="txt_ref_celular" placeholder="Ej. 6040XXXX">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Dirección Referente</label>
                                <input type="text" class="form-control text-uppercase" id="txt_ref_direccion" name="txt_ref_direccion" placeholder="Calle, Nro, Zona del referente">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Coordenadas (Latitud, Longitud)</label>
                                <input type="text" class="form-control" id="txt_ref_coordenadas" name="txt_ref_coordenadas" placeholder="Ej. -17.96213,-67.12351">
                                <small class="form-text text-muted">Ej: -17.960241, -67.112105</small>
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
        
        // Cargar nuevos campos de referencia
        document.getElementsByName("txt_ref_nombre")[0].value = data.REF_NOMBRE ? data.REF_NOMBRE : "";
        document.getElementsByName("txt_ref_parentesco")[0].value = data.REF_PARENTESCO ? data.REF_PARENTESCO : "";
        document.getElementsByName("txt_ref_celular")[0].value = data.REF_CELULAR ? data.REF_CELULAR : "";
        document.getElementsByName("txt_ref_direccion")[0].value = data.REF_DIRECCION ? data.REF_DIRECCION : "";
        document.getElementsByName("txt_ref_coordenadas")[0].value = data.REF_COORDENADAS ? data.REF_COORDENADAS : "";
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

      // Visualización de Referencia en Swal
      $(document).on('click', '.VerReferencia', function(e) {
        e.preventDefault();
        var fila = $(this).closest("tr");
        var data = $("#TablaCliente").DataTable().row(fila).data();
        
        var mapaLink = '';
        if (data.REF_COORDENADAS) {
            mapaLink = '<br><br><a href="https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(data.REF_COORDENADAS) + '" target="_blank" class="btn btn-sm btn-success text-white"><i class="fas fa-map-marked-alt"></i> Ver en Google Maps</a>';
        }

        Swal.fire({
            title: '<strong class="text-guindo"><i class="fas fa-id-card"></i> Contacto de Referencia</strong>',
            icon: 'info',
            html:
                '<div class="text-left p-2 border rounded bg-light" style="font-size: 0.95rem; line-height: 1.6;">' +
                '<strong>Nombre del Referente:</strong> ' + (data.REF_NOMBRE || 'N/A') + '<br>' +
                '<strong>Relación / Parentesco:</strong> ' + (data.REF_PARENTESCO || 'N/A') + '<br>' +
                '<strong>Celular / Contacto:</strong> ' + (data.REF_CELULAR || 'N/A') + '<br>' +
                '<strong>Dirección de Domicilio:</strong> ' + (data.REF_DIRECCION || 'N/A') + '<br>' +
                '<strong>Coordenadas GPS:</strong> ' + (data.REF_COORDENADAS || 'N/A') + 
                mapaLink +
                '</div>',
            showCloseButton: true,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#701a27'
        });
      });
   });
</script>