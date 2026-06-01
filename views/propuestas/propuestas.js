$(document).ready(function(){
    ListarPropuestas();

    $('#SelArea, #SelItemCatalogo').select2({ dropdownParent: $('#ModalPropuesta') });

    $('#ModalPropuesta').on('show.bs.modal', function () {
        $('#FormPropuesta')[0].reset();
        $('#SelArea').empty().append('<option value="0">-- Seleccione un Área --</option>').trigger('change.select2');
        $('#SelItemCatalogo').empty().append('<option value="0">-- Primero seleccione Área --</option>').prop('disabled', true).trigger('change.select2');
        
        $.ajax({
            url: base_url + 'propuestas/listar_areas',
            type: 'GET',
            success: function(resp) {
                var datos = JSON.parse(resp).data;
                $(datos).each(function(i, v) { 
                    $("#SelArea").append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>');
                });
                $("#SelArea").trigger('change.select2');
            }
        });
    });

    $("#SelArea").on("change", function() {
        var idarea = $(this).val();
        var $selectCatalogo = $("#SelItemCatalogo");
        $selectCatalogo.empty().append('<option value="0">-- Seleccione Ítem --</option>');
        
        if (idarea && idarea != "0") {
            $selectCatalogo.prop('disabled', false);
            $.ajax({
                url: base_url + 'propuestas/listar_catalogo_por_area/' + idarea,
                type: 'GET',
                success: function(resp) {
                    var datos = JSON.parse(resp).data;
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

    $("#btn_GuardarPropuesta").click(function(e) {
        e.preventDefault();
        $.ajax({
            url: base_url + 'propuestas/add',
            type: 'POST',
            data: $('#FormPropuesta').serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#ModalPropuesta').modal('hide');
                    $('#TablaPropuestas').DataTable().ajax.reload();
                    // Al guardar, abrimos inmediatamente el reporte con el ID mas alto (el ultimo insertado)
                    setTimeout(function(){ window.open(base_url + 'propuestas/comprobante_ingreso/ultimo', '_blank'); }, 500);
                    Swal.fire('Registrado', resp.message, 'success');
                } else { Swal.fire('Error', resp.message, 'error'); }
            }
        });
    });
});

function ListarPropuestas() {
    $("#TablaPropuestas").DataTable({     
        "responsive": true, "destroy": true, "order": [[0, "desc"]],
        "ajax": { "url": base_url + 'propuestas/listar' },
        "columns": [
            {"data": "IDPROPUESTA"},
            {"data": null, "render": function(data, type, row) { return row.NOMBRE_POSTULANTE + " <br><small>CI: " + row.CI_POSTULANTE + "</small>"; }},
            {"data": null, "render": function(data, type, row) { return "<strong>" + row.ITEM + "</strong><br><small>" + row.REFERENCIA + " - " + row.UBICACION + "</small>"; }},
            {"data": "MONTO", "render": function(data) { return "Bs. " + data; }},
            {"data": "FECHA_COBRO"},
            {"data": "ESTADO", "render": function(data) {
                if(data === 'RETENIDA') return '<span class="badge badge-warning">RETENIDA</span>';
                if(data === 'DEVUELTA') return '<span class="badge badge-success">DEVUELTA</span>';
                return '<span class="badge badge-danger">EJECUTADA (Penalización)</span>';
            }},
            {"data": null, "render": function(data, type, row) {
                var btn_ingreso = "<a href='"+base_url+"propuestas/comprobante_ingreso/"+row.IDPROPUESTA+"' target='_blank' class='btn btn-info btn-sm' title='Ver Ingreso'><i class='fas fa-print'></i></a>";
                if(row.ESTADO === 'RETENIDA') {
                    var btn_devolver = "<button class='btn btn-success btn-sm ml-1' onclick='AccionPropuesta("+row.IDPROPUESTA+", \"devolver\", \"¿Devolver el dinero al postulante?\")' title='Devolver Dinero'><i class='fas fa-hand-holding-usd'></i></button>";
                    var btn_ejecutar = "<button class='btn btn-danger btn-sm ml-1' onclick='AccionPropuesta("+row.IDPROPUESTA+", \"ejecutar\", \"¿Ejecutar garantía (Penalizar y retener dinero)?\")' title='Penalizar'><i class='fas fa-gavel'></i></button>";
                    return btn_ingreso + btn_devolver + btn_ejecutar;
                } else if(row.ESTADO === 'DEVUELTA') {
                    return btn_ingreso + " <a href='"+base_url+"propuestas/comprobante_egreso/"+row.IDPROPUESTA+"' target='_blank' class='btn btn-success btn-sm ml-1' title='Ver Comprobante Egreso'><i class='fas fa-file-invoice'></i> Egreso</a>";
                }
                return btn_ingreso;
            }}
        ]
    }); 
}

function AccionPropuesta(id, accion, mensaje) {
    Swal.fire({
        title: mensaje, icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Sí, Confirmar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + 'propuestas/' + accion + '/' + id,
                dataType: 'json',
                success: function(resp) {
                    $('#TablaPropuestas').DataTable().ajax.reload();
                    Swal.fire('Completado', resp.message, 'success');
                    if(accion === 'devolver') window.open(base_url + 'propuestas/comprobante_egreso/' + id, '_blank');
                }
            });
        }
    });
}