$(document).ready(function() {
    $('#btn_buscar_recibo').on('click', function(e) {
        e.preventDefault();
        var nro = $('#txt_buscar_recibo').val();
        if(!nro) { Swal.fire('Atención', 'Ingrese un número de recibo', 'warning'); return; }

        Swal.fire({ title: 'Buscando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
        
        $.ajax({
            url: base_url + 'estorno/buscar', type: 'POST', data: { nro_recibo: nro }, dataType: 'json',
            success: function(resp) {
                Swal.close();
                if(resp.status === 'success') {
                    var d = resp.data;
                    $('#lbl_cliente').text(d.CLIENTE); $('#lbl_monto').text('Bs. ' + parseFloat(d.TOTAL).toFixed(2));
                    $('#lbl_periodos').text(d.PERIODOS); $('#lbl_fecha_cajero').text(d.FECHA_PAGO + ' | ' + d.USR);

                    var htmlAccion = '';
                    if(d.ESTADO_RECIBO === 'ACTIVO') {
                        htmlAccion = `<button class="btn btn-danger btn-lg shadow-sm font-weight-bold" id="btn_procesar_anulacion" data-nro="${d.NRO_RECIBO}"><i class="fas fa-times-circle"></i> Anular y Revertir Recibo</button>`;
                    } else {
                        htmlAccion = `<div class="alert alert-danger d-inline-block shadow-sm"><i class="fas fa-ban fa-2x mb-2"></i><br><strong>RECIBO YA ANULADO</strong><br><small>Motivo: ${d.MOTIVO_ANULACION}</small></div>`;
                    }
                    $('#div_accion_estorno').html(htmlAccion);
                    $('#panel_resultado_estorno').slideDown();
                } else {
                    $('#panel_resultado_estorno').slideUp(); Swal.fire('No encontrado', resp.message, 'error');
                }
            }
        });
    });

    $(document).on('click', '#btn_procesar_anulacion', function(e) {
        e.preventDefault();
        var nro = $(this).data('nro');

        Swal.fire({
            title: '¿Confirmar Anulación Irreversible?',
            html: 'Al anular el Recibo Nro: <strong>' + nro + '</strong>, la deuda volverá a figurar como <b>PENDIENTE</b> en el sistema y el dinero se restará del Arqueo de Caja.<br><br>Ingrese el motivo de la anulación:',
            input: 'text', inputAttributes: { required: 'true' }, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, Anular Recibo!', cancelButtonText: 'Cancelar',
            preConfirm: (motivo) => { if (!motivo) { Swal.showValidationMessage('Debe ingresar un motivo para auditoría'); } return motivo; }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                $.ajax({
                    url: base_url + 'estorno/anular', type: 'POST', data: { nro_recibo: nro, motivo: result.value }, dataType: 'json',
                    success: function(res) { if(res.status === 'success') { Swal.fire('¡Anulado!', res.message, 'success'); $('#btn_buscar_recibo').trigger('click'); } else { Swal.fire('Error', res.message, 'error'); } }
                });
            }
        });
    });
});