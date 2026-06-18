<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-folder-open text-guindo"></i> 
                <strong>Informes y Reportes / Kardex Histórico</strong>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-guindo shadow-sm">

                    <div class="card-body">
                        <table id="TablaHistorialClientes" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                            <thead class="bg-guindo">
                                <tr>
                                    <th class="text-center" style="width: 5%;">N°</th>
                                    <th style="width: 15%;">CÉDULA (CI)</th>
                                    <th style="width: 30%;">NOMBRE DEL ARRENDATARIO</th>
                                    <th style="width: 15%;">CELULAR</th>
                                    <th class="text-center" style="width: 20%;">CONTRATOS EN HISTORIAL</th>
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

<!-- Modal Kardex (Expediente) -->
<div class="modal fade" id="ModalKardex" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-guindo text-white">
                <h4 class="modal-title"><i class="fas fa-id-card-alt"></i> Expediente Histórico del Arrendatario</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-info shadow-sm py-2 mb-0" id="info_cliente_kardex" style="font-size: 1rem;">
                            <!-- Info inyectada por JS -->
                        </div>
                    </div>
                </div>
                <!-- Contenedor dinámico de las Tarjetas de Gestión -->
                <div id="timeline_kardex" class="row"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar Expediente</button>
            </div>
        </div>
    </div>
</div>

<!-- Sub-Modal: Detalle de Pagos Históricos -->
<div class="modal fade" id="ModalPagosHistoricos" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title font-weight-bold" id="titulo_pagos_historicos"><i class="fas fa-list-ul"></i> Detalle de Pagos</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <table id="TablaPagosHistoricos" class="table table-bordered table-striped table-hover table-sm m-0" style="width: 100%;">
                    <thead class="bg-light"><tr><th>MES ALQUILER</th><th>PERIODO</th><th class="text-right">MONTO (Bs.)</th><th class="text-center">ESTADO</th><th class="text-center">RECIBO</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URL; ?>views/historial/historial.js"></script>