$(document).ready(function() {
    // Cargar la tabla principal al iniciar
    ListarDeudores();

    // Lógica Correlativa de Selección Múltiple (Igual que en Caja)
    $(document).on('change', '.chk_pago_deuda', function() {
        var $chkboxes = $('.chk_pago_deuda');
        var idx = $chkboxes.index(this);
        var isChecked = $(this).is(':checked');

        if (isChecked) {
            $chkboxes.each(function(i) {
                if (i <= idx) $(this).prop('checked', true);
            });
        } else {
            $chkboxes.each(function(i) {
                if (i >= idx) $(this).prop('checked', false);
            });
        }
        
        var total = 0;
        var count = 0;
        $chkboxes.filter(':checked').each(function() {
            total += parseFloat($(this).data('monto'));
            count++;
        });
        
        if (count > 0) {
            $('#btn_pagar_deuda_seleccionada').prop('disabled', false).html('<i class="fas fa-dollar-sign"></i> Pagar ' + count + ' Mes(es) (Bs. ' + total.toFixed(2) + ')');
        } else {
            $('#btn_pagar_deuda_seleccionada').prop('disabled', true).html('<i class="fas fa-dollar-sign"></i> Pagar Seleccionados');
        }
    });

    // Botón para procesar el cobro de la deuda
    $('#btn_pagar_deuda_seleccionada').on('click', function(e) {
        e.preventDefault();
        var $checked = $('.chk_pago_deuda:checked');
        if ($checked.length === 0) return;

        var ids = [];
        var total = 0;
        var periodos = [];
        $checked.each(function() {
            ids.push($(this).data('id'));
            total += parseFloat($(this).data('monto'));
            periodos.push($(this).data('periodo'));
        });
        
        var idpagos_str = ids.join(',');
        var texto_meses = periodos.length > 1 ? periodos[0] + ' al ' + periodos[periodos.length - 1] : periodos[0];

        Swal.fire({
            title: '¿Confirmar Cobro de Deuda?',
            text: 'Se registrará el pago de ' + periodos.length + ' mes(es) en mora (' + texto_meses + ') por un total de Bs. ' + total.toFixed(2),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, Cobrar Deuda!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Procesando...', text: 'Registrando ingresos...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                $.ajax({
                    url: base_url + 'pagos/realizar_pago_multiple',
                    type: 'POST',
                    data: { idpagos: idpagos_str },
                    dataType: 'json',
                    success: function(resp) {
                        if(resp.status === 'success') {
                            // Recargamos ambas tablas en segundo plano
                            $('#TablaDeudores').DataTable().ajax.reload(null, false);
                            $('#TablaDetalleDeuda').DataTable().ajax.reload();
                            $('#btn_pagar_deuda_seleccionada').prop('disabled', true).html('<i class="fas fa-dollar-sign"></i> Pagar Seleccionados');
                            
                            Swal.fire({
                                title: '¡Deuda Reducida!',
                                text: 'El pago se registró exitosamente.',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#17a2b8',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '<i class="fas fa-print"></i> Imprimir Recibo',
                                cancelButtonText: 'Cerrar'
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    window.open(base_url + 'pagos/imprimir_recibo_multiple?ids=' + idpagos_str, '_blank');
                                }
                            });
                        } else {
                            Swal.fire('Error', resp.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Abrir Modal de Cobro al hacer clic en el botón de la tabla
    $(document).on('click', '.CobrarDeuda', function(e) {
        e.preventDefault();
        var idarriendo = $(this).data("id");
        var cliente = $(this).data("cliente");
        var ci = $(this).data("ci");
        var contrato = $(this).data("contrato");
        
        var infoHtml = "<strong>Nro Cite/Contrato:</strong> " + contrato + " (Gestión Pasada)<br>" +
                       "<strong>Arrendatario:</strong> " + cliente + " | <strong>CI:</strong> " + ci;
        $("#info_deudor").html(infoHtml);
        
        CargarDetalleDeuda(idarriendo);
        $('#ModalCobroDeuda').modal('show');
    });
});

function ListarDeudores() {
    $("#TablaDeudores").DataTable({     
        "responsive": true, "destroy": true, "order": [], "autoWidth": false,
        "ajax": {
            "url": base_url + 'cuentascobrar/listar',
            "dataSrc": function(json) { return json.data ? json.data : []; }       
        },
        "columns": [
            {"data": null, "searchable": false, "orderable": false, "className": "text-center font-weight-bold", "render": function (data, type, row, meta) { return meta.row + 1; }},
            {"data": null, "render": function(data) { return "<strong>" + data.CLIENTE + "</strong><br><small class='text-muted'>CI: " + data.CEDULA + " | Cel: " + data.CONTACTOS + "</small>"; }},
            {"data": null, "render": function(data) { return "<strong>Contrato: " + data.CONTRATO + "</strong><br><small class='text-muted'>Actividad: " + data.ACTIVIDAD + "</small>"; }},
            {"data": "MESES_MORA", "className": "text-center align-middle", "render": function(data) { return "<span class='badge badge-danger px-2 py-1' style='font-size: 14px;'>" + data + " Mes(es)</span>"; }},
            {"data": "DEUDA_TOTAL", "className": "text-center align-middle", "render": function(data) { return "<strong class='text-danger'>Bs. " + parseFloat(data).toFixed(2) + "</strong>"; }},
            {"data": null, "className": "text-center align-middle", "render": function(data, type, row) {
                return "<button class='CobrarDeuda btn btn-warning font-weight-bold shadow-sm' data-id='" + row.IDARRIENDO + "' data-cliente='" + row.CLIENTE + "' data-ci='" + row.CEDULA + "' data-contrato='" + row.CONTRATO + "'><i class='fas fa-hand-holding-usd'></i> Ver y Cobrar</button>";
            }}
        ]
    }); 
}

function CargarDetalleDeuda(idarriendo) {
    $("#TablaDetalleDeuda").DataTable({     
        "responsive": true, "destroy": true, "bPaginate": false, "order": [], "autoWidth": false,
        "ajax": {
            "url": base_url + 'pagos/plan_pagos/' + idarriendo,
            "dataSrc": function(json) { 
                var datos = json.data ? json.data : [];
                return datos.filter(p => p.PENDIENTE === 'SI'); // Filtro Mágico: Mostrar SOLO la mora
            }       
        },
        "drawCallback": function(settings) {
            var api = this.api();
            if (api.rows().count() > 0) {
                $("#mensaje_sin_deuda").hide(); 
                $("#contenedor_tabla_deuda").show();
                $('#btn_pagar_deuda_seleccionada').prop('disabled', true).html('<i class="fas fa-dollar-sign"></i> Pagar Seleccionados');
            } else {
                $("#contenedor_tabla_deuda").hide(); 
                $("#mensaje_sin_deuda").show(); 
            }
        },
        "createdRow": function(row) { $(row).addClass('table-danger text-danger'); },
        "columns": [
            {"data": null, "orderable": false, "searchable": false, "className": "text-center align-middle", "render": function(data, type, row) { return "<input type='checkbox' class='chk_pago_deuda' style='transform: scale(1.5); cursor: pointer;' data-id='"+row.IDPAGO+"' data-monto='"+row.MONTO+"' data-periodo='"+row.PERIODO+"'>"; }},
            {"data": "PERIODO", "className": "align-middle", "render": function(data) { var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; return (data && data.includes('-')) ? meses[parseInt(data.split('-')[1]) - 1] : data; }},
            {"data": "PERIODO", "className": "align-middle"},
            {"data": "MONTO", "className": "align-middle", "render": function(data) { return "Bs. " + parseFloat(data).toFixed(2); }},
            {"data": null, "className": "align-middle text-center", "render": function() { return '<span class="badge badge-danger border border-white"><i class="fas fa-exclamation-triangle"></i> EN MORA</span>'; }}
        ]
    }); 
}