<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-balance-scale-right text-guindo"></i> 
                <strong>Caja / Cuentas por Cobrar (Deudores)</strong>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-guindo">

                    <div class="card-body">
                        <table id="TablaDeudores" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                            <thead class="bg-guindo">
                                <tr>
                                    <th class="text-center" style="width: 5%;">N°</th>
                                    <th style="width: 25%;">ARRENDATARIO</th>
                                    <th style="width: 25%;">CONTRATO / ACTIVIDAD</th>
                                    <th class="text-center" style="width: 15%;">MESES EN MORA</th>
                                    <th class="text-center" style="width: 15%;">DEUDA TOTAL</th>
                                    <th class="text-center" style="width: 15%;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Cobro de Deuda -->
<div class="modal fade" id="ModalCobroDeuda" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-guindo text-white">
                <h4 class="modal-title"><i class="fas fa-folder-open"></i> Detalle de Deuda y Cobro</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Info del deudor -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info py-2 mb-0" id="info_deudor" style="font-size: 0.9rem;">
                        </div>
                    </div>
                </div>

                <!-- Tabla de meses adeudados -->
                <div id="contenedor_tabla_deuda" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                        <h6 class="text-secondary mb-0">Seleccione los meses a cobrar:</h6>
                        <button class="btn btn-success font-weight-bold shadow-sm" id="btn_pagar_deuda_seleccionada" disabled><i class="fas fa-dollar-sign"></i> Pagar Seleccionados</button>
                    </div>
                    <div class="table-responsive">
                        <table id="TablaDetalleDeuda" class="table table-bordered table-striped table-hover table-sm" style="width: 100%;">
                            <thead class="bg-guindo">
                                <tr>
                                    <th class="text-center" style="width: 40px;"><i class="fas fa-check-square"></i></th>
                                    <th>Mes a Pagar</th>
                                    <th>Periodo</th>
                                    <th>Monto (Bs.)</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tfoot class="bg-guindo">
                                <tr>
                                    <th class="text-center" style="width: 40px;"><i class="fas fa-check-square"></i></th>
                                    <th>Mes a Pagar</th>
                                    <th>Periodo</th>
                                    <th>Monto (Bs.)</th>
                                    <th>Estado</th>
                                </tr>
                            </tfoot>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div id="mensaje_sin_deuda" class="alert alert-success text-center mt-3" style="display:none;">
                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                    <strong>¡DEUDA SALDADA!</strong><br>Este contrato ha llegado a cero deudas y el cliente ha sido liberado de sus bloqueos en el sistema.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Script específico del módulo -->
<script src="<?php echo URL; ?>views/cuentascobrar/cuentascobrar.js"></script>