<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-ban text-guindo"></i> 
                <strong>Informes y Reportes / Anular Recibos(Estornos)</strong>
            </div>

        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-outline card-danger shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title font-weight-bold text-danger">Buscador de Recibos</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-4">
                            <input type="number" id="txt_buscar_recibo" class="form-control form-control-lg text-center font-weight-bold" placeholder="Ingrese el Número de Recibo (Ej: 154)" autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-danger font-weight-bold px-4" id="btn_buscar_recibo"><i class="fas fa-search"></i> Buscar</button>
                            </div>
                        </div>

                        <!-- Panel de Resultados -->
                        <div id="panel_resultado_estorno" style="display: none;">
                            <div class="alert alert-secondary border">
                                <div class="row">
                                    <div class="col-md-6 mb-2"><small class="text-muted d-block">Arrendatario</small><strong id="lbl_cliente" style="font-size: 1.1rem;"></strong></div>
                                    <div class="col-md-6 mb-2 text-right"><small class="text-muted d-block">Monto Total</small><strong class="text-danger" id="lbl_monto" style="font-size: 1.2rem;"></strong></div>
                                    <div class="col-md-6 mb-2"><small class="text-muted d-block">Meses Cobrados</small><strong id="lbl_periodos"></strong></div>
                                    <div class="col-md-6 mb-2 text-right"><small class="text-muted d-block">Fecha de Cobro y Cajero</small><strong id="lbl_fecha_cajero"></strong></div>
                                </div>
                            </div>
                            <div class="text-center mt-4" id="div_accion_estorno">
                                <!-- Botones inyectados por JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo URL; ?>views/estorno/estorno.js"></script>