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

        // Abrimos el PDF en una nueva pestaña
        var url = base_url + 'pagos/imprimir_cierre?inicio=' + fecha_inicio + '&fin=' + fecha_fin;
        window.open(url, '_blank');

        // Recargamos la tabla de logs para que aparezca el nuevo registro
        setTimeout(function() {
            $('#TablaLogsCierres').DataTable().ajax.reload();
        }, 2000); // Esperamos 2 segundos para dar tiempo a que se registre el log
    });
});

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
            { "data": null, "render": function(data, type, row) { return "<a href='" + base_url + "pagos/imprimir_cierre?inicio=" + row.FECHA_INICIO + "&fin=" + row.FECHA_FIN + "' target='_blank' class='btn btn-info btn-sm'><i class='fas fa-print'></i> Reimprimir</a>"; }}
        ]
    });
}