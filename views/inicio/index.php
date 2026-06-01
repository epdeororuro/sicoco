<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-tachometer-alt"></i> 
                <strong>Dashboard Analítico</strong>
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
            <div class="col-md-12">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title"><i class="fas fa-chart-bar text-primary"></i> Ingresos de los Últimos 6 Meses</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <!-- Aquí Chart.js dibujará el gráfico -->
                            <canvas id="graficoIngresos" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cargamos la librería Chart.js desde su CDN oficial -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URL; ?>views/inicio/inicio.js"></script>