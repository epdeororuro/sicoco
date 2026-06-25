<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-file-invoice-dollar"></i> 
                <strong>Informes y Reportes / Reporte de Ingresos</strong>
            </div>
        </div>        
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-guindo">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="form-inline ml-auto">
                            <label class="mr-2 font-weight-bold text-secondary">Generar Reporte del:</label>
                            <input type="date" id="fecha_inicio_reporte" class="form-control form-control-sm mr-2 shadow-sm" value="<?php echo date('Y-m-01'); ?>" max="<?php echo date('Y-m-d'); ?>">
                            <label class="mr-2 font-weight-bold text-secondary">al:</label>
                            <input type="date" id="fecha_fin_reporte" class="form-control form-control-sm mr-2 shadow-sm" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                            <button id="btn_filtrar_reporte" class="btn btn-primary btn-sm font-weight-bold mr-2 shadow-sm" title="Filtrar Tabla">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button id="btn_generar_reporte" class="btn btn-success btn-sm font-weight-bold shadow-sm" title="Imprimir PDF">
                                <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table id="TablaReporteIngresos" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                            <thead class="bg-guindo">
                                <tr>
                                    <th>Nº</th>
                                    <th>Nro. Recibo</th>
                                    <th>Fecha y Hora</th>
                                    <th>Cliente</th>
                                    <th>Contrato</th>
                                    <th>Periodos Cobrados</th>
                                    <th>Total (Bs.)</th>
                                    <th>Cajero</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tfoot class="bg-guindo">
                                <tr>
                                    <th>Nº</th>
                                    <th>Nro. Recibo</th>
                                    <th>Fecha y Hora</th>
                                    <th>Cliente</th>
                                    <th>Contrato</th>
                                    <th>Periodos Cobrados</th>
                                    <th>Total (Bs.)</th>
                                    <th>Cajero</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <!-- Datos por AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo URL; ?>views/reportes/reportes.js"></script>