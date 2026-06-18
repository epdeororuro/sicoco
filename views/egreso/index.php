<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <i class="fas fa-money-bill-wave text-guindo"></i> 
                <strong>Informes y Reportes / Módulo de Egresos (Devoluciones)</strong>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-guindo">
            <div class="card-header bg-light">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="mb-1 text-muted" style="font-size: 13px;">Fecha Inicio</label>
                        <input type="date" id="fecha_inicio" class="form-control form-control-sm" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="mb-1 text-muted" style="font-size: 13px;">Fecha Fin</label>
                        <input type="date" id="fecha_fin" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="mb-1 text-muted" style="font-size: 13px;">Tipo de Egreso</label>
                        <select id="tipo_egreso" class="form-control form-control-sm">
                            <option value="TODOS">Todas las Devoluciones</option>
                            <option value="PROPUESTA">Solo Garantías de Propuesta</option>
                            <option value="CUMPLIMIENTO">Solo Garantías de Cumplimiento</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-2">
                        <button id="btn_buscar_egresos" class="btn btn-guindo btn-sm btn-block font-weight-bold shadow-sm"><i class="fas fa-search"></i> Filtrar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="TablaEgresos" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                    <thead class="bg-guindo">
                        <tr>
                            <th class="text-center" style="width: 5%;">N°</th>
                            <th style="width: 20%;">TIPO DE GARANTÍA</th>
                            <th style="width: 30%;">BENEFICIARIO</th>
                            <th class="text-center" style="width: 15%;">FECHA DE EGRESO</th>
                            <th class="text-center" style="width: 15%;">MONTO</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo URL; ?>views/egreso/egreso.js"></script>