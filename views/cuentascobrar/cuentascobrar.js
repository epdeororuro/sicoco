$(document).ready(function() {
    // Desactivar el enforceFocus de Bootstrap para que SweetAlert2 permita escribir en sus inputs
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};

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

        // --- Nuevo Modal de Swal con Formulario ---
        $('#ModalCobroDeuda').removeAttr('tabindex'); // Corrección para permitir foco en inputs de SweetAlert2
        Swal.fire({
            title: 'Confirmar Cobro y Facturación',
            icon: 'warning',
            html: `
                <p class="mb-3">Se registrará el pago de <strong>${periodos.length} mes(es)</strong> en mora (${texto_meses}) por un total de <strong>Bs. ${total.toFixed(2)}</strong>.</p>
                <hr>
                <div class="text-left">
                    <div class="form-group">
                        <label for="swal_metodo_pago"><strong>Método de Pago:</strong></label>
                        <select id="swal_metodo_pago" class="form-control">
                            <option value="EFECTIVO" selected>Efectivo</option>
                            <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                            <option value="DEPOSITO">Depósito Bancario</option>
                            <option value="QR">Pago por QR</option>
                        </select>
                    </div>
                    <div class="form-group" id="swal_div_comprobante" style="display:none;">
                        <label for="swal_nro_comprobante"><strong>Nro. de Transacción/Comprobante:</strong></label>
                        <input type="text" id="swal_nro_comprobante" class="form-control" placeholder="Ej: 845123">
                    </div>
                    <div class="form-group">
                        <label for="swal_nro_factura"><strong>Nro. de Factura SIAT (Opcional):</strong></label>
                        <input type="text" id="swal_nro_factura" class="form-control" placeholder="Ej: 10258">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, Cobrar y Registrar',
            didOpen: () => {
                $('#swal_metodo_pago').on('change', function() {
                    if ($(this).val() === 'EFECTIVO') {
                        $('#swal_div_comprobante').hide();
                    } else {
                        $('#swal_div_comprobante').show();
                    }
                });
            },
            preConfirm: () => {
                return {
                    metodo_pago: $('#swal_metodo_pago').val(),
                    nro_comprobante: $('#swal_nro_comprobante').val(),
                    nro_factura_siat: $('#swal_nro_factura').val()
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                var postData = result.value;
                postData.idpagos = idpagos_str;

                Swal.fire({ title: 'Procesando...', text: 'Registrando ingresos...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                $.ajax({
                    url: base_url + 'pagos/realizar_pago_multiple',
                    type: 'POST',
                    data: postData,
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
            {"data": null, "className": "text-center align-middle text-nowrap", "render": function(data, type, row) {
                var btnCobrar = "<button class='CobrarDeuda btn btn-warning font-weight-bold shadow-sm' data-id='" + row.IDARRIENDO + "' data-cliente='" + row.CLIENTE + "' data-ci='" + row.CEDULA + "' data-contrato='" + row.CONTRATO + "'><i class='fas fa-hand-holding-usd'></i> Ver y Cobrar</button>";
                var btnWa = "<button type='button' class='btn btn-success font-weight-bold shadow-sm ml-1' onclick='enviarRecordatorioDeudaCXC(\""+row.CLIENTE+"\", \""+row.CONTACTOS+"\", \""+row.CONTRATO+"\", \""+row.ACTIVIDAD+"\", "+row.DEUDA_TOTAL+", "+row.MESES_MORA+")' title='Enviar Recordatorio WhatsApp'><i class='fab fa-whatsapp'></i></button>";
                return btnCobrar + btnWa;
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

window.enviarRecordatorioDeudaCXC = function(cliente, contactos, contrato, actividad, totalDeuda, mesesMora) {
    var celular = contactos ? contactos.trim() : '';
    celular = celular.replace(/[^0-9]/g, '');
    if (celular.length > 0 && !celular.startsWith('591')) {
        celular = '591' + celular;
    }
    
    if (celular === '' || celular === '591' || celular === '59100000000') {
        Swal.fire('Atención', 'El cliente no tiene registrado un número de celular válido para notificar.', 'warning');
        return;
    }

    var texto_wa = "⚠️ *EPDEOR - Alerta de Mora Contable*%0A%0A" +
                   "Estimado(a) *" + cliente + "*, se registra en el sistema de la Empresa Pública Departamental de Oruro una deuda vencida (Cuentas por Cobrar) de gestiones anteriores correspondiente a su contrato *" + contrato + "* (" + (actividad ? actividad : 'Arrendamiento') + ").%0A%0A" +
                   "• Meses en Mora: *" + mesesMora + " mes(es)*%0A" +
                   "• Deuda Total Acumulada: *Bs. " + parseFloat(totalDeuda).toFixed(2) + "*%0A%0A" +
                   "Le solicitamos pasar por oficinas de caja a la brevedad posible para regularizar su cuenta y evitar procesos administrativos. ¡Muchas gracias por su atención!";
                   
    var url_wa = "https://api.whatsapp.com/send?phone=" + celular + "&text=" + texto_wa;
    window.open(url_wa, '_blank');
};