<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-tachometer-alt"></i> 
                <strong>Dashboard Analítico</strong>
            </div>
            <!-- Botón de Cierre de Caja -->
            <div class="col-sm-6 text-right">
               <button type="button" class="btn btn-warning font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalCierreCaja">
                <i class="fas fa-file-invoice-dollar"></i> Reporte de Cierre de Caja
               </button>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Tarjetas de KPIs Superiores -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="kpi_hoy">Bs. 0.00</h3>
                        <p>Recaudación de Hoy</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="kpi_mes">Bs. 0.00</h3>
                        <p>Recaudación del Mes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="kpi_contratos">0</h3>
                        <p>Contratos Vigentes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="kpi_espacios">0</h3>
                        <p>Ítems Disponibles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-store-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico Estadístico -->
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-chart-bar text-primary"></i> Ingresos Mensuales</h3>
                        <select id="filtroAnio" class="form-control form-control-sm w-auto">
                            <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="graficoIngresos" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title"><i class="fas fa-chart-pie text-info"></i> Ocupación de Espacios</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="graficoEspacios" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Cierre de Caja -->
<div id="ModalCierreCaja" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> Generar Reporte de Ingresos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCierreCaja" method="GET" action="<?php echo URL; ?>pagos/imprimir_cierre" target="_blank">
                    <div class="form-group">
                        <label>Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="inicio" id="cierre_inicio" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Fin (Opcional):</label>
                        <input type="date" class="form-control" name="fin" id="cierre_fin" value="<?php echo date('Y-m-d'); ?>">
                        <small class="form-text text-muted">Deje la misma fecha en ambos campos si solo desea el reporte de un día en específico.</small>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Generar Reporte PDF</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cargamos la librería Chart.js desde su CDN oficial -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URL; ?>views/inicio/inicio.js"></script>