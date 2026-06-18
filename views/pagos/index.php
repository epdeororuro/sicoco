
<?php
  // Obtener si el usuario actual es administrador (para el botón de anular)
  $sessionController = new \Config\sessionController();
  $currentUser = $sessionController->getCurrentUser();
  
  // Validación a prueba de balas para detectar el Rol de Administrador
  $es_admin = 'false';
  if (
      (isset($currentUser['rol']) && $currentUser['rol'] == 1) || 
      (isset($currentUser['ROL']) && $currentUser['ROL'] == 1) || 
      (isset($currentUser['idrol']) && $currentUser['idrol'] == 1) ||
      (isset($currentUser['IDROL']) && $currentUser['IDROL'] == 1) ||
      (isset($currentUser['cargo']) && $currentUser['cargo'] == 1) ||
      (isset($currentUser['CARGO']) && $currentUser['CARGO'] == 1)
  ) {
      $es_admin = 'true';
  }
?>
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-cash-register"></i> 
             <strong>Módulo de Caja - Historial de Transacciones</strong>
          </div>
        </div>        
    </div><!-- /.container-fluid -->
  </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-guindo">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Listado de todos los pagos registrados en el sistema</h3>
                
                <div class="form-inline ml-auto">
                    <!-- Filtros por Estado -->
                    <div class="custom-control custom-radio mr-3">
                        <input class="custom-control-input" type="radio" id="filtro_todos" name="filtro_estado" value="TODOS" checked>
                        <label for="filtro_todos" class="custom-control-label">Todos</label>
                    </div>
                    <div class="custom-control custom-radio mr-3">
                        <input class="custom-control-input" type="radio" id="filtro_activos" name="filtro_estado" value="ACTIVO">
                        <label for="filtro_activos" class="custom-control-label text-success">Válidos</label>
                    </div>
                    <div class="custom-control custom-radio mr-4">
                        <input class="custom-control-input" type="radio" id="filtro_anulados" name="filtro_estado" value="ANULADO">
                        <label for="filtro_anulados" class="custom-control-label text-danger">Anulados</label>
                    </div>

                    <label for="fecha_cierre" class="mr-2 font-weight-bold text-secondary">Cierre Diario:</label>
                    <input type="date" id="fecha_cierre" class="form-control form-control-sm mr-2 shadow-sm" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                    <button id="btn_imprimir_cierre" class="btn btn-guindo btn-sm font-weight-bold shadow-sm" title="Imprimir PDF de Cierre">
                        <i class="fas fa-print"></i> Generar Cierre
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="TablaHistorial" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                  <thead class="bg-guindo">
                  <tr>
                    <th>Nº</th>
                    <th>Nro. Recibo</th>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
                    <th>Contrato</th>
                    <th>Periodos Cobrados</th>
                    <th>Total (Bs.)</th>
                    <th>Cajero</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tfoot class="bg-guindo">
                  <tr>
                    <th>Nº</th>
                    <th>Nro. Recibo</th>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
                    <th>Contrato</th>
                    <th>Periodos Cobrados</th>
                    <th>Total (Bs.)</th>
                    <th>Cajero</th>
                    <th>Acción</th>
                  </tr>
                  </tfoot>
                  <tbody>
                    <!-- El contenido se cargará por Ajax -->
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->     
    </section>
    <!-- /.content -->

<script src="<?php echo URL; ?>views/pagos/pagos.js"></script>

<script type="text/javascript">
   var es_admin = <?php echo $es_admin; ?>;

   // Verificamos en la consola del navegador si detectó correctamente el rol
   console.log("¿Es administrador?:", es_admin);

   $(document).ready(function(){
    ListarHistorialCaja();

    // Filtrado por Radio Buttons
    $('input[name="filtro_estado"]').on('change', function() {
        $('#TablaHistorial').DataTable().ajax.reload();
    });

    // Anulación directa desde el Historial (Llama silenciosamente al controlador de Estornos)
    $(document).on('click', '.BtnAnularDesdeHistorial', function(e) {
        e.preventDefault();
        var nro = $(this).data('nro');
        Swal.fire({
            title: '¿Confirmar Anulación Irreversible?',
            html: 'Al anular el Recibo Nro: <strong>' + nro + '</strong>, la deuda volverá a figurar como <b>PENDIENTE</b>.<br><br>Ingrese el motivo de la anulación:',
            input: 'text', inputAttributes: { required: 'true' }, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, Anular Recibo!', cancelButtonText: 'Cancelar',
            preConfirm: (motivo) => { if (!motivo) { Swal.showValidationMessage('Debe ingresar un motivo para auditoría'); } return motivo; }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                $.ajax({
                    url: base_url + 'estorno/anular', type: 'POST', data: { nro_recibo: nro, motivo: result.value }, dataType: 'json',
                    success: function(res) { 
                        if(res.status === 'success') { 
                            Swal.fire('¡Anulado!', res.message, 'success'); 
                            $('#TablaHistorial').DataTable().ajax.reload(); 
                        } else { 
                            Swal.fire('Error', res.message, 'error'); 
                        } 
                    }
                });
            }
        });
   });
   });

   // Sobrescribimos la función de pagos.js dinámicamente
   function ListarHistorialCaja() {
       $("#TablaHistorial").DataTable({     
        "responsive": true, "destroy": true, "order": [[2, "desc"]], "autoWidth": false,
        "ajax": {
            "url": base_url + 'pagos/historial',
            "dataSrc": function(json) { 
                var datos = json.data ? json.data : [];
                var filtro = $('input[name="filtro_estado"]:checked').val();
                if (filtro !== 'TODOS') {
                    return datos.filter(d => d.ESTADO_RECIBO === filtro);
                }
                return datos;
            }       
        },
        "columns": [
            {"data": null, "searchable": false, "orderable": false, "className": "text-center", "render": function (data, type, row, meta) { return meta.row + 1; }},
            {"data": "NRO_RECIBO", "className": "text-center font-weight-bold", "render": function(data, type, row) { 
                var badge = (row.ESTADO_RECIBO === 'ANULADO') ? '<br><span class="badge badge-danger shadow-sm mt-1" style="font-size:10px;">ANULADO</span>' : '';
                return data + badge;
            }},
            {"data": "FECHA", "className": "text-center"},
            {"data": "CLIENTE", "className": "text-uppercase"},
            {"data": "CONTRATO", "className": "text-center font-weight-bold"},
            {"data": "PERIODOS", "className": "text-center", "render": function(data) {
                var periodos = data.split(', ');
                if(periodos.length > 3) { return periodos[0] + ' ... ' + periodos[periodos.length-1]; }
                return data;
            }},
            {"data": "TOTAL", "className": "text-right font-weight-bold", "render": function(data) { return "Bs. " + parseFloat(data).toFixed(2); }},
            {"data": "CAJERO", "className": "text-center"},
            {"data": null, "className": "text-center text-nowrap", "render": function(data, type, row) {
                var btnReimprimir = "<a href='" + base_url + "pagos/reimprimir/" + row.NRO_RECIBO + "' target='_blank' class='btn btn-info btn-sm shadow-sm mx-1' title='Reimprimir Recibo'><i class='fas fa-print'></i></a>";
                var btnAnular = "";
                // DIBUJAMOS EL BOTÓN SOLO SI ES ADMINISTRADOR Y EL RECIBO NO ESTÁ YA ANULADO
                if (es_admin && row.ESTADO_RECIBO !== 'ANULADO') {
                    btnAnular = "<button class='BtnAnularDesdeHistorial btn btn-danger btn-sm shadow-sm mx-1' data-nro='" + row.NRO_RECIBO + "' title='Anular Recibo'><i class='fas fa-ban'></i></button>";
                }
                return btnReimprimir + btnAnular;
            }}
        ]
    }); 
   }
</script>
