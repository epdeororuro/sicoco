<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-shield-alt text-guindo"></i> 
                <strong>Caja / Garantías de Cumplimiento</strong>
            </div>
            <div class="col-sm-6 text-right">
               <button type="button" class="btn btn-guindo font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalCumplimiento">
                <i class="fas fa-plus-circle"></i> Cobrar Garantía de Cumplimiento
               </button>
            </div>
        </div>        
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-guindo">
            <div class="card-body">
                <table id="TablaCumplimiento" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                 <thead class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>CITE Carta</th>
                    <th>Adjudicado</th>
                    <th>Ítem Ganado</th>
                    <th>Monto (Bs.)</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado Legal</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tfoot class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>CITE Carta</th>
                    <th>Adjudicado</th>
                    <th>Ítem Ganado</th>
                    <th>Monto (Bs.)</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado Legal</th>
                    <th>Acciones</th>
                  </tr>
                </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="ModalCumplimiento" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-shield-alt"></i> Cobro: Garantía de Cumplimiento de Contrato</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="FormCumplimiento">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CITE Carta de Adjudicación</label>
                                <input type="text" class="form-control font-weight-bold text-danger" name="txt_cite" id="txt_cite" placeholder="Ej. 045/2024" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CI / Cédula</label>
                                <input type="text" class="form-control" name="txt_ci" id="txt_ci" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nombre Completo del Adjudicado</label>
                                <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" required style="text-transform:uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Área Adjudicada</label>
                                <select class="form-control select2" id="SelArea" name="SelArea" style="width: 100%;" required></select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Ítem Exacto del Catálogo</label>
                                <select class="form-control select2" id="SelItemCatalogo" name="SelItemCatalogo" style="width: 100%;" required disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0 text-center">
                        <i class="fas fa-info-circle"></i> El <strong>Monto (Bs)</strong> equivale a 1 mes de alquiler y se obtendrá de forma automática y segura desde el Catálogo del Sistema.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_GuardarCumplimiento"><i class="fas fa-print"></i> Retener e Imprimir Recibo</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo URL; ?>views/cumplimiento/cumplimiento.js"></script>