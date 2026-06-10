var url_accion = '';
var item_catalogo_editar = 0; // Variable global para recordar qué ítem preseleccionar

$(document).ready(function() {
    // Asignamos el valor una vez que la plantilla ya haya cargado el base_url
    url_accion = base_url + 'propuesta/add';

    // 1. Cargar la tabla principal
    ListarPropuesta();

    // 2. Inicializar plugins Select2
    $('#SelArea, #SelItemCatalogo').select2({ dropdownParent: $('#ModalPropuesta') });

    // 3. Evento para limpiar y preparar el modal para un NUEVO registro
    $('#ModalPropuesta').on('show.bs.modal', function (e) {
        // Solo si el modal fue abierto por el botón "Nuevo" (que tiene data-toggle)
        if (e.relatedTarget) {
            url_accion = base_url + 'propuesta/add';
            $('#FormPropuesta')[0].reset();
            $('#txt_idpropuesta').val('');
            
            LlenarArea();
            
            $('#SelItemCatalogo').empty().append('<option value="0">-- Primero seleccione Área --</option>').prop('disabled', true).trigger('change.select2');
        }
    });

    // 4. Evento: Cuando cambia el Área, cargar el Catálogo
    $("#SelArea").on("change", function() {
        var idarea = $(this).val();
        var $selectCatalogo = $("#SelItemCatalogo");
        
        if (idarea && idarea != "0") {
            $selectCatalogo.empty().append('<option value="0">-- Cargando... --</option>').prop('disabled', false);
            $.ajax({
                url: base_url + 'propuesta/listar_catalogo_por_area/' + idarea,
                type: 'GET',
                dataType: 'json',
                success: function(resp) {
                    var datos = resp.data ? resp.data : [];
                    $selectCatalogo.empty().append('<option value="0">-- Seleccione un Espacio / Servicio --</option>');
                    $(datos).each(function(i, v) {
                        $selectCatalogo.append('<option value="' + v.IDCATALOGO + '">' + v.BESPACIO + '</option>');
                    });
                    
                    // Si estamos en modo edición, pre-seleccionamos el ítem correspondiente
                    if (item_catalogo_editar > 0) {
                        $selectCatalogo.val(item_catalogo_editar);
                        item_catalogo_editar = 0; // Limpiar para futuros usos normales
                    }
                    
                    $selectCatalogo.trigger('change.select2');
                }
            });
        } else {
            $selectCatalogo.empty().append('<option value="0">-- Primero seleccione Área --</option>');
            $selectCatalogo.prop('disabled', true).trigger('change.select2');
        }
    });

    // 5. Guardar (Añadir o Editar) el Registro
    $('#btn_GuardarPropuesta').on('click', function(e) {
        e.preventDefault();
        if(!$('#FormPropuesta')[0].checkValidity()) {
            $('#FormPropuesta')[0].reportValidity(); return;
        }
        if($('#SelItemCatalogo').val() == "0" || !$('#SelItemCatalogo').val()) {
            Swal.fire('Atención', 'Debe seleccionar un Ítem del catálogo.', 'warning'); return;
        }
        
        $.ajax({
            url: url_accion, type: 'POST', data: $('#FormPropuesta').serialize(), dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#ModalPropuesta').modal('hide');
                    $('#TablaPropuesta').DataTable().ajax.reload();
                    
                    if (resp.idpropuesta) {
                        Swal.fire({ title: '¡Garantía Cobrada!', text: 'Se imprimirá el recibo.', icon: 'success' }).then(() => {
                            // Crear un formulario oculto para enviar por POST (Seguridad)
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = base_url + 'propuesta/imprimir_recibo';
                            form.target = '_blank';
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'idpropuesta';
                            input.value = resp.idpropuesta;
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

    // 6. Abrir modal en modo Edición
    $(document).on('click', '.EditarPropuesta', function(e) {
        e.preventDefault();
        url_accion = base_url + 'propuesta/edit';
        
        if($('#txt_idpropuesta').length === 0) {
            $('#FormPropuesta').prepend('<input type="hidden" id="txt_idpropuesta" name="txt_idpropuesta" value="">');
        }
        
        $('#txt_idpropuesta').val($(this).data('id'));
        $('#txt_ci').val($(this).data('ci'));
        $('#txt_nombre').val($(this).data('nombre'));
        
        var idarea = $(this).data('idarea');
        item_catalogo_editar = $(this).data('idcatalogo'); // Guardamos el valor para que el evento change lo utilice

        $.ajax({
            url: base_url + 'propuesta/listar_areas', type: 'GET', dataType: 'json',
            success: function(resp) {
                var datos = resp.data ? resp.data : [];
                var $selectArea = $('#SelArea');
                $selectArea.empty().append('<option value="0">-- Seleccione un Área --</option>');
                $(datos).each(function(i, v) { $selectArea.append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>'); });
                
                // Disparamos el 'change' global. Éste cargará automáticamente el catálogo
                // y usará la variable item_catalogo_editar para pre-seleccionar sin crear conflictos.
                $selectArea.val(idarea).trigger('change');
            }
        });
        $('#ModalPropuesta').modal('show');
    });
    
    // 7. Devolver Garantía
    $(document).on('click', '.DevolverGarantia', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Confirmar Devolución?', text: 'Se registrará la devolución de los Bs. 100.00.', icon: 'question',
            showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, Devolver'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: base_url + 'propuesta/devolver/' + id, type: 'POST', dataType: 'json',
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
                                $('#TablaPropuesta').DataTable().ajax.reload();
                                if (res.isConfirmed) {
                                    var form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = base_url + 'propuesta/imprimir_recibo';
                                    form.target = '_blank';
                                    var input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'idpropuesta';
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

    // 8. Imprimir Recibo vía POST para seguridad
    $(document).on('click', '.ImprimirRecibo', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        // Crear un formulario oculto
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = base_url + 'propuesta/imprimir_recibo';
        form.target = '_blank'; // Abrir en una nueva pestaña

        // Crear un input oculto para el ID
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'idpropuesta';
        input.value = id;
        form.appendChild(input);

        // Añadir el formulario al body, enviarlo y removerlo
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
});

function LlenarArea() {
    $.ajax({
        url: base_url + 'propuesta/listar_areas', type: 'GET', dataType: 'json',
        success: function(resp) {
            var datos = resp.data ? resp.data : [];
            var $selectArea = $("#SelArea");
            $selectArea.empty().append('<option value="0">-- Seleccione un Área --</option>');
            $(datos).each(function(i, v) { $selectArea.append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>'); });
            $selectArea.trigger('change.select2');
        }
    });
}

function ListarPropuesta() {
    $("#TablaPropuesta").DataTable({ 
        "responsive": true, "destroy": true, "order": [[0, "desc"]], "autoWidth": false,
        "ajax": {
            "url": base_url + 'propuesta/listar',
            "dataSrc": function(resp) {
                if (Array.isArray(resp)) return resp;
                if (resp && Array.isArray(resp.data)) return resp.data;
                console.error('Respuesta inesperada en propuesta/listar:', resp);
                return [];
            },
            "error": function(xhr, status, error) {
                console.error('Error al cargar propuestas:', status, error, xhr.responseText);
                Swal.fire('Error', 'No se pudo cargar la lista de garantias de propuesta.', 'error');
            }
        },
        "columns": [ 
            {"data": "IDPROPUESTA"}, 
            {"data": null, "render": function(data) { return "<strong>" + data.NOMBRE_POSTULANTE + "</strong><br><small>CI: " + data.CI_POSTULANTE + "</small>"; }}, 
            {"data": "ESPACIO"}, 
            {"data": "MONTO", "render": function(data) { return "Bs. " + parseFloat(data || 0).toFixed(2); }}, 
            {"data": null, "render": function(data, type, row) { 
                return row.FECHA_COBRO || row.FECHA_INGRESO || row.FECHA_REGISTRO || 'S/D'; 
            }}, 
            {"data": "ESTADO", "render": function(data) { 
                return data === 'RETENIDA' ? "<span class='badge badge-warning'><i class='fas fa-lock'></i> RETENIDA</span>" : "<span class='badge badge-success'><i class='fas fa-unlock'></i> DEVUELTA</span>"; 
            }}, 
            {"data": null, "render": function(data, type, row) { 
                var btn_print = "<button class='ImprimirRecibo btn btn-info btn-sm mx-1' data-id='" + row.IDPROPUESTA + "' title='Imprimir Recibo'><i class='fas fa-print'></i></button>"; 
                var btn_devolver = "", btn_edit = "";
                if(row.ESTADO === 'RETENIDA') { 
                    btn_edit = "<button class='EditarPropuesta btn btn-warning btn-sm mx-1' data-id='" + row.IDPROPUESTA + "' data-ci='" + row.CI_POSTULANTE + "' data-nombre='" + row.NOMBRE_POSTULANTE + "' data-idcatalogo='" + row.IDCATALOGO + "' data-idarea='" + row.IDAREA + "' title='Editar'><i class='fas fa-edit'></i></button>";
                    btn_devolver = "<button class='DevolverGarantia btn btn-success btn-sm mx-1' data-id='" + row.IDPROPUESTA + "' title='Devolver'><i class='fas fa-hand-holding-usd'></i></button>"; 
                } 
                return "<div class='text-center text-nowrap'>" + btn_edit + btn_print + btn_devolver + "</div>"; 
            }} 
        ]
    });
}
