<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-file-invoice-dollar"></i> 
                <strong>Reportes - Movimientos de Ingresos</strong>
            </div>
        </div>        
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Historial de Cierres Contables Generados</h3>
                        
                        <div class="form-inline ml-auto">
                            <label class="mr-2 font-weight-bold text-secondary">Generar Reporte del:</label>
                            <input type="date" id="fecha_inicio_reporte" class="form-control form-control-sm mr-2 shadow-sm" value="<?php echo date('Y-m-01'); ?>" max="<?php echo date('Y-m-d'); ?>">
                            <label class="mr-2 font-weight-bold text-secondary">al:</label>
                            <input type="date" id="fecha_fin_reporte" class="form-control form-control-sm mr-2 shadow-sm" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                            <button id="btn_generar_reporte" class="btn btn-warning btn-sm font-weight-bold shadow-sm" title="Imprimir PDF">
                                <i class="fas fa-print"></i> Generar Cierre
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table id="TablaLogsCierres" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                            <thead class="table-info">
                                <tr>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Fecha/Hora de Generación</th>
                                    <th>Usuario que Generó</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
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