$(document).ready(function() {
    ListarLogsCierres();

    $('#btn_generar_reporte').on('click', function(e) {
        e.preventDefault();
        var fecha_inicio = $('#fecha_inicio_reporte').val();
        var fecha_fin = $('#fecha_fin_reporte').val();
        
        if(fecha_inicio && fecha_fin) {
            // 1. Abrir el PDF de cierre generado desde el módulo pagos (reutilizamos la función)
            window.open(base_url + 'pagos/imprimir_cierre/' + fecha_inicio + '?fin=' + fecha_fin, '_blank');
            
            // 2. Recargar la tabla silenciosamente después de 2 segundos para mostrar el log recién insertado
            setTimeout(function() {
                $('#TablaLogsCierres').DataTable().ajax.reload(null, false);
            }, 2000);
        } else {
            Swal.fire('Atención', 'Seleccione un rango de fechas válido.', 'warning');
        }
    });
});

function ListarLogsCierres() {
    $("#TablaLogsCierres").DataTable({     
        "responsive": true,
        "destroy": true, 
        "order": [[2, "desc"]], // Ordenar por fecha de generación más reciente
        "autoWidth": false,
        "ajax": {
            "url": base_url + 'reportes/listar_logs_cierres',
            "dataSrc": function(json) { 
                if (json.status === 'error') {
                    console.error("Error backend BD:", json.message);
                }
                return json.data ? json.data : []; 
            }
        },
        "columns": [
            {"data": "FECHA_INICIO", "render": function(data) {
                return "<strong>" + data + "</strong>";
            }},
            {"data": "FECHA_FIN", "render": function(data) {
                return "<strong>" + data + "</strong>";
            }},
            {"data": "FECHA_GENERACION"},
            {"data": "USUARIO", "render": function(data) {
                return "<span class='text-primary font-weight-bold'><i class='fas fa-user-tie'></i> " + data + "</span>";
            }},
            {"data": null, "render": function(data, type, row) {
                return "<a href='" + base_url + "pagos/imprimir_cierre/" + row.FECHA_INICIO + "?fin=" + row.FECHA_FIN + "' target='_blank' class='btn btn-danger btn-sm' title='Volver a Imprimir'><i class='fas fa-file-pdf'></i> Ver Reporte</a>";
            }}
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print']       
    }); 
}