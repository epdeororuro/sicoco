var url_accion = '';
var item_catalogo_editar = 0;

$(document).ready(function(){
    url_accion = base_url + 'cumplimiento/add';
    ListarCumplimiento();

    $('#SelArea, #SelItemCatalogo').select2({ dropdownParent: $('#ModalCumplimiento') });

    $('#ModalCumplimiento').on('show.bs.modal', function (e) {
        if (e.relatedTarget) {
            url_accion = base_url + 'cumplimiento/add';
            $('#FormCumplimiento')[0].reset();
            $('#txt_idgarantia').val('');
            LlenarArea();
            $('#SelItemCatalogo').empty().append('<option value="0">-- Primero seleccione Área --</option>').prop('disabled', true).trigger('change.select2');
        }
    });

    $("#SelArea").on("change", function() {
        var idarea = $(this).val();
        var $selectCatalogo = $("#SelItemCatalogo");
        
        if (idarea && idarea != "0") {
            $selectCatalogo.empty().append('<option value="0">-- Cargando... --</option>').prop('disabled', false);
            $.ajax({
                url: base_url + 'cumplimiento/listar_catalogo_por_area/' + idarea,
                type: 'GET',
                success: function(resp) {
                    var datos = JSON.parse(resp).data;
                    $selectCatalogo.empty().append('<option value="0">-- Seleccione Ítem --</option>');
                    $(datos).each(function(i, v) {
                        $selectCatalogo.append('<option value="' + v.IDCATALOGO + '" data-precio="'+v.ALQUILER+'">' + v.BESPACIO + ' (Bs. '+v.ALQUILER+')</option>');
                    });
                    
                    if (item_catalogo_editar > 0) {
                        $selectCatalogo.val(item_catalogo_editar);
                        item_catalogo_editar = 0;
                    }
                    $selectCatalogo.trigger('change.select2');
                }
            });
        } else {
            $selectCatalogo.empty().append('<option value="0">-- Primero seleccione Área --</option>');
            $selectCatalogo.prop('disabled', true).trigger('change.select2');
        }
    });

    $("#btn_GuardarCumplimiento").click(function(e) {
        e.preventDefault();
        if(!$('#FormCumplimiento')[0].checkValidity()) {
            $('#FormCumplimiento')[0].reportValidity(); return;
        }
        if($('#SelItemCatalogo').val() == "0" || !$('#SelItemCatalogo').val()) {
            Swal.fire('Atención', 'Debe seleccionar un Ítem del catálogo.', 'warning'); return;
        }

        $.ajax({
            url: url_accion,
            type: 'POST',
            data: $('#FormCumplimiento').serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#ModalCumplimiento').modal('hide');
                    $('#TablaCumplimiento').DataTable().ajax.reload();
                    
                    if (resp.idgarantia && url_accion.includes('add')) {
                        Swal.fire({ title: '¡Garantía Cobrada!', text: 'Se imprimirá el recibo.', icon: 'success' }).then(() => {
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = base_url + 'cumplimiento/comprobante_ingreso';
                            form.target = '_blank';
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'idgarantia';
                            input.value = resp.idgarantia;
                            form.appendChild(input);
                            document.body.appendChild(form);
                            form.submit();
                            document.body.removeChild(form);
                        });
                    } else {
                        Swal.fire('¡Éxito!', resp.message, 'success');
                    }
                } else { Swal.fire('Error', resp.message, 'error'); }
            }
        });
    });

    // Modo Edición
    $(document).on('click', '.EditarCumplimiento', function(e) {
        e.preventDefault();
        url_accion = base_url + 'cumplimiento/edit';
        
        if($('#txt_idgarantia').length === 0) {
            $('#FormCumplimiento').prepend('<input type="hidden" id="txt_idgarantia" name="txt_idgarantia" value="">');
        }
        
        $('#txt_idgarantia').val($(this).data('id'));
        $('#txt_cite').val($(this).data('cite'));
        $('#txt_ci').val($(this).data('ci'));
        $('#txt_nombre').val($(this).data('nombre'));
        
        var idarea = $(this).data('idarea');
        item_catalogo_editar = $(this).data('idcatalogo'); 

        $.ajax({
            url: base_url + 'cumplimiento/listar_areas',
            type: 'GET',
            success: function(resp) {
                var datos = JSON.parse(resp).data;
                var $selectArea = $('#SelArea');
                $selectArea.empty().append('<option value="0">-- Seleccione un Área --</option>');
                $(datos).each(function(i, v) { $selectArea.append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>'); });
                
                $selectArea.val(idarea).trigger('change');
            }
        });
        $('#ModalCumplimiento').modal('show');
    });

    // Imprimir vía POST (Seguridad)
    $(document).on('click', '.ImprimirRecibo', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = base_url + 'cumplimiento/comprobante_ingreso';
        form.target = '_blank'; 
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'idgarantia';
        input.value = id;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });

    // Devolver Garantía
    $(document).on('click', '.DevolverGarantia', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Confirmar Devolución?', text: 'Se registrará la devolución de la garantía.', icon: 'question',
            showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, Devolver'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: base_url + 'cumplimiento/devolver/' + id, type: 'POST', dataType: 'json',
                    success: function(resp) {
                        if(resp.status === 'success') {
                            Swal.fire({
                                title: '¡Garantía Devuelta!', 
                                text: 'Se registró el egreso. ¿Desea imprimir el comprobante de devolución?', 
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#17a2b8',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '<i class="fas fa-print"></i> Imprimir Egreso',
                                cancelButtonText: 'Cerrar'
                            }).then((res) => {
                                $('#TablaCumplimiento').DataTable().ajax.reload();
                                if (res.isConfirmed) {
                                    var form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = base_url + 'cumplimiento/comprobante_ingreso';
                                    form.target = '_blank';
                                    var input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'idgarantia';
                                    input.value = id;
                                    form.appendChild(input);
                                    document.body.appendChild(form);
                                    form.submit();
                                    document.body.removeChild(form);
                                }
                            });
                        } else { Swal.fire('Error', resp.message, 'error'); }
                    }
                });
            }
        });
    });
});

function LlenarArea() {
    $.ajax({
        url: base_url + 'cumplimiento/listar_areas', type: 'GET',
        success: function(resp) {
            var datos = JSON.parse(resp).data;
            var $selectArea = $("#SelArea");
            $selectArea.empty().append('<option value="0">-- Seleccione un Área --</option>');
            $(datos).each(function(i, v) { $selectArea.append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>'); });
            $selectArea.trigger('change.select2');
        }
    });
}

function ListarCumplimiento() {
    $("#TablaCumplimiento").DataTable({     
        "responsive": true, "destroy": true, "order": [[0, "desc"]],
        "ajax": { "url": base_url + 'cumplimiento/listar' },
        "columns": [
            {"data": "IDGARANTIA"},
            {"data": "CITE_ADJUDICACION", "render": function(data) { return "<strong>" + data + "</strong>"; }},
            {"data": null, "render": function(data, type, row) { return row.NOMBRE_POSTULANTE + " <br><small>CI: " + row.CI_POSTULANTE + "</small>"; }},
            {"data": null, "render": function(data, type, row) { return "<strong>" + row.ITEM + "</strong><br><small>" + row.REFERENCIA + " - " + row.UBICACION + "</small>"; }},
            {"data": "MONTO", "render": function(data) { return "<strong class='text-success'>Bs. " + parseFloat(data).toFixed(2) + "</strong>"; }},
            {"data": null, "render": function(data, type, row) { return row.FECHA_COBRO || 'S/D'; }},
            {"data": "ESTADO", "render": function(data, type, row) { if(data === 'RETENIDA') return '<span class="badge badge-warning"><i class="fas fa-lock"></i> RETENIDA (Falta Contrato)</span>'; if(data === 'ENLAZADA') return '<span class="badge badge-info"><i class="fas fa-link"></i> ENLAZADA AL CONTRATO</span><br><small class="text-muted">' + row.NRO_CONTRATO + '</small>'; if(data === 'DEVUELTA') return '<span class="badge badge-success"><i class="fas fa-unlock"></i> DEVUELTA</span>'; return '<span class="badge badge-secondary">' + data + '</span>'; }},
            {"data": null, "render": function(data, type, row) { 
                var btn_print = "<button class='ImprimirRecibo btn btn-info btn-sm mx-1' data-id='" + row.IDGARANTIA + "' title='Imprimir Recibo'><i class='fas fa-print'></i></button>";
                var btn_edit = "", btn_devolver = "";
                if(row.ESTADO === 'RETENIDA' || row.ESTADO === 'ENLAZADA') { 
                    btn_devolver = "<button class='DevolverGarantia btn btn-success btn-sm mx-1' data-id='" + row.IDGARANTIA + "' title='Devolver'><i class='fas fa-hand-holding-usd'></i></button>"; 
                }
                if(row.ESTADO === 'RETENIDA') { 
                    btn_edit = "<button class='EditarCumplimiento btn btn-warning btn-sm mx-1' data-id='" + row.IDGARANTIA + "' data-cite='" + row.CITE_ADJUDICACION + "' data-ci='" + row.CI_POSTULANTE + "' data-nombre='" + row.NOMBRE_POSTULANTE + "' data-idcatalogo='" + row.IDCATALOGO + "' data-idarea='" + row.IDAREA + "' title='Editar'><i class='fas fa-edit'></i></button>";
                } 
                return "<div class='text-center text-nowrap'>" + btn_edit + btn_print + btn_devolver + "</div>"; 
            }}
        ]
    }); 
}