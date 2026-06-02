$(document).ready(function(){
    ListarCumplimiento();

    $('#SelArea, #SelItemCatalogo').select2({ dropdownParent: $('#ModalCumplimiento') });

    $('#ModalCumplimiento').on('show.bs.modal', function () {
        $('#FormCumplimiento')[0].reset();
        $('#SelArea').empty().append('<option value="0">-- Seleccione un Área --</option>').trigger('change.select2');
        $('#SelItemCatalogo').empty().append('<option value="0">-- Primero seleccione Área --</option>').prop('disabled', true).trigger('change.select2');
        
        $.ajax({
            url: base_url + 'cumplimiento/listar_areas',
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
                url: base_url + 'cumplimiento/listar_catalogo_por_area/' + idarea,
                type: 'GET',
                success: function(resp) {
                    var datos = JSON.parse(resp).data;
                    $(datos).each(function(i, v) {
                        $selectCatalogo.append('<option value="' + v.IDCATALOGO + '" data-precio="'+v.ALQUILER+'">' + v.BESPACIO + ' (Bs. '+v.ALQUILER+')</option>');
                    });
                    $selectCatalogo.trigger('change.select2');
                }
            });
        } else {
            $selectCatalogo.prop('disabled', true).trigger('change.select2');
        }
    });

    $("#btn_GuardarCumplimiento").click(function(e) {
        e.preventDefault();
        $.ajax({
            url: base_url + 'cumplimiento/add',
            type: 'POST',
            data: $('#FormCumplimiento').serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#ModalCumplimiento').modal('hide');
                    $('#TablaCumplimiento').DataTable().ajax.reload();
                    setTimeout(function(){ window.open(base_url + 'cumplimiento/comprobante_ingreso/ultimo', '_blank'); }, 500);
                    Swal.fire('Registrado', resp.message, 'success');
                } else { Swal.fire('Error', resp.message, 'error'); }
            }
        });
    });
});

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
            {"data": "FECHA_COBRO"},
            {"data": "ESTADO", "render": function(data, type, row) { if(data === 'RETENIDA') return '<span class="badge badge-warning"><i class="fas fa-lock"></i> RETENIDA (Falta Contrato)</span>'; if(data === 'ENLAZADA') return '<span class="badge badge-success"><i class="fas fa-link"></i> ENLAZADA AL CONTRATO</span><br><small class="text-muted">' + row.NRO_CONTRATO + '</small>'; return '<span class="badge badge-secondary">' + data + '</span>'; }},
            {"data": null, "render": function(data, type, row) { return "<a href='"+base_url+"cumplimiento/comprobante_ingreso/"+row.IDGARANTIA+"' target='_blank' class='btn btn-info btn-sm' title='Ver Ingreso'><i class='fas fa-print'></i> Recibo</a>"; }}
        ]
    }); 
}