
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-file-contract text-guindo"></i> 
             <strong>Caja / Contratos</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-guindo font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalContrato">
                <i class="fas fa-plus-circle"></i> 
                 Nuevo Registro
               </button>
               <button type="button" id="btnCierreGestion" class="btn btn-danger font-weight-bold shadow-sm ml-2" title="Ejecutar Cierre Anual">
                <i class="fas fa-calendar-times"></i> 
                 Cierre de Gestión
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

                <table id="TablaContrato"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Cliente</th>
                    <th>Actividad</th>
                    <th>Razón Social</th>
                    <th>Contrato</th>
                    <th>Inicio</th>
                    <th>Suscrip.</th>
                    <th>Duración</th>
                    <th>[Bs.]</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Cliente</th>
                    <th>Actividad</th>
                    <th>Razón Social</th>
                    <th>Contrato</th>
                    <th>Inicio</th>
                    <th>Suscrip.</th>
                    <th>Duración</th>
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
              <div class="modal-header bg-guindo text-white">
                <h4 class="modal-title" id="titulo">Registro de Contratos</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                        <div class="col-md-4">
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
                                <label>Cédula de Identidad <span id="referencia_status_badge" class="ml-1"></span></label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular / Contacto</label>
                                <input type="text" class="form-control" name="txt_celular" id="txt_celular" placeholder="Nro de Celular" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dirección Actual</label>
                                <input type="text" class="form-control" name="txt_direccion" id="txt_direccion" placeholder="Zona, Calle, Nro" required>
                            </div>
                        </div>

                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label>Latitud (Opcional)</label>
                                <input type="text" class="form-control bg-white" name="txt_latitud" id="txt_latitud" placeholder="-17.9XXXXX" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Longitud (Opcional)</label>
                                <input type="text" class="form-control bg-white" name="txt_longitud" id="txt_longitud" placeholder="-67.1XXXXX" readonly>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Ubicación en el Mapa</label>
                            <div id="mapa_ubicacion" style="width: 100%; height: 250px; background-color: #e9ecef; border: 1px solid #ced4da; border-radius: 4px;"></div>
                            <small class="text-muted"><i class="fas fa-info-circle"></i> Haga clic en el mapa o arrastre el marcador para fijar las coordenadas.</small>
                        </div>
                    </div> -->

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
                                <label>Fecha Suscripción <a href="#" id="btn_hoy_suscripcion" class="text-primary ml-1"><small>[Hoy]</small></a></label>
                                <input type="date" class="form-control" id="txt_fecha_suscripcion" name="txt_fecha_suscripcion" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Inicio <a href="#" id="btn_recomendacion_inicio" class="text-primary ml-1" style="display:none;"><small>[Recomendada: <span id="lbl_fecha_recomendada"></span>]</small></a></label>
                                <input type="date" class="form-control" id="txt_fecha_inicio" name="txt_fecha_inicio" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="custom-control custom-checkbox my-2">
                                <input class="custom-control-input" type="checkbox" id="chk_temporal" name="chk_temporal">
                                <label for="chk_temporal" class="custom-control-label font-weight-bold text-guindo" style="cursor: pointer;">¿Es un Contrato Temporal?</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="div_fecha_fin" style="display: none;">
                            <div class="form-group mb-2">
                                <label class="text-guindo font-weight-bold">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="txt_fecha_fin" name="txt_fecha_fin">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mt-3">
                        <div class="col-md-12">
                            <input type="hidden" id="txt_tiempo" name="txt_tiempo" value="1">
                            <div class="alert alert-info py-2 mb-2">
                                <i class="fas fa-clock"></i> <span id="texto_duracion">Seleccione la Fecha de Inicio para calcular el tiempo del contrato.</span>
                            </div>
                            <div class="alert alert-success py-2 mb-0 font-weight-bold" id="alert_prorrateo_temporal" style="display: none;">
                                <i class="fas fa-calculator"></i> <span id="texto_prorrateo"></span>
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
                
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarContrato" name="btn_EditarContrato" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->


<!-- Modal PAGOS (Antiguo Detalle) -->
        <div id="ModalDetalle" class="modal fade" role="dialog">

          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header bg-guindo text-white">
                <h4 class="modal-title"><i class="fas fa-folder-open"></i> Expediente y Plan de Pagos</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>
              
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="alert alert-info py-2 h-100 mb-0" id="info_pago_contrato" style="font-size: 0.9rem;">
                            <!-- Info del contrato se cargará aquí por JS -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-0 h-100 shadow-sm border-secondary">
                            <div class="card-body p-2 text-center d-flex flex-column justify-content-center bg-light" id="panel_pdf">
                                <!-- JS inyectará el botón de subir o descargar PDF aquí -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="contenedor_tabla_pagos" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                        <h6 class="text-secondary mb-0">Seleccione los meses a cobrar:</h6>
                        <button class="btn btn-success font-weight-bold shadow-sm" id="btn_pagar_seleccionados" disabled><i class="fas fa-dollar-sign"></i> Pagar Seleccionados</button>
                    </div>
                    <div class="table-responsive">
                    <table id="TablaDetalle"  class="table table-bordered table-striped table-hover table-sm" style="width: 100%;">
                     <thead class="bg-guindo">
                      <tr>
                        <th class="text-center" style="width: 40px;"><i class="fas fa-check-square"></i></th>
                        <th>Mes a Pagar</th>
                        <th>Periodo</th>
                        <th>Monto (Bs.)</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tfoot class="bg-guindo">
                      <tr>
                        <th class="text-center" style="width: 40px;"><i class="fas fa-check-square"></i></th>
                        <th>Mes a Pagar</th>
                        <th>Periodo</th>
                        <th>Monto (Bs.)</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </tr>
                    </tfoot>
                    </table>
                    </div>
                </div>

                <div id="mensaje_sin_pagos" class="alert alert-warning text-center mt-3" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                    <strong>El Plan de Pagos aún no ha sido generado.</strong><br>
                    Debe hacer clic en el botón <span class="badge badge-success"><i class="fas fa-thumbs-up"></i> Confirmar</span> en la tabla principal para habilitar el cobro de mensualidades.
                </div>

               </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
              </div>

            </div>
          </div>
        </div>  <!--FIN Modal PAGOS-->

<!-- Script de Google Maps API (Reemplaza TU_API_KEY por tu clave de Google Cloud) -->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AQUI_PEGA_TU_NUEVA_CLAVE_DE_GOOGLE"></script> -->
<script src="<?php echo URL; ?>views/contrato/contrato.js"></script>