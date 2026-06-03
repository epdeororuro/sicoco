<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-hand-holding-usd"></i> 
                <strong>Caja / Garantías de Seriedad de Propuesta</strong>
            </div>
            <div class="col-sm-6 text-right">
               <button type="button" class="btn btn-guindo font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalPropuesta">
                <i class="fas fa-plus-circle"></i> Cobrar Nueva Garantía (100 Bs)
               </button>
            </div>
        </div>        
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-guindo">
            <div class="card-body">
                <table id="TablaPropuestas" class="table table-bordered table-striped table-hover table-sm">
                 <thead class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Postulante</th>
                    <th>Ítem al que postula</th>
                    <th>Monto (Bs.)</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tfoot class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Postulante</th>
                    <th>Ítem al que postula</th>
                    <th>Monto (Bs.)</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="ModalPropuesta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-guindo text-white">
                <h4 class="modal-title">Cobro de Garantía de Propuesta</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="FormPropuesta">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cédula de Identidad</label>
                                <input type="text" class="form-control" name="txt_ci" id="txt_ci" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nombre Completo del Postulante</label>
                                <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" required style="text-transform:uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Seleccionar Área</label>
                                <select class="form-control select2" id="SelArea" name="SelArea" style="width: 100%;" required></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Seleccionar Ítem del Catálogo</label>
                                <select class="form-control select2" id="SelItemCatalogo" name="SelItemCatalogo" style="width: 100%;" required disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning mb-0 text-center font-weight-bold">
                        Monto fijo a retener: Bs. 100.00
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_GuardarPropuesta">Procesar Cobro e Imprimir</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo URL; ?>views/propuestas/propuestas.js"></script>