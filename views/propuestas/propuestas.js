var url_accion = base_url + 'propuestas/add';

$(document).ready(function() {
    $('#SelArea, #SelItemCatalogo').select2({ dropdownParent: $('#ModalPropuesta') });
    LlenarArea();
    ListarPropuestas();

    $("#SelArea").on("change", function() {
        var idarea = $(this).val();
        var $selectCatalogo = $("#SelItemCatalogo");
        $selectCatalogo.empty().append('<option value="0">-- Seleccione un Espacio / Servicio --</option>');
        if (idarea && idarea != "0") {
            $selectCatalogo.prop('disabled', false);
            $.ajax({
                url: base_url + 'propuestas/listar_catalogo_por_area/' + idarea,
                type: 'POST',
                dataType: 'json',
                success: function(e) {
                    var datos = e.data ? e.data : e;
                    $(datos).each(function(i, v) {
                        $selectCatalogo.append('<option value="' + v.IDCATALOGO + '">' + v.BESPACIO + '</option>');
                    });
                    $selectCatalogo.trigger('change.select2');
                }
            });
        } else {
            $selectCatalogo.prop('disabled', true).trigger('change.select2');
        }
    });

    $('#btn_GuardarPropuesta').on('click', function(e) {
        e.preventDefault();
        if(!$('#FormPropuesta')[0].checkValidity()) {
            $('#FormPropuesta')[0].reportValidity();
            return;
        }
        if($('#SelItemCatalogo').val() == "0" || !$('#SelItemCatalogo').val()) {
            Swal.fire('Atención', 'Debe seleccionar un Ítem del catálogo.', 'warning');
            return;
        }

        if($('#txt_idpropuesta').length === 0) {
            $('#FormPropuesta').append('<input type="hidden" id="txt_idpropuesta" name="txt_idpropuesta" value="">');
        }
        
        $.ajax({
            url: url_accion,
            type: 'POST',
            data: $('#FormPropuesta').serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#ModalPropuesta').modal('hide');
                    $('#FormPropuesta')[0].reset();
                    $('#txt_idpropuesta').val('');
                    url_accion = base_url + 'propuestas/add';
                    $('#SelArea').val('0').trigger('change.select2');
                    $('#TablaPropuestas').DataTable().ajax.reload();
                    
                    if (resp.idpropuesta) {
                        Swal.fire({
                            title: '¡Garantía Cobrada!', text: 'Se imprimirá el recibo correspondiente.', icon: 'success'
                        }).then(() => {
                            window.open(base_url + 'propuestas/imprimir_recibo/' + resp.idpropuesta, '_blank');
                        });
                    } else {
                        Swal.fire('¡Éxito!', resp.message, 'success');
                    }
                } else { Swal.fire('Error en el Registro', resp.message, 'error'); }
            },
            error: function(xhr) {
                Swal.fire('Error de Comunicación', 'El servidor devolvió un formato incorrecto.', 'error');
                console.error(xhr.responseText);
            }
        });
    });

    $('#ModalPropuesta').on('hidden.bs.modal', function () {
        $('#FormPropuesta')[0].reset();
        if($('#txt_idpropuesta').length > 0) $('#txt_idpropuesta').val('');
        url_accion = base_url + 'propuestas/add';
        $('#SelArea').val('0').trigger('change.select2');
        $('#SelItemCatalogo').empty().append('<option value="0">-- Seleccione un Espacio / Servicio --</option>').prop('disabled', true);
    });

    $(document).on('click', '.EditarPropuesta', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var ci = $(this).data('ci');
        var nombre = $(this).data('nombre');
        var idcatalogo = $(this).data('idcatalogo');
        var idarea = $(this).data('idarea');

        if($('#txt_idpropuesta').length === 0) {
            $('#FormPropuesta').append('<input type="hidden" id="txt_idpropuesta" name="txt_idpropuesta" value="">');
        }

        $('#FormPropuesta')[0].reset();
        $('#txt_idpropuesta').val(id);
        $('#txt_ci').val(ci);
        $('#txt_nombre').val(nombre);
        
        url_accion = base_url + 'propuestas/edit';
        
        $('#SelArea').val(idarea).trigger('change.select2');
        
        var $selectCatalogo = $("#SelItemCatalogo");
        $selectCatalogo.empty().append('<option value="0">-- Cargando... --</option>').prop('disabled', false);
        $.ajax({
            url: base_url + 'propuestas/listar_catalogo_por_area/' + idarea,
            type: 'POST',
            dataType: 'json',
            success: function(catResp) {
                var datos = catResp.data ? catResp.data : catResp;
                $selectCatalogo.empty().append('<option value="0">-- Seleccione un Espacio / Servicio --</option>');
                $(datos).each(function(i, v) {
                    $selectCatalogo.append('<option value="' + v.IDCATALOGO + '">' + v.BESPACIO + '</option>');
                });
                $selectCatalogo.val(idcatalogo).trigger('change.select2');
            }
        });

        $('#ModalPropuesta').modal('show');
    });
    
    $(document).on('click', '.DevolverGarantia', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Confirmar Devolución?', text: 'Se registrará la devolución de los Bs. 100.00 al postulante.', icon: 'question',
            showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, Devolver'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: base_url + 'propuestas/devolver/' + id, type: 'POST', dataType: 'json',
                    success: function(resp) {
                        if(resp.status === 'success') {
                            Swal.fire('¡Devuelta!', 'La garantía ha sido registrada como devuelta.', 'success');
                            $('#TablaPropuestas').DataTable().ajax.reload();
                        } else { Swal.fire('Error', resp.message, 'error'); }
                    },
                    error: function(xhr) {
                        Swal.fire('Error de Comunicación', 'Problema al procesar la devolución.', 'error');
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
});

function LlenarArea() {
    $.ajax({
        url: base_url + 'propuestas/listar_areas', type: 'POST', dataType: 'json',
        success: function(e) {
            var datos = e.data ? e.data : e;
            $("#SelArea").empty().append('<option value="0">-- Seleccione un Área --</option>');
            $(datos).each(function(i, v) { $("#SelArea").append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>'); });
            $("#SelArea").trigger('change.select2');
        }
    });
}

function ListarPropuestas() {
    $("#TablaPropuestas").DataTable({ "responsive": true, "destroy": true, "order": [[0, "desc"]], "autoWidth": false,
        "ajax": { "url": base_url + 'propuestas/listar', "dataSrc": function(json) { return json.data ? json.data : []; } },
        "columns": [ 
            {"data": "IDPROPUESTA"}, 
            {"data": null, "render": function(data) { return "<strong>" + data.NOMBRE_POSTULANTE + "</strong><br><small>CI: " + data.CI_POSTULANTE + "</small>"; }}, 
            {"data": "ESPACIO"}, 
            {"data": "MONTO", "render": function(data) { return "Bs. " + parseFloat(data).toFixed(2); }}, 
            {"data": "FECHA_COBRO"}, 
            {"data": "ESTADO", "render": function(data) { if(data === 'RETENIDA') return "<span class='badge badge-warning'><i class='fas fa-lock'></i> RETENIDA</span>"; return "<span class='badge badge-success'><i class='fas fa-unlock'></i> DEVUELTA</span>"; }}, 
            {"data": null, "render": function(data, type, row) { 
                var btn_print = "<a href='" + base_url + "propuestas/imprimir_recibo/" + row.IDPROPUESTA + "' target='_blank' class='btn btn-info btn-sm mx-1' title='Imprimir Recibo'><i class='fas fa-print'></i></a>"; 
                var btn_devolver = ""; 
                var btn_edit = "";
                if(row.ESTADO === 'RETENIDA') { 
                    btn_edit = "<button class='EditarPropuesta btn btn-warning btn-sm mx-1' data-id='" + row.IDPROPUESTA + "' data-ci='" + row.CI_POSTULANTE + "' data-nombre='" + row.NOMBRE_POSTULANTE + "' data-idcatalogo='" + row.IDCATALOGO + "' data-idarea='" + row.IDAREA + "' title='Editar Garantía'><i class='fas fa-edit'></i></button>";
                    btn_devolver = "<button class='DevolverGarantia btn btn-success btn-sm mx-1' data-id='" + row.IDPROPUESTA + "' title='Devolver Garantía'><i class='fas fa-hand-holding-usd'></i></button>"; 
                } 
                return "<div class='text-center text-nowrap'>" + btn_edit + btn_print + btn_devolver + "</div>"; 
            }} 
        ]
    });
}