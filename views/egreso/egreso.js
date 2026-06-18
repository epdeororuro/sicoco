$(document).ready(function() {
    ListarEgresos();

    // Al hacer clic en el botón Filtrar, recargamos el DataTable
    $('#btn_buscar_egresos').on('click', function(e) {
        e.preventDefault();
        $('#TablaEgresos').DataTable().ajax.reload();
    });
});

function ListarEgresos() {
    $("#TablaEgresos").DataTable({     
        "responsive": true, "destroy": true, "order": [], "autoWidth": false,
        "ajax": {
            "url": base_url + 'egreso/listar',
            "type": "POST",
            "data": function(d) {
                d.inicio = $('#fecha_inicio').val();
                d.fin = $('#fecha_fin').val();
                d.tipo = $('#tipo_egreso').val();
            },
            "dataSrc": function(json) { return json.data ? json.data : []; }       
        },
        "columns": [
            {"data": null, "searchable": false, "orderable": false, "className": "text-center font-weight-bold", "render": function (data, type, row, meta) { return meta.row + 1; }},
            {"data": "TIPO", "render": function(data, type, row) { return "<strong>" + data + "</strong><br><small class='text-muted'>Nro Registro: " + row.NRO + "</small>"; }},
            {"data": null, "render": function(data) { return "<strong>" + data.CLIENTE + "</strong><br><small class='text-muted'>CI: " + data.CI + "</small>"; }},
            {"data": "FECHA", "className": "text-center align-middle"},
            {"data": "MONTO", "className": "text-center align-middle", "render": function(data) { return "<strong class='text-danger'>- Bs. " + parseFloat(data).toFixed(2) + "</strong>"; }}
        ]
    }); 
}