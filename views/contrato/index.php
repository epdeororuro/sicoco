
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

                    <!-- SECCIÓN 1: ÍTEM A ARRENDAR -->
                    <h5 class="text-guindo border-bottom pb-2 mb-3">1. Detalle del Arrendamiento</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seleccionar Área</label>
                                <select class="form-control select2" id="SelArea" name="SelArea" style="width: 100%;" required>
                                    <option value="0">-- Seleccione un Área --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Seleccionar Ítem del Catálogo</label>
                                <select class="form-control select2" id="SelItemCatalogo" name="SelItemCatalogo" style="width: 100%;" required disabled>
                                    <option value="0">-- Primero seleccione un Área --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Alquiler Ref. (Bs)</label>
                                <input type="text" class="form-control text-right font-weight-bold" id="txt_alquiler_ref" readonly placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descripción de la Actividad</label>
                                <input type="text" class="form-control" id="txt_actividad" name="txt_actividad" required minlength="5" maxlength="100" placeholder="Ej. Venta de ropa, Oficina, etc.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Razón Social (Si corresponde)</label>
                                <input type="text" class="form-control" id="txt_razon_social" name="txt_razon_social" value="Sin Dato" placeholder="Escriba la Razón Social" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: DATOS DEL ARRENDATARIO (CLIENTE) -->
                    <h5 class="text-guindo border-bottom pb-2 mb-3 mt-4">2. Datos del Arrendatario</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cédula de Identidad</label>
                                <input type="text" class="form-control" name="txt_cedula" id="txt_cedula" placeholder="Nro de CI" required>
                                <small class="text-muted" style="font-size: 0.75rem;">Si existe, se autocompletará.</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombres</label>
                                <input type="text" class="form-control" name="txt_nombres" id="txt_nombres" placeholder="Nombres" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Apellido Paterno</label>
                                <input type="text" class="form-control" name="txt_paterno" id="txt_paterno" placeholder="Paterno" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" class="form-control" name="txt_materno" id="txt_materno" placeholder="Materno">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Celular / Contacto</label>
                                <input type="text" class="form-control" name="txt_celular" id="txt_celular" placeholder="Nro de Celular" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Dirección Actual</label>
                                <input type="text" class="form-control" name="txt_direccion" id="txt_direccion" placeholder="Zona, Calle, Nro" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: DETALLES DEL CONTRATO -->
                    <h5 class="text-guindo border-bottom pb-2 mb-3 mt-4">3. Detalles del Contrato</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Número / Cite de Contrato</label>
                                <input type="text" class="form-control" id="txt_contrato" name="txt_contrato" required minlength="2" maxlength="30" placeholder="Ej. 123/2024">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Inicio</label>
                                <input type="date" class="form-control" id="txt_fecha_inicio" name="txt_fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tiempo (Meses)</label>
                                <input type="number" class="form-control" id="txt_tiempo" name="txt_tiempo" value="1" min="1" step="1" required>
                            </div>
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