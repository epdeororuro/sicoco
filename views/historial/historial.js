$(document).ready(function() {
    // Cargar la tabla principal al iniciar
    ListarClientesHistorial();

    // Evento para abrir el Expediente (Kardex)
    $(document).on('click', '.VerKardex', function(e) {
        e.preventDefault();
        var idcliente = $(this).data("id");
        var cliente = $(this).data("cliente");
        var ci = $(this).data("ci");
        var contactos = $(this).data("contactos");
        
        var infoHtml = "<strong><i class='fas fa-user-tie'></i> Arrendatario:</strong> " + cliente + " &nbsp; | &nbsp; " +
                       "<strong><i class='fas fa-id-badge'></i> CI:</strong> " + ci + " &nbsp; | &nbsp; " +
                       "<strong><i class='fas fa-phone-alt'></i> Contacto:</strong> " + contactos;
        $("#info_cliente_kardex").html(infoHtml);
        
        CargarKardex(idcliente);
    });

    // Evento para abrir el Detalle de Pagos de un año específico
    $(document).on('click', '.VerPagosHistoricos', function(e) {
        e.preventDefault();
        var idarriendo = $(this).data("id");
        var gestion = $(this).data("gestion");
        VerPagosHistoricos(idarriendo, gestion);
    });
});

function ListarClientesHistorial() {
    $("#TablaHistorialClientes").DataTable({     
        "responsive": true, "destroy": true, "order": [], "autoWidth": false,
        "ajax": {
            "url": base_url + 'historial/listar_clientes',
            "dataSrc": function(json) { return json.data ? json.data : []; }       
        },
        "columns": [
            {"data": null, "searchable": false, "orderable": false, "className": "text-center font-weight-bold", "render": function (data, type, row, meta) { return meta.row + 1; }},
            {"data": "CEDULA", "className": "align-middle font-weight-bold"},
            {"data": "CLIENTE", "className": "align-middle text-uppercase"},
            {"data": "CONTACTOS", "className": "align-middle"},
            {"data": "TOTAL_CONTRATOS", "className": "text-center align-middle", "render": function(data) { 
                return "<span class='badge badge-primary px-2 py-1' style='font-size: 13px;'>" + data + " Registro(s)</span>"; 
            }},
            {"data": null, "className": "text-center align-middle", "render": function(data, type, row) {
                return "<button class='VerKardex btn btn-info font-weight-bold shadow-sm btn-sm' data-id='" + row.IDCLIENTE + "' data-cliente='" + row.CLIENTE + "' data-ci='" + row.CEDULA + "' data-contactos='" + row.CONTACTOS + "'><i class='fas fa-folder-open'></i> Ver Expediente</button>";
            }}
        ]
    }); 
}

function CargarKardex(idcliente) {
    Swal.fire({ title: 'Recopilando datos...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
    $.ajax({
        url: base_url + 'historial/obtener_kardex/' + idcliente,
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            Swal.close();
            if (resp.status === 'success') {
                var html = '';
                if (resp.data.length === 0) {
                    html = '<div class="col-12"><div class="alert alert-warning"><i class="fas fa-info-circle"></i> Este cliente no tiene contratos registrados.</div></div>';
                } else {
                    $(resp.data).each(function(i, v) {
                        var anio_gestion = v.FECHA_INICIO ? v.FECHA_INICIO.split('-')[0] : 'S/F';
                        
                        var badgeEstado = '';
                        if(v.VIGENTE === 'SI') badgeEstado = '<span class="badge badge-info shadow-sm"><i class="fas fa-check-circle"></i> VIGENTE (AÑO EN CURSO)</span>';
                        else if(v.VIGENTE === 'FIN') badgeEstado = '<span class="badge badge-success shadow-sm"><i class="fas fa-flag-checkered"></i> FINALIZADO (SIN DEUDAS)</span>';
                        else if(v.VIGENTE === 'CXC') badgeEstado = '<span class="badge badge-danger shadow-sm"><i class="fas fa-exclamation-triangle"></i> CUENTA POR COBRAR (MOROSO)</span>';
                        else badgeEstado = '<span class="badge badge-secondary shadow-sm">' + v.VIGENTE + '</span>';

                        var total = parseInt(v.TOTAL_MESES);
                        var pagados = parseInt(v.MESES_PAGADOS);
                        var mora = parseInt(v.MESES_MORA);
                        var porc = total > 0 ? Math.round((pagados / total) * 100) : 0;
                        var colorBarra = porc === 100 ? 'bg-success' : (porc > 50 ? 'bg-primary' : 'bg-warning');

                        html += `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm border-top border-primary" style="border-top-width: 3px !important;">
                                <div class="card-header bg-white pb-1 pt-2">
                                    <h6 class="card-title font-weight-bold mb-0 text-primary"><i class="fas fa-calendar-alt"></i> Gestión ${anio_gestion}</h6>
                                    <div class="float-right">${badgeEstado}</div>
                                </div>
                                <div class="card-body p-3" style="font-size: 13px;">
                                    <p class="mb-1"><strong><i class="fas fa-file-contract text-secondary"></i> Contrato:</strong> ${v.CONTRATO}</p>
                                    <p class="mb-1"><strong><i class="fas fa-store text-secondary"></i> Ítem:</strong> ${v.REFERENCIA} / ${v.UBICACION} <br><small class="text-muted ml-3">${v.ESPACIO}</small></p>
                                    <p class="mb-3"><strong><i class="fas fa-briefcase text-secondary"></i> Actividad:</strong> ${v.ACTIVIDAD}</p>
                                    
                                    <div class="progress shadow-sm mb-1" style="height: 18px; border-radius: 4px;">
                                        <div class="progress-bar ${colorBarra} font-weight-bold" role="progressbar" style="width: ${porc}%" aria-valuenow="${porc}" aria-valuemin="0" aria-valuemax="100">${porc}% Pagado</div>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                        <span><i class="fas fa-check text-success"></i> ${pagados} Pagados</span>
                                        <span><i class="fas fa-times text-danger"></i> ${mora} Mora</span>
                                        <span><i class="fas fa-layer-group"></i> ${total} Total</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-light p-2 text-center">
                                    <button class="btn btn-outline-secondary btn-sm font-weight-bold w-100 VerPagosHistoricos" data-id="${v.IDARRIENDO}" data-gestion="${anio_gestion}"><i class="fas fa-search-dollar"></i> Ver Detalle de Pagos y Recibos</button>
                                </div>
                            </div>
                        </div>`;
                    });
                }
                $('#timeline_kardex').html(html);
                $('#ModalKardex').modal('show');
            } else {
                Swal.fire('Error', resp.message, 'error');
            }
        }
    });
}

function VerPagosHistoricos(idarriendo, gestion) {
    $('#titulo_pagos_historicos').html('<i class="fas fa-list-ul"></i> Detalle de Pagos - Gestión ' + gestion);
    
    $("#TablaPagosHistoricos").DataTable({     
        "responsive": true, "destroy": true, "bPaginate": false, "info": false, "searching": false, "order": [], "autoWidth": false,
        "ajax": {
            "url": base_url + 'pagos/plan_pagos/' + idarriendo,
            "dataSrc": function(json) { return json.data ? json.data : []; }       
        },
        "columns": [
            {"data": "PERIODO", "className": "align-middle", "render": function(data) { 
                var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; 
                return (data && data.includes('-')) ? meses[parseInt(data.split('-')[1]) - 1] : data; 
            }},
            {"data": "PERIODO", "className": "align-middle"},
            {"data": "MONTO", "className": "align-middle text-right font-weight-bold", "render": function(data) { return "Bs. " + parseFloat(data).toFixed(2); }},
            {"data": "PENDIENTE", "className": "align-middle text-center", "render": function(data) { 
                if(data === 'SI') return '<span class="badge badge-danger shadow-sm"><i class="fas fa-exclamation-triangle"></i> EN MORA</span>';
                return '<span class="badge badge-success shadow-sm"><i class="fas fa-check"></i> PAGADO</span>';
            }},
            {"data": null, "className": "align-middle text-center", "render": function(data, type, row) { 
                if(row.PENDIENTE === 'NO') {
                    // Reutilizamos la función de reimpresión de recibos del Controlador de Pagos
                    return "<a href='" + base_url + "pagos/imprimir_recibo_multiple?ids=" + row.IDPAGO + "' target='_blank' class='btn btn-danger btn-sm shadow-sm' title='Reimprimir Recibo'><i class='fas fa-file-pdf'></i> Ver Recibo</a>";
                } else {
                    return "<span class='text-muted' style='font-size: 12px;'><i class='fas fa-ban'></i> Impago</span>";
                }
            }}
        ]
    }); 
    
    $('#ModalPagosHistoricos').modal('show');
}