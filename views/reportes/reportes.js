$(document).ready(function() {
    // 1. Cargar la tabla de ingresos al iniciar
    ListarIngresos();

    // 2. Filtrar tabla por rango de fechas
    $('#btn_filtrar_reporte').on('click', function(e) {
        e.preventDefault();
        $('#TablaReporteIngresos').DataTable().ajax.reload();
    });

    // 3. Evento para generar el reporte consolidado PDF
    $('#btn_generar_reporte').on('click', function(e) {
        e.preventDefault();
        var fecha_inicio = $('#fecha_inicio_reporte').val();
        var fecha_fin = $('#fecha_fin_reporte').val();

        if (!fecha_inicio || !fecha_fin) {
            Swal.fire('Atención', 'Debe seleccionar ambas fechas para generar el reporte.', 'warning');
            return;
        }

        // Abrimos el PDF en una nueva pestaña usando POST
        abrirReporteCierre(fecha_inicio, fecha_fin);
    });
});

// Helper para abrir el reporte de cierre consolidado usando POST
function abrirReporteCierre(inicio, fin) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = base_url + 'pagos/imprimir_cierre';
    form.target = '_blank';

    var tokenVal = (typeof csrf_token !== 'undefined') ? csrf_token : $('meta[name="csrf-token"]').attr('content');
    var inputCsrf = document.createElement('input');
    inputCsrf.type = 'hidden';
    inputCsrf.name = 'csrf_token';
    inputCsrf.value = tokenVal || '';
    form.appendChild(inputCsrf);

    var inputInicio = document.createElement('input');
    inputInicio.type = 'hidden';
    inputInicio.name = 'inicio';
    inputInicio.value = inicio;
    form.appendChild(inputInicio);

    var inputFin = document.createElement('input');
    inputFin.type = 'hidden';
    inputFin.name = 'fin';
    inputFin.value = fin;
    form.appendChild(inputFin);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function ListarIngresos() {
    $("#TablaReporteIngresos").DataTable({
        "responsive": true,
        "destroy": true,
        "order": [[2, "desc"]], // Ordenar por fecha y hora de cobro
        "ajax": {
            "url": base_url + 'reportes/listar_ingresos',
            "type": "POST",
            "data": function(d) {
                d.inicio = $('#fecha_inicio_reporte').val();
                d.fin = $('#fecha_fin_reporte').val();
            },
            "dataSrc": function(json) { return json.data ? json.data : []; }
        },
        "columns": [
            { "data": null, "searchable": false, "orderable": false, "className": "text-center align-middle", "render": function (data, type, row, meta) { return meta.row + 1; }},
            { "data": "NRO_RECIBO", "className": "text-center align-middle font-weight-bold", "render": function(data) {
                return "<span class='text-danger'><i class='fas fa-receipt'></i> " + data + "</span>";
            }},
            { "data": "FECHA", "className": "text-center align-middle" },
            { "data": "CLIENTE", "className": "align-middle" },
            { "data": "CONTRATO", "className": "text-center align-middle" },
            { "data": "PERIODOS", "className": "align-middle", "render": function(data) { return "<small>" + data + "</small>"; }},
            { "data": "TOTAL", "className": "text-right align-middle font-weight-bold text-success", "render": function(data) {
                return "Bs. " + parseFloat(data).toFixed(2);
            }},
            { "data": "CAJERO", "className": "text-center align-middle" },
            { "data": "ESTADO_RECIBO", "className": "text-center align-middle", "render": function(data, type, row) {
                if (data === 'ANULADO') {
                    return "<span class='badge badge-danger' title='Motivo: " + (row.MOTIVO_ANULACION || 'No especificado') + "'>ANULADO</span>";
                }
                return "<span class='badge badge-success'>ACTIVO</span>";
            }},
            { 
                "data": null, 
                "searchable": false,
                "orderable": false,
                "className": "text-center align-middle",
                "render": function(data, type, row) { 
                    return "<a href='" + base_url + "pagos/reimprimir/" + row.NRO_RECIBO + "' target='_blank' class='btn btn-info btn-sm' title='Reimprimir Recibo'><i class='fas fa-print'></i> Reimprimir</a>"; 
                }
            }
        ]
    });
}