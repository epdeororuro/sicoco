$(document).ready(function() {
    // 1. Cargar la tabla de logs al iniciar
    ListarLogs();

    // 2. Evento para generar el reporte PDF
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

        // Recargamos la tabla de logs para que aparezca el nuevo registro
        setTimeout(function() {
            $('#TablaLogsCierres').DataTable().ajax.reload();
        }, 2000); // Esperamos 2 segundos para dar tiempo a que se registre el log
    });

    // 3. Delegación de evento para los botones de reimprimir
    $('#TablaLogsCierres').on('click', '.btn-reimprimir', function(e) {
        e.preventDefault();
        var inicio = $(this).data('inicio');
        var fin = $(this).data('fin');
        abrirReporteCierre(inicio, fin);
    });
});

// Helper para abrir el reporte de cierre usando POST
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

function ListarLogs() {
    $("#TablaLogsCierres").DataTable({
        "responsive": true,
        "destroy": true,
        "order": [[2, "desc"]],
        "ajax": { "url": base_url + 'reportes/listar_logs' },
        "columns": [
            { "data": "FECHA_INICIO" },
            { "data": "FECHA_FIN" },
            { "data": "FECHA_GENERACION" },
            { "data": "USUARIO" },
            { 
                "data": null, 
                "render": function(data, type, row) { 
                    return "<button type='button' class='btn btn-info btn-sm btn-reimprimir' data-inicio='" + row.FECHA_INICIO + "' data-fin='" + row.FECHA_FIN + "'><i class='fas fa-print'></i> Reimprimir</button>"; 
                }
            }
        ]
    });
}